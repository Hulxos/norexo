<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    use HasFactory;

    protected $primaryKey = 'sale_id';

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'delivery_address',
        'delivery_city',
        'delivery_province',
        'delivery_postal_code',
        'estimated_delivery',
        'delivered_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'estimated_delivery' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get user who made the purchase
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get sale details
     */
    public function details(): HasMany
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'sale_id');
    }

    /**
     * Get reviews for this sale
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'sale_id', 'sale_id');
    }

    /**
     * Get transactions for this sale
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'sale_id', 'sale_id');
    }

    /**
     * Check if user can review this sale
     */
    public function canReview(): bool
    {
        return $this->status === 'delivered' && $this->reviews()->count() === 0;
    }
}
