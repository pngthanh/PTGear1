function showToast(message, type = "success", duration = 3000) {
  const toast = document.getElementById("toast");
  if (!toast) {
    console.error("Phần tử #toast không tìm thấy trong HTML!");
    const tempToast = document.createElement("div");
    tempToast.id = "toast";
    document.body.appendChild(tempToast);
    setTimeout(() => showToast(message, type, duration), 10);
    return;
  }
  if (toast.timeoutId) {
    clearTimeout(toast.timeoutId);
  }
  toast.textContent = message;
  toast.className = `toast ${type} show`;
  toast.timeoutId = setTimeout(() => {
    toast.className = toast.className.replace("show", "hidden");
  }, duration);
}

function formatPrice(price) {
  return parseInt(price).toLocaleString("vi-VN") + "đ";
}

function closeModal(modalId) {
  if (!modalId) {
    console.error("Lỗi: Bạn chưa truyền ID vào hàm closeModal!");
    return;
  }

  const modalElement = document.getElementById(modalId);
  if (modalElement) {
    modalElement.style.display = "none"; // Ép ẩn = style
    modalElement.classList.add("hidden");
  } else {
    console.error(`Không tìm thấy phần tử modal có ID: ${modalId}`);
  }
}
