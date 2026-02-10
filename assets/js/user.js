document.addEventListener("DOMContentLoaded", () => {
  const editModal = document.getElementById("editModal");
  const deleteModal = document.getElementById("deleteModal");
  const toggleModal = document.getElementById("toggleModal");
  const addModal = document.getElementById("addModal");
  const editForm = document.getElementById("editForm");
  const deleteForm = document.getElementById("deleteForm");
  const toggleForm = document.getElementById("toggleForm");
  const addForm = document.getElementById("addForm");

  // mở modal sửa
  document.querySelectorAll(".btn-edit").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.getElementById("editId").value = btn.dataset.id;
      document.getElementById("editUsername").value = btn.dataset.username;
      document.getElementById("editEmail").value = btn.dataset.email;
      document.getElementById("editPhone").value = btn.dataset.phone;
      document.getElementById("editRole").value = btn.dataset.role;
      editModal.classList.remove("hidden");
    });
  });

  // Submit form sửa
  editForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    let formData = new FormData(editForm);
    try {
      let res = await fetch("/PTGear/assets/api/update_user.php", {
        method: "POST",
        body: formData,
      });
      let text = await res.text();
      if (text === "success") {
        closeModal("editModal");
        showToast("Cập nhật thành công!", "success");
        setTimeout(() => location.reload(), 2000);
      } else {
        if (text.includes("Email đã được sử dụng")) {
          showToast("Email đã được sử dụng bởi tài khoản khác!", "error");
        } else {
          showToast("Lỗi khi cập nhật: " + text, "error");
        }
      }
    } catch (err) {
      showToast("Lỗi kết nối: " + err.message, "error");
    }
  });

  // mở modal xóa
  document.querySelectorAll(".btn-delete").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.getElementById("deleteId").value = btn.dataset.id;
      deleteModal.classList.remove("hidden");
    });
  });

  // submit form xóa
  deleteForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    let formData = new FormData(deleteForm);
    try {
      let res = await fetch("/PTGear/assets/api/delete_user.php", {
        method: "POST",
        body: formData,
      });
      let text = await res.text();
      if (text === "success") {
        closeModal("deleteModal");
        showToast("Xóa thành công!", "success");
        setTimeout(() => location.reload(), 1000);
      } else {
        showToast("Xóa thất bại: " + text, "error");
      }
    } catch (err) {
      showToast("Lỗi kết nối: " + err.message, "error");
    }
  });

  // mở modal khóa/mở khóa
  document.querySelectorAll(".btn-toggle").forEach((btn) => {
    btn.addEventListener("click", () => {
      document.getElementById("toggleId").value = btn.dataset.id;
      document.getElementById("toggleStatus").value = btn.dataset.status;
      toggleModal.classList.remove("hidden");
    });
  });

  // submit form khóa/mở khóa
  toggleForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    let formData = new FormData(toggleForm);
    try {
      let res = await fetch("/PTGear/assets/api/toggle_status.php", {
        method: "POST",
        body: formData,
      });
      let text = await res.text();
      if (text === "success") {
        closeModal("toggleModal");
        showToast("Cập nhật trạng thái thành công!", "success");
        setTimeout(() => location.reload(), 1000);
      } else {
        showToast("Thao tác thất bại: " + text, "error");
      }
    } catch (err) {
      showToast("Lỗi kết nối: " + err.message, "error");
    }
  });

  // Mở modal thêm tài khoản
  window.openAddModal = function () {
    if (addModal) {
      document.getElementById("addUsername").value = "";
      document.getElementById("addEmail").value = "";
      document.getElementById("addPassword").value = "";
      document.getElementById("addRole").value = "user";
      addModal.classList.remove("hidden");
      console.log("Modal add opened");
    } else {
      console.error("Modal #addModal not found");
    }
  };

  // submit form thêm tài khoản
  addForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    let formData = new FormData(addForm);
    try {
      let res = await fetch("/PTGear/assets/api/add_user.php", {
        method: "POST",
        body: formData,
      });
      let text = await res.text();
      if (text === "success") {
        closeModal("addModal");
        showToast("Thêm tài khoản thành công!", "success");
        setTimeout(() => location.reload(), 1000);
      } else {
        showToast(text, "error");
      }
    } catch (err) {
      showToast("Lỗi kết nối: " + err.message, "error");
    }
  });

  const searchInput = document.getElementById("searchUser");
  const tableBody = document.getElementById("userTableBody");

  if (searchInput && tableBody) {
    const noResultRow = document.createElement("tr");
    noResultRow.innerHTML = `<td colspan="6" style="text-align:center; color:red; font-weight:bold;">
                                Không tìm thấy tài khoản
                             </td>`;
    noResultRow.style.display = "none";
    tableBody.appendChild(noResultRow);

    searchInput.addEventListener("input", function () {
      const filter = searchInput.value.toLowerCase().trim();
      let rows = tableBody.querySelectorAll("tr");
      let found = false;

      rows.forEach((row) => {
        let username = row.cells[1]?.textContent.toLowerCase();
        let email = row.cells[2]?.textContent.toLowerCase();

        if (username && email) {
          if (username.startsWith(filter) || email.startsWith(filter)) {
            row.style.display = "";
            found = true;
          } else {
            row.style.display = "none";
          }
        }
      });

      if (!found && filter !== "") {
        noResultRow.style.display = "";
      } else {
        noResultRow.style.display = "none";
      }

      if (filter === "") {
        rows.forEach((row) => (row.style.display = ""));
        noResultRow.style.display = "none";
      }
    });
  }
});
