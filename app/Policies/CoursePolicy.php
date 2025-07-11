<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true; // يمكن للجميع عرض قائمة الدورات
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Course $course)
    {
        // يمكن للجميع عرض الدورات العامة
        if ($course->visibility === 'public') {
            return true;
        }

        // يمكن للمسؤول والمدرس عرض أي دورة
        if ($user->hasRole('admin') || ($user->hasRole('instructor') && $course->created_by === $user->id)) {
            return true;
        }

        // يمكن للطالب عرض الدورات المسجل فيها
        if ($course->visibility === 'private' || $course->visibility === 'password_protected') {
            return $course->students()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('instructor');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Course $course)
    {
        return $user->hasRole('admin') || ($user->hasRole('instructor') && $course->created_by === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Course $course)
    {
        return $user->hasRole('admin') || ($user->hasRole('instructor') && $course->created_by === $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Course $course)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Course $course)
    {
        return $user->hasRole('admin');
    }
    
    /**
     * Determine whether the user can enroll in the course.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function enroll(User $user, Course $course)
    {
        // لا يمكن للمستخدم التسجيل إذا كان هو منشئ الدورة
        if ($course->created_by === $user->id) {
            return false;
        }
        
        // لا يمكن التسجيل في الدورات غير النشطة
        if ($course->status !== 'active') {
            return false;
        }
        
        // التحقق من عدم التسجيل المسبق
        if ($course->students()->where('user_id', $user->id)->exists()) {
            return false;
        }
        
        return true;
    }
} 