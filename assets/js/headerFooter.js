document.addEventListener("DOMContentLoaded", () => {
  const menu = document.querySelector(".menu");
  const items = menu.querySelectorAll(".menu-item");
  const indicator = document.querySelector(".menu-indicator");
  let isHovering = false;

  function moveIndicator(element) {
    const rect = element.getBoundingClientRect();
    const menuRect = menu.getBoundingClientRect();
    indicator.style.width = rect.width + "px";
    indicator.style.left = rect.left - menuRect.left + "px";
    indicator.style.opacity = "1";
  }

  items.forEach((item) => {
    item.addEventListener("mouseenter", () => {
      isHovering = true;
      moveIndicator(item);
    });
  });

  menu.addEventListener("mouseleave", () => {
    isHovering = false;
    indicator.style.opacity = "0";
  });
});
