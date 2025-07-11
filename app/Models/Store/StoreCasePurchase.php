<?php

namespace App\Models\Store;

use App\Models\User;
use App\Models\VirtualCourt\ActiveCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreCasePurchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'case_file_id',
        'user_id',
        'role',
        'price',
        'payment_method',
        'transaction_id',
        'status',
        'active_case_id',
        'activated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'activated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'is_activated_in_court',
    ];

    /**
     * Get the case file that was purchased.
     */
    public function caseFile(): BelongsTo
    {
        return $this->belongsTo(StoreCaseFile::class, 'case_file_id');
    }

    /**
     * Get the user that made the purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the active case associated with the purchase.
     */
    public function activeCase(): BelongsTo
    {
        return $this->belongsTo(ActiveCase::class, 'active_case_id');
    }

    /**
     * Scope a query to only include completed purchases.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending purchases.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include activated purchases.
     */
    public function scopeActivated($query)
    {
        return $query->whereNotNull('active_case_id');
    }

    /**
     * Scope a query to only include purchases for a specific role.
     */
    public function scopeForRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Check if the purchase is activated.
     */
    public function isActivated(): bool
    {
        return $this->active_case_id !== null;
    }

    /**
     * Activate the purchase by associating it with an active case.
     */
    public function activate(int $activeCaseId): void
    {
        $this->update([
            'active_case_id' => $activeCaseId,
            'activated_at' => now(),
        ]);
    }

    /**
     * Get whether the purchase is activated in the virtual court.
     *
     * @return bool
     */
    public function getIsActivatedInCourtAttribute(): bool
    {
        return $this->active_case_id !== null;
    }
}
