<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukOption extends Model
{
    use HasFactory;

    // Alias to the original table
    protected $table = 'produk_bumdes_options';
    protected $guarded = ['id'];
}
