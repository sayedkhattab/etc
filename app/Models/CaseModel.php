<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_id',
        'judge_id',
        'defendant_id',
        'case_number',
        'status_id',
        'court_type_id',
        'start_date',
        'close_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'close_date' => 'date',
    ];

    /**
     * Get the request that initiated the case.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Get the judge assigned to the case.
     */
    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    /**
     * Get the defendant in the case.
     */
    public function defendant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'defendant_id');
    }

    /**
     * Get the status of the case.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(CaseStatus::class, 'status_id');
    }

    /**
     * Get the court type for the case.
     */
    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class);
    }

    /**
     * Get the participants in the case.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(CaseParticipant::class, 'case_id');
    }

    /**
     * Get the attachments for the case.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(CaseAttachment::class, 'case_id');
    }

    /**
     * Get the sessions for the case.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(CourtSession::class, 'case_id');
    }

    /**
     * Get the judgments for the case.
     */
    public function judgments(): HasMany
    {
        return $this->hasMany(Judgment::class, 'case_id');
    }

    /**
     * Get the defense entries for the case.
     */
    public function defenseEntries(): HasMany
    {
        return $this->hasMany(DefenseEntry::class, 'case_id');
    }

    /**
     * Generate a unique case number.
     */
    public static function generateCaseNumber(): string
    {
        $prefix = 'ITHBAT-CASE-';
        $year = date('Y');
        $uniqueId = strtoupper(substr(uniqid(), -6));
        
        return $prefix . $year . '-' . $uniqueId;
    }

    /**
     * Check if the case is closed.
     */
    public function isClosed(): bool
    {
        return $this->close_date !== null;
    }
} 