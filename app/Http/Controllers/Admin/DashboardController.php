<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Course;
use App\Models\CaseModel;
use App\Models\Certificate;
use App\Models\CourtSession;
use App\Models\StudentCourse;
use App\Models\CourseCategory;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // إحصائيات عامة
        $stats = [
            'users' => User::count(),
            'courses' => Course::count(),
            'cases' => CaseModel::count(),
            'certificates' => Certificate::count(),
        ];
        
        // الجلسات القادمة
        $upcomingSessions = CourtSession::with(['caseModel', 'sessionType', 'sessionStatus'])
            ->where('date', '>=', now())
            ->orderBy('date')
            ->orderBy('time')
            ->take(5)
            ->get();
            
        // الدورات النشطة - استخدام استعلام مخصص
        $activeCourses = DB::table('courses')
            ->select('courses.*', DB::raw('(SELECT COUNT(*) FROM student_courses WHERE courses.id = student_courses.course_id) as students_count'))
            ->orderByDesc('students_count')
            ->limit(5)
            ->get();
            
        // الحصول على معلومات إضافية للدورات
        foreach ($activeCourses as $course) {
            // حساب نسبة التقدم (افتراضية)
            $course->progress = rand(20, 95);
            
            // الحصول على تصنيف الدورة
            $category = CourseCategory::find($course->category_id);
            $course->category_name = $category ? $category->name : 'غير مصنف';
        }
        
        // النشاط الأخير (افتراضي)
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.dashboard', compact(
            'stats', 
            'upcomingSessions', 
            'activeCourses', 
            'recentActivities'
        ));
    }
    
    /**
     * الحصول على النشاط الأخير (بيانات افتراضية)
     * في التطبيق الحقيقي، يمكن استبدالها بنشاط فعلي من قاعدة البيانات
     */
    private function getRecentActivities()
    {
        return [
            [
                'type' => 'user',
                'icon' => 'bi-person-check',
                'color' => 'primary',
                'title' => 'تم تسجيل مستخدم جديد',
                'subject' => 'أحمد محمد',
                'time' => 'منذ 5 دقائق'
            ],
            [
                'type' => 'course',
                'icon' => 'bi-mortarboard',
                'color' => 'success',
                'title' => 'تم إضافة دورة جديدة',
                'subject' => 'أساسيات القانون التجاري',
                'time' => 'منذ ساعة'
            ],
            [
                'type' => 'case',
                'icon' => 'bi-briefcase',
                'color' => 'warning',
                'title' => 'تم إنشاء قضية جديدة',
                'subject' => 'قضية رقم #12345',
                'time' => 'منذ 3 ساعات'
            ],
            [
                'type' => 'session',
                'icon' => 'bi-calendar-check',
                'color' => 'info',
                'title' => 'تم تحديد موعد جلسة',
                'subject' => 'قضية رقم #12340',
                'time' => 'منذ 5 ساعات'
            ],
            [
                'type' => 'certificate',
                'icon' => 'bi-award',
                'color' => 'danger',
                'title' => 'تم إصدار شهادة',
                'subject' => 'محمد أحمد',
                'time' => 'منذ يوم'
            ]
        ];
    }
}
