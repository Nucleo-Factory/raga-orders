<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class breadcrumb extends Component
{
    public $segments = [];
    public $currentPath = '';

    // Translation map for any text that appears in the breadcrumb
    protected $translations = [
        // Common route name mappings
        'purchase-orders.index' => 'Órdenes de compra',
        'purchase-orders.requests' => 'Autorizaciones',
        'purchase-orders.create' => 'Nueva orden',
        'purchase-orders.show' => 'Detalle',
        'purchase-orders.edit' => 'Editar',
        'purchase-orders.tracking' => 'Seguimiento',
        'purchase-orders.kanban' => 'Kanban',
        'purchase-orders.consolidated-orders' => 'Órdenes consolidadas',
        'purchase-orders.consolidated-order-detail' => 'Detalle de orden consolidada',
        'products.index' => 'Productos',
        'products.create' => 'Nuevo producto',
        'products.edit' => 'Editar producto',
        'products.forecast' => 'Pronóstico',
        'products.forecast-graph' => 'Gráfico de pronóstico',
        'products.forecast.edit' => 'Editar pronóstico',
        'vendors.index' => 'Proveedores',
        'vendors.create' => 'Nuevo proveedor',
        'vendors.edit' => 'Editar proveedor',
        'ship-to.index' => 'Direcciones de envío',
        'ship-to.create' => 'Nueva dirección',
        'ship-to.edit' => 'Editar dirección',
        'shipping-documentation.index' => 'Documentación de envío',
        'shipping-documentation.create' => 'Nueva documentación',
        'shipping-documentation.requests' => 'Solicitudes',
        'hub.index' => 'Hubs',
        'hub.create' => 'Nuevo hub',
        'hub.edit' => 'Editar hub',
        'settings.index' => 'Configuración',
        'settings.notifications' => 'Notificaciones',
        'settings.password' => 'Contraseña',
        'settings.history' => 'Historial',
        'settings.roles' => 'Roles',
        'settings.roles.create' => 'Crear rol',
        'settings.roles.edit' => 'Editar rol',
        'settings.kanban' => 'Kanban',
        'settings.stages' => 'Etapas',
        'settings.users' => 'Usuarios',
        'settings.users.create' => 'Crear usuario',
        'settings.users.edit' => 'Editar usuario',
        'settings.active-sessions' => 'Sesiones activas',
        'settings.profile' => 'Perfil',
        'settings.sessions' => 'Sesiones',
        'bill-to.index' => 'Facturación',
        'bill-to.create' => 'Nueva facturación',
        'bill-to.edit' => 'Editar facturación',
        'authorizations.index' => 'Autorizaciones',
        'authorizations.show' => 'Detalle de autorización',
        'support.index' => 'Soporte',

        // Text-based translations (exact text that appears in breadcrumb)
        'Purchase orders' => 'Órdenes de compra',
        'purchase orders' => 'Órdenes de compra',
        'Pucharse orders' => 'Órdenes de compra',
        'pucharse orders' => 'Órdenes de compra',
        'Purchase order' => 'Órdenes de compra',
        'purchase order' => 'Órdenes de compra',
        'Pucharse order' => 'Órdenes de compra',
        'pucharse order' => 'Órdenes de compra',
        'Purchase' => 'Órdenes de compra',
        'purchase' => 'Órdenes de compra',
        'Pucharse' => 'Órdenes de compra',
        'pucharse' => 'Órdenes de compra',
        'Support' => 'Soporte',
        'support' => 'Soporte',
        'Nueva orden' => 'Nueva orden',
        'New order' => 'Nueva orden',
        'new order' => 'Nueva orden',
        'Orders' => 'Órdenes',
        'orders' => 'Órdenes',
        'Create' => 'Crear',
        'create' => 'Crear',
        'Settings' => 'Configuración',
        'settings' => 'Configuración',
        'Vendors' => 'Proveedores',
        'vendors' => 'Proveedores',
        'Products' => 'Productos',
        'products' => 'Productos',
        'Documentación' => 'Documentación',
        'documentación' => 'Documentación',
        'Documentation' => 'Documentación',
        'documentation' => 'Documentación',
        'Requests' => 'Solicitudes',
        'requests' => 'Solicitudes',
        'Authorizations' => 'Autorizaciones',
        'authorizations' => 'Autorizaciones',
        'Autorizaciones' => 'Autorizaciones',
        'autorizaciones' => 'Autorizaciones',
        'Detail' => 'Detalle',
        'detail' => 'Detalle',
        'Edit' => 'Editar',
        'edit' => 'Editar',
        'Tracking' => 'Seguimiento',
        'tracking' => 'Seguimiento',
        'Kanban' => 'Kanban',
        'kanban' => 'Kanban',
    ];

    // Base path groups - maps a segment to its parent group name
    protected $pathGroups = [
        'purchase-orders' => 'Órdenes de compra',
        'products' => 'Productos',
        'vendors' => 'Proveedores',
        'ship-to' => 'Direcciones de envío',
        'shipping-documentation' => 'Documentación de envío',
        'hub' => 'Hubs',
        'settings' => 'Configuración',
        'bill-to' => 'Facturación',
        'authorizations' => 'Autorizaciones',
        'support' => 'Soporte',
    ];

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->currentPath = request()->path();
        $this->buildBreadcrumb();
    }

    /**
     * Build the breadcrumb segments
     */
    protected function buildBreadcrumb()
    {
        $currentRouteName = Route::currentRouteName();

        if ($currentRouteName) {
            $this->buildFromRoute($currentRouteName);
        } else {
            $this->buildFromPath();
        }

        // Finally, translate each segment name
        $this->segments = array_map(function($segment) {
            $segment['name'] = $this->translate($segment['name']);
            return $segment;
        }, $this->segments);
    }

    /**
     * Build breadcrumb from a route name
     */
    protected function buildFromRoute($routeName)
    {
        $routeParts = explode('.', $routeName);
        $segments = [];

        foreach ($routeParts as $index => $part) {
            // Skip the last part if it's 'index'
            if ($index === count($routeParts) - 1 && $part === 'index') {
                continue;
            }

            // Build up the route name to this point
            $currentRouteName = implode('.', array_slice($routeParts, 0, $index + 1));

            try {
                // Handle routes with parameters
                if (strpos($routeName, $currentRouteName) === 0 && $currentRouteName !== $routeName) {
                    // This is a parent route, get its URL without parameters
                    $url = route($currentRouteName . '.index', [], false);
                } else {
                    // For the current route, use the actual URL
                    $url = request()->path();
                }

                $name = $this->translations[$currentRouteName] ?? ucfirst(str_replace(['-', '_', '.'], ' ', $part));

                $segments[] = [
                    'name' => $name,
                    'url' => $url
                ];
            } catch (\Exception $e) {
                // Skip routes that can't be resolved
            }
        }

        $this->segments = $segments;
    }

    /**
     * Build breadcrumb from the current path
     */
    protected function buildFromPath()
    {
        if ($this->currentPath === '/') {
            return;
        }

        $pathParts = collect(explode('/', $this->currentPath))->filter()->values();
        $segments = [];
        $urlPath = '';

        for ($i = 0; $i < $pathParts->count(); $i++) {
            $segment = $pathParts[$i];
            $urlPath .= ($i > 0 ? '/' : '') . $segment;

            // For first segment (section)
            if ($i === 0) {
                if (isset($this->pathGroups[$segment])) {
                    $name = $this->pathGroups[$segment];
                } else {
                    $name = ucfirst(str_replace(['-', '_'], ' ', $segment));
                }
            } else {
                $name = ucfirst(str_replace(['-', '_'], ' ', $segment));
            }

            $segments[] = [
                'name' => $name,
                'url' => $urlPath
            ];
        }

        $this->segments = $segments;
    }

    /**
     * Translate a text to its Spanish equivalent if available
     */
    protected function translate($text)
    {
        // Direct match
        if (isset($this->translations[$text])) {
            return $this->translations[$text];
        }

        // Case insensitive match
        $lowerText = strtolower($text);
        foreach ($this->translations as $key => $value) {
            if (strtolower($key) === $lowerText) {
                return $value;
            }
        }

        return $text;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
