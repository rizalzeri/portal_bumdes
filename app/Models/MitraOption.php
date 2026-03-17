<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MitraOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function mitraKerjasamas(): HasMany
    {
        return $this->hasMany(MitraKerjasama::class);
    }
}
