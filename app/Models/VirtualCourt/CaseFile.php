<?php

namespace App\Models\VirtualCourt;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseFile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'virtual_case_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'court_type_id',
        'case_number',
        'status',
        'tags',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tags' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Get the court type that owns the case file.
     */
    public function courtType(): BelongsTo
    {
        return $this->belongsTo(CourtType::class, 'court_type_id');
    }

    /**
     * Get the user who created the case file.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the attachments for the case file.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(CaseFileAttachment::class, 'case_file_id');
    }

    /**
     * Get the categories for the case file.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CaseFileCategory::class, 'virtual_case_file_category', 'case_file_id', 'case_file_category_id');
    }

    /**
     * Get the active cases for this case file.
     */
    public function activeCases(): HasMany
    {
        return $this->hasMany(ActiveCase::class, 'case_file_id');
    }
}
