<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assessment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'level_id',
        'assessment_type_id',
        'title',
        'description',
        'passing_score',
        'time_limit',
        'attempts_allowed',
        'is_active',
        'is_pretest',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_pretest' => 'boolean',
    ];

    /**
     * Get the level that owns the assessment.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the assessment type.
     */
    public function assessmentType(): BelongsTo
    {
        return $this->belongsTo(AssessmentType::class);
    }

    /**
     * Get the questions for this assessment.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the student assessments (attempts) for this assessment.
     */
    public function studentAttempts(): HasMany
    {
        return $this->hasMany(StudentAssessment::class);
    }

    /**
     * Check if the assessment is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get the course through the level.
     */
    public function course()
    {
        return $this->level->course();
    }
} 