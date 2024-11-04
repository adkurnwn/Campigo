<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

// Ensure the ItemController class exists in the specified namespace
// If it doesn't exist, create it in the appropriate directory

Route::get('/items', [ItemController::class, 'index']);