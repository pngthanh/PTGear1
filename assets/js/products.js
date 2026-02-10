let slot1 = null,
  slot2 = null;

function addToCompare(el) {
  let card = el.closest(".product-card");
  let product = {
    id: card.dataset.id,
    name: card.dataset.name,
    image: card.dataset.image,
    price: card.dataset.price,
    newprice: card.dataset.newprice,
    description: card.dataset.description,
  };
  if (!slot1) {
    slot1 = product;
    renderSlot("slot1", product);
  } else if (!slot2) {
    slot2 = product;
    renderSlot("slot2", product);
  } else {
    showToast("Chỉ so sánh tối đa 2 sản phẩm", "error");
  }
}

function renderSlot(slotId, product) {
  let div = document.getElementById(slotId);
  div.textContent = product.name;
  div.title = product.name;
}

function clearCompare() {
  slot1 = null;
  slot2 = null;
  document.getElementById("slot1").textContent = "Sản phẩm 1";
  document.getElementById("slot2").textContent = "Sản phẩm 2";
}

function openCompare() {
  if (!slot1 || !slot2) {
    showToast("Hãy chọn 2 sản phẩm!", "error");
    return;
  }
  let table = document.getElementById("compareTable");
  table.innerHTML = `
        <tr>
            <td>Tên sản phẩm</td>
            <td><strong>${slot1.name}</strong></td>
            <td><strong>${slot2.name}</strong></td>
        </tr>
        <tr>
            <td>Hình ảnh</td>
            <td><img src="${slot1.image}" height="120" alt="${slot1.name}"></td>
            <td><img src="${slot2.image}" height="120" alt="${slot2.name}"></td>
        </tr>
        <tr>
            <td>Giá gốc</td>
            <td><del>${formatPrice(slot1.price)}</del></td>
            <td><del>${formatPrice(slot2.price)}</del></td>
        </tr>
        <tr>
            <td>Giá khuyến mãi</td>
            <td class="highlight">${formatPrice(slot1.newprice)}</td>
            <td class="highlight">${formatPrice(slot2.newprice)}</td>
        </tr>
        <tr>
            <td>Mô tả sản phẩm</td>
            <td>${slot1.description || "Không có mô tả"}</td>
            <td>${slot2.description || "Không có mô tá"}</td>
        </tr>
    `;
  document.getElementById("compareModal").style.display = "block";
}

// Hàm đóng modal so sánh
function closeCompareModal() {
  const modal = document.getElementById("compareModal");
  if (modal) {
    modal.style.display = "none";
  }
}

// Xử lý đóng khi bấm ra ngoài vùng modal
window.addEventListener("click", function (event) {
  const modal = document.getElementById("compareModal");
  if (event.target === modal) {
    closeCompareModal();
  }
});

// Thêm sản phẩm vào giỏ hàng
function addToCart(productId, quantity = 1, variant = "Mặc định") {
  fetch("index.php?page=cart&action=add", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `product_id=${productId}&quantity=${quantity}&variant=${encodeURIComponent(
      variant,
    )}`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast(data.message || "Đã thêm vào giỏ hàng", "success");
      } else {
        showToast(data.message || "Có lỗi xảy ra", "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showToast("Có lỗi xảy ra khi thêm vào giỏ hàng", "error");
    });
}

document.addEventListener("DOMContentLoaded", function () {
  // Gắn sự kiện cho nút "Thêm giỏ"
  document.querySelectorAll(".btn-add").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const card = this.closest(".product-card, .flash-item, .sanpham");
      if (card) {
        const productId = card.dataset.id;
        if (productId) {
          addToCart(productId);
        }
      }
    });
  });

  // Gắn sự kiện cho nút "Mua ngay"
  document.querySelectorAll(".btn-buy").forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const card = this.closest(".product-card, .flash-item, .sanpham");
      let productId = null;
      if (card) {
        productId = card.dataset.id;
      } else {
        productId = this.dataset.id;
      }
      if (productId) {
        window.location.href = `index.php?page=checkout&buy_now_id=${productId}&quantity=1`;
      }
    });
  });
});
