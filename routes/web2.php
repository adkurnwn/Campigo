
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', function () {
        return view('app');
    });
    
    Route::get('/myrent', function () {
        return view('app');
    });
});