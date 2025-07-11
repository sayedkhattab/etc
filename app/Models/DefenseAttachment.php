<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefenseAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'defense_entry_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
    ];

    /**
     * Get the defense entry that owns the attachment.
     */
    public function defenseEntry(): BelongsTo
    {
        return $this->belongsTo(DefenseEntry::class);
    }
} 