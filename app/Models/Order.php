<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    // Mass assignable attributes
    protected $fillable = [
        'user_id',
        'course_id',
        'total_amount',
        'payment_status',
        'payment_reference',
    ];

    // Constants for payment status values
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';

    // Casts
    protected $casts = [
        'total_amount' => 'float',
    ];

    /**
     * User who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Course being purchased.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
} 