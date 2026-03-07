<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;

// Super Admin Controllers
use App\Http\Controllers\SuperAdmin\DashboardController as SADashboard;
use App\Http\Controllers\SuperAdmin\KabupatenController as SAKabupaten;
use App\Http\Controllers\SuperAdmin\UserController as SAUser;
use App\Http\Controllers\SuperAdmin\LanggananController as SALangganan;
use App\Http\Controllers\SuperAdmin\MateriController as SAMateri;
use App\Http\Controllers\SuperAdmin\ArtikelController as SAArtikel;
use App\Http\Controllers\SuperAdmin\KatalogController as SAKatalog;
use App\Http\Controllers\SuperAdmin\MitraController as SAMitra;
use App\Http\Controllers\SuperAdmin\GaleriController as SAGaleri;
use App\Http\Controllers\SuperAdmin\PengumumanController as SAPengumuman;
use App\Http\Controllers\SuperAdmin\DataMasterController as SADataMaster;

// Admin Kabupaten Controllers
use App\Http\Controllers\AdminKabupaten\DashboardController as AKDashboard;
use App\Http\Controllers\AdminKabupaten\BumdesController as AKBumdes;
use App\Http\Controllers\AdminKabupaten\KeuanganController as AKKeuangan;
use App\Http\Controllers\AdminKabupaten\PengumumanController as AKPengumuman;
use App\Http\Controllers\AdminKabupaten\LanggananController as AKLangganan;

// User BUMDes Controllers
use App\Http\Controllers\User\DashboardController as UDashboard;
use App\Http\Controllers\User\ProfilController as UProfil;
use App\Http\Controllers\User\PersonaliaController as UPersonalia;
use App\Http\Controllers\User\UnitUsahaController as UUnitUsaha;
use App\Http\Controllers\User\ProdukController as UProduk;
use App\Http\Controllers\User\ProdukKetapangController as UKetapang;
use App\Http\Controllers\User\GaleriController as UGaleri;
use App\Http\Controllers\User\FinansialController as UFinansial;
use App\Http\Controllers\User\TransparansiController as UTransparansi;
use App\Http\Controllers\User\MitraController as UMitra;
use App\Http\Controllers\User\KinerjaController as UKinerja;
use App\Http\Controllers\User\ArtikelController as UArtikel;
use App\Http\Controllers\User\LanggananController as ULangganan;

// ------------- PUBLIC ROUTES -------------
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/kunjungi-bumdes', [PublicController::class, 'kunjungiBumdes'])->name('public.bumdes.list');
Route::get('/bumdes/{slug}', [PublicController::class, 'bumdesProfile'])->name('public.bumdes.profile');
Route::get('/infografis', [PublicController::class, 'infografis'])->name('public.infografis');
Route::get('/materi', [PublicController::class, 'materi'])->name('public.materi');
Route::get('/papan-informasi', [PublicController::class, 'papanInformasi'])->name('public.informasi');
Route::get('/katalog-produk', [PublicController::class, 'katalog'])->name('public.katalog');
Route::get('/mitra', [PublicController::class, 'mitra'])->name('public.mitra');
Route::get('/galeri', [PublicController::class, 'galeri'])->name('public.galeri');

// Generic route for login fallback
Route::get('/login', [AuthController::class, 'showUserLogin'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ------------- SUPER ADMIN ROUTES -------------
Route::prefix('superadmin')->name('superadmin.')->group(function() {
    Route::get('/login', [AuthController::class, 'showSuperAdminLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'superAdminLogin'])->name('login.post');
    
    Route::middleware(['auth', 'superadmin'])->group(function() {
        Route::get('/dashboard', [SADashboard::class, 'index'])->name('dashboard');
        Route::resource('kabupaten', SAKabupaten::class);
        Route::resource('user', SAUser::class);
        Route::resource('langganan', SALangganan::class);
        Route::resource('materi', SAMateri::class);
        Route::resource('artikel', SAArtikel::class);
        Route::resource('katalog', SAKatalog::class);
        Route::resource('mitra', SAMitra::class);
        Route::resource('galeri', SAGaleri::class);
        Route::resource('pengumuman', SAPengumuman::class);
        Route::post('datamaster/inline-update/{id}', [SADataMaster::class, 'inlineUpdate'])->name('datamaster.inline_update');
        Route::resource('datamaster', SADataMaster::class);
    });
});

// ------------- ADMIN KABUPATEN ROUTES -------------
Route::prefix('admin-kabupaten')->name('adminkab.')->group(function() {
    Route::get('/login', [AuthController::class, 'showAdminKabupatenLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'adminKabupatenLogin'])->name('login.post');
    
    Route::middleware(['auth', 'admin_kabupaten'])->group(function() {
        Route::get('/dashboard', [AKDashboard::class, 'index'])->name('dashboard');
        Route::resource('bumdes', AKBumdes::class);
        Route::resource('keuangan', AKKeuangan::class);
        Route::resource('pengumuman', AKPengumuman::class);
        Route::resource('langganan', AKLangganan::class);
    });
});

// ------------- USER BUMDES ROUTES -------------
Route::prefix('user')->name('user.')->group(function() {
    Route::get('/login', [AuthController::class, 'showUserLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'userLogin'])->name('login.post');
    
    Route::middleware(['auth', 'user'])->group(function() {
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
        Route::resource('langganan', ULangganan::class);
        
        // Handling Midtrans callback/checkout specific routes
        Route::get('langganan/success', [ULangganan::class, 'successCallback'])->name('langganan.success');
    });
});
