// Data from the Forecast v/s Real dashboard
const forecastData = [
  { material: "20526345", forecast: "4.536", cantidadKgs: "4.536", desviacion: "0" },
  { material: "20526615", forecast: "3.375", cantidadKgs: "4.500", desviacion: "1.125" },
  { material: "20712399", forecast: "1.814,4", cantidadKgs: "1.814,4", desviacion: "0" },
  { material: "20526019", forecast: "884,52", cantidadKgs: "45,36", desviacion: "-839,16" },
  { material: "20526564", forecast: "612,36", cantidadKgs: "1.179,36", desviacion: "567" },
  { material: "20526351", forecast: "181,44", cantidadKgs: "1.081,48", desviacion: "900,04" },
  { material: "20588970", forecast: "136,08", cantidadKgs: "181,44", desviacion: "45,36" },
  { material: "20526699", forecast: "68,04", cantidadKgs: "45,36", desviacion: "-22,68" },
]

const vendorData = [
  { name: "KERRY – MANITOWOC...", value: 50, amount: "$1.2M", color: "#565AFF" },
  { name: "Tate & Lyle Solutions USA...", value: 30, amount: "$800K", color: "#F46844" },
  { name: "Crossville", value: 20, amount: "$645K", color: "#5AE7F4" },
]

const treemapData = [
  { material: "20526615", size: "large", color: "#565AFF", gridArea: "1 / 1 / 3 / 3" },
  { material: "20525889", size: "medium", color: "#7288FF", gridArea: "1 / 3 / 2 / 4" },
  { material: "20526302", size: "medium", color: "#9AABFF", gridArea: "1 / 4 / 2 / 5" },
  { material: "20526564", size: "small", color: "#AEBBFF", gridArea: "2 / 3 / 3 / 4" },
  { material: "20470935", size: "small", color: "#C9CFFF", gridArea: "2 / 4 / 3 / 5" },
  { material: "20526351", size: "medium", color: "#565AFF", gridArea: "3 / 1 / 4 / 3" },
  { material: "20511463", size: "small", color: "#7288FF", gridArea: "3 / 3 / 4 / 4" },
  { material: "203...", size: "small", color: "#9AABFF", gridArea: "3 / 4 / 4 / 5" },
  { material: "20...", size: "small", color: "#AEBBFF", gridArea: "4 / 1 / 5 / 2" },
  { material: "20...", size: "small", color: "#C9CFFF", gridArea: "4 / 2 / 5 / 3" },
]

// Function to populate forecast table
function populateForecastTable() {
  const tbody = document.getElementById("forecastTableBody")
  tbody.innerHTML = ""

  forecastData.forEach((row, index) => {
    const tr = document.createElement("tr")

    const deviationValue = Number.parseFloat(row.desviacion.replace(",", "."))
    let deviationClass = "deviation-zero"
    if (deviationValue > 0) {
      deviationClass = "deviation-positive"
    } else if (deviationValue < 0) {
      deviationClass = "deviation-negative"
    }

    tr.innerHTML = `
            <td class="material-code">${row.material}</td>
            <td class="text-right">${row.forecast}</td>
            <td class="text-right">${row.cantidadKgs}</td>
            <td class="text-right ${deviationClass}">${row.desviacion}</td>
        `
    tbody.appendChild(tr)
  })

  // Add total row
  const totalRow = document.createElement("tr")
  totalRow.className = "total-row"
  totalRow.innerHTML = `
        <td>Total</td>
        <td class="text-right">11.708,15</td>
        <td class="text-right">19.501,23</td>
        <td class="text-right total-deviation">7.793,08</td>
    `
  tbody.appendChild(totalRow)
}

// Function to create bar chart
function createBarChart() {
  const ctx = document.getElementById("barChart").getContext("2d")

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: ["abr 2025"],
      datasets: [
        {
          label: "Cantidad kgs",
          data: [46304.85],
          backgroundColor: "#565AFF",
          borderWidth: 0,
          barThickness: 80,
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
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: (value) => (value / 1000).toFixed(0) + "k",
            font: {
              family: "Inter",
              size: 12,
            },
          },
          grid: {
            display: true,
            color: "#f3f4f6",
          },
        },
        x: {
          ticks: {
            font: {
              family: "Inter",
              size: 12,
            },
          },
          grid: {
            display: false,
          },
        },
      },
    },
  })
}

// Function to create pie chart
function createPieChart() {
  const ctx = document.getElementById("pieChart").getContext("2d")

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: vendorData.map((item) => item.name),
      datasets: [
        {
          data: vendorData.map((item) => item.value),
          backgroundColor: vendorData.map((item) => item.color),
          borderWidth: 0,
          cutout: "40%",
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
  })
}

// Function to create vendor legend
function createVendorLegend() {
  const legendContainer = document.getElementById("vendorLegend")
  legendContainer.innerHTML = ""

  vendorData.forEach((item) => {
    const vendorItem = document.createElement("div")
    vendorItem.className = "vendor-item"
    vendorItem.innerHTML = `
            <div class="vendor-info">
                <div class="vendor-color" style="background-color: ${item.color}"></div>
                <span class="vendor-name">${item.name}</span>
            </div>
            <div class="vendor-stats">
                <span class="vendor-amount">${item.amount}</span>
                <div class="vendor-percentage">${item.value}%</div>
            </div>
        `
    legendContainer.appendChild(vendorItem)
  })
}

// Function to create treemap
function createTreemap() {
  const container = document.getElementById("treemapContainer")
  container.innerHTML = ""

  treemapData.forEach((item) => {
    const element = document.createElement("div")
    element.className = "treemap-item"
    element.style.backgroundColor = item.color
    element.style.gridArea = item.gridArea
    element.textContent = item.material
    container.appendChild(element)
  })
}

// Initialize everything when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  populateForecastTable()
  createBarChart()
  createPieChart()
  createVendorLegend()
  createTreemap()
})
