<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Based on database migration 'galeris'
    protected $table = 'galeris';
    protected $guarded = ['id'];
}
