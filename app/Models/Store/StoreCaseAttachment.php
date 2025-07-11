<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class StoreCaseAttachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_file_id',
        'title',
        'file_path',
        'file_type',
        'role',
        'is_visible_before_purchase',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible_before_purchase' => 'boolean',
    ];

    /**
     * Get the case file that owns the attachment.
     */
    public function caseFile(): BelongsTo
    {
        return $this->belongsTo(StoreCaseFile::class, 'case_file_id');
    }

    /**
     * Get the URL for the attachment file.
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Scope a query to only include attachments for a specific role.
     */
    public function scopeForRole($query, $role)
    {
        return $query->where('role', $role)->orWhere('role', 'عام');
    }

    /**
     * Scope a query to only include public attachments.
     */
    public function scopePublic($query)
    {
        return $query->where('is_visible_before_purchase', true);
    }
}
