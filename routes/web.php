<?php

use App\Http\Middleware\CheckAuth;
use Illuminate\Support\Facades\Route;
use App\Models\OPD;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AduanController;
use App\Http\Controllers\PeranController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\UnitOpdController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AksesaduanController;
use App\Http\Controllers\DaftarAduanController;
use App\Http\Controllers\LaporanAduanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\Api\AduanPhotoController;

// Halaman utama
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// API-ish route to list uploaded photos for an aduan (used by frontend loadPhotos)
// Route::get('/api/opd/aduan/{id}/photos', [AduanPhotoController::class, 'index']); // Removed to use protected route below

// Auth routes - Guest only
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', function () {
        return inertia('Auth/ChooseRole');
    })->name('register');
    Route::get('/register/masyarakat', function () {
        return inertia('Auth/RegisterMasyarakat');
    })->name('register.masyarakat');
    Route::post('/register/masyarakat', [AuthController::class, 'registerMasyarakat'])->name('register.masyarakat.post');
    Route::get('/register/opd', function () {
        $opdList = OPD::select('id', 'nama_opd', 'no_telp', 'alamat')->get();
        return inertia('Auth/RegisterOPD', [
            'opdList' => $opdList,
        ]);
    })->name('register.opd');
    Route::post('/register/opd', [AuthController::class, 'registerOPD'])->name('register.opd.post');

    // Email Verification Routes
    Route::get('/verify-email/notice', [AuthController::class, 'verifyEmailNotice'])->name('verification.notice');
    Route::get('/verify-email/{token}', [AuthController::class, 'verifyEmail'])->name('verification.verify');
});

// Logout route - Authenticated only
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Master routes - Authenticated users only
Route::middleware(CheckAuth::class)->group(function () {
    // Pengguna routes
    Route::get('/master/pengguna', [PenggunaController::class, 'index'])->name('master.pengguna');
    Route::get('/master/pengguna/create', [PenggunaController::class, 'create'])->name('master.pengguna.create');
    Route::post('/master/pengguna', [PenggunaController::class, 'store'])->name('master.pengguna.store');
    Route::get('/master/pengguna/{pengguna}/edit', [PenggunaController::class, 'edit'])->name('master.pengguna.edit');
    Route::put('/master/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('master.pengguna.update');
    Route::delete('/master/pengguna/{pengguna}', [PenggunaController::class, 'destroy'])->name('master.pengguna.destroy');

    // Peran routes
    Route::get('/master/peran', [PeranController::class, 'index'])->name('master.peran');
    Route::get('/master/peran/create', [PeranController::class, 'create'])->name('master.peran.create');
    Route::post('/master/peran', [PeranController::class, 'store'])->name('master.peran.store');
    Route::get('/master/peran/{peran}/edit', [PeranController::class, 'edit'])->name('master.peran.edit');
    Route::put('/master/peran/{peran}', [PeranController::class, 'update'])->name('master.peran.update');
    Route::delete('/master/peran/{peran}', [PeranController::class, 'destroy'])->name('master.peran.destroy');

    // Kategori Aduan routes
    Route::get('/master/kategori', [KategoriController::class, 'index'])->name('master.kategori');
    Route::get('/master/kategori/create', [KategoriController::class, 'create'])->name('master.kategori.create');
    Route::post('/master/kategori', [KategoriController::class, 'store'])->name('master.kategori.store');
    Route::get('/master/kategori/{kategoriAduan}/edit', [KategoriController::class, 'edit'])->name('master.kategori.edit');
    Route::put('/master/kategori/{kategoriAduan}', [KategoriController::class, 'update'])->name('master.kategori.update');
    Route::delete('/master/kategori/{kategoriAduan}', [KategoriController::class, 'destroy'])->name('master.kategori.destroy');

    // Akses Aduan routes
    Route::get('/master/aksesaduan', [AksesaduanController::class, 'index'])->name('master.aksesaduan');
    Route::get('/master/aksesaduan/create', [AksesaduanController::class, 'create'])->name('master.aksesaduan.create');
    Route::post('/master/aksesaduan', [AksesaduanController::class, 'store'])->name('master.aksesaduan.store');
    Route::get('/master/aksesaduan/{aksesAduan}/edit', [AksesaduanController::class, 'edit'])->name('master.aksesaduan.edit');
    Route::put('/master/aksesaduan/{aksesAduan}', [AksesaduanController::class, 'update'])->name('master.aksesaduan.update');
    Route::delete('/master/aksesaduan/{aksesAduan}', [AksesaduanController::class, 'destroy'])->name('master.aksesaduan.destroy');

    // OPD routes
    Route::get('/master/opd', [OpdController::class, 'index'])->name('master.opd');
    Route::get('/master/opd/create', [OpdController::class, 'create'])->name('master.opd.create');
    Route::post('/master/opd', [OpdController::class, 'store'])->name('master.opd.store');
    Route::get('/master/opd/{opd}/edit', [OpdController::class, 'edit'])->name('master.opd.edit');
    Route::put('/master/opd/{opd}', [OpdController::class, 'update'])->name('master.opd.update');
    Route::delete('/master/opd/{opd}', [OpdController::class, 'destroy'])->name('master.opd.destroy');

    // Unit OPD routes
    Route::get('/master/unit-opd', [UnitOpdController::class, 'index'])->name('master.unitopd');
    Route::get('/master/unit-opd/create', [UnitOpdController::class, 'create'])->name('master.unitopd.create');
    Route::post('/master/unit-opd', [UnitOpdController::class, 'store'])->name('master.unitopd.store');
    Route::get('/master/unit-opd/{unitOpd}/edit', [UnitOpdController::class, 'edit'])->name('master.unitopd.edit');
    Route::put('/master/unit-opd/{unitOpd}', [UnitOpdController::class, 'update'])->name('master.unitopd.update');
    Route::delete('/master/unit-opd/{unitOpd}', [UnitOpdController::class, 'destroy'])->name('master.unitopd.destroy');
});
// manajemen aduan routes - Authenticated users only
Route::middleware(CheckAuth::class)->group(function () {
    Route::get('/manajemenaduan/daftaraduan', [DaftarAduanController::class, 'index'])->name('manajemenaduan.daftaraduan');
    Route::get('/manajemenaduan/daftaraduan/{aduan}', [DaftarAduanController::class, 'show'])->name('manajemenaduan.daftaraduan.show');
    Route::put('/manajemenaduan/daftaraduan/{aduan}/status', [DaftarAduanController::class, 'updateStatus'])->name('manajemenaduan.daftaraduan.updateStatus');
});
// laporan routes - Authenticated users only
Route::middleware(CheckAuth::class)->group(function () {
    Route::get('/laporan/laporanaduan', [LaporanAduanController::class, 'index'])->name('laporan.laporanaduan');
    Route::get('/laporan-pengaduan', [LaporanAduanController::class, 'fixLaporan'])->name('laporan.fixlaporan');
    Route::get('/laporan-pengaduan/{aduan}', [LaporanAduanController::class, 'show'])->name('laporan.fixlaporan.show');


    Route::post('/laporan/laporanaduan/export', [LaporanAduanController::class, 'export'])->name('laporan.laporanaduan.export');
});
// pengaturan routes - Authenticated users only
Route::middleware(CheckAuth::class)->group(function () {
    Route::get('/pengaturan/profile', [PengaturanController::class, 'profil'])->name('pengaturan.profil');
    Route::put('/pengaturan/profile', [PengaturanController::class, 'updateProfil'])->name('pengaturan.profil.update');
    Route::get('/pengaturan/password', [PengaturanController::class, 'ubahPassword'])->name('pengaturan.password');
    Route::put('/pengaturan/password', [PengaturanController::class, 'updatePassword'])->name('pengaturan.password.update');
    Route::get('/pengaturan/sistem', [PengaturanController::class, 'sistem'])->name('pengaturan.sistem');
});

// OPD routes - Only OPD staff can access
Route::middleware([CheckAuth::class, 'opd.only'])->group(function () {
    Route::get('/opd/dashboard', [OpdController::class, 'dashboard'])->name('opd.dashboard');
});

// OPD Wizard routes - Only OPD staff can access
Route::middleware([CheckAuth::class, 'opd.only'])->group(function () {
    Route::get('/opd/wizard/{aduan}', [OpdController::class, 'wizard'])->name('opd.wizard');
    Route::post('/opd/wizard/{aduan}/submit', [OpdController::class, 'submitWizard'])->name('opd.wizard.submit');
    Route::get('/api/opd/unit/{unitOpd}/pic', [OpdController::class, 'getPicByUnit'])->name('api.opd.unit.pic');
    Route::post('/api/opd/wizard/{aduan}/save', [OpdController::class, 'saveWizardPartial'])->name('api.opd.wizard.save');
    Route::get('/api/opd/wizard/{aduan}', [OpdController::class, 'getWizardDraft'])->name('api.opd.wizard.get');
    Route::get('/api/opd/aduan/{aduan}/photos', [OpdController::class, 'getAduanPhotos'])->name('api.opd.aduan.photos');
});


Route::middleware(CheckAuth::class)->group(function () {
    Route::get('/aduan/create', [AduanController::class, 'create'])
        ->name('aduan.create');

    Route::post('/aduan', [AduanController::class, 'store'])
        ->name('aduan.store');
});

// Complaint detail - Accessible by everyone
Route::get('/aduan/{id}', [AduanController::class, 'show'])
    ->whereNumber('id')
    ->name('aduan.show');

Route::post('/aduan/{id}/vote', [AduanController::class, 'vote'])
    ->whereNumber('id')
    ->name('aduan.vote');

// Location API
Route::post('/api/reverse-geocode', [LocationController::class, 'reverseGeocode'])
    ->name('api.reverse-geocode');
