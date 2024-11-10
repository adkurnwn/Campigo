<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;

// Add this route to handle requests for Barang data
Route::get('/api/barang', [BarangController::class, 'index']);

Route::get('/api/auth-status', function () {
    return response()->json(['authenticated' => Auth::check()]);
});

Route::middleware('auth:sanctum')->group(function () {
    // Profile routes using existing ProfileController methods
    Route::get('/user', [ProfileController::class, 'edit'])->name('api.profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('api.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('api.profile.password');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('api.profile.destroy');
});