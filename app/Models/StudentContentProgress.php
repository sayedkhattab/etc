<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentContentProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'content_id',
        'watched_seconds',
        'duration_seconds',
        'fully_watched',
        'watched_at',
        'is_required_content',
    ];

    protected $casts = [
        'fully_watched' => 'boolean',
        'watched_at' => 'datetime',
        'is_required_content' => 'boolean',
    ];

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * العلاقة مع المحتوى
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(CourseContent::class, 'content_id');
    }
    
    /**
     * حساب نسبة المشاهدة
     */
    public function getWatchedPercentageAttribute()
    {
        if ($this->duration_seconds <= 0) {
            return 0;
        }
        
        return min(100, round(($this->watched_seconds / $this->duration_seconds) * 100));
    }
    
    /**
     * تحديث حالة المشاهدة الكاملة
     */
    public function markAsFullyWatched()
    {
        $this->fully_watched = true;
        $this->watched_at = now();
        $this->save();
        
        return $this;
    }
    
    /**
     * التحقق مما إذا كان المحتوى قد تمت مشاهدته بنسبة معينة
     */
    public function isWatchedAtLeast($percentage)
    {
        if ($this->fully_watched) {
            return true;
        }
        
        return $this->getWatchedPercentageAttribute() >= $percentage;
    }
    
    /**
     * الحصول على حالة تقدم المشاهدة
     */
    public function getStatusAttribute()
    {
        if ($this->fully_watched) {
            return 'completed';
        }
        
        $percentage = $this->getWatchedPercentageAttribute();
        
        if ($percentage >= 75) {
            return 'almost_completed';
        } elseif ($percentage >= 50) {
            return 'half_completed';
        } elseif ($percentage > 0) {
            return 'started';
        }
        
        return 'not_started';
    }
}
