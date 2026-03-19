document.addEventListener("DOMContentLoaded", function () {
  const showFormBtn = document.getElementById("show-add-service-form-btn");
  const addServiceFormContainer = document.getElementById(
    "add-service-form-container",
  );
  const cancelFormBtn = document.getElementById("cancel-add-service-form-btn");
  const addServiceForm = document.getElementById("add-service-form");

  if (
    showFormBtn &&
    addServiceFormContainer &&
    cancelFormBtn &&
    addServiceForm
  ) {
    // Bật form
    showFormBtn.addEventListener("click", function () {
      addServiceFormContainer.classList.add("visible");
      addServiceFormContainer.style.display = "block";
      showFormBtn.style.display = "none";

      addServiceForm.reset();
      document.getElementById("service-id").value = "";
      document.querySelector(".add-service-block h2").innerText =
        "Thêm Dịch vụ Mới";
      document.getElementById("submit-btn").innerHTML =
        '<i class="fas fa-save"></i> Lưu Dịch vụ';
    });

    // Tắt form
    cancelFormBtn.addEventListener("click", function () {
      addServiceFormContainer.classList.remove("visible");
      addServiceFormContainer.style.display = "none";
      showFormBtn.style.display = "inline-block";
    });
  }
});
