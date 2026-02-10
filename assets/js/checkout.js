document.addEventListener("DOMContentLoaded", function () {
  const checkoutForm = document.getElementById("checkoutForm");
  if (checkoutForm) {
    checkoutForm.addEventListener("submit", function (e) {
      const recipientName = document
        .getElementById("recipient_name")
        .value.trim();
      const phone = document.getElementById("phone").value.trim();
      const address = document.getElementById("shipping_address").value.trim();

      if (!recipientName) {
        e.preventDefault();
        showToast("Vui lòng nhập tên người nhận!", "error");
        document.getElementById("recipient_name").focus();
        return false;
      }

      if (!phone) {
        e.preventDefault();
        showToast("Vui lòng nhập số điện thoại!", "error");
        document.getElementById("phone").focus();
        return false;
      }

      if (!/^[0-9]{10,11}$/.test(phone)) {
        e.preventDefault();
        showToast(
          "Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số.",
          "error"
        );
        document.getElementById("phone").focus();
        return false;
      }

      if (!address) {
        e.preventDefault();
        showToast("Vui lòng nhập địa chỉ giao hàng!", "error");
        document.getElementById("shipping_address").focus();
        return false;
      }

      const submitBtn = this.querySelector(".btn-submit");
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML =
          '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';
      }
    });
  }
});
