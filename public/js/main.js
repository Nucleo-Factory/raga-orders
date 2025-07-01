// main.js - Lógica base para el dashboard migrado sin React

// Datos del dashboard
const hubData = [
  { name: "EWR", value: 47.8, color: "#565aff", hub_id: 1 },
  { name: "MIA", value: 50, color: "#9aabff", hub_id: 2 },
  { name: "AVR", value: 1.2, color: "#ff3459", hub_id: 3 },
  { name: "DIR", value: 1, color: "#f46844", hub_id: 4 },
];
const deliveryStatusData = [
  { name: "On Time", value: 71.7, color: "#565aff", status: "on_time" },
  { name: "Atrasado", value: 28.3, color: "#c9cfff", status: "delayed" },
];
const transportTypeData = [
  { name: "Marítimo", value: 60, color: "#565aff", transport: "sea" },
  { name: "Aéreo", value: 40, color: "#ff3459", transport: "air" },
];
const delayReasonsData = [
  { name: "Problemas de transporte", value: 26.7, color: "#565aff" },
  { name: "Error documental", value: 20, color: "#9aabff" },
  { name: "Clima adverso", value: 20, color: "#5ae7f4" },
  { name: "Retraso en aduana", value: 17.8, color: "#f46844" },
  { name: "Demora en despacho", value: 15.6, color: "#5dd595" },
];
const posByStageData = [
  { name: "Etapa 1", value: 28.3, color: "#565aff" },
  { name: "Etapa 2", value: 21.7, color: "#ff3459" },
  { name: "Etapa 3", value: 22.8, color: "#f46844" },
  { name: "Etapa 4", value: 13, color: "#5dd595" },
  { name: "Etapa 5", value: 16.2, color: "#9aabff" },
];

// Dataset de ejemplo con campos reales
const detailData = [
  { po_number: "PO-001", fecha_salida: "01/01/2024", fecha_estimada: "15/01/2024", cantidad_kg: "1500.00", hub_id: 1, status: "on_time", product_id: 1, material_type: "type1", transport: "sea", delay_reason: "transporte", stage: "1" },
  { po_number: "PO-002", fecha_salida: "02/01/2024", fecha_estimada: "16/01/2024", cantidad_kg: "2000.00", hub_id: 2, status: "delayed", product_id: 2, material_type: "type2", transport: "air", delay_reason: "documental", stage: "2" },
  { po_number: "PO-003", fecha_salida: "03/01/2024", fecha_estimada: "17/01/2024", cantidad_kg: "1800.00", hub_id: 1, status: "on_time", product_id: 1, material_type: "type1", transport: "sea", delay_reason: "clima", stage: "3" },
  { po_number: "PO-004", fecha_salida: "04/01/2024", fecha_estimada: "18/01/2024", cantidad_kg: "2200.00", hub_id: 3, status: "delayed", product_id: 3, material_type: "type3", transport: "air", delay_reason: "aduana", stage: "4" },
  { po_number: "PO-005", fecha_salida: "05/01/2024", fecha_estimada: "19/01/2024", cantidad_kg: "1700.00", hub_id: 2, status: "on_time", product_id: 2, material_type: "type2", transport: "sea", delay_reason: "despacho", stage: "5" },
  { po_number: "PO-006", fecha_salida: "06/01/2024", fecha_estimada: "20/01/2024", cantidad_kg: "2100.00", hub_id: 4, status: "delayed", product_id: 4, material_type: "type4", transport: "air", delay_reason: "transporte", stage: "1" },
  { po_number: "PO-007", fecha_salida: "07/01/2024", fecha_estimada: "21/01/2024", cantidad_kg: "1600.00", hub_id: 1, status: "on_time", product_id: 1, material_type: "type1", transport: "sea", delay_reason: "documental", stage: "2" },
  { po_number: "PO-008", fecha_salida: "08/01/2024", fecha_estimada: "22/01/2024", cantidad_kg: "2300.00", hub_id: 2, status: "delayed", product_id: 2, material_type: "type2", transport: "air", delay_reason: "clima", stage: "3" },
];

// Estado de filtros activos
const activeFilters = {
  hub_id: [],
  status: [],
  transport: [],
  delay: [],
  stage: [],
  product_id: [],
  material_type: [],
  delay_reason: [],
};

// Guarda los colores originales de cada gráfico
const hubOriginalColors = hubData.map(item => item.color);
const statusOriginalColors = deliveryStatusData.map(item => item.color);
const transportOriginalColors = transportTypeData.map(item => item.color);
const delayOriginalColors = delayReasonsData.map(item => item.color);
const stageOriginalColors = posByStageData.map(item => item.color);

// Filtrar datos de la tabla según los filtros activos
function getFilteredDetailData() {
  let filtered = [...detailData];
  if (activeFilters.hub_id.length > 0) {
    filtered = filtered.filter(row => activeFilters.hub_id.includes(row.hub_id));
  }
  if (activeFilters.status.length > 0) {
    filtered = filtered.filter(row => activeFilters.status.includes(row.status));
  }
  if (activeFilters.product_id.length > 0) {
    filtered = filtered.filter(row => activeFilters.product_id.includes(row.product_id));
  }
  if (activeFilters.material_type.length > 0) {
    filtered = filtered.filter(row => activeFilters.material_type.includes(row.material_type));
  }
  if (activeFilters.transport.length > 0) {
    filtered = filtered.filter(row => activeFilters.transport.includes(row.transport));
  }
  if (activeFilters.delay_reason.length > 0) {
    filtered = filtered.filter(row => activeFilters.delay_reason.includes(row.delay_reason));
  }
  if (activeFilters.stage.length > 0) {
    filtered = filtered.filter(row => activeFilters.stage.includes(row.stage));
  }
  return filtered;
}

// Actualizar tabla según filtros
function updateFilteredTable() {
  const data = getFilteredDetailData();
  const tbody = document.getElementById("detailTableBody");
  tbody.innerHTML = "";
  data.forEach((row) => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${row.po_number}</td>
      <td style="color: #6b7280;">${row.fecha_estimada}</td>
      <td class="text-right">${row.cantidad_kg}</td>
      <td class="text-right">${row.hub_id}</td>
    `;
    tbody.appendChild(tr);
  });
  // Fila total
  const totalRow = document.createElement("tr");
  totalRow.className = "total-row";
  totalRow.innerHTML = `
    <td>Total</td>
    <td></td>
    <td class="text-right">${data.reduce((acc, row) => acc + parseFloat(row.cantidad_kg), 0).toFixed(2)}</td>
    <td class="text-right">${data.length}</td>
  `;
  tbody.appendChild(totalRow);
}

// Efecto de aclarado en los gráficos
function highlightChartSegment(chartId, selectedIdx) {
  const chart = Chart.getChart(chartId);
  if (!chart) return;
  chart.data.datasets[0].backgroundColor = chart.data.datasets[0].backgroundColor.map((color, idx) => {
    if (selectedIdx === null) return color;
    return idx === selectedIdx ? color : color + '33'; // Añade opacidad a los no seleccionados
  });
  chart.update();
}

// Actualizar todos los multi-selects visualmente según los filtros activos
function syncMultiSelectsWithFilters() {
  // Hub (categoría)
  const categoriaGroup = document.querySelectorAll('.filter-group')[1];
  if (categoriaGroup) {
    const checkboxes = categoriaGroup.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => {
      cb.checked = activeFilters.hub_id.includes(Number(cb.value));
    });
    updateMultiSelectDisplay(categoriaGroup);
  }
  // Puedes agregar lógica similar para otros filtros multi-select si los implementas
}

// Actualizar el contador/placeholder de un multi-select
function updateMultiSelectDisplay(multiSelectGroup) {
  const valueSpan = multiSelectGroup.querySelector('.multi-select-value');
  const checked = multiSelectGroup.querySelectorAll('input[type="checkbox"]:checked');
  if (checked.length === 0) {
    valueSpan.textContent = valueSpan.getAttribute('data-placeholder') || 'Seleccionar';
  } else if (checked.length === 1) {
    valueSpan.textContent = checked[0].parentElement.querySelector('label').textContent;
  } else {
    valueSpan.textContent = `${checked.length} seleccionados`;
  }
}

// Actualizar el color de los gráficos según los filtros activos
function syncChartsWithFilters() {
  // Hub
  const hubChart = Chart.getChart('hubChart');
  if (hubChart) {
    hubChart.data.datasets[0].backgroundColor = hubOriginalColors.map((color, idx) => {
      if (activeFilters.hub_id.length === 0) return color;
      return activeFilters.hub_id.includes(hubData[idx].hub_id) ? color : color + '33';
    });
    hubChart.update();
  }
  // Estado entrega
  const statusChart = Chart.getChart('statusChart');
  if (statusChart) {
    statusChart.data.datasets[0].backgroundColor = statusOriginalColors.map((color, idx) => {
      if (activeFilters.status.length === 0) return color;
      return activeFilters.status.includes(deliveryStatusData[idx].status) ? color : color + '33';
    });
    statusChart.update();
  }
  // Tipo de transporte
  const transportChart = Chart.getChart('transportChart');
  if (transportChart) {
    transportChart.data.datasets[0].backgroundColor = transportOriginalColors.map((color, idx) => {
      if (activeFilters.transport.length === 0) return color;
      return activeFilters.transport.includes(transportTypeData[idx].transport) ? color : color + '33';
    });
    transportChart.update();
  }
  // Motivo de atraso
  const delayChart = Chart.getChart('delayChart');
  if (delayChart) {
    delayChart.data.datasets[0].backgroundColor = delayOriginalColors.map((color, idx) => {
      if (activeFilters.delay_reason.length === 0) return color;
      // Mapea el label a valor
      const delayMap = {
        "Problemas de transporte": "transporte",
        "Error documental": "documental",
        "Clima adverso": "clima",
        "Retraso en aduana": "aduana",
        "Demora en despacho": "despacho"
      };
      const value = delayMap[delayReasonsData[idx].name];
      return activeFilters.delay_reason.includes(value) ? color : color + '33';
    });
    delayChart.update();
  }
  // PO's por etapa
  const stageChart = Chart.getChart('stageChart');
  if (stageChart) {
    stageChart.data.datasets[0].backgroundColor = stageOriginalColors.map((color, idx) => {
      if (activeFilters.stage.length === 0) return color;
      const stageValue = (idx + 1).toString();
      return activeFilters.stage.includes(stageValue) ? color : color + '33';
    });
    stageChart.update();
  }
}

// Centralizar la actualización de todo el estado visual y de datos
function updateDashboardUI() {
  syncMultiSelectsWithFilters();
  syncChartsWithFilters();
  updateFilteredTable();
  updateClearFiltersButton();
}

// MULTISELECT REUTILIZABLE
(function() {
  function closeAllDropdowns(except) {
    document.querySelectorAll('.multi-select-content').forEach(content => {
      if (content !== except) content.classList.remove('active');
    });
    document.querySelectorAll('.multi-select-trigger').forEach(trigger => {
      if (!except || trigger.parentElement.querySelector('.multi-select-content') !== except) {
        trigger.classList.remove('active');
      }
    });
  }

  document.querySelectorAll('[data-multiselect]').forEach(ms => {
    const trigger = ms.querySelector('.multi-select-trigger');
    const content = ms.querySelector('.multi-select-content');
    const valueSpan = ms.querySelector('.multi-select-value');
    const searchInput = ms.querySelector('.multi-select-search-input');
    const optionsBox = ms.querySelector('.multi-select-options');
    const clearBtn = ms.querySelector('.multi-select-clear');
    const checkboxes = optionsBox.querySelectorAll('input[type="checkbox"]');
    const placeholder = ms.getAttribute('data-placeholder') || 'Seleccionar';

    // Abrir/cerrar dropdown
    trigger.addEventListener('click', e => {
      e.stopPropagation();
      const isActive = content.classList.contains('active');
      closeAllDropdowns(content);
      content.classList.toggle('active', !isActive);
      trigger.classList.toggle('active', !isActive);
      if (!isActive && searchInput) {
        searchInput.value = '';
        Array.from(optionsBox.children).forEach(opt => opt.style.display = 'flex');
        searchInput.focus();
      }
    });

    // Selección múltiple
    checkboxes.forEach(cb => {
      cb.addEventListener('change', () => {
        updateDisplay();
      });
    });

    // Búsqueda
    if (searchInput) {
      searchInput.addEventListener('input', e => {
        const term = e.target.value.toLowerCase();
        Array.from(optionsBox.children).forEach(opt => {
          const label = opt.textContent.toLowerCase();
          opt.style.display = label.includes(term) ? 'flex' : 'none';
        });
      });
    }

    // Limpiar selección
    clearBtn.addEventListener('click', e => {
      e.stopPropagation();
      checkboxes.forEach(cb => cb.checked = false);
      if (searchInput) {
        searchInput.value = '';
        Array.from(optionsBox.children).forEach(opt => opt.style.display = 'flex');
      }
      updateDisplay();
      content.classList.remove('active');
      trigger.classList.remove('active');
    });

    // Cerrar al hacer click fuera
    document.addEventListener('click', e => {
      if (!ms.contains(e.target)) {
        content.classList.remove('active');
        trigger.classList.remove('active');
      }
    });

    function updateDisplay() {
      const selected = Array.from(checkboxes).filter(cb => cb.checked);
      if (selected.length === 0) {
        valueSpan.textContent = placeholder;
      } else if (selected.length === 1) {
        valueSpan.textContent = selected[0].parentElement.textContent.trim();
      } else {
        valueSpan.textContent = `${selected.length} seleccionados`;
      }
    }
    updateDisplay();
  });
})();

// Modificar la lógica de los gráficos para que usen la función centralizada
function setupChartClickFilters() {
  const chartConfigs = [
    { chartId: "hubChart", filterType: "hub_id", options: [1, 2, 3, 4] },
    // ...otros gráficos
  ];
  chartConfigs.forEach(({ chartId, filterType, options }) => {
    const canvas = document.getElementById(chartId);
    if (!canvas) return;
    canvas.onclick = function (evt) {
      const chart = Chart.getChart(chartId);
      if (!chart) return;
      const points = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
      if (points.length) {
        const idx = points[0].index;
        const value = options[idx];
        if (activeFilters[filterType].includes(value)) {
          activeFilters[filterType] = activeFilters[filterType].filter(v => v !== value);
        } else {
          activeFilters[filterType].push(value);
        }
        updateDashboardUI();
      }
    };
  });
}

// Gráficos
function createPieChart(canvasId, data, legendId, onClickHandler) {
  const ctx = document.getElementById(canvasId).getContext("2d");
  const chart = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: data.map((item) => item.name),
      datasets: [
        {
          data: data.map((item) => item.value),
          backgroundColor: data.map((item) => item.color),
          borderWidth: 0,
          cutout: "30%",
        },
      ],
    },
    options: {
      responsive: false,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
      },
      onClick: onClickHandler || undefined,
    },
  });
  window[canvasId] = chart;
  // Leyenda
  const legendContainer = document.getElementById(legendId);
  legendContainer.innerHTML = "";
  data.forEach((item) => {
    const legendItem = document.createElement("div");
    legendItem.className = "legend-item";
    legendItem.innerHTML = `
      <div class="legend-label">
        <div class="legend-color" style="background-color: ${item.color}"></div>
        <span>${item.name}</span>
      </div>
      <span>${item.value}%</span>
    `;
    legendContainer.appendChild(legendItem);
  });
}

// Tabla de detalle
function populateDetailTable() {
  const tbody = document.getElementById("detailTableBody");
  tbody.innerHTML = "";
  detailData.forEach((row) => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${row.po_number}</td>
      <td style="color: #6b7280;">${row.fecha_estimada}</td>
      <td class="text-right">${row.cantidad_kg}</td>
      <td class="text-right">${row.hub_id}</td>
    `;
    tbody.appendChild(tr);
  });
  // Fila total
  const totalRow = document.createElement("tr");
  totalRow.className = "total-row";
  totalRow.innerHTML = `
    <td>Total</td>
    <td></td>
    <td class="text-right">${detailData.reduce((acc, row) => acc + parseFloat(row.cantidad_kg), 0).toFixed(2)}</td>
    <td class="text-right">${detailData.length}</td>
  `;
  tbody.appendChild(totalRow);
}

// Modales
function showModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.style.display = "flex";
  document.body.style.overflow = "hidden";
}
function hideModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.style.display = "none";
  document.body.style.overflow = "auto";
}

// --- INTEGRACIÓN CLICK EN GRÁFICOS Y MULTISELECTS ---
function selectMultiSelectOption(filterType, value) {
  let key;
  if (filterType === 'categoría') key = 'hub_id';
  else if (filterType === 'producto') key = 'product_id';
  else if (filterType === 'material') key = 'material_type';
  else if (filterType === 'status') key = 'status';
  else if (filterType === 'transport') key = 'transport';
  else if (filterType === 'delay_reason') key = 'delay_reason';
  else if (filterType === 'stage') key = 'stage';
  else return;
  if (!activeFilters[key].includes(value)) {
    activeFilters[key].push(value);
  }
  updateDashboardUI();
}

// Función para limpiar todos los filtros
function clearAllFilters() {
  activeFilters.hub_id = [];
  activeFilters.status = [];
  activeFilters.product_id = [];
  activeFilters.material_type = [];
  activeFilters.transport = [];
  activeFilters.delay_reason = [];
  activeFilters.stage = [];
  updateDashboardUI();
  updateClearFiltersButton();
}

// Inicialización
window.addEventListener("DOMContentLoaded", () => {
  // Gráficos
  createPieChart("hubChart", hubData, "hubLegend", function(evt, elements) {
    if (elements.length > 0) {
      const idx = elements[0].index;
      const hubId = hubData[idx].hub_id;
      selectMultiSelectOption('categoría', hubId);
    }
  });
  createPieChart("statusChart", deliveryStatusData, "statusLegend", function(evt, elements) {
    if (elements.length > 0) {
      const idx = elements[0].index;
      const status = deliveryStatusData[idx].status;
      selectMultiSelectOption('status', status);
    }
  });
  createPieChart("transportChart", transportTypeData, "transportLegend", function(evt, elements) {
    if (elements.length > 0) {
      const idx = elements[0].index;
      const transport = transportTypeData[idx].transport;
      selectMultiSelectOption('transport', transport);
    }
  });
  createPieChart("delayChart", delayReasonsData, "delayLegend", function(evt, elements) {
    if (elements.length > 0) {
      const idx = elements[0].index;
      // Mapear el nombre a un valor de delay_reason
      const delayMap = {
        "Problemas de transporte": "transporte",
        "Error documental": "documental",
        "Clima adverso": "clima",
        "Retraso en aduana": "aduana",
        "Demora en despacho": "despacho"
      };
      const delayLabel = delayReasonsData[idx].name;
      const delayValue = delayMap[delayLabel];
      selectMultiSelectOption('delay_reason', delayValue);
    }
  });
  const stageDataWithNumbers = posByStageData.map((item, index) => ({ ...item, name: `${index + 1}` }));
  createPieChart("stageChart", stageDataWithNumbers, "stageLegend", function(evt, elements) {
    if (elements.length > 0) {
      const idx = elements[0].index;
      const stage = (idx + 1).toString();
      selectMultiSelectOption('stage', stage);
    }
  });
  // Tabla y UI
  updateDashboardUI();
  // Modales
  document.getElementById("showSuccessBtn").addEventListener("click", () => showModal("successModal"));
  document.getElementById("showErrorBtn").addEventListener("click", () => showModal("errorModal"));
  document.getElementById("closeSuccessBtn").addEventListener("click", () => hideModal("successModal"));
  document.getElementById("closeErrorBtn").addEventListener("click", () => hideModal("errorModal"));
  document.getElementById("successModal").addEventListener("click", (e) => { if (e.target.id === "successModal") hideModal("successModal"); });
  document.getElementById("errorModal").addEventListener("click", (e) => { if (e.target.id === "errorModal") hideModal("errorModal"); });
  document.addEventListener("keydown", (e) => { if (e.key === "Escape") { hideModal("successModal"); hideModal("errorModal"); } });
  // Asocia el botón de borrar filtros a la función
  const clearBtn = document.getElementById("clearFiltersBtn");
  if (clearBtn) {
    clearBtn.addEventListener("click", clearAllFilters);
  }
});

function updateClearFiltersButton() {
  const clearBtn = document.getElementById("clearFiltersBtn");
  if (!clearBtn) return;
  const anyActive = (
    activeFilters.hub_id.length > 0 ||
    activeFilters.status.length > 0 ||
    activeFilters.product_id.length > 0 ||
    activeFilters.material_type.length > 0 ||
    activeFilters.transport.length > 0 ||
    activeFilters.delay_reason.length > 0 ||
    activeFilters.stage.length > 0
  );
  clearBtn.style.display = anyActive ? "block" : "none";
}
