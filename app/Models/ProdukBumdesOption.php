<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdukBumdesOption extends Model
{
    use HasFactory;

    protected $table = 'produk_bumdes_options';
    protected $guarded = ['id'];

    public function produkBumdes(): HasMany
    {
        return $this->hasMany(ProdukBumdes::class);
    }
}
