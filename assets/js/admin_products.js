window.openCategoryModal = () =>
  document.getElementById("categoryModal").classList.remove("hidden");
window.openAddModal = () =>
  document.getElementById("addModal").classList.remove("hidden");
window.closeModal = (modalId) =>
  document.getElementById(modalId).classList.add("hidden");
document.addEventListener("DOMContentLoaded", () => {
  // Nút Sửa
  document.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.addEventListener("click", () => {
      const modal = document.getElementById("editModal");
      const form = document.getElementById("editForm");

      form.querySelector("#edit_id").value = btn.dataset.id;
      form.querySelector("#edit_name").value = btn.dataset.name;
      form.querySelector("#edit_description").value = btn.dataset.description;
      form.querySelector("#edit_price").value = btn.dataset.price;
      form.querySelector("#edit_stock").value = btn.dataset.stock;
      form.querySelector("#edit_category_id").value = btn.dataset.category_id;
      form.querySelector("#editImagePreview").src = btn.dataset.image;

      loadSubcategories(
        btn.dataset.category_id,
        "#edit_subcategory_id",
        btn.dataset.subcategory_id,
      );

      modal.classList.remove("hidden");
    });
  });

  // Nút Xóa
  document.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.getElementById("deleteId").value = btn.dataset.id;
      document.getElementById("deleteModal").classList.remove("hidden");
    });
  });

  // Hàm tải danh mục con
  const loadSubcategories = async (
    categoryId,
    targetSelectId,
    selectedId = null,
  ) => {
    const targetSelect = document.querySelector(targetSelectId);
    targetSelect.innerHTML = '<option value="">Đang tải...</option>';

    if (!categoryId) {
      targetSelect.innerHTML =
        '<option value="">Vui lòng chọn danh mục chính</option>';
      return;
    }

    try {
      const response = await fetch(
        `assets/api/get_subcategories.php?category_id=${categoryId}`,
      );
      const subcategories = await response.json();

      targetSelect.innerHTML = ""; // Xóa

      // Option
      if (targetSelectId === "#catModal_Subcategory") {
        targetSelect.innerHTML +=
          '<option value="new" style="font-weight: bold; color: blue;">Thêm Danh mục mới</option>';
      }

      if (subcategories.length === 0) {
        targetSelect.innerHTML +=
          '<option value="">Không có danh mục con</option>';
      }

      subcategories.forEach((sub) => {
        const selected = sub.id == selectedId ? "selected" : "";
        targetSelect.innerHTML += `<option value="${sub.id}" ${selected}>${sub.name}</option>`;
      });

      // Kích hoạt sự kiện change thủ công
      if (targetSelectId === "#catModal_Subcategory") {
        targetSelect.dispatchEvent(new Event("change"));
      }
    } catch (error) {
      console.error("Lỗi tải danh mục con:", error);
      targetSelect.innerHTML = '<option value="">-- Lỗi tải --</option>';
    }
  };

  // Modal Cập nhật Danh mục
  document
    .getElementById("catModal_Category")
    .addEventListener("change", (e) => {
      loadSubcategories(e.target.value, "#catModal_Subcategory");
    });

  //  Modal Thêm Sản phẩm
  document.getElementById("add_category_id").addEventListener("change", (e) => {
    loadSubcategories(e.target.value, "#add_subcategory_id");
  });

  // Modal Sửa Sản phẩm
  document
    .getElementById("edit_category_id")
    .addEventListener("change", (e) => {
      loadSubcategories(e.target.value, "#edit_subcategory_id");
    });

  document
    .getElementById("catModal_Subcategory")
    .addEventListener("change", (e) => {
      const nameInput = document.getElementById("catModal_Name");
      const selectedOption = e.target.options[e.target.selectedIndex];

      if (e.target.value === "new") {
        nameInput.value = "";
        nameInput.placeholder = "Nhập tên danh mục con MỚI";
      } else if (selectedOption) {
        nameInput.value = selectedOption.text;
        nameInput.placeholder = "Sửa tên danh mục con";
      }
    });

  document
    .getElementById("categoryForm")
    .addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      try {
        const response = await fetch("assets/api/manage_category.php", {
          method: "POST",
          body: formData,
        });
        const text = await response.text();

        if (text === "success") {
          showToast("Cập nhật danh mục thành công!", "success");
          closeModal("categoryModal");
          setTimeout(() => location.reload(), 1500);
        } else {
          showToast(`Lỗi: ${text}`, "error");
        }
      } catch (error) {
        showToast(`Lỗi: ${error.message}`, "error");
      }
    });

  // Thêm Sản phẩm
  document.getElementById("addForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    try {
      const response = await fetch("assets/api/add_product.php", {
        method: "POST",
        body: formData,
      });
      const text = await response.text();

      if (text === "success") {
        showToast("Thêm sản phẩm thành công!", "success");
        closeModal("addModal");
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast(`Lỗi: ${text}`, "error");
      }
    } catch (error) {
      showToast(`Lỗi: ${error.message}`, "error");
    }
  });

  // Sửa Sản phẩm
  document.getElementById("editForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    try {
      const response = await fetch("assets/api/update_product.php", {
        method: "POST",
        body: formData,
      });
      const text = await response.text();

      if (text === "success") {
        showToast("Cập nhật sản phẩm thành công!", "success");
        closeModal("editModal");
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast(`Lỗi: ${text}`, "error");
      }
    } catch (error) {
      showToast(`Lỗi: ${error.message}`, "error");
    }
  });

  // Xóa Sản phẩm
  document
    .getElementById("deleteForm")
    .addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);

      try {
        const response = await fetch("assets/api/delete_product.php", {
          method: "POST",
          body: formData,
        });
        const text = await response.text();

        if (text === "success") {
          showToast("Xóa sản phẩm thành công!", "success");
          closeModal("deleteModal");
          setTimeout(() => location.reload(), 1500);
        } else {
          showToast(`Lỗi: ${text}`, "error");
        }
      } catch (error) {
        showToast(`Lỗi: ${error.message}`, "error");
      }
    });
});
