<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Hub;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardService
{
    /**
     * Get dashboard metrics
     *
     * @param array $filters
     * @return array
     */
    public function getMetrics(array $filters): array
    {
        try {
            Log::info('DashboardService::getMetrics starting', ['filters' => $filters]);

            $companyId = auth()->user()->company_id ?? null;
            Log::info('User company ID', ['company_id' => $companyId]);

            // 1. Total POs - Using the user's corrected query
            Log::info('Getting total POs...');
            $totalPOsQuery = DB::table('purchase_orders');
            if ($companyId) {
                $totalPOsQuery->where('company_id', $companyId);
            }
            $totalPOs = $totalPOsQuery->count();
            Log::info('Total POs retrieved', ['total' => $totalPOs]);

            // 2. % POs On-Time - Using the user's exact corrected query
            Log::info('Getting on-time metrics...');
            $onTimeQuery = DB::table('purchase_orders')
                ->selectRaw('
                    COUNT(*) AS on_time_count,
                    100.0 * COUNT(*)
                      / (
                        SELECT COUNT(*)
                        FROM purchase_orders
                        WHERE date_ata IS NOT NULL
                          AND date_eta IS NOT NULL
                          ' . ($companyId ? 'AND company_id = ' . $companyId : '') . '
                      ) AS pct_on_time
                ')
                ->whereNotNull('date_ata')
                ->whereNotNull('date_eta')
                ->where('date_ata', '<=', DB::raw('date_eta'));

            if ($companyId) {
                $onTimeQuery->where('company_id', $companyId);
            }

            $onTimeResult = $onTimeQuery->first();
            Log::info('On-time metrics retrieved', [
                'on_time_count' => $onTimeResult->on_time_count ?? 0,
                'pct_on_time' => $onTimeResult->pct_on_time ?? 0
            ]);

            // 3. % POs Delayed - Using the user's exact corrected query
            Log::info('Getting delayed metrics...');
            $delayedQuery = DB::table('purchase_orders')
                ->selectRaw('
                    COUNT(*) AS delayed_count,
                    100.0 * COUNT(*)
                      / (
                        SELECT COUNT(*)
                        FROM purchase_orders
                        WHERE date_ata IS NOT NULL
                          AND date_eta IS NOT NULL
                          ' . ($companyId ? 'AND company_id = ' . $companyId : '') . '
                      ) AS pct_delayed
                ')
                ->whereNotNull('date_ata')
                ->whereNotNull('date_eta')
                ->where('date_ata', '>', DB::raw('date_eta'));

            if ($companyId) {
                $delayedQuery->where('company_id', $companyId);
            }

            $delayedResult = $delayedQuery->first();
            Log::info('Delayed metrics retrieved', [
                'delayed_count' => $delayedResult->delayed_count ?? 0,
                'pct_delayed' => $delayedResult->pct_delayed ?? 0
            ]);

            // 4. Material count - Using the user's exact corrected query
            Log::info('Getting material count...');
            $materialQuery = DB::table('purchase_order_product as pp')
                ->selectRaw('COUNT(DISTINCT pp.product_id) AS material_count');

            if ($companyId) {
                $materialQuery->join('purchase_orders as po', 'pp.purchase_order_id', '=', 'po.id')
                             ->where('po.company_id', $companyId);
            }

            $materialResult = $materialQuery->first();
            $materialCount = $materialResult->material_count ?? 0;
            Log::info('Material count retrieved', ['count' => $materialCount]);

            $result = [
                'total_pos' => $totalPOs,
                'on_time_percentage' => round((float)($onTimeResult->pct_on_time ?? 0), 1),
                'delayed_percentage' => round((float)($delayedResult->pct_delayed ?? 0), 1),
                'material_count' => $materialCount,
            ];

            Log::info('DashboardService::getMetrics completed successfully', $result);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in DashboardService::getMetrics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            // Return default values instead of throwing to prevent dashboard crash
            return [
                'total_pos' => 0,
                'on_time_percentage' => 0,
                'delayed_percentage' => 0,
                'material_count' => 0,
            ];
        }
    }

    /**
     * Get on-time delivery metrics
     *
     * @param array $filters
     * @return array
     */
    private function getOnTimeMetrics(array $filters): array
    {
        try {
            Log::info('Getting on-time metrics query...');

            $query = $this->getBaseQuery($filters)
                ->whereNotNull('date_ata')
                ->whereNotNull('date_eta')
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN date_ata <= date_eta THEN 1 ELSE 0 END) as on_time_count,
                    SUM(CASE WHEN date_ata > date_eta THEN 1 ELSE 0 END) as delayed_count
                ');

            Log::info('Executing on-time metrics query...');
            $result = $query->first();
            Log::info('On-time metrics raw result', [
                'total' => $result->total ?? null,
                'on_time_count' => $result->on_time_count ?? null,
                'delayed_count' => $result->delayed_count ?? null
            ]);

            $total = $result->total ?? 0;
            $onTimeCount = $result->on_time_count ?? 0;
            $delayedCount = $result->delayed_count ?? 0;

            return [
                'on_time_percentage' => $total > 0 ? round(($onTimeCount / $total) * 100, 1) : 0,
                'delayed_percentage' => $total > 0 ? round(($delayedCount / $total) * 100, 1) : 0,
            ];
        } catch (\Exception $e) {
            Log::error('Error in getOnTimeMetrics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'on_time_percentage' => 0,
                'delayed_percentage' => 0,
            ];
        }
    }

    /**
     * Get material count
     *
     * @param array $filters
     * @return int
     */
    private function getMaterialCount(array $filters): int
    {
        try {
            Log::info('Getting material count query...');

            $query = $this->getBaseQuery($filters)
                ->join('purchase_order_product', 'purchase_orders.id', '=', 'purchase_order_product.purchase_order_id')
                ->distinct('purchase_order_product.product_id');

            Log::info('Executing material count query...');
            $count = $query->count('purchase_order_product.product_id');
            Log::info('Material count result', ['count' => $count]);

            return $count;
        } catch (\Exception $e) {
            Log::error('Error in getMaterialCount', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Return 0 instead of throwing to not break the whole dashboard
            return 0;
        }
    }

    /**
     * Get charts data
     *
     * @param array $filters
     * @return array
     */
    public function getChartsData(array $filters): array
    {
        try {
            Log::info('DashboardService::getChartsData starting');

            $result = [
                'hub_distribution' => $this->getHubDistribution($filters),
                'delivery_status' => $this->getDeliveryStatus($filters),
                'transport_type' => $this->getTransportType($filters),
                'delay_reasons' => $this->getDelayReasons($filters),
                'pos_by_stage' => $this->getPosByStage($filters),
            ];

            Log::info('DashboardService::getChartsData completed successfully');
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in DashboardService::getChartsData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get hub distribution data - Using user's exact corrected query
     *
     * @param array $filters
     * @return Collection
     */
    private function getHubDistribution(array $filters): Collection
    {
        try {
            Log::info('Getting hub distribution...');

            $companyId = auth()->user()->company_id ?? null;

            // Using the user's exact query structure
            $query = DB::table('purchase_orders as po')
                ->join('hubs as h', 'po.actual_hub_id', '=', 'h.id')
                ->selectRaw('
                    h.code AS hub,
                    COUNT(po.id) AS total_pos,
                    100.0 * COUNT(po.id) / (SELECT COUNT(*) FROM purchase_orders' .
                    ($companyId ? ' WHERE company_id = ' . $companyId : '') .
                    ') AS pct_total
                ')
                ->groupBy('h.code');

            if ($companyId) {
                $query->where('po.company_id', $companyId);
            }

            $result = $query->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->hub,
                        'value' => (int) $item->total_pos,
                        'percentage' => round((float) $item->pct_total, 1),
                    ];
                });

            Log::info('Hub distribution retrieved', ['count' => $result->count()]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getHubDistribution', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get delivery status data
     *
     * @param array $filters
     * @return Collection
     */
    private function getDeliveryStatus(array $filters): Collection
    {
        try {
            Log::info('Getting delivery status...');

            $result = $this->getBaseQuery($filters)
                ->whereNotNull('date_ata')
                ->whereNotNull('date_eta')
                ->selectRaw('
                    SUM(CASE WHEN date_ata <= date_eta THEN 1 ELSE 0 END) as on_time,
                    SUM(CASE WHEN date_ata > date_eta THEN 1 ELSE 0 END) as delayed
                ')
                ->first();

            $collection = collect([
                ['name' => 'On Time', 'value' => (int) ($result->on_time ?? 0)],
                ['name' => 'Atrasado', 'value' => (int) ($result->delayed ?? 0)],
            ]);

            Log::info('Delivery status retrieved', ['count' => $collection->count()]);
            return $collection;
        } catch (\Exception $e) {
            Log::error('Error in getDeliveryStatus', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get transport type data
     *
     * @param array $filters
     * @return Collection
     */
    private function getTransportType(array $filters): Collection
    {
        try {
            Log::info('Getting transport type...');

            $result = $this->getBaseQuery($filters)
                ->select('mode', DB::raw('count(*) as total'))
                ->whereNotNull('mode')
                ->groupBy('mode')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => ucfirst($item->mode ?? 'Sin especificar'),
                        'value' => (int) $item->total,
                    ];
                });

            Log::info('Transport type retrieved', ['count' => $result->count()]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getTransportType', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get delay reasons data (mock data for now)
     *
     * @param array $filters
     * @return Collection
     */
    private function getDelayReasons(array $filters): Collection
    {
        try {
            Log::info('Getting delay reasons (mock data)...');

            // This would ideally come from a dedicated table
            // For now, returning mock data based on your requirements
            $result = collect([
                ['name' => 'Problemas de transporte', 'value' => 26.7],
                ['name' => 'Error documental', 'value' => 20],
                ['name' => 'Clima adverso', 'value' => 20],
                ['name' => 'Retraso en aduana', 'value' => 17.8],
                ['name' => 'Demora en despacho', 'value' => 15.6],
            ]);

            Log::info('Delay reasons retrieved', ['count' => $result->count()]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getDelayReasons', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get POs by stage data
     *
     * @param array $filters
     * @return Collection
     */
    private function getPosByStage(array $filters): Collection
    {
        try {
            Log::info('Getting POs by stage...');

            $result = $this->getBaseQuery($filters)
                ->leftJoin('kanban_statuses', 'purchase_orders.kanban_status_id', '=', 'kanban_statuses.id')
                ->select(DB::raw("COALESCE(kanban_statuses.name, 'Sin etapa') as stage_name"), DB::raw('count(*) as total'))
                ->groupBy('kanban_statuses.id', 'kanban_statuses.name')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->stage_name,
                        'value' => (int) $item->total,
                    ];
                });

            Log::info('POs by stage retrieved', ['count' => $result->count()]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getPosByStage', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get detail table data
     *
     * @param array $filters
     * @param int $limit
     * @return Collection
     */
    public function getDetailTableData(array $filters, int $limit = 50): Collection
    {
        try {
            Log::info('Getting detail table data...');

            $result = $this->getBaseQuery($filters)
                ->select([
                    'order_number',
                    'date_atd as fecha_salida',
                    'date_eta as fecha_estimada',
                    'date_ata as fecha_real',
                    'weight_kg as cantidad_kg'
                ])
                ->orderBy('order_date', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($item) {
                    return [
                        'po_number' => $item->order_number,
                        'fecha_salida' => $item->fecha_salida ? Carbon::parse($item->fecha_salida)->format('d/m/Y') : '-',
                        'fecha_estimada' => $item->fecha_estimada ? Carbon::parse($item->fecha_estimada)->format('d/m/Y') : '-',
                        'fecha_real' => $item->fecha_real ? Carbon::parse($item->fecha_real)->format('d/m/Y') : '-',
                        'cantidad_kg' => number_format((float)($item->cantidad_kg ?? 0), 2),
                    ];
                });

            Log::info('Detail table data retrieved', ['count' => $result->count()]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getDetailTableData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get export data
     *
     * @param array $filters
     * @return Collection
     */
    public function getExportData(array $filters): Collection
    {
        try {
            Log::info('Getting export data...');

            $result = $this->getBaseQuery($filters)
                ->with(['vendor', 'plannedHub', 'actualHub'])
                ->get()
                ->map(function ($po) {
                    return [
                        $po->order_number,
                        $po->date_atd ? Carbon::parse($po->date_atd)->format('d/m/Y') : '',
                        $po->date_eta ? Carbon::parse($po->date_eta)->format('d/m/Y') : '',
                        $po->date_ata ? Carbon::parse($po->date_ata)->format('d/m/Y') : '',
                        $po->weight_kg ?? 0,
                        $po->status ?? '',
                        $po->plannedHub->name ?? '',
                        $po->actualHub->name ?? '',
                        $po->vendor->name ?? '',
                        $po->mode ?? '',
                    ];
                });

            Log::info('Export data retrieved', ['count' => $result->count()]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getExportData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get filter options
     *
     * @return array
     */
    public function getFilterOptions(): array
    {
        try {
            Log::info('Getting filter options...');
            $companyId = auth()->user()->company_id ?? null;
            Log::info('User company ID', ['company_id' => $companyId]);

            Log::info('Getting products...');
            $products = Product::select('id', 'short_text as name', 'material_id')
                ->orderBy('short_text')
                ->get();
            Log::info('Products retrieved', ['count' => $products->count()]);

            Log::info('Getting hubs...');
            $hubs = Hub::select('id', 'name', 'code')
                ->orderBy('name')
                ->get();
            Log::info('Hubs retrieved', ['count' => $hubs->count()]);

            Log::info('Getting vendors...');
            $vendors = Vendor::select('id', 'name')
                ->when($companyId, function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                })
                ->orderBy('name')
                ->get();
            Log::info('Vendors retrieved', ['count' => $vendors->count()]);

            Log::info('Getting materials...');
            $materials = $this->getMaterialOptions($companyId);
            Log::info('Materials retrieved', ['count' => $materials->count()]);

            $result = [
                'products' => $products,
                'hubs' => $hubs,
                'vendors' => $vendors,
                'materials' => $materials,
            ];

            Log::info('Filter options retrieved successfully');
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getFilterOptions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get material options
     *
     * @param int|null $companyId
     * @return Collection
     */
    private function getMaterialOptions(?int $companyId): Collection
    {
        try {
            Log::info('Getting material options for company', ['company_id' => $companyId]);

            $result = PurchaseOrder::when($companyId, function ($query) use ($companyId) {
                    return $query->where('company_id', $companyId);
                })
                ->whereNotNull('material_type')
                ->get()
                ->pluck('material_type')
                ->flatten()
                ->unique()
                ->values();

            Log::info('Material options retrieved', ['count' => $result->count()]);
            return $result;
        } catch (\Exception $e) {
            Log::error('Error in getMaterialOptions', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return collect([]);
        }
    }

    /**
     * Get base query with filters applied
     *
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function getBaseQuery(array $filters)
    {
        try {
            Log::info('Creating base query with filters', ['filters' => $filters]);

            $query = PurchaseOrder::query()
                ->with(['vendor', 'plannedHub', 'actualHub', 'products']);

            // Apply company filter for current user
            $companyId = auth()->user()->company_id ?? null;
            if ($companyId) {
                Log::info('Applying company filter', ['company_id' => $companyId]);
                $query->where('company_id', $companyId);
            } else {
                Log::warning('No company ID found for user', ['user_id' => auth()->id()]);
            }

            // Date filters
            if (!empty($filters['date_from'])) {
                Log::info('Applying date_from filter', ['date_from' => $filters['date_from']]);
                $query->where('order_date', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                Log::info('Applying date_to filter', ['date_to' => $filters['date_to']]);
                $query->where('order_date', '<=', $filters['date_to']);
            }

            // Product filter
            if (!empty($filters['product_id'])) {
                Log::info('Applying product filter', ['product_id' => $filters['product_id']]);
                $query->whereHas('products', function ($q) use ($filters) {
                    $q->where('product_id', $filters['product_id']);
                });
            }

            // Material type filter
            if (!empty($filters['material_type'])) {
                Log::info('Applying material_type filter', ['material_type' => $filters['material_type']]);
                $query->whereJsonContains('material_type', $filters['material_type']);
            }

            // Hub filter
            if (!empty($filters['hub_id'])) {
                Log::info('Applying hub filter', ['hub_id' => $filters['hub_id']]);
                $query->where(function ($q) use ($filters) {
                    $q->where('planned_hub_id', $filters['hub_id'])
                      ->orWhere('actual_hub_id', $filters['hub_id']);
                });
            }

            // Vendor filter
            if (!empty($filters['vendor_id'])) {
                Log::info('Applying vendor filter', ['vendor_id' => $filters['vendor_id']]);
                $query->where('vendor_id', $filters['vendor_id']);
            }

            // Status filter
            if (!empty($filters['status'])) {
                Log::info('Applying status filter', ['status' => $filters['status']]);
                $query->where('status', $filters['status']);
            }

            Log::info('Base query created successfully');
            return $query;
        } catch (\Exception $e) {
            Log::error('Error creating base query', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
