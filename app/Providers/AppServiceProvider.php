<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseContent;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // إضافة view composer لمشاركة المحتويات الإجبارية مع sidebar
        View::composer('partials.sidebar', function ($view) {
            // التحقق من تسجيل دخول المستخدم
            if (Auth::check() && Auth::user()->hasRole('student')) {
                // الحصول على المحتويات الإجبارية للطالب
                $requiredContents = [];
                
                try {
                    $studentId = Auth::id();
                    
                    // استعلام للحصول على المحتويات الإجبارية التي لم يكملها الطالب بعد
                    $requiredContents = CourseContent::with('level.course')
                        ->whereHas('studentProgress', function ($query) use ($studentId) {
                            $query->where('student_id', $studentId)
                                ->where('is_required_content', true)
                                ->where('fully_watched', false);
                        })->get();
                    
                } catch (\Exception $e) {
                    // تسجيل الخطأ ولكن عدم إظهاره للمستخدم
                    \Log::error('Error loading required contents: ' . $e->getMessage());
                }
                
                $view->with('requiredContents', $requiredContents);
            } else {
                $view->with('requiredContents', collect([]));
            }
        });
    }
}
