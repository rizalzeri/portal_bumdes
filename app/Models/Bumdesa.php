<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bumdesa extends Model
{
    use HasFactory;

    // Map Bumdesa directly to the bumdes table, since they are used interchangeably by controllers
    protected $table = 'bumdes';
    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
         return $this->belongsTo(User::class);
    }
    
    public function kabupaten(): BelongsTo
    {
         return $this->belongsTo(Kabupaten::class);
    }

    // ─── Has Many Relationships ─────────────────────────────────

    public function laporanKeuangan(): HasMany
    {
         return $this->hasMany(LaporanKeuangan::class, 'bumdes_id');
    }

    public function unitUsaha(): HasMany
    {
        return $this->hasMany(UnitUsaha::class, 'bumdes_id');
    }

    public function katalogProduk(): HasMany
    {
        return $this->hasMany(KatalogProduk::class, 'bumdes_id');
    }

    public function artikel(): HasMany
    {
        return $this->hasMany(Artikel::class, 'bumdes_id');
    }

    public function pengurus(): HasMany
    {
        return $this->hasMany(Pengurus::class, 'bumdes_id');
    }

    public function personils(): HasMany
    {
        return $this->hasMany(Personil::class, 'bumdes_id');
    }

    public function transparansi(): HasMany
    {
        return $this->hasMany(StandarTransparansi::class, 'bumdes_id');
    }

    public function mitraKerjasama(): HasMany
    {
        return $this->hasMany(MitraKerjasama::class, 'bumdes_id');
    }

    public function mitraKerjasamas(): HasMany
    {
        return $this->hasMany(MitraKerjasama::class, 'bumdes_id');
    }

    public function kinerjaCapaian(): HasMany
    {
        return $this->hasMany(KinerjaCapaian::class, 'bumdes_id');
    }

    public function kinerjaCapaians(): HasMany
    {
        return $this->hasMany(KinerjaCapaian::class, 'bumdes_id');
    }

    public function galeri(): HasMany
    {
        return $this->hasMany(Galeri::class, 'bumdes_id');
    }

    public function galeris(): HasMany
    {
        return $this->hasMany(Galeri::class, 'bumdes_id');
    }

    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class, 'bumdes_id');
    }

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class, 'bumdes_id');
    }

    public function langganan(): HasMany
    {
        return $this->hasMany(Langganan::class, 'bumdes_id');
    }

    public function langganans(): HasMany
    {
        return $this->hasMany(Langganan::class, 'bumdes_id');
    }

    // Alias relationships used by PublicController
    public function unitUsahaAktifs(): HasMany
    {
        return $this->hasMany(UnitUsaha::class, 'bumdes_id');
    }

    public function produkBumdes(): HasMany
    {
        return $this->hasMany(ProdukBumdes::class, 'bumdes_id');
    }

    public function produkKetahananPangans(): HasMany
    {
        return $this->hasMany(ProdukKetahananPangan::class, 'bumdes_id');
    }

    public function katalogProduks(): HasMany
    {
        return $this->hasMany(KatalogProduk::class, 'bumdes_id');
    }

    public function transparansis(): HasMany
    {
        return $this->hasMany(StandarTransparansi::class, 'bumdes_id');
    }
}
