document.addEventListener("DOMContentLoaded", function () {
  const approveBtn = document.getElementById("btn-approve-detail");
  const cancelBtn = document.getElementById("btn-cancel-detail");

  const orderId = CURRENT_ORDER_ID;

  if (approveBtn) {
    approveBtn.addEventListener("click", handleApproveOrder);
  }
  if (cancelBtn) {
    cancelBtn.addEventListener("click", handleCancelOrder);
  }

  // duyệt
  async function handleApproveOrder() {
    setButtonsLoading(true);

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
        showToast(result.message, "success");
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast(result.message, "error");
        setButtonsLoading(false);
      }
    } catch (error) {
      showToast("Lỗi kết nối.", "error");
      setButtonsLoading(false);
    }
  }

  // hủy
  async function handleCancelOrder() {
    setButtonsLoading(true);

    try {
      const response = await fetch("assets/api/admin_cancel_order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ order_id: orderId }),
      });
      const result = await response.json();

      if (result.success) {
        showToast(result.message, "success");
        // Tải lại trang
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast(result.message, "error");
        setButtonsLoading(false);
      }
    } catch (error) {
      showToast("Lỗi kết nối.", "error");
      setButtonsLoading(false);
    }
  }

  // bật tắt nút
  function setButtonsLoading(isLoading) {
    if (approveBtn) {
      approveBtn.disabled = isLoading;
      approveBtn.innerHTML = isLoading
        ? '<i class="fa fa-spinner fa-spin"></i> Đang...'
        : '<i class="fa fa-check"></i> Duyệt Đơn Hàng';
    }
    if (cancelBtn) {
      cancelBtn.disabled = isLoading;
      cancelBtn.innerHTML = isLoading
        ? '<i class="fa fa-spinner fa-spin"></i> Đang...'
        : '<i class="fa fa-times"></i> Hủy Đơn Hàng';
    }
  }

  function showToast(message, type = "success") {
    const toast = document.getElementById("toast");
    if (!toast) return;

    toast.textContent = message;
    toast.className = `toast ${type}`;
    toast.classList.remove("hidden");

    setTimeout(() => {
      toast.classList.add("hidden");
    }, 3000);
  }
});
