<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertificatePolicy
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
        // فقط المسؤول والمدرس يمكنهم عرض قائمة جميع الشهادات
        return $user->hasRole('admin') || $user->hasRole('instructor');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Certificate $certificate)
    {
        // المسؤول والمدرس يمكنهم عرض أي شهادة
        if ($user->hasRole('admin') || $user->hasRole('instructor')) {
            return true;
        }
        
        // الطالب يمكنه عرض شهاداته فقط
        return $certificate->student_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // فقط المسؤول والمدرس يمكنهم إنشاء شهادات
        return $user->hasRole('admin') || $user->hasRole('instructor');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Certificate $certificate)
    {
        // لا يمكن تعديل الشهادات بعد إنشائها
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Certificate $certificate)
    {
        // فقط المسؤول يمكنه حذف الشهادات
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Certificate $certificate)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Certificate $certificate)
    {
        return $user->hasRole('admin');
    }
    
    /**
     * Determine whether the user can download the certificate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function download(User $user, Certificate $certificate)
    {
        // نفس صلاحية عرض الشهادة
        return $this->view($user, $certificate);
    }
    
    /**
     * Determine whether the user can verify the certificate.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function verify(?User $user)
    {
        // يمكن لأي شخص (حتى الزوار) التحقق من صحة الشهادات
        return true;
    }
} 