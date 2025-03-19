<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Forms\ShowPucharseOrder;
use App\Livewire\Forms\PucharseOrderDetail;
use App\Livewire\Settings\Index;
use App\Livewire\Settings\Notifications;
use App\Livewire\Settings\Password;

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

Route::middleware(['auth'])->group(function () {
    // Vista principal de Productos
    Route::view('products', 'products.index')
        ->name('products.index');

    // Formulario creación de productos
    Route::view('products/create', 'products.create')
        ->name('products.create');
});

// Rutas para documentación de envío
Route::middleware(['auth'])->group(function () {
    // Vista principal de documentación de envío
    Route::view('shipping-documentation', 'shipping-documentation.index')
        ->name('shipping-documentation.index');

    Route::view('shipping-documentation/create', 'shipping-documentation.create')
        ->name('shipping-documentation.create');

    // Rutas para órdenes de compra (si no existen ya)
    Route::view('purchase-orders', 'purchase-orders.kanban')
        ->name('purchase-orders.index');

    Route::view('new-purchase-order', 'new-purchase-order')
        ->name('new-purchase-order');

    Route::view('purchase-orders/consolidated-orders', 'purchase-orders.consolidated-orders')
        ->name('purchase-orders.consolidated-orders');

    // Ver detalles de una orden de compra
    Route::get('purchase-orders/{id}', ShowPucharseOrder::class)
        ->name('purchase-orders.show');

    // TODO: La ruta de arriba es lo mismo que esta (?)
    Route::get('purchase-orders/{id}/detail', PucharseOrderDetail::class)
        ->name('purchase-orders.detail');

    // Editar una orden de compra
    Route::view('purchase-orders/{id}/edit', 'purchase-orders.edit')
        ->name('purchase-orders.edit');

    // Kanban de órdenes de compra (si lo necesitas)
    Route::get('purchase-orders/kanban/{boardId?}', \App\Livewire\Kanban\KanbanBoard::class)
        ->name('purchase-orders.kanban');

    // Listar tableros Kanban (si lo necesitas)
    Route::get('purchase-orders/kanban-boards', \App\Livewire\Kanban\KanbanBoardList::class)
        ->name('purchase-orders.kanban-boards');
});

// Rutas para configuraciones
Route::middleware(['auth'])->group(function () {
    Route::get('settings', Index::class)
        ->name('settings.index');

    Route::get('settings/notifications', Notifications::class)
        ->name('settings.notifications');

    Route::get('settings/password', Password::class)
        ->name('settings.password');
});

require __DIR__ . '/auth.php';
