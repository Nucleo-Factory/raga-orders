<x-app-layout>
    @push('styles')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/dashboard-styles.css') }}">
    @endpush

    <div class="dashboard-container">
        <!-- Filter Controls -->
        <div class="filters-section">
            <div class="filter-group">
                <label class="filter-label">Fecha</label>
                <div class="date-range">
                    <div class="date-input-wrapper">
                        <input type="date" placeholder="asdf" class="date-input">
                    </div>
                    <span class="date-separator">→</span>
                    <div class="date-input-wrapper">
                        <input type="date" placeholder="End date" class="date-input">
                    </div>
                </div>
            </div>

            <div class="filter-group">
                <label class="filter-label">Categoría</label>
                <select class="filter-select">
                    <option value="">Seleccionar</option>
                    <option value="cat1">Categoría 1</option>
                    <option value="cat2">Categoría 2</option>
                    <option value="cat3">Categoría 3</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Producto</label>
                <select class="filter-select">
                    <option value="">Seleccionar</option>
                    <option value="prod1">Producto 1</option>
                    <option value="prod2">Producto 2</option>
                    <option value="prod3">Producto 3</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">Material</label>
                <select class="filter-select">
                    <option value="">Material</option>
                    <option value="mat1">Material 1</option>
                    <option value="mat2">Material 2</option>
                    <option value="mat3">Material 3</option>
                </select>
            </div>

            <div class="action-buttons">
                <button class="btn-primary">Aceptar</button>
                <button class="btn-secondary">
                    <i class="fas fa-download"></i>
                    Descargar
                </button>
            </div>
        </div>

        <!-- Top Metrics Cards -->
        <div class="metrics-grid">
            <div class="metric-card">
                <p class="metric-label">Total PO's</p>
                <p class="metric-value">91</p>
            </div>
            <div class="metric-card">
                <p class="metric-label">% PO's on time</p>
                <p class="metric-value">71,7%</p>
            </div>
            <div class="metric-card">
                <p class="metric-label">% PO's atrasadas</p>
                <p class="metric-value">28,3%</p>
            </div>
            <div class="metric-card">
                <p class="metric-label">Material</p>
                <p class="metric-value">91</p>
            </div>
        </div>

        <!-- Pie Charts Row -->
        <div class="charts-row">
            <div class="chart-card">
                <h3 class="chart-title">Entregas por hub</h3>
                <div class="chart-content">
                    <div class="chart-container">
                        <canvas id="hubChart" width="120" height="120"></canvas>
                    </div>
                    <div class="chart-legend" id="hubLegend"></div>
                </div>
            </div>

            <div class="chart-card">
                <h3 class="chart-title">Estado entrega</h3>
                <div class="chart-content">
                    <div class="chart-container">
                        <canvas id="deliveryChart" width="120" height="120"></canvas>
                    </div>
                    <div class="chart-legend" id="deliveryLegend"></div>
                </div>
            </div>

            <div class="chart-card">
                <h3 class="chart-title">Tipo de transporte</h3>
                <div class="chart-content">
                    <div class="chart-container">
                        <canvas id="transportChart" width="120" height="120"></canvas>
                    </div>
                    <div class="chart-legend" id="transportLegend"></div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="bottom-section">
            <!-- Detail Table -->
            <div class="table-card">
                <h3 class="chart-title">Detalle</h3>
                <div class="table-container">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Fecha salida</th>
                                <th>Fecha estimada</th>
                                <th class="text-right">Cantidad kgs</th>
                                <th class="text-right">N° PO</th>
                            </tr>
                        </thead>
                        <tbody id="detailTableBody">
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Charts Column -->
            <div class="right-charts">
                <div class="chart-card">
                    <h3 class="chart-title">Motivo de atraso</h3>
                    <div class="chart-content-horizontal">
                        <div class="chart-container-small">
                            <canvas id="delayChart" width="120" height="120"></canvas>
                        </div>
                        <div class="chart-legend-small" id="delayLegend"></div>
                    </div>
                </div>

                <div class="chart-card">
                    <h3 class="chart-title">PO's por etapa</h3>
                    <div class="chart-content-horizontal">
                        <div class="chart-container-small">
                            <canvas id="stageChart" width="120" height="120"></canvas>
                        </div>
                        <div class="chart-legend-small" id="stageLegend"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Buttons -->
        <div class="test-buttons">
            <button id="showSuccessBtn" class="btn-primary">Show Success Modal</button>
            <button id="showErrorBtn" class="btn-secondary">Show Error Modal</button>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-icon success">
                    <svg width="87" height="87" viewBox="0 0 87 87" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M72.5 22V39.9C72.5 42.3268 72.5 43.5403 72.972 44.4672C73.387 45.2826 74.05 45.9455 74.866 46.361C75.793 46.8333 77.006 46.8333 79.433 46.8333H97.332M58 81.668L66.667 90.334L86.167 70.834M72.5 21.001H49.966C42.686 21.001 39.045 21.001 36.264 22.4179C33.818 23.6642 31.83 25.653 30.583 28.0991C29.166 30.8799 29.166 34.5203 29.166 41.801V86.868C29.166 94.148 29.166 97.789 30.583 100.57C31.83 103.016 33.818 105.004 36.264 106.251C39.045 107.668 42.686 107.668 49.966 107.668H77.7C84.98 107.668 88.621 107.668 91.401 106.251C93.848 105.004 95.836 103.016 97.083 100.57C98.5 97.789 98.5 94.148 98.5 86.868V47.001L72.5 21.001Z" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="modal-title success">Registro fue creado correctamente</h3>
                <p class="modal-text">Tarea completada</p>
            </div>
            <button id="closeSuccessBtn" class="modal-btn">Aceptar</button>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-icon error">
                    <svg width="87" height="87" viewBox="0 0 87 87" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="43.5" cy="43.5" r="36.25" stroke="white" stroke-width="6"/>
                        <path d="M43.5 29V43.5M43.5 58H43.5435" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h3 class="modal-title error">¡Ha ocurrido un error!</h3>
                <p class="modal-text">No se pudo descargar correctamente el reporte</p>
            </div>
            <button id="closeErrorBtn" class="modal-btn">Intentar de nuevo</button>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/dashboard-script.js') }}"></script>
    @endpush
</x-app-layout>
