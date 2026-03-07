<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bumdes extends Model
{
    use HasFactory;

    protected $table = 'bumdes';
    protected $guarded = ['id'];

    public function kabupaten(): BelongsTo
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function unitUsahaAktifs(): HasMany
    {
        return $this->hasMany(UnitUsahaAktif::class);
    }

    public function produkBumdes(): HasMany
    {
        return $this->hasMany(ProdukBumdes::class);
    }

    public function produkKetahananPangans(): HasMany
    {
        return $this->hasMany(ProdukKetahananPangan::class);
    }

    public function mitraKerjasamas(): HasMany
    {
        return $this->hasMany(MitraKerjasama::class);
    }

    public function kinerjaCapaians(): HasMany
    {
        return $this->hasMany(KinerjaCapaian::class);
    }

    public function transparansis(): HasMany
    {
        return $this->hasMany(Transparansi::class);
    }

    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class);
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class);
    }

    public function galeris(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }

    public function katalogProduks(): HasMany
    {
        return $this->hasMany(KatalogProduk::class);
    }

    public function laporanKeuangans(): HasMany
    {
        return $this->hasMany(LaporanKeuangan::class);
    }

    public function personils(): HasMany
    {
        return $this->hasMany(Personil::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
