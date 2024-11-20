<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiSewaController;

// Add this route to handle requests for Barang data
Route::get('/api/barang', [BarangController::class, 'index']);
Route::get('/api/barang/{id}', [BarangController::class, 'show']);

Route::get('/api/auth-status', function () {
    return response()->json(['authenticated' => Auth::check()]);
});

Route::post('/api/cart/add/{id}', [BarangController::class, 'addToCart']);
Route::get('/api/cart', [BarangController::class, 'viewCart']);
Route::delete('/api/cart/remove/{id}', [BarangController::class, 'removeFromCart']);
Route::put('/api/cart/update/{id}', [BarangController::class, 'updateCart']);

Route::post('/api/pesan', [TransaksiSewaController::class, 'store'])->name('pesan.store');

Route::get('/api/user-transactions', [TransaksiSewaController::class, 'getUserTransactions']);

Route::get('/api/user/', [ProfileController::class, 'getInfo']);
Route::patch('/api/profile/update', [ProfileController::class, 'update']);
Route::put('/api/profile/password', [ProfileController::class, 'password']);
Route::delete('/api/profile/delete', [ProfileController::class, 'destroy']);

