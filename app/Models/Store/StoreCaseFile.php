<?php

namespace App\Models\Store;

use App\Models\Admin;
use App\Models\VirtualCourt\ActiveCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreCaseFile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'case_type',
        'case_number',
        'facts',
        'legal_articles',
        'price',
        'difficulty',
        'estimated_duration_days',
        'thumbnail',
        'is_active',
        'is_featured',
        'purchases_count',
        'admin_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the category that owns the case file.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(StoreCaseCategory::class, 'category_id');
    }

    /**
     * Get the admin that created the case file.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Get the attachments for the case file.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(StoreCaseAttachment::class, 'case_file_id');
    }

    /**
     * Get the purchases for the case file.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(StoreCasePurchase::class, 'case_file_id');
    }

    /**
     * Get the active cases for the case file.
     */
    public function activeCases(): HasMany
    {
        return $this->hasMany(ActiveCase::class, 'store_case_file_id');
    }

    /**
     * Scope a query to only include active case files.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured case files.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to filter by case type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('case_type', $type);
    }

    /**
     * Increment the purchases count.
     */
    public function incrementPurchasesCount(): void
    {
        $this->increment('purchases_count');
    }

    /**
     * Get the public attachments for the case file (visible before purchase).
     */
    public function publicAttachments()
    {
        return $this->attachments()->where('is_visible_before_purchase', true);
    }

    /**
     * Check if the case file can be activated in the virtual court.
     */
    public function canActivateInCourt(): bool
    {
        $plaintiffPurchase = $this->getPurchaseByRole('مدعي');
        $defendantPurchase = $this->getPurchaseByRole('مدعى عليه');

        return $plaintiffPurchase && $defendantPurchase && 
               $plaintiffPurchase->user_id !== $defendantPurchase->user_id;
    }

    /**
     * Get a purchase by role.
     */
    public function getPurchaseByRole(string $role): ?StoreCasePurchase
    {
        return $this->purchases()
            ->where('role', $role)
            ->where('status', 'completed')
            ->whereNull('active_case_id')
            ->first();
    }

    /**
     * Generate a unique case number.
     * 
     * @return string
     */
    public static function generateCaseNumber(): string
    {
        $year = date('Y');
        $lastCase = self::whereNotNull('case_number')
            ->where('case_number', 'like', $year . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(case_number, "-", -1) AS UNSIGNED) DESC')
            ->first();
        
        if ($lastCase) {
            $parts = explode('-', $lastCase->case_number);
            $number = intval(end($parts)) + 1;
        } else {
            $number = 1;
        }
        
        return $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
