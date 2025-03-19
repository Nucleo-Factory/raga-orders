<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Tables\PurchaseOrdersTable;
use App\Livewire\Ui\PurchaseOrderCard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('tables.purchase-orders-table', PurchaseOrdersTable::class);
        Livewire::component('ui.purchase-order-card', PurchaseOrderCard::class);
        Livewire::component('tables.vendors-table', \App\Livewire\Tables\VendorsTable::class);
        Livewire::component('forms.vendor-form', \App\Livewire\Forms\VendorForm::class);
    }
}
