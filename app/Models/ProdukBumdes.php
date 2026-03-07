<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukBumdes extends Model
{
    use HasFactory;

    protected $table = 'produk_bumdes';
    protected $guarded = ['id'];

    public function bumdes(): BelongsTo
    {
        return $this->belongsTo(Bumdes::class);
    }

    public function produkOption(): BelongsTo
    {
        return $this->belongsTo(ProdukBumdesOption::class, 'produk_option_id');
    }
}
