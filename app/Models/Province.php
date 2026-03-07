<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    protected $guarded = ['id'];

    public function kabupatens(): HasMany
    {
        return $this->hasMany(Kabupaten::class);
    }

    public function infografisData(): HasMany
    {
        return $this->hasMany(InfografisData::class);
    }
}
