<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['student', 'course'])->paginate(20);
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $students = User::whereHas('role', function($q){ $q->where('name','student'); })->get();
        $courses  = Course::all();
        return view('admin.certificates.create', compact('students','courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'        => 'required|exists:users,id',
            'course_id'         => 'required|exists:courses,id',
            'issue_date'        => 'required|date',
            'expiry_date'       => 'nullable|date|after_or_equal:issue_date',
            'certificate_file'  => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $validated['certificate_number'] = Certificate::generateCertificateNumber();

        if($request->hasFile('certificate_file')){
            $path = $request->file('certificate_file')->store('certificates','public');
            $validated['certificate_file'] = $path;
        }

        $certificate = Certificate::create($validated);

        return redirect()->route('admin.certificates.show',$certificate)->with('success','تم إصدار الشهادة بنجاح');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['student','course']);
        return view('admin.certificates.show', compact('certificate'));
    }

    public function destroy(Certificate $certificate)
    {
        if($certificate->certificate_file && Storage::disk('public')->exists($certificate->certificate_file)){
            Storage::disk('public')->delete($certificate->certificate_file);
        }
        $certificate->delete();
        return redirect()->route('admin.certificates.index')->with('success','تم حذف الشهادة');
    }
} 