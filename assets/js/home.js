// Đếm ngược thời gian cho Flash Sale
function startTimer() {
  const countDownDate = new Date().getTime() + 24 * 60 * 60 * 1000; // 24 giờ
  const timer = setInterval(function () {
    const now = new Date().getTime();
    const distance = countDownDate - now;

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor(
      (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
    document.getElementById("hours").innerHTML =
      hours < 10 ? "0" + hours : hours;
    document.getElementById("minutes").innerHTML =
      minutes < 10 ? "0" + minutes : minutes;
    document.getElementById("seconds").innerHTML =
      seconds < 10 ? "0" + seconds : seconds;

    if (distance < 0) {
      clearInterval(timer);
      document.getElementById("days").innerHTML = "00";
      document.getElementById("hours").innerHTML = "00";
      document.getElementById("minutes").innerHTML = "00";
      document.getElementById("seconds").innerHTML = "00";
    }
  }, 1000);
}
startTimer();

// Toggle sản phẩm trong Ưu đãi dành cho bạn
function showProduct(category) {
  const products = document.getElementsByClassName("product-item");
  const buttons = document.getElementsByClassName("tab-button");

  for (let i = 0; i < products.length; i++) {
    products[i].classList.remove("active");
    if (products[i].id === category) {
      products[i].classList.add("active");
    }
  }

  for (let i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove("active");
    if (buttons[i].getAttribute("onclick").includes(category)) {
      buttons[i].classList.add("active");
    }
  }
}

//Nhấn chuột kéo flash sale
const flashGrid = document.querySelector(".flash-grid");
let isDragging = false;
let startX;
let scrollLeft;
let lastX;

flashGrid.addEventListener("mousedown", (e) => {
  isDragging = true;
  startX = e.pageX - flashGrid.offsetLeft;
  scrollLeft = flashGrid.scrollLeft;
  lastX = e.pageX;
  flashGrid.style.scrollBehavior = "auto";
  flashGrid.style.userSelect = "none"; // Ngăn chặn chọn văn bản
});

flashGrid.addEventListener("mousemove", (e) => {
  if (!isDragging) return;
  e.preventDefault();
  const x = e.pageX - flashGrid.offsetLeft;
  const walk = x - startX;
  flashGrid.scrollLeft = scrollLeft - walk;
  lastX = x;
});

flashGrid.addEventListener("mouseup", () => {
  isDragging = false;
  flashGrid.style.scrollBehavior = "smooth";
  flashGrid.style.userSelect = "auto";
});

flashGrid.addEventListener("mouseleave", () => {
  if (isDragging) {
    isDragging = false;
    flashGrid.style.scrollBehavior = "smooth";
    flashGrid.style.userSelect = "auto";
  }
});

// Ngăn kéo chuột làm chọn văn bản trên toàn bộ trang
document.addEventListener("dragstart", (e) => {
  if (isDragging) e.preventDefault();
});

const flashgrid = document.querySelector(".flash-grid");
const btnPrev = document.querySelector(".flash-btn.prev");
const btnNext = document.querySelector(".flash-btn.next");

function updateButtons() {
  const scrollLeft = flashgrid.scrollLeft;
  const maxScroll = flashgrid.scrollWidth - flashgrid.clientWidth;

  if (scrollLeft <= 0) {
    btnPrev.style.display = "none";
  } else {
    btnPrev.style.display = "flex";
  }

  if (scrollLeft >= maxScroll - 1) {
    btnNext.style.display = "none";
  } else {
    btnNext.style.display = "flex";
  }
}

// Sk cuộn
flashgrid.addEventListener("scroll", updateButtons);

// Nút điều hướng
btnNext.addEventListener("click", () => {
  flashgrid.scrollBy({ left: 300, behavior: "smooth" });
});

btnPrev.addEventListener("click", () => {
  flashgrid.scrollBy({ left: -300, behavior: "smooth" });
});

// Kiểm tra lần đầu khi load
updateButtons();

// Chuyển banner
let currentSlideIndex = 0;
const slides = document.querySelectorAll(".slideshow .slide");
const totalSlides = slides.length;

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.toggle("active", i === index);
  });
}

setInterval(() => {
  currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
  showSlide(currentSlideIndex);
}, 3000);
