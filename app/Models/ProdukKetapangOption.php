<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdukKetapangOption extends Model
{
    use HasFactory;

    protected $table = 'produk_ketapang_options';
    protected $guarded = ['id'];

    public function produkKetahananPangans(): HasMany
    {
        return $this->hasMany(ProdukKetahananPangan::class);
    }
}
