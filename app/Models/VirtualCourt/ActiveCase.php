<?php

namespace App\Models\VirtualCourt;

use App\Models\Store\StoreCaseFile;
use App\Models\Store\StoreCasePurchase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActiveCase extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'virtual_active_cases';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_case_file_id',
        'title',
        'status',
        'plaintiff_id',
        'defendant_id',
        'judge_id',
        'started_at',
        'expected_end_at',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'started_at' => 'datetime',
        'expected_end_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the case file that owns the active case.
     */
    public function storeCaseFile(): BelongsTo
    {
        return $this->belongsTo(StoreCaseFile::class, 'store_case_file_id');
    }

    /**
     * Get the plaintiff user.
     */
    public function plaintiff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'plaintiff_id');
    }

    /**
     * Get the defendant user.
     */
    public function defendant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'defendant_id');
    }

    /**
     * Get the judge assigned to the case.
     */
    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    /**
     * Get the purchases associated with this active case.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(StoreCasePurchase::class, 'active_case_id');
    }

    /**
     * Get the court sessions for the case.
     */
    public function courtSessions(): HasMany
    {
        return $this->hasMany(CourtSession::class, 'active_case_id');
    }

    /**
     * Get the defense entries for the case.
     */
    public function defenseEntries(): HasMany
    {
        return $this->hasMany(DefenseEntry::class, 'active_case_id');
    }

    /**
     * Get the judgments for the case.
     */
    public function judgments(): HasMany
    {
        return $this->hasMany(Judgment::class, 'active_case_id');
    }

    /**
     * Get the court archive for the case.
     */
    public function courtArchive(): HasMany
    {
        return $this->hasMany(CourtArchive::class, 'active_case_id');
    }

    /**
     * Scope a query to only include active cases.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include completed cases.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cases waiting for judge assignment.
     */
    public function scopeWaitingForJudge($query)
    {
        return $query->whereNull('judge_id')->where('status', 'active');
    }
}
