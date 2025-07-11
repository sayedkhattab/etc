<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FailedQuestion;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseContent;
use App\Models\Level;

class LevelAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Expect parameters in route
        $level = $request->route('level');
        if ($level && $user) {
            /** @var Level $level */
            // Check unresolved failed questions
            $unresolved = FailedQuestion::where('student_id', $user->id)
                ->where('level_id', $level->id)
                ->where('resolved', false)
                ->get();

            if ($unresolved->isNotEmpty()) {
                // If accessing a specific content, allow only if it's among required ones
                $content = $request->route('content');
                if ($content) {
                    /** @var CourseContent $content */
                    $allowedContentIds = $unresolved->map(function ($fq) {
                        return $fq->question->content_id;
                    })->filter()->unique();

                    if ($allowedContentIds->contains($content->id)) {
                        // allowed – continue
                        return $next($request);
                    }
                }

                // Otherwise redirect to level page with message
                return redirect()->route('courses.levels.show', [$level->course, $level])
                    ->with('warning', 'يجب عليك مشاهدة الفيديوهات المطلوبة المرتبطة بالأسئلة التي أخطأت بها قبل متابعة الدرس.');
            }
        }

        return $next($request);
    }
}
