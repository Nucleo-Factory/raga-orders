<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Forms\ShowPucharseOrder;
use App\Livewire\Forms\PucharseOrderDetail;
use App\Livewire\Settings\Index;
use App\Livewire\Settings\Notifications;
use App\Livewire\Settings\Password;
use App\Http\Controllers\VendorController;
use App\Livewire\Settings\History;
use App\Livewire\Settings\Roles;
use App\Livewire\Settings\RoleEdit;
use App\Livewire\Forms\PucharseOrderConsolidateDetail;
use App\Livewire\Settings\ActiveSessions;
use App\Livewire\Settings\Kanban;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Stages;
use App\Livewire\Settings\Users;
use App\Livewire\Settings\RoleCreate;
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

    Route::get('products/{product}/edit', function ($product) {
        return view('products.edit', ['product' => \App\Models\Product::findOrFail($product)]);
    })->name('products.edit');

    Route::view('products/forecast', 'products.forecast')
        ->name('products.forecast');
});

// Rutas para documentación de envío
Route::middleware(['auth'])->group(function () {
    // Vista principal de documentación de envío
    Route::view('shipping-documentation', 'shipping-documentation.index')
        ->name('shipping-documentation.index');

    Route::view('shipping-documentation/create', 'shipping-documentation.create')
        ->name('shipping-documentation.create');

    // Rutas para proveedores
    Route::view('vendors', 'vendors.index')
        ->name('vendors.index');

    Route::view('vendors/create', 'vendors.create')
        ->name('vendors.create');

    Route::get('vendors/{vendor}/edit', function ($vendor) {
        return view('vendors.edit', ['vendor' => \App\Models\Vendor::findOrFail($vendor)]);
    })->name('vendors.edit');

    // Rutas para direcciones de envío (ship-to)
    Route::view('ship-to', 'ship-to.index')
        ->name('ship-to.index');

    Route::view('ship-to/create', 'ship-to.create')
        ->name('ship-to.create');

    Route::get('ship-to/{shipTo}/edit', function ($shipTo) {
        return view('ship-to.edit', ['shipTo' => \App\Models\ShipTo::findOrFail($shipTo)]);
    })->name('ship-to.edit');

    Route::view('shipping-documentation/requests', 'shipping-documentation.requests')
        ->name('shipping-documentation.requests');

    // Rutas para órdenes de compra (si no existen ya)
    Route::view('purchase-orders', 'purchase-orders.index')
        ->name('purchase-orders.index');

    // Formulario creación de órdenes de compra
    Route::view('purchase-orders/create', 'purchase-orders.create')
        ->name('purchase-orders.create');

    // Seguimiento órdenes de compra
    Route::view('purchase-orders/tracking', 'purchase-orders.kanban')
        ->name('purchase-orders.tracking');

    Route::view('purchase-orders/consolidated-orders', 'purchase-orders.consolidated-orders')
        ->name('purchase-orders.consolidated-orders');

    Route::get('purchase-orders/consolidated-orders/{id}/detail', PucharseOrderConsolidateDetail::class)
        ->name('purchase-orders.consolidated-order-detail');

    // Solicitudes y aprobaciones
    Route::view('purchase-orders/requests', 'purchase-orders.requests')
        ->name('purchase-orders.requests');

    // Kanban de órdenes de compra
    Route::get('purchase-orders/kanban/{boardId?}', \App\Livewire\Kanban\KanbanBoard::class)
        ->name('purchase-orders.kanban');

    // Listar tableros Kanban
    Route::get('purchase-orders/kanban-boards', \App\Livewire\Kanban\KanbanBoardList::class)
        ->name('purchase-orders.kanban-boards');

    // Ver detalles de una orden de compra
    Route::get('purchase-orders/{id}', ShowPucharseOrder::class)
        ->name('purchase-orders.show');

    // TODO: La ruta de arriba es lo mismo que esta (?)
    Route::get('purchase-orders/{id}/detail', PucharseOrderDetail::class)
        ->name('purchase-orders.detail');

    // Editar una orden de compra
    Route::view('purchase-orders/{id}/edit', 'purchase-orders.edit')
        ->name('purchase-orders.edit');
});

// Rutas para configuraciones
Route::middleware(['auth'])->group(function () {
    Route::get('settings', Index::class)
        ->name('settings.index');

    Route::get('settings/notifications', Notifications::class)
        ->name('settings.notifications');

    Route::get('settings/password', Password::class)
        ->name('settings.password');

    Route::get('settings/history', History::class)
        ->name('settings.history');

    Route::get('settings/roles', Roles::class)
        ->name('settings.roles');

    Route::get('settings/roles/{roleId}/edit', RoleEdit::class)
        ->name('settings.roles.edit');

    Route::get('settings/roles/create', RoleCreate::class)
        ->name('settings.roles.create');

    Route::get('settings/kanban', Kanban::class)
        ->name('settings.kanban');

    Route::get('settings/stages', Stages::class)
        ->name('settings.stages');

    Route::get('settings/users', Users::class)
        ->name('settings.users');

    Route::get('settings/active-sessions', ActiveSessions::class)
        ->name('settings.active-sessions');

    Route::get('settings/profile', Profile::class)
        ->name('settings.profile');

    Route::get('/bill-to', [App\Http\Controllers\BillToController::class, 'index'])->name('bill-to.index');
    Route::get('/bill-to/create', [App\Http\Controllers\BillToController::class, 'create'])->name('bill-to.create');
    Route::get('/bill-to/{billTo}/edit', [App\Http\Controllers\BillToController::class, 'edit'])->name('bill-to.edit');
});

Route::view('support', 'support.index')
    ->middleware(['auth'])
    ->name('support.index');

require __DIR__ . '/auth.php';
