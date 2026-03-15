document.addEventListener("DOMContentLoaded", function () {
  // Lấy các phần tử nút Tab và Nội dung Tab
  const tabAppointmentsBtn = document.getElementById("tab-appointments-btn");
  const tabLeaveBtn = document.getElementById("tab-leave-btn");
  const sectionAppointments = document.getElementById("appointments-section");
  const sectionLeave = document.getElementById("leave-section");

  // Logic 1: Chuyển đổi qua lại giữa 2 Tab
  function switchTab(activeBtn, activeSection, inactiveBtn, inactiveSection) {
    // Đổi màu nút
    activeBtn.classList.add("active");
    inactiveBtn.classList.remove("active");

    // Hiện/Ẩn nội dung
    activeSection.classList.add("active");
    inactiveSection.classList.remove("active");
  }

  if (tabAppointmentsBtn && tabLeaveBtn) {
    // Bấm vào tab Xem lịch
    tabAppointmentsBtn.addEventListener("click", function () {
      switchTab(
        tabAppointmentsBtn,
        sectionAppointments,
        tabLeaveBtn,
        sectionLeave,
      );
    });

    // Bấm vào tab Xin nghỉ
    tabLeaveBtn.addEventListener("click", function () {
      switchTab(
        tabLeaveBtn,
        sectionLeave,
        tabAppointmentsBtn,
        sectionAppointments,
      );
    });
  }

  // Logic 2: Xử lý Form xin nghỉ (Frontend Only)
  const leaveForm = document.getElementById("leave-request-form");

  if (leaveForm) {
    leaveForm.addEventListener("submit", function (event) {
      event.preventDefault(); // Chặn tải lại trang

      // Lấy dữ liệu từ form để thông báo
      const date = document.getElementById("leave-date").value;
      const type = document.getElementById("leave-type").value;

      // Hiển thị thông báo thành công
      alert(
        `Đã gửi yêu cầu xin nghỉ!\nNgày: ${date}\nLý do: ${type}\nVui lòng chờ Quản lý duyệt.`,
      );

      // Xóa rỗng form sau khi gửi
      leaveForm.reset();

      // Tự động chuyển về tab xem lịch sau khi gửi đơn xong
      switchTab(
        tabAppointmentsBtn,
        sectionAppointments,
        tabLeaveBtn,
        sectionLeave,
      );
    });
  }
});
