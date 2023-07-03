<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestReportController;
use App\Http\Controllers\StrukturController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
})->middleware(['auth', 'verified'])->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// pegawai
Route::get('/manajemen-pegawai', [PegawaiController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('manajemen-pegawai');

Route::put('/manajemen-pegawai/{id}', [PegawaiController::class, 'update'])->name('pegawai.update');
Route::delete('/manajemen-pegawai/{id}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
Route::post('/manajemen-pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');

// struktur
Route::get('/manajemen-struktur', [StrukturController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('manajemen-struktur');

Route::put('/manajemen-struktur/{id}', [StrukturController::class, 'update'])->name('struktur.update');
Route::delete('/manajemen-struktur/{id}', [StrukturController::class, 'destroy'])->name('struktur.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//CRUD REQUEST
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('request', RequestController::class);
    Route::any('/request/sendemailrequest/{id}', [RequestController::class,'sendemailrequest'])->name('request.sendemailrequest');
    Route::any('/request/loadviewstatusrequest/{id}', [RequestController::class,'loadviewstatusrequest'])->name('request.loadviewstatusrequest');
    Route::resource('struktur', StrukturController::class);

    Route::get('request-report', [RequestReportController::class, 'index'])->name('requestreport.edit');
});

Route::any('/request/updatestatusrequest/{id}', [RequestController::class,'updatestatusrequest']);

Route::middleware('auth')->group(function () {

    Route::any('/file/loadberkas', [FileController::class, 'loadberkas']);
    Route::any('/file/uploadfile', [FileController::class, 'uploadfile']);
    Route::any('/file/deletefile', [FileController::class, 'deletefile']);
});


require __DIR__.'/auth.php';
