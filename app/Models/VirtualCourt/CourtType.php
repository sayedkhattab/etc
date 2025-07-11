<?php

namespace App\Models\VirtualCourt;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourtType extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'virtual_court_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'hierarchy_level',
    ];

    /**
     * Get the case files that belong to this court type.
     */
    public function caseFiles(): HasMany
    {
        return $this->hasMany(CaseFile::class, 'court_type_id');
    }

    /**
     * Get the court archives that belong to this court type.
     */
    public function courtArchives(): HasMany
    {
        return $this->hasMany(CourtArchive::class, 'court_type_id');
    }
}
