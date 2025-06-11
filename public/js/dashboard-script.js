// Data from the operational dashboard
const hubData = [
  { name: "EWR", value: 47.8, color: "#565aff" },
  { name: "MIA", value: 50, color: "#9aabff" },
  { name: "AVR", value: 1.2, color: "#ff3459" },
  { name: "DIR", value: 1, color: "#f46844" },
]

const deliveryStatusData = [
  { name: "On Time", value: 71.7, color: "#565aff" },
  { name: "Atrasado", value: 28.3, color: "#c9cfff" },
]

const transportTypeData = [{ name: "Marítimo", value: 100, color: "#565aff" }]

const delayReasonsData = [
  { name: "Problemas de transporte", value: 26.7, color: "#565aff" },
  { name: "Error documental", value: 20, color: "#9aabff" },
  { name: "Clima adverso", value: 20, color: "#5ae7f4" },
  { name: "Retraso en aduana", value: 17.8, color: "#f46844" },
  { name: "Demora en despacho", value: 15.6, color: "#5dd595" },
]

const posByStageData = [
  { name: "Etapa 1", value: 28.3, color: "#565aff" },
  { name: "Etapa 2", value: 21.7, color: "#ff3459" },
  { name: "Etapa 3", value: 22.8, color: "#f46844" },
  { name: "Etapa 4", value: 13, color: "#5dd595" },
  { name: "Etapa 5", value: 16.2, color: "#9aabff" },
]

const detailData = [
  { fechaSalida: "18 abr 2025", fechaEstimada: "null", cantidadKgs: "4.899,28", noPO: "21" },
  { fechaSalida: "19 abr 2025", fechaEstimada: "null", cantidadKgs: "343,96", noPO: "5" },
  { fechaSalida: "20 abr 2025", fechaEstimada: "null", cantidadKgs: "2.042,78", noPO: "6" },
  { fechaSalida: "21 abr 2025", fechaEstimada: "null", cantidadKgs: "15.660,87", noPO: "18" },
  { fechaSalida: "22 abr 2025", fechaEstimada: "null", cantidadKgs: "8.126,03", noPO: "4" },
  { fechaSalida: "23 abr 2025", fechaEstimada: "null", cantidadKgs: "2.140,59", noPO: "4" },
  { fechaSalida: "24 abr 2025", fechaEstimada: "null", cantidadKgs: "1.669,86", noPO: "7" },
  { fechaSalida: "25 abr 2025", fechaEstimada: "null", cantidadKgs: "1.481,52", noPO: "4" },
  { fechaSalida: "26 abr 2025", fechaEstimada: "null", cantidadKgs: "529,09", noPO: "6" },
  { fechaSalida: "27 abr 2025", fechaEstimada: "null", cantidadKgs: "5.281,29", noPO: "4" },
  { fechaSalida: "28 abr 2025", fechaEstimada: "null", cantidadKgs: "4.129,58", noPO: "12" },
]

// Function to check if Chart.js is loaded and DOM elements exist
function waitForChartAndElements() {
  return new Promise((resolve) => {
    const checkReady = () => {
      console.log('Checking Chart.js availability...', typeof Chart !== 'undefined');

      // Check if Chart.js is loaded
      if (typeof Chart === 'undefined') {
        console.log('Chart.js not loaded yet, waiting...');
        setTimeout(checkReady, 100);
        return;
      }

      // Check if all canvas elements exist
      const canvasIds = ['hubChart', 'deliveryChart', 'transportChart', 'delayChart', 'stageChart'];
      const allCanvasExist = canvasIds.every(id => {
        const element = document.getElementById(id);
        const exists = element !== null;
        if (!exists) {
          console.log(`Canvas element ${id} not found`);
        }
        return exists;
      });

      if (!allCanvasExist) {
        console.log('Not all canvas elements ready, waiting...');
        setTimeout(checkReady, 100);
        return;
      }

      // Check if table body exists
      const tableBody = document.getElementById('detailTableBody');
      if (!tableBody) {
        console.log('Table body not found, waiting...');
        setTimeout(checkReady, 100);
        return;
      }

      console.log('All elements ready, resolving...');
      resolve();
    };

    checkReady();
  });
}

// Function to create pie chart
function createPieChart(canvasId, data, legendId) {
  console.log(`Creating pie chart for ${canvasId}...`);

  const canvas = document.getElementById(canvasId);
  if (!canvas) {
    console.error(`Canvas element ${canvasId} not found`);
    return;
  }

  const ctx = canvas.getContext("2d");
  if (!ctx) {
    console.error(`Could not get 2D context for ${canvasId}`);
    return;
  }

  try {
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
          legend: {
            display: false,
          },
        },
      },
    });

    console.log(`Chart ${canvasId} created successfully`);

    // Create custom legend
    const legendContainer = document.getElementById(legendId);
    if (legendContainer) {
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
      console.log(`Legend ${legendId} created successfully`);
    } else {
      console.error(`Legend container ${legendId} not found`);
    }
  } catch (error) {
    console.error(`Error creating chart ${canvasId}:`, error);
  }
}

// Function to populate detail table
function populateDetailTable() {
  console.log('Populating detail table...');

  const tbody = document.getElementById("detailTableBody");
  if (!tbody) {
    console.error('Table body not found');
    return;
  }

  tbody.innerHTML = "";

  detailData.forEach((row) => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
            <td>${row.fechaSalida}</td>
            <td style="color: #6b7280;">${row.fechaEstimada}</td>
            <td class="text-right">${row.cantidadKgs}</td>
            <td class="text-right">${row.noPO}</td>
        `;
    tbody.appendChild(tr);
  });

  // Add total row
  const totalRow = document.createElement("tr");
  totalRow.className = "total-row";
  totalRow.innerHTML = `
        <td>Total</td>
        <td></td>
        <td class="text-right">46.304,85</td>
        <td class="text-right">91</td>
    `;
  tbody.appendChild(totalRow);

  console.log('Detail table populated successfully');
}

// Function to initialize all charts
async function initializeCharts() {
  console.log('Starting chart initialization...');

  try {
    await waitForChartAndElements();

    console.log('All prerequisites met, creating charts...');

    // Initialize charts
    createPieChart("hubChart", hubData, "hubLegend");
    createPieChart("deliveryChart", deliveryStatusData, "deliveryLegend");
    createPieChart("transportChart", transportTypeData, "transportLegend");
    createPieChart("delayChart", delayReasonsData, "delayLegend");

    // Create stage chart with numbered labels
    const stageDataWithNumbers = posByStageData.map((item, index) => ({
      ...item,
      name: `${index + 1}`,
    }));
    createPieChart("stageChart", stageDataWithNumbers, "stageLegend");

    console.log('All charts initialized successfully');
  } catch (error) {
    console.error('Error initializing charts:', error);
  }
}

// Modal functions
function showModal(modalId) {
  const modal = document.getElementById(modalId)
  modal.style.display = "flex"
  document.body.style.overflow = "hidden"
}

function hideModal(modalId) {
  const modal = document.getElementById(modalId)
  modal.style.display = "none"
  document.body.style.overflow = "auto"
}

// Initialize everything when page loads
function initializeDashboard() {
  console.log('Initializing dashboard...');

  // Initialize charts
  initializeCharts();

  // Populate table
  populateDetailTable();

  // Setup modal event listeners
  const showSuccessBtn = document.getElementById("showSuccessBtn");
  const showErrorBtn = document.getElementById("showErrorBtn");
  const closeSuccessBtn = document.getElementById("closeSuccessBtn");
  const closeErrorBtn = document.getElementById("closeErrorBtn");
  const successModal = document.getElementById("successModal");
  const errorModal = document.getElementById("errorModal");

  if (showSuccessBtn) {
    showSuccessBtn.addEventListener("click", () => {
      showModal("successModal");
    });
  }

  if (showErrorBtn) {
    showErrorBtn.addEventListener("click", () => {
      showModal("errorModal");
    });
  }

  if (closeSuccessBtn) {
    closeSuccessBtn.addEventListener("click", () => {
      hideModal("successModal");
    });
  }

  if (closeErrorBtn) {
    closeErrorBtn.addEventListener("click", () => {
      hideModal("errorModal");
    });
  }

  // Close modals when clicking outside
  if (successModal) {
    successModal.addEventListener("click", (e) => {
      if (e.target.id === "successModal") {
        hideModal("successModal");
      }
    });
  }

  if (errorModal) {
    errorModal.addEventListener("click", (e) => {
      if (e.target.id === "errorModal") {
        hideModal("errorModal");
      }
    });
  }

  // Close modals with ESC key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      hideModal("successModal");
      hideModal("errorModal");
    }
  });
}

// Multiple initialization strategies
document.addEventListener("DOMContentLoaded", initializeDashboard);

// Fallback for when DOMContentLoaded has already fired
if (document.readyState === 'loading') {
  // Document still loading, DOMContentLoaded will fire
  console.log('Document still loading...');
} else {
  // Document already loaded
  console.log('Document already loaded, initializing immediately...');
  initializeDashboard();
}

// Additional fallback with window.onload
window.addEventListener('load', () => {
  console.log('Window load event fired, checking if charts exist...');
  // Only initialize if charts haven't been created yet
  if (!document.querySelector('#hubChart canvas')) {
    console.log('Charts not found, initializing as fallback...');
    initializeDashboard();
  }
});
