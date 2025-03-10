<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Forms\ShowPucharseOrder;

Route::view('/', 'welcome')
    ->name('welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('new-purchase-order', 'new-purchase-order')
    ->middleware(['auth'])
    ->name('new-purchase-order');

Route::view('products/create', 'products.create')
    ->middleware(['auth'])
    ->name('products.create');

// Rutas para órdenes de compra
Route::middleware(['auth'])->group(function () {
    // Listar órdenes de compra
    Route::view('purchase-orders', 'purchase-orders.index')
        ->name('purchase-orders.index');

    // Listar tableros Kanban
    Route::get('purchase-orders/kanban-boards', \App\Livewire\Kanban\KanbanBoardList::class)
        ->name('purchase-orders.kanban-boards');

    // Kanban de órdenes de compra
    Route::get('purchase-orders/kanban/{boardId?}', \App\Livewire\Kanban\KanbanBoard::class)
        ->name('purchase-orders.kanban');

    // Ver detalles de una orden de compra
    Route::get('purchase-orders/{id}', ShowPucharseOrder::class)
        ->name('purchase-orders.show');

    // Editar una orden de compra (redirige al formulario de edición)
    Route::view('purchase-orders/{id}/edit', 'purchase-orders.edit')
        ->name('purchase-orders.edit');
});

require __DIR__ . '/auth.php';
