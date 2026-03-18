<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingConfig extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active'            => 'boolean',
        'base_price_per_month' => 'decimal:2',
    ];

    /**
     * Total price for the plan (base_price_per_month * months)
     */
    public function getTotalPriceAttribute(): float
    {
        return (float) $this->base_price_per_month * $this->months;
    }

    /**
     * Only active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('months');
    }
}
