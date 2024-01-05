<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PesertaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/peserta', [PesertaController::class, 'index'])->name('peserta');

// UMUM
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

// KAMPUS
Route::post('/register-kampus', [AuthController::class, 'register_kampus']);
Route::post('/verifikasi-mahasiswa', [AuthController::class, 'verifikasi_peserta_mahasiswa']);
Route::post('/verifikasi-mahasiswa/buat-password', [AuthController::class, 'buatPassword']);
Route::post('/login-mahasiswa', [AuthController::class, 'login_mahasiswa'])->name('login');
Route::post('/reset-password', [AuthController::class, 'resetPassword']);



// SETELAH LOGIN
Route::middleware('auth:sanctum')->group(function () {
     Route::get('/profile', function(Request $request) {
           return $request->user()->createToken('api-token')->plainTextToken;
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::put('/edit-profile/peserta/{id}', [PesertaController::class, 'update']);
    Route::get('/show-profile/peserta/{id}', [PesertaController::class, 'showProfile']);
    Route::patch('/list', [PesertaController::class, 'index']);
});
