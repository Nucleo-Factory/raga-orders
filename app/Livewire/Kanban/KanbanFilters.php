<?php

namespace App\Livewire\Kanban;

use App\Models\Hub;
use App\Models\PurchaseOrder;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class KanbanFilters extends Component
{
    public $currencies = [];
    public $incoterms = [];
    public $plannedHubs = [];
    public $actualHubs = [];
    public $materialTypes = [];

    public $selectedCurrency = null;
    public $selectedIncoterm = null;
    public $selectedPlannedHub = null;
    public $selectedActualHub = null;
    public $selectedMaterialType = null;

    public $filtersApplied = false;
    public $filterCount = 0;

    protected $listeners = [
        'refreshFilters' => 'loadFilterOptions',
        'clearKanbanFilters' => 'resetFilters'
    ];

    public function mount()
    {
        // Cargar las opciones de filtro iniciales
        $this->loadFilterOptions();

        // Restaurar filtros de la sesión
        $this->restoreFiltersFromSession();
    }

    public function loadFilterOptions()
    {
        // Obtener opciones únicas de la base de datos
        $purchaseOrders = PurchaseOrder::select('currency', 'incoterms', 'planned_hub_id', 'actual_hub_id', 'material_type')
            ->where('company_id', auth()->user()->company_id)
            ->get();

        // Extraer valores únicos para cada filtro
        $this->currencies = $purchaseOrders->pluck('currency')->filter()->unique()->values()->toArray();
        $this->incoterms = $purchaseOrders->pluck('incoterms')->filter()->unique()->values()->toArray();

        // Obtener los hubs planificados y reales
        $plannedHubIds = $purchaseOrders->pluck('planned_hub_id')->filter()->unique()->values()->toArray();
        $actualHubIds = $purchaseOrders->pluck('actual_hub_id')->filter()->unique()->values()->toArray();

        // Cargar nombres de hubs
        $hubs = Hub::whereIn('id', array_merge($plannedHubIds, $actualHubIds))->get();

        $this->plannedHubs = $hubs->whereIn('id', $plannedHubIds)
            ->pluck('name', 'id')
            ->toArray();

        $this->actualHubs = $hubs->whereIn('id', $actualHubIds)
            ->pluck('name', 'id')
            ->toArray();

        // Procesar material_type que es un array en la base de datos
        $materialTypes = [];
        foreach ($purchaseOrders as $po) {
            if (is_array($po->material_type)) {
                foreach ($po->material_type as $type) {
                    if (!empty($type)) {
                        $materialTypes[] = $type;
                    }
                }
            } elseif (!empty($po->material_type)) {
                $materialTypes[] = $po->material_type;
            }
        }

        $this->materialTypes = array_unique($materialTypes);
        sort($this->materialTypes);
    }

    public function applyFilters()
    {
        $this->filtersApplied = $this->hasActiveFilters();
        $this->updateFilterCount();
        $this->saveFiltersToSession();

        // Emitir evento para que el KanbanBoard actualice sus datos
        $this->dispatch('kanbanFiltersChanged', $this->getActiveFilters());
    }

    public function resetFilters()
    {
        $this->selectedCurrency = null;
        $this->selectedIncoterm = null;
        $this->selectedPlannedHub = null;
        $this->selectedActualHub = null;
        $this->selectedMaterialType = null;

        $this->filtersApplied = false;
        $this->filterCount = 0;

        // Limpiar filtros de sesión
        Session::forget('kanban_filters');

        // Emitir evento para que el KanbanBoard actualice sus datos
        $this->dispatch('kanbanFiltersChanged', []);
    }

    protected function hasActiveFilters()
    {
        return $this->selectedCurrency ||
               $this->selectedIncoterm ||
               $this->selectedPlannedHub ||
               $this->selectedActualHub ||
               $this->selectedMaterialType;
    }

    protected function updateFilterCount()
    {
        $this->filterCount = 0;

        if ($this->selectedCurrency) $this->filterCount++;
        if ($this->selectedIncoterm) $this->filterCount++;
        if ($this->selectedPlannedHub) $this->filterCount++;
        if ($this->selectedActualHub) $this->filterCount++;
        if ($this->selectedMaterialType) $this->filterCount++;
    }

    protected function getActiveFilters()
    {
        $filters = [];

        if ($this->selectedCurrency) {
            $filters['currency'] = $this->selectedCurrency;
        }

        if ($this->selectedIncoterm) {
            $filters['incoterms'] = $this->selectedIncoterm;
        }

        if ($this->selectedPlannedHub) {
            $filters['planned_hub_id'] = $this->selectedPlannedHub;
        }

        if ($this->selectedActualHub) {
            $filters['actual_hub_id'] = $this->selectedActualHub;
        }

        if ($this->selectedMaterialType) {
            $filters['material_type'] = $this->selectedMaterialType;
        }

        return $filters;
    }

    protected function saveFiltersToSession()
    {
        Session::put('kanban_filters', [
            'currency' => $this->selectedCurrency,
            'incoterm' => $this->selectedIncoterm,
            'planned_hub' => $this->selectedPlannedHub,
            'actual_hub' => $this->selectedActualHub,
            'material_type' => $this->selectedMaterialType,
        ]);
    }

    protected function restoreFiltersFromSession()
    {
        if (Session::has('kanban_filters')) {
            $filters = Session::get('kanban_filters');

            $this->selectedCurrency = $filters['currency'] ?? null;
            $this->selectedIncoterm = $filters['incoterm'] ?? null;
            $this->selectedPlannedHub = $filters['planned_hub'] ?? null;
            $this->selectedActualHub = $filters['actual_hub'] ?? null;
            $this->selectedMaterialType = $filters['material_type'] ?? null;

            if ($this->hasActiveFilters()) {
                $this->filtersApplied = true;
                $this->updateFilterCount();
                $this->dispatch('kanbanFiltersChanged', $this->getActiveFilters());
            }
        }
    }

    public function render()
    {
        return view('livewire.kanban.kanban-filters');
    }
}
