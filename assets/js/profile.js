const profileForm = document.getElementById("profileForm");
if (profileForm) {
  profileForm.addEventListener("submit", (e) => {
    const actionInput = document.createElement("input");
    actionInput.type = "hidden";
    actionInput.name = "action";
    actionInput.value = "update_profile";
    profileForm.appendChild(actionInput);
  });
}

// Form đổi mật khẩu
const passwordForm = document.getElementById("passwordForm");
if (passwordForm) {
  passwordForm.addEventListener("submit", (e) => {
    const oldPassword = document.getElementById("oldPassword").value;
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (!oldPassword || !newPassword || !confirmPassword) {
      e.preventDefault();
      showToast("Vui lòng điền đầy đủ thông tin!", "error");
      return false;
    }

    if (newPassword !== confirmPassword) {
      e.preventDefault();
      showToast("Mật khẩu mới và xác nhận mật khẩu không khớp!", "error");
      return false;
    }

    if (newPassword.length < 6) {
      e.preventDefault();
      showToast("Mật khẩu mới phải có ít nhất 6 ký tự!", "error");
      return false;
    }

    const actionInput = document.createElement("input");
    actionInput.type = "hidden";
    actionInput.name = "action";
    actionInput.value = "change_password";
    passwordForm.appendChild(actionInput);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const toast = document.getElementById("toast");
  if (toast && !toast.classList.contains("hidden")) {
    setTimeout(() => {
      toast.classList.add("hidden");
    }, 3000);
  }
});
