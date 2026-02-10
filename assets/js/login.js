const inputs = document.querySelectorAll(".input");
const form = document.querySelector("form");
const errorMessage = document.getElementById("errorMessage");

// Hàm thêm class focus khi focus vào input và xóa thông báo lỗi
function addcl() {
  let parent = this.parentNode.parentNode;
  parent.classList.add("focus");
  clearError();
}

// Hàm xóa class focus khi blur và kiểm tra trống
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
    setTimeout(() => {
      errorMessage.style.display = "none";
    }, 5000);
  }
}

// Hàm xóa thông báo lỗi
function clearError() {
  if (errorMessage) {
    errorMessage.textContent = "";
    errorMessage.style.display = "none";
  }
}
// Hàm kiểm tra lỗi trống
function validateInputs() {
  let isEmpty = false;
  inputs.forEach((input) => {
    if (input.value.trim() === "") {
      isEmpty = true;
      input.parentNode.parentNode.classList.add("error");
    }
  });
  return !isEmpty;
}

// Hàm xử lý đăng nhập
function handleLogin(event) {
  event.preventDefault();
  clearError();

  if (!validateInputs()) {
    showError("Vui lòng điền đầy đủ thông tin!");
    return;
  }

  form.submit();
}

// Thêm sự kiện cho input
inputs.forEach((input) => {
  input.addEventListener("focus", addcl);
  input.addEventListener("blur", remcl);
});

// Thêm sự kiện submit cho form
form.addEventListener("submit", handleLogin);

// Kiểm tra DOM sau khi tải xong
document.addEventListener("DOMContentLoaded", () => {
  if (!errorMessage) {
    console.error(
      "Phần tử thông báo lỗi không tìm thấy! Vui lòng kiểm tra HTML."
    );
  }
  inputs.forEach((input) => {
    input.value = "";
    let parent = input.parentNode.parentNode;
    parent.classList.remove("focus");
  });
  if (errorMessage) {
    errorMessage.textContent = "";
    errorMessage.style.display = "none";
  }
});
