<?php

namespace App\Models\VirtualCourt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CaseFileCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'virtual_case_file_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

    /**
     * Get the case files for the category.
     */
    public function caseFiles(): BelongsToMany
    {
        return $this->belongsToMany(CaseFile::class, 'virtual_case_file_category', 'case_file_category_id', 'case_file_id');
    }
}
