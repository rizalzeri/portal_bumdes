<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitUsaha extends Model
{
    use HasFactory;

    protected $table = 'unit_usaha_aktifs';
    protected $guarded = ['id'];

    public function bumdes(): BelongsTo
    {
        return $this->belongsTo(Bumdesa::class, 'bumdes_id');
    }
}
