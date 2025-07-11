<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseContent extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'level_id',
        'content_type_id',
        'title',
        'description',
        'content_url',
        'duration',
        'order',
        'is_required',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_required' => 'boolean',
    ];

    /**
     * Get the level that owns the content.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    /**
     * Get the content type.
     */
    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    /**
     * Questions associated with this content (used in pre/post tests).
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'content_id');
    }

    /**
     * Get the course through the level.
     */
    public function course()
    {
        return $this->level->course();
    }
    
    /**
     * Get the progress records for students viewing this content.
     */
    public function studentProgress(): HasMany
    {
        return $this->hasMany(StudentContentProgress::class, 'content_id');
    }
} 