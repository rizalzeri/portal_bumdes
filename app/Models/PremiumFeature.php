<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumFeature extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    /**
     * Daftar modul yang tersedia untuk diatur premiumnya
     */
    public static function getModules()
    {
        return [
            'profil' => 'Profil BUMDesa',
            'personalia' => 'Personaria / Struktur',
            'unit_usaha' => 'Unit Usaha',
            'produk' => 'Produk Desa',
            'ketapang' => 'Ketahanan Pangan',
            'galeri' => 'Galeri Kegiatan',
            'finansial' => 'Finansial / Keuangan',
            'transparansi' => 'Transparansi',
            'mitra' => 'Mitra Kerjasama',
            'kinerja' => 'Kinerja & Capaian',
            'artikel' => 'Artikel & Opini',
            'pengumuman' => 'Pengumuman',
        ];
    }

    /**
     * Daftar aksi CRUD
     */
    public static function getActions()
    {
        return [
            'view' => 'Melihat Menu (View)',
            'create' => 'Menambah Data (Create)',
            'update' => 'Mengubah Data (Update)',
            'delete' => 'Menghapus Data (Delete)',
            'special' => 'Fitur Khusus (Special)',
        ];
    }

    /**
     * Helper check if feature is permitted
     */
    public static function isAllowed($bumdes, $module, $action = 'view')
    {
        // 1. Cek aturan spesifik (misal: unit_usaha + create)
        $feature = self::where('module', '=', $module)
            ->where('action', '=', $action)
            ->first();

        // 2. Jika tidak ada aturan spesifik, cek apakah ada aturan "SPECIAL" untuk modul ini
        // Aksi "SPECIAL" dianggap sebagai aturan umum untuk modul tersebut
        if (!$feature) {
            $feature = self::where('module', '=', $module)
                ->where('action', '=', 'special')
                ->first();
        }
        
        // 3. Jika benar-benar tidak ada aturan di database, maka fitur ini GRATIS (Bebas)
        if (!$feature) return true; 
        
        // 4. Jika aturan ada tapi is_premium = false, maka GRATIS
        if (!$feature->is_premium) return true;

        // 5. Cek apakah BUMDesa ini punya langganan PREMIUM yang AKTIF
        // Kita cek di tabel langganan (dengan status aktif) dan masa berlaku belum habis
        $hasPremium = $bumdes->langganan()
            ->where('status', '=', 'active')
            ->where('end_date', '>', now())
            ->exists();

        // Tambahan: Cek juga field subscription_status di tabel user/bumdes jika ada sebagai cadangan
        if (!$hasPremium && isset($bumdes->user) && $bumdes->user->subscription_status === 'premium') {
            $hasPremium = true;
        }

        if ($hasPremium) return true;

        // --- LOGIKA UNTUK PENGGUNA GRATIS ---
        
        // 6. PRIORITAS: Jika aksi adalah 'view' dan diatur 'readonly', maka kita izinkan (agar list tetap tampil)
        if ($action === 'view' && $feature->fallback_action === 'readonly') {
            return true;
        }

        // 7. Jika aksi adalah untuk MODIFIKASI (create/update/delete) 
        // tapi diatur 'hide' atau 'readonly', maka tetap kita kembalikan false agar terproteksi.
        
        // 8. Cek apakah ada Limit (Contoh: Max 3 data)
        if ($feature->free_limit !== null) {
            return 'limit';
        }

        return false;
    }
}
