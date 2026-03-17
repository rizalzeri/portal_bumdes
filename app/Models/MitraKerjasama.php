<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MitraKerjasama extends Model
{
    use HasFactory;

    protected $table = 'mitra_kerjasamas';
    protected $guarded = ['id'];

    public function bumdes(): BelongsTo
    {
        return $this->belongsTo(Bumdesa::class); // usually it's Bumdesa, not Bumdes
    }

    public function mitraOption(): BelongsTo
    {
        return $this->belongsTo(MitraOption::class);
    }
}
