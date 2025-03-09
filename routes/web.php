<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')
    ->name('welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// TODO: modificar nombre de ruta y componente
Route::view('new-purchase-order', 'new-purchase-order')
    ->middleware(['auth'])
    ->name('new-purchase-order');

Route::view('products', 'products.index')
    ->middleware('auth')
    ->name('products');

Route::view('products/create', 'products.create')
    ->middleware('auth')
    ->name('products.create');

require __DIR__ . '/auth.php';
