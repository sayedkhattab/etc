<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\CaseModel;
use App\Models\CourtSession;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // الدورات المسجل فيها المستخدم
        $enrolledCourses = $user->enrolledCourses()
            ->orderBy('student_courses.updated_at', 'desc')
            ->take(5)
            ->get();
        
        $enrolledCoursesCount = $user->enrolledCourses()->count();
        
        // الشهادات التي حصل عليها المستخدم
        $certificatesCount = $user->certificates()->count();
        
        // القضايا النشطة التي يشارك فيها المستخدم
        $activeCases = $user->participatingCases()
            ->whereHas('status', function($query) {
                $query->where('name', '!=', 'مغلقة')
                    ->where('name', '!=', 'مؤرشفة');
            })
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        
        $activeCasesCount = $user->participatingCases()
            ->whereHas('status', function($query) {
                $query->where('name', '!=', 'مغلقة')
                    ->where('name', '!=', 'مؤرشفة');
            })
            ->count();
        
        // الجلسات القادمة للمستخدم
        $upcomingSessions = CourtSession::whereHas('case.participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('status', function($query) {
                $query->where('name', 'قادمة');
            })
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date')
            ->orderBy('time')
            ->take(5)
            ->get();
        
        $upcomingSessionsCount = CourtSession::whereHas('case.participants', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('status', function($query) {
                $query->where('name', 'قادمة');
            })
            ->where('date', '>=', now()->format('Y-m-d'))
            ->count();
        
        return view('dashboard', compact(
            'enrolledCourses',
            'enrolledCoursesCount',
            'certificatesCount',
            'activeCases',
            'activeCasesCount',
            'upcomingSessions',
            'upcomingSessionsCount'
        ));
    }
} 