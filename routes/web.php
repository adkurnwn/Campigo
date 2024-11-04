<?php

use Illuminate\Support\Facades\Route;

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');

require __DIR__.'/web1.php';