<?php

use App\Http\Controllers\AdminKabupaten\BumdesController as AKBumdes;
use App\Http\Controllers\AdminKabupaten\DashboardController as AKDashboard;
use App\Http\Controllers\AdminKabupaten\KeuanganController as AKKeuangan;
// Super Admin Controllers
use App\Http\Controllers\AdminKabupaten\LanggananController as AKLangganan;
use App\Http\Controllers\AdminKabupaten\PengumumanController as AKPengumuman;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SuperAdmin\ArtikelController as SAArtikel;
use App\Http\Controllers\SuperAdmin\DashboardController as SADashboard;
use App\Http\Controllers\SuperAdmin\DataMasterController as SADataMaster;
use App\Http\Controllers\SuperAdmin\GaleriController as SAGaleri;
use App\Http\Controllers\SuperAdmin\KabupatenController as SAKabupaten;
use App\Http\Controllers\SuperAdmin\KatalogController as SAKatalog;
use App\Http\Controllers\SuperAdmin\LanggananController as SALangganan;
// Admin Kabupaten Controllers
use App\Http\Controllers\SuperAdmin\MateriController as SAMateri;
use App\Http\Controllers\SuperAdmin\MitraController as SAMitra;
use App\Http\Controllers\SuperAdmin\PengumumanController as SAPengumuman;
use App\Http\Controllers\SuperAdmin\UserController as SAUser;
use App\Http\Controllers\User\ArtikelController as UArtikel;
// User BUMDes Controllers
use App\Http\Controllers\User\DashboardController as UDashboard;
use App\Http\Controllers\User\FinansialController as UFinansial;
use App\Http\Controllers\User\GaleriController as UGaleri;
use App\Http\Controllers\User\KinerjaController as UKinerja;
use App\Http\Controllers\User\LanggananController as ULangganan;
use App\Http\Controllers\User\MitraController as UMitra;
use App\Http\Controllers\User\PengumumanController as UPengumuman;
use App\Http\Controllers\User\PersonaliaController as UPersonalia;
use App\Http\Controllers\User\ProdukController as UProduk;
use App\Http\Controllers\User\ProdukKetapangController as UKetapang;
use App\Http\Controllers\User\ProfilController as UProfil;
use App\Http\Controllers\User\TransparansiController as UTransparansi;
use App\Http\Controllers\User\UnitUsahaController as UUnitUsaha;
use Illuminate\Support\Facades\Route;

// ------------- PUBLIC ROUTES -------------
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/kunjungi-bumdes', [PublicController::class, 'kunjungiBumdes'])->name('public.bumdes.list');
// Route /bumdes/{slug} dihapus - sekarang langsung /{slug}
Route::get('/infografis', [PublicController::class, 'infografis'])->name('public.infografis');
Route::get('/infografis/kabupaten/{id}', [PublicController::class, 'infografisKabupaten'])->name('public.infografis.kabupaten');
Route::get('/materi', [PublicController::class, 'materi'])->name('public.materi');
Route::get('/papan-informasi', [PublicController::class, 'papanInformasi'])->name('public.informasi');
Route::get('/katalog-produk', [PublicController::class, 'katalog'])->name('public.katalog');
Route::get('/mitra', [PublicController::class, 'mitra'])->name('public.mitra');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('public.galeri');
Route::get('/pengumuman', [PublicController::class, 'pengumuman'])->name('public.pengumuman');

Route::get('/buat-website', [\App\Http\Controllers\PublicRegistrationController::class, 'create'])->name('public.register');
Route::post('/buat-website', [\App\Http\Controllers\PublicRegistrationController::class, 'store'])->name('public.register.store');
Route::get('/api/kabupatens/{province}', [\App\Http\Controllers\PublicRegistrationController::class, 'getKabupatens'])->name('api.kabupatens');

// Generic route for login fallback
Route::get('/login', [AuthController::class, 'showUserLogin'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------- SUPER ADMIN ROUTES -------------
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showSuperAdminLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'superAdminLogin'])->name('login.post');

    Route::middleware(['auth', 'superadmin'])->group(function () {
        Route::get('/dashboard', [SADashboard::class, 'index'])->name('dashboard');
        Route::resource('kabupaten', SAKabupaten::class);
        Route::get('/infografis-featured', [SAKabupaten::class, 'infografisIndex'])->name('infografis.index');
        Route::post('/infografis-featured/toggle/{id}', [SAKabupaten::class, 'toggleFeatured'])->name('infografis.toggle_featured');
        Route::resource('adminkab', \App\Http\Controllers\SuperAdmin\AdminKabupatenController::class)->except(['show', 'edit', 'update']);
        Route::resource('user', SAUser::class);
        Route::resource('langganan', SALangganan::class);
        Route::resource('materi', SAMateri::class);
        Route::post('materi/toggle-featured/{id}', [SAMateri::class, 'toggleFeatured'])->name('materi.toggle_featured');

        Route::resource('artikel', SAArtikel::class);
        Route::post('artikel/toggle-featured/{id}', [SAArtikel::class, 'toggleFeatured'])->name('artikel.toggle_featured');

        Route::resource('katalog', SAKatalog::class);
        Route::post('katalog/toggle-featured/{id}', [SAKatalog::class, 'toggleFeatured'])->name('katalog.toggle_featured');

        Route::resource('mitra', SAMitra::class);
        Route::post('mitra/toggle-featured/{id}', [SAMitra::class, 'toggleFeatured'])->name('mitra.toggle_featured');

        Route::resource('galeri', SAGaleri::class);
        Route::post('galeri/toggle-featured/{id}', [SAGaleri::class, 'toggleFeatured'])->name('galeri.toggle_featured');

        Route::resource('pengumuman', SAPengumuman::class);
        Route::post('pengumuman/toggle-featured/{id}', [SAPengumuman::class, 'toggleFeatured'])->name('pengumuman.toggle_featured');
        Route::post('datamaster/inline-update/{id}', [SADataMaster::class, 'inlineUpdate'])->name('datamaster.inline_update');
        Route::resource('datamaster', SADataMaster::class);
        Route::post('premium-features/inline-update/{id}', [\App\Http\Controllers\SuperAdmin\PremiumFeatureController::class, 'inlineUpdate'])->name('premium-features.inline_update');
        Route::resource('premium-features', \App\Http\Controllers\SuperAdmin\PremiumFeatureController::class);
    });
});

// ------------- ADMIN KABUPATEN ROUTES -------------
Route::prefix('admin-kabupaten')->name('adminkab.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminKabupatenLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'adminKabupatenLogin'])->name('login.post');

    Route::middleware(['auth', 'admin_kabupaten'])->group(function () {
        Route::get('/dashboard', [AKDashboard::class, 'index'])->name('dashboard');
        Route::resource('bumdes', AKBumdes::class);
        Route::post('bumdes/toggle-status/{id}', [AKBumdes::class, 'toggleStatus'])->name('bumdes.toggle_status');
        Route::resource('keuangan', AKKeuangan::class);
        Route::resource('pengumuman', AKPengumuman::class);
        Route::resource('langganan', AKLangganan::class);
    });
});

// ------------- USER BUMDES ROUTES (Dynamic Slug Based) -------------
Route::prefix('{slug}')->middleware(['auth', 'user', 'premium_check'])->name('user.')->group(function () {
    Route::get('/dashboard', [UDashboard::class, 'index'])->name('dashboard');
    Route::get('/profil', [UProfil::class, 'index'])->name('profil.index');
    Route::put('/profil', [UProfil::class, 'update'])->name('profil.update');
    Route::resource('personalia', UPersonalia::class);
    Route::resource('unit_usaha', UUnitUsaha::class);
    Route::resource('produk', UProduk::class);
    Route::resource('ketapang', UKetapang::class);
    Route::resource('galeri', UGaleri::class);
    Route::resource('finansial', UFinansial::class);
    Route::resource('transparansi', UTransparansi::class);
    Route::resource('mitra', UMitra::class);
    Route::resource('kinerja', UKinerja::class);
    Route::resource('artikel', UArtikel::class);
    Route::resource('pengumuman', UPengumuman::class);
    Route::resource('langganan', ULangganan::class);

    // Handling Midtrans callback/checkout specific routes
    Route::get('langganan/success', [ULangganan::class, 'successCallback'])->name('langganan.success');
});

// ------------- PUBLIC AUTH ROUTES -------------
Route::get('/user/login', [AuthController::class, 'showUserLogin'])->name('login');
Route::post('/user/login', [AuthController::class, 'userLogin'])->name('user.login.post');

// Catch-all route for dynamic BUMDes domains (must be at the very end)
Route::get('/{slug}', [PublicController::class, 'bumdesProfile'])->name('public.bumdes.profile');
