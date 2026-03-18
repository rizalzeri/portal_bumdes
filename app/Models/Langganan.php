<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Langganan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'amount'     => 'decimal:2',
    ];

    public function bumdes(): BelongsTo
    {
        return $this->belongsTo(Bumdesa::class, 'bumdes_id');
    }

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id');
    }

    /**
     * Days remaining for active subscription
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'active' || !$this->end_date) return 0;
        $remaining = now()->diffInDays($this->end_date, false);
        return max(0, (int) $remaining);
    }

    /**
     * Check if subscription is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }
}
