<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JudgmentAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judgment_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'description',
    ];

    /**
     * Get the judgment that owns the attachment.
     */
    public function judgment(): BelongsTo
    {
        return $this->belongsTo(Judgment::class);
    }
} 