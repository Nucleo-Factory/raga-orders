@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles-forecast.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/script-forecast.js') }}"></script>
@endpush

<x-app-layout>
    <div class="content">
        <!-- Filters Section -->
        <div class="filters-section">
            <div class="filters-left">
                <!-- Date Range -->
                <div class="filter-group">
                    <label class="filter-label">Fecha</label>
                    <div class="date-range">
                        <div class="date-input-wrapper">
                            <input type="text" placeholder="Start date" class="date-input">
                            <i class="fas fa-calendar-alt date-icon"></i>
                        </div>
                        <div class="date-separator"></div>
                        <div class="date-input-wrapper">
                            <input type="text" placeholder="End date" class="date-input">
                            <i class="fas fa-calendar-alt date-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="filter-group">
                    <label class="filter-label">Categoría</label>
                    <select class="filter-select">
                        <option value="">Seleccionar</option>
                        <option value="cat1">Categoría 1</option>
                        <option value="cat2">Categoría 2</option>
                    </select>
                </div>

                <!-- Product -->
                <div class="filter-group">
                    <label class="filter-label">Producto</label>
                    <select class="filter-select">
                        <option value="">Seleccionar</option>
                        <option value="prod1">Producto 1</option>
                        <option value="prod2">Producto 2</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn-primary">Aceptar</button>
                <button class="btn-secondary">
                    <i class="fas fa-download"></i>
                    Descargar
                </button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-title">Total PO's</div>
                <div class="kpi-value">91</div>
                <div class="kpi-description">Description Bottom</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-title">% PO's on time</div>
                <div class="kpi-value">71,7%</div>
                <div class="kpi-description">Description Bottom</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-title">% PO's atrasadas</div>
                <div class="kpi-value">28,3%</div>
                <div class="kpi-description">Description Bottom</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-title">Monto total</div>
                <div class="kpi-value">$270.346</div>
                <div class="kpi-description">Description Bottom</div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="main-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Forecast Table -->
                <div class="card">
                    <h3 class="card-title">Forecast v/s real - Mensual</h3>
                    <div class="table-container">
                        <table class="forecast-table">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th class="text-right">Forecast</th>
                                    <th class="text-right">Cantidad kgs</th>
                                    <th class="text-right">Desviación KG</th>
                                </tr>
                            </thead>
                            <tbody id="forecastTableBody">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Bar Chart -->
                <div class="card">
                    <h3 class="card-title">Cantidad kgs por mes</h3>
                    <div class="chart-container">
                        <canvas id="barChart" width="400" height="200"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: #565AFF;"></div>
                            <span>Cantidad kgs</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <!-- Treemap -->
                <div class="card treemap-card">
                    <div class="card-header">
                        <h3 class="card-title !mb-0">Desviación por Material</h3>
                        <select class="treemap-select">
                            <option value="all">Todo</option>
                        </select>
                    </div>
                    <div class="treemap-container" id="treemapContainer">
                        <!-- Populated by JavaScript -->
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="card">
                    <h3 class="card-title">Desviación por vendor</h3>
                    <div class="pie-chart-container">
                        <div class="pie-chart-wrapper">
                            <canvas id="pieChart" width="240" height="240"></canvas>
                        </div>
                        <div class="vendor-legend" id="vendorLegend">
                            <!-- Populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
