document.addEventListener("DOMContentLoaded", function () {
  const tableBody = document.getElementById("order-table-body");
  if (!tableBody) return;

  tableBody.addEventListener("click", function (event) {
    // Xử lý nút Duyệt
    if (event.target.classList.contains("btn-approve")) {
      handleApproveOrder(event.target);
    }

    // Xử lý nút Hủy (Admin)
    if (event.target.classList.contains("btn-cancel-admin")) {
      handleCancelOrder(event.target);
    }
  });

  async function handleApproveOrder(button) {
    const orderId = button.getAttribute("data-order-id");
    const row = button.closest("tr");
    if (!orderId || !row) return;

    // Vô hiệu hóa các nút
    button.disabled = true;
    button.textContent = "Đang...";
    const cancelButton = row.querySelector(".btn-cancel-admin");
    if (cancelButton) cancelButton.disabled = true;

    try {
      const response = await fetch("assets/api/approve_order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ order_id: orderId }),
      });

      const result = await response.json();

      if (result.success) {
        updateRowUI_Approve(row);
        showToast(result.message || "Đã duyệt đơn hàng thành công!", "success");
      } else {
        showToast(result.message || "Duyệt đơn thất bại.", "error");
        button.disabled = false;
        button.textContent = "Duyệt";
        if (cancelButton) cancelButton.disabled = false;
      }
    } catch (error) {
      console.error("Lỗi:", error);
      showToast("Lỗi kết nối. Vui lòng thử lại.", "error");
      button.disabled = false;
      button.textContent = "Duyệt";
      if (cancelButton) cancelButton.disabled = false;
    }
  }

  async function handleCancelOrder(button) {
    const orderId = button.getAttribute("data-order-id");
    const row = button.closest("tr");
    if (!orderId || !row) return;

    // Vô hiệu hóa các nút
    button.disabled = true;
    button.textContent = "Đang...";
    const approveButton = row.querySelector(".btn-approve");
    if (approveButton) approveButton.disabled = true;

    try {
      const response = await fetch(
        "/ptgear/assets/api/admin_cancel_order.php",
        {
          // Gọi API mới
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify({ order_id: orderId }),
        },
      );

      const result = await response.json();

      if (result.success) {
        updateRowUI_Cancel(row);
        showToast(result.message || "Đã hủy đơn hàng.", "success");
      } else {
        showToast(result.message || "Hủy đơn thất bại.", "error");
        button.disabled = false;
        button.textContent = "Hủy";
        if (approveButton) approveButton.disabled = false;
      }
    } catch (error) {
      showToast("Lỗi kết nối. Vui lòng thử lại.", "error");
      button.disabled = false;
      button.textContent = "Hủy";
      if (approveButton) approveButton.disabled = false;
    }
  }

  function updateRowUI_Approve(row) {
    const statusBadge = row.querySelector(".status-badge");
    if (statusBadge) {
      statusBadge.textContent = "Đang Giao"; // <-- THÊM DÒNG NÀY
      statusBadge.className = "status-badge status-shipping";
    }

    const approveButton = row.querySelector(".btn-approve");
    if (approveButton) {
      approveButton.textContent = "Duyệt";
      approveButton.disabled = true;
    }

    const cancelButton = row.querySelector(".btn-cancel-admin");
    if (cancelButton) {
      cancelButton.disabled = true;
    }
  }

  function updateRowUI_Cancel(row) {
    const statusBadge = row.querySelector(".status-badge");
    if (statusBadge) {
      statusBadge.textContent = "Đã Hủy";
      statusBadge.className = "status-badge status-canceled";
    }

    const approveButton = row.querySelector(".btn-approve");
    if (approveButton) {
      approveButton.disabled = true;
    }

    const cancelButton = row.querySelector(".btn-cancel-admin");
    if (cancelButton) {
      cancelButton.textContent = "Hủy";
      cancelButton.disabled = true;
    }
  }

  function showToast(message, type = "success") {
    const toast = document.getElementById("toast");
    if (!toast) return;

    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.classList.remove("hidden");

    // Tự động ẩn sau 3 giây
    setTimeout(() => {
      toast.classList.add("hidden");
    }, 3000);
  }
});
