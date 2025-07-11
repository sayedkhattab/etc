<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Judgment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_id',
        'judgment_type_id',
        'title',
        'content',
        'judgment_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'judgment_date' => 'date',
    ];

    /**
     * Get the case that the judgment belongs to.
     */
    public function case(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    /**
     * Get the judgment type.
     */
    public function judgmentType(): BelongsTo
    {
        return $this->belongsTo(JudgmentType::class);
    }

    /**
     * Get the attachments for this judgment.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(JudgmentAttachment::class);
    }

    /**
     * Check if the judgment is final.
     */
    public function isFinal(): bool
    {
        return $this->status === 'final';
    }

    /**
     * Check if the judgment is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' || $this->status === 'final';
    }

    /**
     * Check if the judgment is a draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
} 