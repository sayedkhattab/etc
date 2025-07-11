<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'status',
        'prerequisites',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'prerequisites' => 'json',
    ];

    /**
     * Get the course that owns the level.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the contents for this level.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(CourseContent::class)->orderBy('order');
    }

    /**
     * Get the assessments for this level.
     */
    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }
    
    /**
     * التحقق مما إذا كان المستوى يحتوي على اختبار تحديد المستوى
     */
    public function hasPreTest(): bool
    {
        return $this->assessments()->where('is_pretest', true)->exists();
    }
    
    /**
     * التحقق مما إذا كان الطالب قد أكمل اختبار تحديد المستوى
     */
    public function hasCompletedPreTest(int $studentId): bool
    {
        $preTest = $this->assessments()->where('is_pretest', true)->first();
        
        if (!$preTest) {
            return false;
        }
        
        // التحقق من وجود محاولة مكتملة للاختبار
        return $preTest->studentAttempts()
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->exists();
    }
    
    /**
     * التحقق مما إذا كان الطالب قد اجتاز اختبار تحديد المستوى
     */
    public function hasPassedPreTest(int $studentId): bool
    {
        $preTest = $this->assessments()->where('is_pretest', true)->first();
        
        if (!$preTest) {
            return false;
        }
        
        // التحقق من وجود محاولة ناجحة للاختبار
        return $preTest->studentAttempts()
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->where('passed', true)
            ->exists();
    }
    
    /**
     * الحصول على المحتوى التالي بعد محتوى معين
     */
    public function getNextContent(int $currentContentId): ?CourseContent
    {
        $currentContent = $this->contents()->find($currentContentId);
        
        if (!$currentContent) {
            return null;
        }
        
        // البحث عن المحتوى التالي حسب الترتيب
        return $this->contents()
            ->where('order', '>', $currentContent->order)
            ->orderBy('order')
            ->first();
    }

    /**
     * Check if the level is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get prerequisite levels.
     */
    public function prerequisiteLevels()
    {
        if (!$this->prerequisites) {
            return collect();
        }
        
        return Level::whereIn('id', $this->prerequisites)->get();
    }
} 