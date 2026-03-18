document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded. Initializing script.js");

  // --- Logic cho Menu Responsive ---
  const hamburgerIcon = document.querySelector(".hamburger-icon");
  const mobileMenuOverlay = document.querySelector(".mobile-menu-overlay");
  const closeIcon = document.querySelector(".mobile-menu-content .close-icon");
  const body = document.body;

  if (hamburgerIcon && mobileMenuOverlay && closeIcon && body) {
    function openMobileMenu() {
      mobileMenuOverlay.classList.add("visible", "active");
      body.style.overflow = "hidden";
      console.log("Mobile menu opened.");
    }

    function closeMobileMenu() {
      mobileMenuOverlay.classList.remove("visible", "active");
      body.style.overflow = "";
      console.log("Mobile menu closed.");
    }

    hamburgerIcon.addEventListener("click", openMobileMenu);
    closeIcon.addEventListener("click", closeMobileMenu);
    mobileMenuOverlay.addEventListener("click", function (event) {
      if (event.target === mobileMenuOverlay) {
        closeMobileMenu();
        console.log("Mobile menu closed by clicking overlay.");
      }
    });

    const mobileNavLinks = document.querySelectorAll(".mobile-nav a");
    mobileNavLinks.forEach((link) => {
      link.addEventListener("click", closeMobileMenu);
    });
    console.log("Menu responsive logic initialized.");
  } else {
    console.log("Menu responsive elements not found.");
  }

  // --- Logic cho Header Đăng nhập/Đăng xuất (FRONTEND ONLY bằng localStorage) ---
  const userAccount = document.querySelector(".user-account");
  const accountDropdown = document.querySelector(".account-dropdown");
  const userIconLink = document.querySelector(".user-icon-link");
  const userIconLinkLoggedIn = document.querySelector(
    ".user-icon-link.logged-in-only",
  );
  const userIconLinkLoggedOut = document.querySelector(
    ".user-icon-link.logged-out-only",
  );
  const desktopLogoutLink = document.getElementById("desktop-logout-link");
  const mobileLogoutLink = document.getElementById("mobile-logout-link");

  function isUserLoggedIn() {
    return localStorage.getItem("isLoggedIn") === "true";
  }

  function getUserRole() {
    return localStorage.getItem("userRole") || "customer";
  }

  function updateHeaderLoginStatus() {
    const isLoggedIn = isUserLoggedIn();
    const loggedInUser = JSON.parse(
      localStorage.getItem("loggedInUser") || "{}",
    );
    const userRole = getUserRole();

    if (body) {
      body.classList.toggle("is-logged-in", isLoggedIn);
      body.classList.toggle("is-logged-out", !isLoggedIn);
      body.classList.remove("user-role-admin", "user-role-customer");
      if (isLoggedIn) {
        body.classList.add(`user-role-${userRole}`);
      }
    }

    if (userIconLink) {
      if (isLoggedIn && loggedInUser.username) {
        userIconLink.href = "#";
        userIconLink.querySelector("span").textContent =
          userRole === "admin"
            ? "Quản lý Admin"
            : loggedInUser.username || "Tài khoản của tôi";
        userIconLink.classList.add("logged-in");
        userIconLink.classList.remove("logged-out-only");
      } else {
        userIconLink.href = "login.html";
        userIconLink.querySelector("span").textContent = "Đăng nhập / Đăng ký";
        userIconLink.classList.remove("logged-in");
        userIconLink.classList.add("logged-out-only");
      }
    }

    if (userIconLinkLoggedIn && isLoggedIn) {
      userIconLinkLoggedIn.querySelector("span").textContent =
        userRole === "admin" ? "Quản lý Admin" : "Tài khoản của tôi";
      if (!userIconLinkLoggedIn.querySelector(".fa-chevron-down")) {
        const arrowIcon = document.createElement("i");
        arrowIcon.classList.add("fas", "fa-chevron-down");
        userIconLinkLoggedIn.appendChild(arrowIcon);
      }
    }

    if (userIconLinkLoggedOut && !isLoggedIn) {
      userIconLinkLoggedOut.querySelector("span").textContent = "Đăng nhập";
      const arrowIcon = userIconLinkLoggedOut.querySelector(".fa-chevron-down");
      if (arrowIcon) arrowIcon.remove();
    }

    if (accountDropdown) {
      accountDropdown.style.display = isLoggedIn ? "block" : "none";
    }

    if (mobileLogoutLink) {
      mobileLogoutLink.style.display = isLoggedIn ? "block" : "none";
      const mobileLoginLink = document.querySelector(
        '.mobile-nav a[href="login.html"]',
      );
      if (mobileLoginLink) {
        mobileLoginLink.style.display = isLoggedIn ? "none" : "block";
      }
    }
  }

  function handleLogout(event) {
    event.preventDefault();
    console.log("Logging out...");
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("loggedInUser");
    localStorage.removeItem("userRole");
    updateHeaderLoginStatus();
    window.location.href = "index.php";
  }

  // --- Bật/tắt menu Dropdown cho PHP ---
  const userIconLinkBtn = document.querySelector(
    ".user-account .user-icon-link",
  );
  if (userIconLinkBtn && userAccount && accountDropdown) {
    userIconLinkBtn.addEventListener("click", function (event) {
      event.preventDefault(); // Chỉ chặn nhảy trang khi bấm vào nút "Xin chào..."
      userAccount.classList.toggle("active");
    });

    // Bấm ra ngoài thì tự động đóng menu
    document.addEventListener("click", function (event) {
      if (
        !event.target.closest(".user-account") &&
        userAccount.classList.contains("active")
      ) {
        userAccount.classList.remove("active");
      }
    });
  }
  // if (desktopLogoutLink)
  //   desktopLogoutLink.addEventListener("click", handleLogout);
  // if (mobileLogoutLink)
  //   mobileLogoutLink.addEventListener("click", handleLogout);

  // updateHeaderLoginStatus();

  // --- Logic cho Background Image Slider ---
  const slides = document.querySelectorAll(".hero-background .slide");
  const indicators = document.querySelectorAll(".slider-indicators span");
  let currentSlideIndex = 0;
  const slideIntervalTime = 10000;

  if (slides.length > 0 && indicators.length === slides.length) {
    function showSlide(index) {
      slides.forEach((slide) => slide.classList.remove("active"));
      indicators.forEach((indicator) => indicator.classList.remove("active"));
      slides[index].classList.add("active");
      indicators[index].classList.add("active");
    }

    function nextSlide() {
      currentSlideIndex = (currentSlideIndex + 1) % slides.length;
      showSlide(currentSlideIndex);
    }

    let sliderInterval = setInterval(nextSlide, slideIntervalTime);
    const heroSection = document.querySelector(".hero-section");
    if (heroSection) {
      heroSection.addEventListener("mouseover", () =>
        clearInterval(sliderInterval),
      );
      heroSection.addEventListener("mouseout", () => {
        sliderInterval = setInterval(nextSlide, slideIntervalTime);
      });
    }

    indicators.forEach((indicator, index) => {
      indicator.addEventListener("click", () => {
        currentSlideIndex = index;
        showSlide(currentSlideIndex);
        clearInterval(sliderInterval);
        sliderInterval = setInterval(nextSlide, slideIntervalTime);
      });
    });

    showSlide(currentSlideIndex);
  }

  // --- Logic cho Trang Đặt lịch hẹn (FRONTEND ONLY) ---
  const appointmentForm = document.getElementById("appointment-form");
  const serviceSelect = document.getElementById("service");
  const branchSelect = document.getElementById("branch");
  const barberSelect = document.getElementById("barber");
  const estimatedTotalDisplay = document.getElementById("estimated-total");

  const confirmationModalOverlay = document.querySelector(
    ".confirmation-modal-overlay",
  );
  const confirmAppointmentBtn = document.getElementById(
    "confirm-appointment-btn",
  );
  const cancelAppointmentBtn = document.getElementById(
    "cancel-appointment-btn",
  );

  if (appointmentForm && serviceSelect && estimatedTotalDisplay) {
    // 1. Tự động tính tổng tiền khi chọn dịch vụ
    function updateTotalAmount() {
      const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
      if (selectedOption && selectedOption.value) {
        const price = selectedOption.getAttribute("data-price") || "0";
        const formattedPrice = parseInt(price).toLocaleString("vi-VN");
        estimatedTotalDisplay.textContent = `${formattedPrice} VNĐ`;

        const summaryTotalAmount = document.getElementById(
          "summary-total-amount",
        );
        if (summaryTotalAmount)
          summaryTotalAmount.textContent = `${formattedPrice} VNĐ`;
      } else {
        estimatedTotalDisplay.textContent = "0 VNĐ";
        const summaryTotalAmount = document.getElementById(
          "summary-total-amount",
        );
        if (summaryTotalAmount) summaryTotalAmount.textContent = "0 VNĐ";
      }
    }

    serviceSelect.addEventListener("change", updateTotalAmount);

    // 2. Mở Popup xác nhận khi ấn nút Submit
    appointmentForm.addEventListener("submit", function (event) {
      event.preventDefault(); // Chặn hành vi gửi form bị lỗi
      if (appointmentForm.checkValidity()) {
        const formData = new FormData(appointmentForm);

        // Đổ dữ liệu vào Modal
        document.getElementById("summary-full-name").textContent =
          document.getElementById("customer-name").value;

        document.getElementById("summary-salon").textContent = branchSelect
          ? branchSelect.options[branchSelect.selectedIndex].text
          : "Chưa chọn";
        document.getElementById("summary-service").textContent =
          serviceSelect.options[serviceSelect.selectedIndex].text;
        document.getElementById("summary-barber").textContent = barberSelect
          ? barberSelect.options[barberSelect.selectedIndex].text ||
            "Không chọn"
          : "Không chọn";
        document.getElementById("summary-date-time").textContent =
          `${formData.get("appointment_date")}, ${formData.get("appointment_time")}`;
        document.getElementById("summary-notes").textContent =
          formData.get("notes") || "Không có ghi chú";

        updateTotalAmount();
        confirmationModalOverlay.classList.add("visible");
      }
    });

    // 3. Xử lý khi ấn "Xác nhận" trong Popup
    confirmAppointmentBtn.addEventListener("click", function () {
      appointmentForm.submit();
    });

    // 4. Xử lý khi ấn "Hủy bỏ" trong Popup
    cancelAppointmentBtn.addEventListener("click", function () {
      confirmationModalOverlay.classList.remove("visible");
    });

    // Khởi tạo chạy tính tiền lần đầu khi load trang
    updateTotalAmount();
    console.log("Appointment page logic initialized for frontend.");
  } else {
    console.log("Appointment page elements not found.");
  }
});
