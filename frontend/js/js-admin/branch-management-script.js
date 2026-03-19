document.addEventListener("DOMContentLoaded", function () {
  const showFormBtn = document.getElementById("show-add-branch-form-btn");
  const addBranchFormContainer = document.getElementById(
    "add-branch-form-container",
  );
  const cancelFormBtn = document.getElementById("cancel-add-branch-form-btn");
  const addBranchForm = document.getElementById("add-branch-form");

  if (showFormBtn && addBranchFormContainer && cancelFormBtn && addBranchForm) {
    // Bật form Thêm mới
    showFormBtn.addEventListener("click", function () {
      addBranchFormContainer.classList.add("visible");
      addBranchFormContainer.style.display = "block";
      showFormBtn.style.display = "none";

      // Làm sạch form
      addBranchForm.reset();
      document.getElementById("branch-id").value = "";
      document.querySelector(".add-branch-block h2").innerText =
        "Thêm Chi nhánh Mới";
      document.getElementById("submit-btn").innerHTML =
        '<i class="fas fa-save"></i> Lưu Chi nhánh';
    });

    // Tắt form
    cancelFormBtn.addEventListener("click", function () {
      addBranchFormContainer.classList.remove("visible");
      addBranchFormContainer.style.display = "none";
      showFormBtn.style.display = "inline-block";
    });
  }
});
