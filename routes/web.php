<?php

use Illuminate\Support\Facades\Route;

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');


require __DIR__.'/api.php';
require __DIR__.'/auth.php';


// Move catch-all route to the end after all requires
Route::get('{any}', function () {
    return view('app');
})->where('any', '.*');

require __DIR__.'/web1.php';