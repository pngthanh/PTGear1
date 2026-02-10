function initRevenueChart(labels, data) {
  const ctx = document.getElementById("revenueChart");
  if (!ctx) return;

  new Chart(ctx, {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Doanh thu (VNĐ)",
          data: data,
          borderColor: "#3b82f6",
          backgroundColor: "rgba(59, 130, 246, 0.1)",
          fill: true,
          tension: 0.4,
          pointBackgroundColor: "#3b82f6",
          pointBorderWidth: 2,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function (value) {
              return new Intl.NumberFormat("vi-VN").format(value) + "đ";
            },
          },
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              let label = context.dataset.label || "";
              if (label) {
                label += ": ";
              }
              if (context.parsed.y !== null) {
                label +=
                  new Intl.NumberFormat("vi-VN").format(context.parsed.y) + "đ";
              }
              return label;
            },
          },
        },
      },
    },
  });
}

function initCategoryChart(labels, data, colors) {
  const ctx = document.getElementById("categoryChart");
  if (!ctx) return;

  new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Sản phẩm đã bán",
          data: data,
          backgroundColor: colors,
          hoverOffset: 4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: "top",
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              let label = context.label || "";
              if (label) {
                label += ": ";
              }
              if (context.parsed !== null) {
                label += context.parsed;
              }
              return label;
            },
          },
        },
      },
    },
  });
}
