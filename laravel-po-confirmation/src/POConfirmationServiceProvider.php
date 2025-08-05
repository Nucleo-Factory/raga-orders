<?php

namespace RagaOrders\POConfirmation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schedule;
use RagaOrders\POConfirmation\Commands\InstallCommand;
use RagaOrders\POConfirmation\Commands\UninstallCommand;
use RagaOrders\POConfirmation\Commands\CheckPendingPOsCommand;
use RagaOrders\POConfirmation\Services\POConfirmationService;

class POConfirmationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/po-confirmation.php', 'po-confirmation');

        $this->app->singleton('RagaOrders\POConfirmation\Services\POConfirmationService', function ($app) {
            return new \RagaOrders\POConfirmation\Services\POConfirmationService();
        });

        $this->registerCommands();

        // Registrar componente Livewire
        if (class_exists('Livewire\Livewire')) {
            \Livewire\Livewire::component('po-confirmation-settings', \RagaOrders\POConfirmation\Livewire\POConfirmationSettings::class);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Siempre cargar las vistas
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'po-confirmation');

        // Solo cargar funcionalidades si el módulo está habilitado
        if (!config('po-confirmation.enabled', false)) {
            return;
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->registerSchedule();
    }

    /**
     * Register the package commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                UninstallCommand::class,
                CheckPendingPOsCommand::class,
            ]);
        }
    }

    /**
     * Register scheduled tasks.
     */
    protected function registerSchedule(): void
    {
        if (config('po-confirmation.auto_send', true)) {
            Schedule::command('po:check-pending')
                ->{config('po-confirmation.check_interval', 'hourly')}()
                ->withoutOverlapping()
                ->runInBackground();
        }
    }

    /**
     * Publish the package configuration.
     */
    public function provides(): array
    {
        return [
            POConfirmationService::class,
        ];
    }
}
