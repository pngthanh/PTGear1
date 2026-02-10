const inputs = document.querySelectorAll(".input");
const form = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");

// Thêm class focus khi focus input
function addcl() {
    let parent = this.parentNode.parentNode;
    parent.classList.add("focus");
    clearError();
}

// Xóa class focus khi blur
function remcl() {
    let parent = this.parentNode.parentNode;
    if (this.value === "") {
        parent.classList.remove("focus");
    }
}

// Hiển thị lỗi
function showError(message) {
    if (errorMessage) {
        errorMessage.textContent = message;
        errorMessage.style.display = "block";
    }
    // Ẩn success message nếu có
    if (successMessage) {
        successMessage.style.display = "none";
    }
}

// Xóa lỗi
function clearError() {
    if (errorMessage) {
        errorMessage.textContent = "";
        errorMessage.style.display = "none";
    }
}

// Kiểm tra input trống
function validateInputs() {
    let isEmpty = false;
    inputs.forEach(input => {
        if (input.value.trim() === "") {
            isEmpty = true;
        }
    });
    return !isEmpty;
}

// Xử lý submit
function handleRegister(event) {
    clearError();
    if (!validateInputs()) {
        showError("Vui lòng nhập đầy đủ thông tin!");
        event.preventDefault();
        return;
    }
}

// Gắn sự kiện
inputs.forEach(input => {
    input.addEventListener("focus", addcl);
    input.addEventListener("blur", remcl);
});

form.addEventListener("submit", handleRegister);

document.addEventListener("DOMContentLoaded", () => {
    // Nếu từ PHP gửi clearAll = true => xóa hết input
    if (typeof clearAll !== 'undefined' && clearAll) {
        document.querySelectorAll(".input").forEach(input => {
            input.value = "";
            input.parentNode.parentNode.classList.remove("focus");
        });
    }

    // Xóa dữ liệu khi load trang từ nơi khác (kh phải submit lỗi/thành công)
    if (!document.referrer.includes("register.php") && !(typeof clearAll !== 'undefined' && clearAll)) {
        document.querySelectorAll(".input").forEach(input => {
            input.value = "";
            input.parentNode.parentNode.classList.remove("focus");
        });
    }
});