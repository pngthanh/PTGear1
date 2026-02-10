document.addEventListener("DOMContentLoaded", function () {
  const tabs = document.querySelectorAll(".tab-item");
  const orderCards = document.querySelectorAll(".order-card");
  const orderList = document.querySelector(".order-list");

  tabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      tabs.forEach((t) => t.classList.remove("active"));
      this.classList.add("active");
      const selectedStatus = this.getAttribute("data-status");
      filterOrders(selectedStatus);
    });
  });

  function filterOrders(status) {
    orderCards.forEach((card) => {
      const cardStatus = card.getAttribute("data-status");
      if (status === "all" || status === cardStatus) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  }

  filterOrders("all");

  if (orderList) {
    orderList.addEventListener("click", function (event) {
      if (event.target.classList.contains("btn-cancel")) {
        handleCancelOrder(event.target);
      }
      if (event.target.classList.contains("btn-complete")) {
        handleCompleteOrder(event.target);
      }
    });
  }

  async function handleCancelOrder(cancelButton) {
    const orderId = cancelButton.getAttribute("data-order-id");
    if (!orderId) return;

    cancelButton.disabled = true;
    cancelButton.textContent = "Đang xử lý...";

    try {
      const response = await fetch("assets/api/cancel_order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ order_id: orderId }),
      });

      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`Lỗi Server: ${response.status}. ${errorText}`);
      }

      const result = await response.json();
      if (result.success) {
        updateOrderCardUI(cancelButton, orderId);
        showToast("Hủy đơn hàng thành công!", "success");
      } else {
        showToast(result.message || "Không thể hủy đơn hàng này.", "error");
        cancelButton.disabled = false;
        cancelButton.textContent = "Hủy Đơn";
      }
    } catch (error) {
      console.error("Lỗi fetch:", error);
      showToast("Lỗi: " + (error.message || "Lỗi kết nối."), "error");
      cancelButton.disabled = false;
      cancelButton.textContent = "Hủy Đơn";
    }
  }

  function updateOrderCardUI(button, orderId) {
    const card = button.closest(".order-card");
    if (!card) return;
    card.setAttribute("data-status", "canceled");

    const statusBadge = card.querySelector(".status-badge");
    if (statusBadge) {
      statusBadge.textContent = "Đã Hủy";
      statusBadge.className = "status-badge status-canceled";
    }

    const actionsContainer = button.parentElement;
    if (actionsContainer) {
      const disabledButton = document.createElement("button");
      disabledButton.className = "btn-action btn-reorder";
      disabledButton.disabled = true;
      disabledButton.textContent = "Đã Hủy";
      actionsContainer.replaceChild(disabledButton, button);
    }

    const activeTab = document.querySelector(".tab-item.active");
    if (activeTab && activeTab.getAttribute("data-status") !== "all") {
      card.style.display = "none";
    }
  }

  async function handleCompleteOrder(completeButton) {
    const orderId = completeButton.getAttribute("data-order-id");
    if (!orderId) return;

    completeButton.disabled = true;
    completeButton.textContent = "Đang xử lý...";

    try {
      const response = await fetch("assets/api/complete_order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({ order_id: orderId }),
      });

      const result = await response.json();

      if (result.success) {
        updateOrderCardUI_Complete(completeButton);
        showToast("Xác nhận đã nhận hàng thành công!", "success");
      } else {
        showToast(result.message || "Không thể xác nhận.", "error");
        completeButton.disabled = false;
        completeButton.textContent = "Đã nhận được hàng";
      }
    } catch (error) {
      console.error("Lỗi fetch:", error);
      showToast("Lỗi kết nối. Vui lòng thử lại.", "error");
      completeButton.disabled = false;
      completeButton.textContent = "Đã nhận được hàng";
    }
  }

  function updateOrderCardUI_Complete(button) {
    const card = button.closest(".order-card");
    if (!card) return;
    card.setAttribute("data-status", "completed");

    const statusBadge = card.querySelector(".status-badge");
    if (statusBadge) {
      statusBadge.textContent = "Đã Giao";
      statusBadge.className = "status-badge status-completed";
    }

    const actionsContainer = button.parentElement;
    if (actionsContainer) {
      const reviewButton = document.createElement("button");
      reviewButton.className = "btn-action btn-review";
      reviewButton.textContent = "Đánh Giá";
      actionsContainer.replaceChild(reviewButton, button);
    }

    const activeTab = document.querySelector(".tab-item.active");
    if (activeTab && activeTab.getAttribute("data-status") !== "all") {
      card.style.display = "none";
    }
  }
});
