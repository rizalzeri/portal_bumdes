<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Province;
use App\Models\Kabupaten;
use App\Models\Bumdes;
use App\Models\UnitUsahaOption;
use App\Models\ProdukBumdesOption;
use App\Models\ProdukKetapangOption;
use App\Models\InfografisData;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Provinces (38 Provinces)
        $provinces = [
            'Aceh', 'Sumatera Utara', 'Sumatera Barat', 'Riau', 'Jambi', 'Sumatera Selatan', 'Bengkulu', 'Lampung', 'Kepulauan Bangka Belitung', 'Kepulauan Riau',
            'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'DI Yogyakarta', 'Jawa Timur', 'Banten',
            'Bali', 'Nusa Tenggara Barat', 'Nusa Tenggara Timur',
            'Kalimantan Barat', 'Kalimantan Tengah', 'Kalimantan Selatan', 'Kalimantan Timur', 'Kalimantan Utara',
            'Sulawesi Utara', 'Sulawesi Tengah', 'Sulawesi Selatan', 'Sulawesi Tenggara', 'Gorontalo', 'Sulawesi Barat',
            'Maluku', 'Maluku Utara',
            'Papua Barat', 'Papua', 'Papua Selatan', 'Papua Tengah', 'Papua Pegunungan', 'Papua Barat Daya'
        ];

        foreach ($provinces as $prov) {
            Province::create(['name' => $prov]);
        }

        // 2. Seed Kabupatens (10 sample kabupatens)
        $kabupatens = [
            ['province_id' => 12, 'name' => 'Kabupaten Bandung', 'slug' => 'kabupaten-bandung'],
            ['province_id' => 12, 'name' => 'Kabupaten Bogor', 'slug' => 'kabupaten-bogor'],
            ['province_id' => 13, 'name' => 'Kabupaten Banyumas', 'slug' => 'kabupaten-banyumas'],
            ['province_id' => 13, 'name' => 'Kabupaten Tegal', 'slug' => 'kabupaten-tegal'],
            ['province_id' => 15, 'name' => 'Kabupaten Malang', 'slug' => 'kabupaten-malang'],
            ['province_id' => 15, 'name' => 'Kabupaten Sidoarjo', 'slug' => 'kabupaten-sidoarjo'],
            ['province_id' => 14, 'name' => 'Kabupaten Bantul', 'slug' => 'kabupaten-bantul'],
            ['province_id' => 17, 'name' => 'Kabupaten Badung', 'slug' => 'kabupaten-badung'],
            ['province_id' => 20, 'name' => 'Kabupaten Kubu Raya', 'slug' => 'kabupaten-kubu-raya'],
            ['province_id' => 27, 'name' => 'Kabupaten Gowa', 'slug' => 'kabupaten-gowa'],
        ];

        foreach ($kabupatens as $kab) {
            Kabupaten::create($kab);
        }

        // 3. Seed BUMDes (5 BUMDes)
        $bumdesData = [
            [
                'kabupaten_id' => 4, // Tegal
                'name' => 'BUMDesa Bogares Kidul',
                'slug' => 'desa-bogares-kidul',
                'address' => 'Jl. Raya Bogares Kidul No 10, Tegal',
                'legal_status' => 'Berbadan Hukum',
                'email' => 'contact@bogareskidul.com',
                'phone' => '081234567890',
                'about' => 'BUMDesa Bogares Kidul berfokus pada kesejahteraan masyarakat desa melalui pengelolaan potensi desa.',
                'is_active' => true,
            ],
            [
                'kabupaten_id' => 1, // Bandung
                'name' => 'BUMDesa Maju Bersama',
                'slug' => 'desa-maju-bersama',
                'address' => 'Jl. Desa Maju No 1, Bandung',
                'legal_status' => 'Berbadan Hukum',
                'email' => 'maju@bersama.com',
                'phone' => '081234567891',
                'about' => 'Membangun ekonomi desa berbasis kerakyatan.',
                'is_active' => true,
            ],
            [
                'kabupaten_id' => 5, // Malang
                'name' => 'BUMDesa Tirta Jaya',
                'slug' => 'desa-tirta-jaya',
                'address' => 'Jl. Tirta Jaya No 5, Malang',
                'legal_status' => 'Berbadan Hukum',
                'email' => 'tirta@jaya.com',
                'phone' => '081234567892',
                'about' => 'Mengelola sumber air desa untuk kesejahteraan warga.',
                'is_active' => true,
            ],
            [
                'kabupaten_id' => 7, // Bantul
                'name' => 'BUMDesa Karya Makmur',
                'slug' => 'desa-karya-makmur',
                'address' => 'Jl. Karya Makmur No 2, Bantul',
                'legal_status' => 'Berbadan Hukum',
                'email' => 'karya@makmur.com',
                'phone' => '081234567893',
                'about' => 'Pengembangan pariwisata dan edukasi pertanian.',
                'is_active' => true,
            ],
            [
                'kabupaten_id' => 8, // Badung
                'name' => 'BUMDesa Bali Asri',
                'slug' => 'desa-bali-asri',
                'address' => 'Jl. Bali Asri No 8, Badung',
                'legal_status' => 'Berbadan Hukum',
                'email' => 'bali@asri.com',
                'phone' => '081234567894',
                'about' => 'Pelestarian budaya dan pengembangan pariwisata lokal.',
                'is_active' => true,
            ],
        ];

        foreach ($bumdesData as $bd) {
            Bumdes::create($bd);
        }

        // 4. Seed Users
        // Superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@portal.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'subscription_status' => 'active',
            'subscription_expiry' => Carbon::now()->addYears(10),
        ]);

        // Admin Kabupaten
        User::create([
            'name' => 'Admin Kabupaten Tegal',
            'email' => 'admin@kabupaten.com',
            'password' => Hash::make('password'),
            'role' => 'admin_kabupaten',
            'kabupaten_id' => 4,
            'subscription_status' => 'active',
            'subscription_expiry' => Carbon::now()->addYears(1),
        ]);

        // User BUMDes
        User::create([
            'name' => 'Admin BUMDesa Bogares',
            'email' => 'user@bumdes.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'bumdes_id' => 1,
            'subscription_status' => 'active',
            'subscription_expiry' => Carbon::now()->addYears(1),
        ]);

        // 5. Seed Options
        $unitUsahaOptions = ['Simpan Pinjam', 'Perdagangan', 'Pertanian', 'Pariwisata', 'Jasa Keuangan', 'Pengelolaan Air Bersih', 'Kerajinan', 'Peternakan', 'Perikanan', 'Pengolahan Sampah'];
        foreach ($unitUsahaOptions as $opt) {
            UnitUsahaOption::create(['name' => $opt]);
        }

        $produkOptions = ['Makanan Ringan', 'Minuman Tradisional', 'Kerajinan Tangan', 'Pakaian Adat', 'Souvenir Pariwisata', 'Sabun Herbal', 'Pupuk Organik', 'Beras Kemasan', 'Madu Hutan', 'Kopi Lokal'];
        foreach ($produkOptions as $opt) {
            ProdukBumdesOption::create(['name' => $opt]);
        }

        $ketapangOptions = ['Beras', 'Sayuran Organik', 'Bibit Padi', 'Pupuk Kompos', 'Telur Ayam', 'Daging Sapi', 'Ikan Nila', 'Bibit Lele', 'Buah-buahan', 'Jagung Manis'];
        foreach ($ketapangOptions as $opt) {
            ProdukKetapangOption::create(['name' => $opt]);
        }

        // 6. Infografis Seed
        InfografisData::create([
            'province_id' => null,
            'kabupaten_id' => null,
            'total_bumdes' => 50000,
            'bumdes_aktif' => 35000,
            'total_aset' => 150000000000,
            'total_pendapatan' => 50000000000,
            'total_unit_usaha' => 120000,
            'year' => '2025'
        ]);
        
        InfografisData::create([
            'province_id' => 13, // Jateng
            'kabupaten_id' => null,
            'total_bumdes' => 7000,
            'bumdes_aktif' => 5500,
            'total_aset' => 25000000000,
            'total_pendapatan' => 8000000000,
            'total_unit_usaha' => 15000,
            'year' => '2025'
        ]);

        InfografisData::create([
            'province_id' => 13,
            'kabupaten_id' => 4, // Tegal
            'total_bumdes' => 280,
            'bumdes_aktif' => 200,
            'total_aset' => 1500000000,
            'total_pendapatan' => 500000000,
            'total_unit_usaha' => 800,
            'year' => '2025'
        ]);
    }
}
