<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandarTransparansi extends Model
{
    use HasFactory;

    protected $table = 'transparansis';
    protected $guarded = ['id'];

    public function bumdes(): BelongsTo
    {
        return $this->belongsTo(Bumdesa::class, 'bumdes_id');
    }
}
