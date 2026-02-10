function updateQuantity(productId, change) {
  const input = document.querySelector(
    `.cart-item[data-product-id="${productId}"] .quantity-input`
  );
  if (!input) return;

  let newQuantity = parseInt(input.value) + change;
  const max = parseInt(input.getAttribute("max")) || 999;
  const min = parseInt(input.getAttribute("min")) || 1;

  if (newQuantity < min) newQuantity = min;
  if (newQuantity > max) newQuantity = max;

  input.value = newQuantity;
  updateQuantityInput(productId, newQuantity);
}

// Cập nhật số lượng từ input
function updateQuantityInput(productId, quantity) {
  quantity = parseInt(quantity) || 1;

  fetch("index.php?page=cart&action=update", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `product_id=${productId}&quantity=${quantity}`,
  })
    .then((response) => response.text())
    .then(() => updateCartDisplay())
    .catch((error) => console.error("Error:", error));
}

// Xóa sản phẩm khỏi giỏ hàng
function removeItem(productId) {
  fetch("index.php?page=cart&action=remove", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `product_id=${productId}`,
  })
    .then((response) => response.text())
    .then(() => {
      showToast("Đã xóa sản phẩm khỏi giỏ hàng", "success");
      // Tải lại trang hoặc xóa DOM
      location.reload();
    })
    .catch((error) => {
      console.error("Error:", error);
      showToast("Lỗi khi xóa sản phẩm", "error");
    });
}

// Áp dụng mã giảm giá
function applyVoucher() {
  const code = document.getElementById("voucherCode").value.trim();
  if (!code) {
    showToast("Vui lòng nhập mã giảm giá", "error");
    return;
  }
  showToast("Tính năng mã giảm giá đang được phát triển", "info");
}

// Cập nhật hiển thị giỏ hàng
function updateCartDisplay() {
  const items = document.querySelectorAll(".cart-item");
  let subtotal = 0;
  let selectedCount = 0;

  items.forEach((item) => {
    const checkbox = item.querySelector(".item-checkbox");
    if (checkbox && checkbox.checked) {
      const quantity =
        parseInt(item.querySelector(".quantity-input").value) || 0;
      const priceText = item.querySelector(".current-price").textContent;
      const price = parseFloat(priceText.replace(/[^\d]/g, "")) || 0;
      const itemSubtotal = price * quantity;
      item.querySelector(".subtotal-price").textContent =
        formatPrice(itemSubtotal);
      subtotal += itemSubtotal;
      selectedCount += quantity;
    }
  });

  document.getElementById("subtotal").textContent = formatPrice(subtotal);
  document.getElementById("selectedCount").textContent = selectedCount;

  const discount = 0;
  const total = subtotal - discount;
  document.getElementById("discount").textContent = "-" + formatPrice(discount);
  document.getElementById("total").textContent = formatPrice(total);
}

// Format giá tiền
function formatPrice(price) {
  return new Intl.NumberFormat("vi-VN").format(Math.round(price)) + "₫";
}

// Tiến hành thanh toán
function proceedCheckout() {
  const selectedItems = document.querySelectorAll(".item-checkbox:checked");

  if (selectedItems.length === 0) {
    showToast("Vui lòng chọn ít nhất một sản phẩm để thanh toán", "error");
    return;
  }

  const selectedIds = [];
  selectedItems.forEach((item) => {
    const row = item.closest(".cart-item");
    if (row && row.dataset.productId) {
      selectedIds.push(row.dataset.productId);
    }
  });

  window.location.href = `index.php?page=checkout&selected_ids=${selectedIds.join(
    ","
  )}`;
}

// Select All checkbox
document.addEventListener("DOMContentLoaded", () => {
  const selectAll = document.getElementById("selectAll");
  if (selectAll) {
    selectAll.addEventListener("change", function () {
      const checkboxes = document.querySelectorAll(".item-checkbox");
      checkboxes.forEach((checkbox) => (checkbox.checked = this.checked));
      updateCartDisplay();
    });
  }

  document.querySelectorAll(".item-checkbox").forEach((checkbox) => {
    checkbox.addEventListener("change", updateCartDisplay);
  });

  updateCartDisplay();
});
