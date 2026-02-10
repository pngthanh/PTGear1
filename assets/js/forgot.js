document.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll(".input");
  // Thêm class focus khi focus input
  function addcl() {
    let parent = this.parentNode.parentNode;
    parent.classList.add("focus");
    clearError();
  }
  // Gắn sự kiện
  inputs.forEach((input) => {
    input.addEventListener("focus", addcl);
    input.addEventListener("blur", remcl);
  });
});
