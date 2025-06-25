// Forecast dashboard dynamic functionality
class ForecastManager {
    constructor() {
        this.charts = {}; // Store chart instances
        this.initialized = false;
        this.init();
    }

    init() {
        console.log('ForecastManager initializing...');
        this.setupEventListeners();

        // Only initialize charts if we have data and haven't initialized yet
        if (!this.initialized && window.forecastData) {
            this.initializeCharts();
            this.initialized = true;
        }
    }

    setupEventListeners() {
        // Form submission for filters
        const form = document.getElementById('forecast-filters');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.applyFilters();
            });
        }

        // Export button
        const exportBtn = document.getElementById('export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                this.exportData();
            });
        }
    }

    async applyFilters() {
        try {
            console.log('Applying forecast filters...');
            this.showLoading();

            const form = document.getElementById('forecast-filters');
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData);

            console.log('Sending request to:', `/products/forecast-graph/data?${searchParams.toString()}`);

            const response = await fetch(`/products/forecast-graph/data?${searchParams.toString()}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json',
                }
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response not ok:', response.status, errorText);
                throw new Error(`Network response was not ok: ${response.status}`);
            }

            const result = await response.json();
            console.log('Forecast AJAX Response:', result);

            if (result.success) {
                this.updateForecast(result.data);
                // Update URL without page reload
                const url = new URL(window.location);
                for (const [key, value] of searchParams.entries()) {
                    if (value) {
                        url.searchParams.set(key, value);
                    } else {
                        url.searchParams.delete(key);
                    }
                }
                window.history.pushState({}, '', url);
            } else {
                throw new Error(result.message || 'Error al obtener los datos del forecast');
            }
        } catch (error) {
            console.error('Error applying forecast filters:', error);
            this.showErrorModal('Error al aplicar filtros: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    async exportData() {
        try {
            console.log('Exporting forecast data...');
            this.showLoading();

            const form = document.getElementById('forecast-filters');
            const formData = new FormData(form);
            const searchParams = new URLSearchParams(formData);

            const response = await fetch(`/products/forecast-graph/export?${searchParams.toString()}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                }
            });

            if (!response.ok) {
                throw new Error('Error al exportar los datos del forecast');
            }

            // Create blob and download
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');

            // Get filename from response headers if available
            const contentDisposition = response.headers.get('Content-Disposition');
            let filename = 'forecast_export.csv';
            if (contentDisposition) {
                const matches = /filename="(.+)"/.exec(contentDisposition);
                if (matches) {
                    filename = matches[1];
                }
            }

            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            console.log('Forecast export completed successfully');
        } catch (error) {
            console.error('Error exporting forecast data:', error);
            this.showErrorModal('Error al exportar los datos: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    updateForecast(data) {
        console.log('Updating forecast with data:', data);

        // Update metrics
        this.updateMetrics(data.metrics);

        // Update charts
        this.updateCharts(data.charts);

        // Update forecast table
        this.updateForecastTable(data.forecast_table);
    }

    updateMetrics(metrics) {
        console.log('Updating forecast metrics:', metrics);

        const elements = {
            'total-pos': metrics.total_pos,
            'on-time-percentage': metrics.on_time_percentage + '%',
            'delayed-percentage': metrics.delayed_percentage + '%',
            'total-amount': '$' + new Intl.NumberFormat().format(metrics.total_amount)
        };

        for (const [id, value] of Object.entries(elements)) {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = value;
            } else {
                console.warn(`Element with id '${id}' not found`);
            }
        }
    }

    updateCharts(chartsData) {
        console.log('Updating forecast charts with data:', chartsData);

        // Update treemap (preserve existing design)
        if (chartsData.material_deviation) {
            this.updateTreemap(chartsData.material_deviation);
        }

        // Update bar chart
        if (chartsData.monthly_kgs) {
            this.updateBarChart(chartsData.monthly_kgs);
        }

        // Update vendor pie chart
        if (chartsData.vendor_deviation) {
            this.updateVendorChart(chartsData.vendor_deviation);
        }
    }

    updateTreemap(data) {
        try {
            console.log('Updating treemap with data:', data);

            const container = document.getElementById('treemapContainer');
            if (!container) {
                console.warn('Treemap container not found');
                return;
            }

            // Clear existing treemap
            container.innerHTML = '';

            if (!data || data.length === 0) {
                container.innerHTML = '<div class="text-gray-500 text-sm">Sin datos disponibles</div>';
                return;
            }

            // Create treemap items based on data
            const colors = this.generateColors(data.length);

            data.forEach((item, index) => {
                const element = document.createElement('div');
                element.className = 'treemap-item';
                element.style.backgroundColor = colors[index];
                element.textContent = item.name;

                // Calculate size based on deviation value
                const size = this.calculateTreemapSize(item.value, data);
                element.style.gridArea = size.gridArea;

                container.appendChild(element);
            });

        } catch (error) {
            console.error('Error updating treemap:', error);
        }
    }

    calculateTreemapSize(value, allData) {
        // Simple implementation - in a real scenario, you'd use a proper treemap algorithm
        const maxValue = Math.max(...allData.map(d => d.value));
        const ratio = value / maxValue;

        if (ratio > 0.7) {
            return { gridArea: "1 / 1 / 3 / 3" }; // large
        } else if (ratio > 0.4) {
            return { gridArea: "1 / 3 / 2 / 4" }; // medium
        } else {
            return { gridArea: "2 / 3 / 3 / 4" }; // small
        }
    }

    updateBarChart(data) {
        try {
            console.log('Updating bar chart with data:', data);

            const canvas = document.getElementById('barChart');
            if (!canvas) {
                console.warn('Bar chart canvas not found');
                return;
            }

            // Destroy existing chart if it exists
            if (this.charts.barChart) {
                this.charts.barChart.destroy();
            }

            if (!data || data.length === 0) {
                console.log('No data for bar chart');
                return;
            }

            const ctx = canvas.getContext('2d');

            this.charts.barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.month),
                    datasets: [{
                        label: 'Cantidad kgs',
                        data: data.map(item => item.total_kgs),
                        backgroundColor: '#565AFF',
                        borderWidth: 0,
                        barThickness: 80,
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => (value / 1000).toFixed(0) + 'k',
                                font: {
                                    family: 'Inter',
                                    size: 12
                                }
                            },
                            grid: {
                                display: true,
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error updating bar chart:', error);
        }
    }

    updateVendorChart(data) {
        try {
            console.log('Updating vendor chart with data:', data);

            const canvas = document.getElementById('pieChart');
            if (!canvas) {
                console.warn('Pie chart canvas not found');
                return;
            }

            // Destroy existing chart if it exists
            if (this.charts.pieChart) {
                this.charts.pieChart.destroy();
            }

            if (!data || data.length === 0) {
                console.log('No data for vendor chart');
                return;
            }

            const ctx = canvas.getContext('2d');
            const colors = this.generateColors(data.length);

            this.charts.pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(item => item.name),
                    datasets: [{
                        data: data.map(item => item.percentage),
                        backgroundColor: colors,
                        borderWidth: 0,
                        cutout: '40%'
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Update vendor legend
            this.updateVendorLegend(data, colors);

        } catch (error) {
            console.error('Error updating vendor chart:', error);
        }
    }

    updateVendorLegend(data, colors) {
        try {
            const legendContainer = document.getElementById('vendorLegend');
            if (!legendContainer) {
                console.warn('Vendor legend container not found');
                return;
            }

            legendContainer.innerHTML = '';

            data.forEach((item, index) => {
                const vendorItem = document.createElement('div');
                vendorItem.className = 'vendor-item';
                vendorItem.innerHTML = `
                    <div class="vendor-info">
                        <div class="vendor-color" style="background-color: ${colors[index]}"></div>
                        <span class="vendor-name">${item.name}</span>
                    </div>
                    <div class="vendor-stats">
                        <span class="vendor-amount">$${new Intl.NumberFormat().format(item.value)}</span>
                        <div class="vendor-percentage">${item.percentage}%</div>
                    </div>
                `;
                legendContainer.appendChild(vendorItem);
            });

        } catch (error) {
            console.error('Error updating vendor legend:', error);
        }
    }

    updateForecastTable(tableData) {
        try {
            console.log('Updating forecast table:', tableData);

            const tbody = document.getElementById('forecastTableBody');
            if (!tbody) {
                console.warn('Forecast table body not found');
                return;
            }

            if (!tableData || tableData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-gray-500">Sin datos disponibles</td></tr>';
                return;
            }

            tbody.innerHTML = tableData.map(row => {
                const deviationValue = parseFloat(row.deviation_kg.replace(',', '.'));
                let deviationClass = 'deviation-zero';
                if (deviationValue > 0) {
                    deviationClass = 'deviation-positive';
                } else if (deviationValue < 0) {
                    deviationClass = 'deviation-negative';
                }

                return `
                    <tr>
                        <td class="material-code">${row.material}</td>
                        <td class="text-right">${row.forecast_kg}</td>
                        <td class="text-right">${row.actual_kg}</td>
                        <td class="text-right ${deviationClass}">${row.deviation_kg}</td>
                    </tr>
                `;
            }).join('');

        } catch (error) {
            console.error('Error updating forecast table:', error);
        }
    }

    generateColors(count) {
        const colors = [
            '#565aff', '#7288ff', '#9aabff', '#aebbff',
            '#c9cfff', '#f46844', '#5ae7f4', '#5dd595'
        ];

        while (colors.length < count) {
            colors.push(...colors);
        }

        return colors.slice(0, count);
    }

    initializeCharts() {
        try {
            console.log('Initializing forecast charts...');

            if (window.forecastData && window.forecastData.charts) {
                this.updateCharts(window.forecastData.charts);
            }

            if (window.forecastData && window.forecastData.forecast_table) {
                this.updateForecastTable(window.forecastData.forecast_table);
            }

            console.log('All forecast charts initialized successfully');
        } catch (error) {
            console.error('Error initializing forecast charts:', error);
        }
    }

    showLoading() {
        // Create loading indicator if it doesn't exist
        let loading = document.getElementById('loading-indicator');
        if (!loading) {
            loading = document.createElement('div');
            loading.id = 'loading-indicator';
            loading.className = 'fixed inset-0 flex items-center justify-center bg-gray-600 bg-opacity-50';
            loading.innerHTML = `
                <div class="p-4 bg-white rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 -ml-1 text-blue-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Cargando datos...
                    </div>
                </div>
            `;
            document.body.appendChild(loading);
        }
        loading.classList.remove('hidden');
    }

    hideLoading() {
        const loading = document.getElementById('loading-indicator');
        if (loading) {
            loading.classList.add('hidden');
        }
    }

    showErrorModal(message) {
        // Simple error alert for now - can be enhanced with modals later
        alert(message);
    }
}

// Initialize forecast manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing ForecastManager');
    new ForecastManager();
});

// Also handle case where this script loads after DOM is ready
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    console.log('DOM already ready - Initializing ForecastManager');
    new ForecastManager();
}
