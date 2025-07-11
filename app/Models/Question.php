<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assessment_id',
        'question_type_id',
        'question_text',
        'points',
        'explanation',
        'content_id',
        'level_id',
        'is_pretest',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_pretest' => 'boolean',
    ];

    /**
     * Get the assessment that owns the question.
     */
    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    /**
     * Get the question type.
     */
    public function questionType(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class);
    }

    /**
     * Get the options for this question.
     */
    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }

    /**
     * Get the correct option(s) for this question.
     */
    public function correctOptions(): HasMany
    {
        return $this->options()->where('is_correct', true);
    }

    /**
     * Get the video/content associated with this question.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(CourseContent::class, 'content_id');
    }
    
    /**
     * Get the level associated with this question.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
    
    /**
     * Get the failed questions records for this question.
     */
    public function failedQuestions(): HasMany
    {
        return $this->hasMany(FailedQuestion::class);
    }
} 