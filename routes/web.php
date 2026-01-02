<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HalamanGuruController;
use App\Http\Controllers\HalamanMuridController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\HariController;
use App\Http\Controllers\PelajaranController;
use App\Http\Controllers\JamController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\MengajarController;
use App\Http\Controllers\GenerateController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\CetakController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login.index');
});
Route::get('/error', function () {
    return view('error.403');
})->name('access_denied');
Route::get('/login', [AuthController::class, 'index'])->name('login.index');
Route::post('/login/action', [AuthController::class, 'login_action'])->name('login.action');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profilemurid/{id}', [ProfileController::class, 'updatemurid'])->name('profilemurid.update');
    Route::put('/profileguru/{id}', [ProfileController::class, 'updateguru'])->name('profileguru.update');
    Route::put('/profilePassword/{id}', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::post('/profileImage/{id}', [ProfileController::class, 'updateProfileImage'])->name('profile.updateImage');

    Route::get('password', [UserController::class, 'password'])->name('password');
    Route::post('password', [UserController::class, 'password_action'])->name('password.action');

    Route::middleware(['auth', 'role:guru'])->group(function () {
        Route::get('/halaman_guru', [HalamanGuruController::class, 'index'])->name('user.site.halaman_guru');
        Route::get('/cetak/jadwalguru', [CetakController::class, 'jadwalguru'])->name('jadwal.guru');
    });
    
    Route::middleware(['auth', 'role:murid'])->group(function () {
        Route::get('/halaman_murid', [HalamanMuridController::class, 'index'])->name('user.site.halaman_murid');
        Route::get('/cetak/jadwalmurid', [CetakController::class, 'jadwalmurid'])->name('jadwal.murid');
    });    

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/halaman_admin', [DashboardController::class, 'index'])->name('admin.site.halaman_admin');

        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::get('/jurusan', [JurusanController::class, 'index'])->name('jurusan.index');
        Route::get('/jurusan/create', [JurusanController::class, 'create'])->name('jurusan.create');
        Route::post('/jurusan', [JurusanController::class, 'store'])->name('jurusan.store');
        Route::get('/jurusan/{id}/edit', [JurusanController::class, 'edit'])->name('jurusan.edit');
        Route::put('/jurusan/{id}', [JurusanController::class, 'update'])->name('jurusan.update');
        Route::delete('/jurusan/{id}', [JurusanController::class, 'destroy'])->name('jurusan.destroy');

        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{id}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        Route::get('/murid', [MuridController::class, 'index'])->name('murid.index');
        Route::get('/murid/create', [MuridController::class, 'create'])->name('murid.create');
        Route::post('/murid', [MuridController::class, 'store'])->name('murid.store');
        Route::get('/murid/{id}/edit', [MuridController::class, 'edit'])->name('murid.edit');
        Route::put('/murid/{id}', [MuridController::class, 'update'])->name('murid.update');
        Route::get('/murid/cetak', [CetakController::class, 'muridexcel'])->name('cetak.murid');
        Route::post('/import-murid', [MuridController::class, 'import'])->name('murid.import');
        Route::delete('/murid/{id}', [MuridController::class, 'destroy'])->name('murid.destroy');

        Route::get('/hari', [HariController::class, 'index'])->name('hari.index');
        Route::post('/hari', [HariController::class, 'store'])->name('hari.store');
        Route::get('/hari/{id}/edit', [HariController::class, 'edit'])->name('hari.edit');
        Route::put('/hari/{id}', [HariController::class, 'update'])->name('hari.update');
        Route::delete('/hari/{id}', [HariController::class, 'destroy'])->name('hari.destroy');

        Route::get('/jam', [JamController::class, 'index'])->name('jam.index');
        Route::post('/jam', [JamController::class, 'store'])->name('jam.store');
        Route::get('/jam/{id}/edit', [JamController::class, 'edit'])->name('jam.edit'); 
        Route::put('/jam/{id}', [JamController::class, 'update'])->name('jam.update');
        Route::delete('/jam/{id}', [JamController::class, 'destroy'])->name('jam.destroy');

        Route::get('/pelajaran', [PelajaranController::class, 'index'])->name('pelajaran.index');
        Route::post('/pelajaran', [PelajaranController::class, 'store'])->name('pelajaran.store');
        Route::get('/pelajaran/{id}/edit', [PelajaranController::class, 'edit'])->name('pelajaran.edit');
        Route::put('/pelajaran/{id}', [PelajaranController::class, 'update'])->name('pelajaran.update');
        Route::delete('/pelajaran/{id}', [PelajaranController::class, 'destroy'])->name('pelajaran.destroy');

        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{id}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{id}', [GuruController::class, 'update'])->name('guru.update');
        Route::get('/guru/cetak', [CetakController::class, 'guruexcel'])->name('cetak.guru');
        Route::post('/import-guru', [GuruController::class, 'import'])->name('guru.import');
        Route::delete('/guru/{id}', [GuruController::class, 'destroy'])->name('guru.destroy');

        Route::get('/mengajar', [MengajarController::class, 'index'])->name('mengajar.index');
        Route::get('/mengajar/create', [MengajarController::class, 'create'])->name('mengajar.create');
        Route::post('/mengajar', [MengajarController::class, 'store'])->name('mengajar.store');
        Route::get('/mengajar/{id}/edit', [MengajarController::class, 'edit'])->name('mengajar.edit');
        Route::put('/mengajar/{id}', [MengajarController::class, 'update'])->name('mengajar.update');
        Route::get('/mengajar/cetak', [CetakController::class, 'mengajarexcel'])->name('cetak.mengajar');
        Route::delete('/mengajar/{id}', [MengajarController::class, 'destroy'])->name('mengajar.destroy');
        Route::post('/mengajar/{id}/deleteall', [MengajarController::class, 'deleteAll'])->name('mengajar.deleteAll');
        Route::post('/mengajar/{id_pel}/getKelas', [MengajarController::class, 'getKelas'])->name('mengajar.getKelas');
        Route::get('/mengajar/{id}/lihat', [MengajarController::class, 'getGuru'])->name('mengajar.lihat');

        Route::get('/generate', [GenerateController::class, 'index'])->name('generate.index');
        Route::get('/generate/create', [GenerateController::class, 'create'])->name('generate.create');
        Route::post('/generate/jadwal', [GenerateController::class, 'generatejadwal'])->name('generate.jadwal');
        Route::post('/generate/simpan', [GenerateController::class, 'simpanjadwal'])->name('generate.simpan');
        
        Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/cetak', [CetakController::class, 'jadwalexcel'])->name('cetak.jadwal');
        Route::post('/import-jadwal', [JadwalController::class, 'import'])->name('jadwal.import');
    });
});