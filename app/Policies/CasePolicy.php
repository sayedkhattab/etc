<?php

namespace App\Policies;

use App\Models\CaseModel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CasePolicy
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
        return true; // يمكن للجميع عرض قائمة القضايا (سيتم تصفية القائمة في التحكم)
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CaseModel $case)
    {
        // المسؤول يمكنه عرض أي قضية
        if ($user->hasRole('admin')) {
            return true;
        }
        
        // القاضي يمكنه عرض القضايا المسندة إليه
        if ($user->hasRole('judge') && $case->judge_id === $user->id) {
            return true;
        }
        
        // المدعى عليه يمكنه عرض القضية المرفوعة ضده
        if ($case->defendant_id === $user->id) {
            return true;
        }
        
        // المشاركون في القضية يمكنهم عرضها
        if ($case->participants()->where('user_id', $user->id)->exists()) {
            return true;
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
        return $user->hasRole('admin') || $user->hasRole('judge');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CaseModel $case)
    {
        // المسؤول والقاضي المسند إليه القضية يمكنهم تعديلها
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CaseModel $case)
    {
        // فقط المسؤول يمكنه حذف القضايا
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CaseModel $case)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CaseModel $case)
    {
        return $user->hasRole('admin');
    }
    
    /**
     * Determine whether the user can add attachments to the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function addAttachment(User $user, CaseModel $case)
    {
        // المسؤول والقاضي والمشاركون في القضية يمكنهم إضافة مرفقات
        return $user->hasRole('admin') || 
               $user->hasRole('judge') && $case->judge_id === $user->id || 
               $case->participants()->where('user_id', $user->id)->exists() || 
               $case->defendant_id === $user->id;
    }
    
    /**
     * Determine whether the user can delete attachments from the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteAttachment(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي يمكنهما حذف المرفقات
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can view attachments in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAttachment(User $user, CaseModel $case)
    {
        // نفس صلاحية عرض القضية
        return $this->view($user, $case);
    }
    
    /**
     * Determine whether the user can create sessions in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createSession(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما إنشاء جلسات
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can update sessions in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateSession(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما تعديل الجلسات
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can delete sessions in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteSession(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما حذف الجلسات
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can create judgments in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createJudgment(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما إنشاء أحكام
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can update judgments in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateJudgment(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما تعديل الأحكام
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can delete judgments in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteJudgment(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما حذف الأحكام
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can view draft judgments in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewDraftJudgment(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما عرض مسودات الأحكام
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can create defense entries in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function createDefenseEntry(User $user, CaseModel $case)
    {
        // فقط المشاركون في القضية والمدعى عليه يمكنهم إنشاء مذكرات دفاعية
        return $case->participants()->where('user_id', $user->id)->exists() || 
               $case->defendant_id === $user->id;
    }
    
    /**
     * Determine whether the user can view defense entries in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewDefenseEntry(User $user, CaseModel $case)
    {
        // المسؤول والقاضي المسند إليه القضية يمكنهما عرض جميع المذكرات الدفاعية
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
    
    /**
     * Determine whether the user can update defense entries in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateDefenseEntry(User $user, CaseModel $case)
    {
        // فقط المسؤول يمكنه تعديل المذكرات الدفاعية (بخلاف صاحب المذكرة)
        return $user->hasRole('admin');
    }
    
    /**
     * Determine whether the user can delete defense entries in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deleteDefenseEntry(User $user, CaseModel $case)
    {
        // فقط المسؤول يمكنه حذف المذكرات الدفاعية (بخلاف صاحب المذكرة)
        return $user->hasRole('admin');
    }
    
    /**
     * Determine whether the user can review defense entries in the case.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CaseModel  $case
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reviewDefenseEntry(User $user, CaseModel $case)
    {
        // فقط المسؤول والقاضي المسند إليه القضية يمكنهما مراجعة المذكرات الدفاعية
        return $user->hasRole('admin') || ($user->hasRole('judge') && $case->judge_id === $user->id);
    }
} 