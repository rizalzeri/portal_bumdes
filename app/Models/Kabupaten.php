<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupatens';
    protected $guarded = ['id'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function bumdes(): HasMany
    {
        return $this->hasMany(Bumdes::class);
    }

    public function infografisData(): HasMany
    {
        return $this->hasMany(InfografisData::class);
    }
}
