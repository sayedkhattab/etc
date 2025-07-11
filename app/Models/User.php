<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status',
        'phone',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the role that the user belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    
    /**
     * Get the user's profile.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }
    
    /**
     * Get the user's permissions.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }
    
    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('permission_name', $permission)
            ->where('granted', true)
            ->exists();
    }
    
    /**
     * Check if the user has a specific role.
     */
    public function hasRole($roles): bool
    {
        // The role may be stored as an enum string column ("admin", "student", ...)
        // OR via a related Role model referenced by role_id. Handle both cases safely.
        $role = $this->role; // could be string attribute or Role model instance
        $roleName = is_object($role) ? $role->name : $role;

        if (is_array($roles)) {
            return in_array($roleName, $roles);
        }

        return $roleName === $roles;
    }

    /**
     * Accessor to always return the role name as string regardless of storage strategy.
     */
    public function getRoleNameAttribute(): ?string
    {
        $role = $this->role;
        return is_object($role) ? $role->name : $role;
    }
    
    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * العلاقة مع الدورات التعليمية التي أنشأها المستخدم
     */
    public function createdCourses()
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    /**
     * العلاقة مع الدورات التعليمية التي سجل فيها المستخدم
     */
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'student_courses', 'student_id', 'course_id')
            ->withPivot('enrollment_date', 'status', 'progress_percentage', 'certificate_issued')
            ->withTimestamps();
    }

    /**
     * العلاقة مع التقييمات التي قام بها المستخدم
     */
    public function assessmentAttempts()
    {
        return $this->hasMany(StudentAssessment::class, 'student_id');
    }

    /**
     * العلاقة مع القضايا التي يشارك فيها المستخدم
     */
    public function participatingCases()
    {
        return $this->belongsToMany(CaseModel::class, 'case_participants', 'user_id', 'case_id')
            ->withPivot('role_id', 'joined_at')
            ->withTimestamps();
    }

    /**
     * العلاقة مع القضايا التي يكون فيها المستخدم قاضيًا
     */
    public function judgedCases()
    {
        return $this->hasMany(CaseModel::class, 'judge_id');
    }

    /**
     * العلاقة مع القضايا التي يكون فيها المستخدم مدعى عليه
     */
    public function defendantCases()
    {
        return $this->hasMany(CaseModel::class, 'defendant_id');
    }

    /**
     * العلاقة مع المذكرات الدفاعية التي قدمها المستخدم
     */
    public function defenseEntries()
    {
        return $this->hasMany(DefenseEntry::class, 'student_id');
    }

    /**
     * العلاقة مع الشهادات التي حصل عليها المستخدم
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'student_id');
    }
}
