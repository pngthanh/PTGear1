document.addEventListener("DOMContentLoaded", function () {
  const cancelButton = document.getElementById("btn-cancel-detail");

  if (cancelButton) {
    cancelButton.addEventListener("click", function () {
      handleCancelOrder(this);
    });
  }

  async function handleCancelOrder(button) {
    const orderId = button.getAttribute("data-order-id");
    if (!orderId) return;

    button.disabled = true;
    button.textContent = "Đang xử lý...";

    try {
      const response = await fetch("assets/api/cancel_order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ order_id: orderId }),
      });

      const result = await response.json();

      if (result.success) {
        showToast("Đã hủy đơn hàng thành công.", "success");
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast(result.message || "Không thể hủy đơn hàng này.", "error");
        button.disabled = false;
        button.textContent = "Hủy Đơn";
      }
    } catch (error) {
      console.error("Lỗi:", error);
      showToast("Lỗi kết nối. Vui lòng thử lại.", "error");
      button.disabled = false;
      button.textContent = "Hủy Đơn";
    }
  }
});
