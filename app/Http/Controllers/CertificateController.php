<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Course;
use App\Models\StudentCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * عرض قائمة الشهادات للمسؤول
     */
    public function index()
    {
        $this->authorize('viewAny', Certificate::class);
        
        $certificates = Certificate::with(['student', 'course'])->paginate(20);
        
        return view('certificates.index', compact('certificates'));
    }

    /**
     * عرض قائمة الشهادات للطالب
     */
    public function studentCertificates()
    {
        $certificates = Certificate::where('student_id', Auth::id())
            ->with('course')
            ->get();
            
        return view('student.certificates.index', compact('certificates'));
    }

    /**
     * عرض نموذج إنشاء شهادة جديدة
     */
    public function create(Course $course, $studentId = null)
    {
        $this->authorize('create', Certificate::class);
        
        // التحقق من وجود قالب للشهادة
        if (!$course->certificate_template_id) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'لا يوجد قالب شهادة مرتبط بهذه الدورة');
        }
        
        // إذا تم تحديد طالب معين
        if ($studentId) {
            $enrollment = StudentCourse::where('course_id', $course->id)
                ->where('student_id', $studentId)
                ->first();
                
            if (!$enrollment) {
                return redirect()->route('courses.show', $course)
                    ->with('error', 'الطالب غير مسجل في هذه الدورة');
            }
            
            if ($enrollment->status !== 'completed') {
                return redirect()->route('courses.show', $course)
                    ->with('error', 'لم يكمل الطالب هذه الدورة بعد');
            }
            
            $student = $enrollment->student;
            
            return view('certificates.create', compact('course', 'student'));
        }
        
        // الحصول على قائمة الطلاب الذين أكملوا الدورة
        $completedStudents = $course->students()
            ->wherePivot('status', 'completed')
            ->wherePivot('certificate_issued', false)
            ->get();
            
        return view('certificates.create', compact('course', 'completedStudents'));
    }

    /**
     * إنشاء شهادة جديدة
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('create', Certificate::class);
        
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
        ]);
        
        // التحقق من وجود قالب للشهادة
        if (!$course->certificate_template_id) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'لا يوجد قالب شهادة مرتبط بهذه الدورة');
        }
        
        // التحقق من أن الطالب أكمل الدورة
        $enrollment = StudentCourse::where('course_id', $course->id)
            ->where('student_id', $validated['student_id'])
            ->first();
            
        if (!$enrollment || $enrollment->status !== 'completed') {
            return redirect()->route('courses.show', $course)
                ->with('error', 'لم يكمل الطالب هذه الدورة بعد');
        }
        
        // التحقق من عدم وجود شهادة سابقة
        if (Certificate::where('student_id', $validated['student_id'])
            ->where('course_id', $course->id)
            ->exists()) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'تم إصدار شهادة لهذا الطالب مسبقاً');
        }
        
        // إنشاء رقم فريد للشهادة
        $certificateNumber = Certificate::generateCertificateNumber();
        
        // إنشاء ملف الشهادة
        $certificateFile = $this->generateCertificateFile($course, $enrollment->student, $certificateNumber, $validated['issue_date']);
        
        // إنشاء الشهادة
        $certificate = Certificate::create([
            'student_id' => $validated['student_id'],
            'course_id' => $course->id,
            'certificate_number' => $certificateNumber,
            'issue_date' => $validated['issue_date'],
            'expiry_date' => $validated['expiry_date'],
            'certificate_file' => $certificateFile,
        ]);
        
        // تحديث حالة التسجيل
        $enrollment->update([
            'certificate_issued' => true
        ]);
        
        return redirect()->route('certificates.show', $certificate)
            ->with('success', 'تم إصدار الشهادة بنجاح');
    }

    /**
     * عرض شهادة محددة
     */
    public function show(Certificate $certificate)
    {
        // التحقق من الصلاحيات
        if (Auth::id() !== $certificate->student_id) {
            $this->authorize('view', $certificate);
        }
        
        $certificate->load(['student', 'course']);
        
        return view('certificates.show', compact('certificate'));
    }

    /**
     * تنزيل الشهادة
     */
    public function download(Certificate $certificate)
    {
        // التحقق من الصلاحيات
        if (Auth::id() !== $certificate->student_id) {
            $this->authorize('view', $certificate);
        }
        
        if (!Storage::disk('public')->exists($certificate->certificate_file)) {
            return redirect()->route('certificates.show', $certificate)
                ->with('error', 'ملف الشهادة غير موجود');
        }
        
        return Storage::disk('public')->download(
            $certificate->certificate_file, 
            'شهادة-' . $certificate->certificate_number . '.pdf'
        );
    }

    /**
     * حذف شهادة
     */
    public function destroy(Certificate $certificate)
    {
        $this->authorize('delete', $certificate);
        
        // حذف ملف الشهادة
        if (Storage::disk('public')->exists($certificate->certificate_file)) {
            Storage::disk('public')->delete($certificate->certificate_file);
        }
        
        // تحديث حالة التسجيل
        StudentCourse::where('course_id', $certificate->course_id)
            ->where('student_id', $certificate->student_id)
            ->update(['certificate_issued' => false]);
            
        $certificate->delete();
        
        return redirect()->route('certificates.index')
            ->with('success', 'تم حذف الشهادة بنجاح');
    }
    
    /**
     * إنشاء ملف الشهادة باستخدام القالب
     */
    private function generateCertificateFile(Course $course, $student, $certificateNumber, $issueDate)
    {
        // الحصول على قالب الشهادة
        $template = CertificateTemplate::find($course->certificate_template_id);
        
        if (!$template) {
            throw new \Exception('قالب الشهادة غير موجود');
        }
        
        // تحضير البيانات للشهادة
        $data = [
            'student_name' => $student->name,
            'course_title' => $course->title,
            'certificate_number' => $certificateNumber,
            'issue_date' => $issueDate,
            'course' => $course,
            'student' => $student,
            'template' => $template,
        ];
        
        // إنشاء ملف PDF
        $pdf = PDF::loadView('certificates.template', $data);
        
        // حفظ الملف
        $fileName = 'certificates/' . $certificateNumber . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());
        
        return $fileName;
    }
} 