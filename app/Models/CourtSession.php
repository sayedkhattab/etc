<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourtSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_id',
        'session_type_id',
        'title',
        'description',
        'date',
        'time',
        'duration',
        'location',
        'zoom_link',
        'recording_url',
        'notes',
        'session_status_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the case that the session belongs to.
     */
    public function caseModel(): BelongsTo
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    /**
     * Alias for backward compatibility: case()
     */
    public function case(): BelongsTo
    {
        return $this->caseModel();
    }

    /**
     * Get the session type.
     */
    public function sessionType(): BelongsTo
    {
        return $this->belongsTo(SessionType::class);
    }

    /**
     * Get the status of the session.
     */
    public function sessionStatus(): BelongsTo
    {
        return $this->belongsTo(SessionStatus::class, 'session_status_id');
    }

    /**
     * Alias for backward compatibility: status()
     */
    public function status(): BelongsTo
    {
        return $this->sessionStatus();
    }

    /**
     * Check if the session is scheduled for the future.
     */
    public function isUpcoming(): bool
    {
        $sessionDateTime = $this->getDateTime();
        return $sessionDateTime->isFuture();
    }

    /**
     * Check if the session is happening now.
     */
    public function isInProgress(): bool
    {
        $now = now();
        $sessionDateTime = $this->getDateTime();
        $endTime = $sessionDateTime->copy()->addMinutes($this->duration ?? 60);
        
        return $now->greaterThanOrEqualTo($sessionDateTime) && $now->lessThanOrEqualTo($endTime);
    }

    /**
     * Check if the session has already happened.
     */
    public function isPast(): bool
    {
        $sessionDateTime = $this->getDateTime();
        $endTime = $sessionDateTime->copy()->addMinutes($this->duration ?? 60);
        
        return $endTime->isPast();
    }
    
    /**
     * Get the full date and time as a Carbon instance.
     */
    protected function getDateTime()
    {
        $timeString = $this->time ?? '00:00:00';
        return $this->date->copy()->setTimeFromTimeString($timeString);
    }
} 