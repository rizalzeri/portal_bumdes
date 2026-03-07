<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitUsahaOption extends Model
{
    use HasFactory;

    protected $table = 'unit_usaha_options';
    protected $guarded = ['id'];

    public function unitUsahaAktifs(): HasMany
    {
        return $this->hasMany(UnitUsahaAktif::class);
    }
}
