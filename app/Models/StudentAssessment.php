<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAssessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'assessment_id',
        'score',
        'passed',
        'attempt_number',
        'status',
        'start_time',
        'end_time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'score' => 'decimal:2',
        'passed' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the student (user) that owns the assessment attempt.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the assessment that was attempted.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Calculate the time spent on the assessment in minutes.
     */
    public function timeSpent(): ?int
    {
        if (!$this->start_time || !$this->end_time) {
            return null;
        }
        
        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Check if the assessment was completed within the time limit.
     */
    public function isCompletedWithinTimeLimit(): bool
    {
        if (!$this->assessment->time_limit) {
            return true;
        }
        
        $timeSpent = $this->timeSpent();
        
        return $timeSpent !== null && $timeSpent <= $this->assessment->time_limit;
    }
} 