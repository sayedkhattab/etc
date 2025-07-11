<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'price',
        'duration',
        'level',
        'status',
        'featured',
        'category_id',
        'instructor_id',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'float',
        'featured' => 'boolean',
    ];

    protected $appends = [
        'is_active',
    ];

    /**
     * Determine if the course is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the category that owns the course.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    /**
     * Get the instructor that owns the course.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * The admin/user who created the course.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the levels for the course.
     */
    public function levels(): HasMany
    {
        return $this->hasMany(Level::class);
    }

    /**
     * Get the students enrolled in this course.
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_courses', 'course_id', 'student_id')
                    ->withPivot('enrollment_date', 'expiry_date', 'status', 'progress_percentage')
                    ->withTimestamps();
    }

    /**
     * Check if a student is enrolled in this course.
     */
    public function isStudentEnrolled($studentId): bool
    {
        return $this->students()->where('student_id', $studentId)->exists();
    }
    
    /**
     * الحصول على المستوى التالي بعد مستوى معين
     */
    public function getNextLevel(int $currentLevelId): ?Level
    {
        $currentLevel = $this->levels()->find($currentLevelId);
        
        if (!$currentLevel) {
            return null;
        }
        
        // البحث عن المستوى التالي حسب الترتيب
        return $this->levels()
            ->where('order', '>', $currentLevel->order)
            ->orderBy('order')
            ->first();
    }

    /**
     * Get the certificates for the course.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
} 