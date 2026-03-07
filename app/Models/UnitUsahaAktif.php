<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitUsahaAktif extends Model
{
    use HasFactory;

    protected $table = 'unit_usaha_aktifs';
    protected $guarded = ['id'];

    public function bumdes(): BelongsTo
    {
        return $this->belongsTo(Bumdes::class);
    }

    public function unitUsahaOption(): BelongsTo
    {
        return $this->belongsTo(UnitUsahaOption::class);
    }
}
