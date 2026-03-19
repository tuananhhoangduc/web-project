document.addEventListener("DOMContentLoaded", function () {
  const showFormBtn = document.getElementById("show-add-stylist-form-btn");
  const formContainer = document.getElementById("add-stylist-form-container");
  const form = document.getElementById("add-stylist-form");
  const cancelBtn = document.getElementById("cancel-add-stylist-form-btn");

  if (showFormBtn && formContainer && form && cancelBtn) {
    showFormBtn.addEventListener("click", function () {
      formContainer.style.display = "block";
      this.style.display = "none";

      form.reset();
      document.getElementById("stylist-id").value = "";
      document.querySelector(".add-stylist-block h2").innerText =
        "Thêm Thợ Cắt Mới";

      const submitBtn = document.getElementById("submit-btn");
      if (submitBtn)
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Lưu Thợ Cắt';
    });

    cancelBtn.addEventListener("click", function () {
      formContainer.style.display = "none";
      showFormBtn.style.display = "inline-block";
    });
  }
});
