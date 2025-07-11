<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DefenseEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_id',
        'student_id',
        'title',
        'content',
        'submitted_at',
        'status',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the case that the defense entry belongs to.
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    /**
     * Get the student (user) that created the defense entry.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the attachments for this defense entry.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(DefenseAttachment::class);
    }

    /**
     * Check if the defense entry is submitted.
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted' || $this->status === 'reviewed';
    }

    /**
     * Check if the defense entry has been reviewed.
     */
    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    /**
     * Check if the defense entry is a draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
} 