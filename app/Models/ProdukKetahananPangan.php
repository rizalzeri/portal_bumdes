<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukKetahananPangan extends Model
{
    use HasFactory;

    protected $table = 'produk_ketahanan_pangans';
    protected $guarded = ['id'];

    public function bumdes(): BelongsTo
    {
        return $this->belongsTo(Bumdes::class);
    }

    public function produkKetapangOption(): BelongsTo
    {
        return $this->belongsTo(ProdukKetapangOption::class, 'produk_ketapang_option_id');
    }
}
