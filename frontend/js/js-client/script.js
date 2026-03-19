document.addEventListener("DOMContentLoaded", function () {
  // 1. MENU MOBILE (GIỮ NGUYÊN)
  const hb = document.querySelector(".hamburger-icon"),
    ov = document.querySelector(".mobile-menu-overlay"),
    cl = document.querySelector(".close-icon");
  if (hb && ov && cl) {
    hb.onclick = () => ov.classList.add("active");
    cl.onclick = () => ov.classList.remove("active");
  }

  // 2. SLIDER (GIỮ NGUYÊN)
  const slides = document.querySelectorAll(".hero-background .slide");
  if (slides.length > 0) {
    let cur = 0;
    setInterval(() => {
      slides[cur].classList.remove("active");
      cur = (cur + 1) % slides.length;
      slides[cur].classList.add("active");
    }, 5000);
  }

  // 3. BỘ ĐIỀU KHIỂN CLICK MENU (SỬA LỖI KHÔNG HIỆN)
  document.addEventListener("click", function (e) {
    const link = e.target.closest(".user-icon-link");
    const acc = e.target.closest(".user-account");
    const allDrop = document.querySelectorAll(".account-dropdown");

    if (link && acc) {
      e.preventDefault();
      e.stopPropagation();
      const drop = acc.querySelector(".account-dropdown");
      if (drop) {
        // Kiểm tra trạng thái hiện tại (JS ưu tiên lấy style inline)
        const isHidden =
          drop.style.display === "none" || drop.style.display === "";

        // Đóng các menu khác trước
        allDrop.forEach((d) => (d.style.display = "none"));

        if (isHidden) {
          drop.style.display = "block";
          // Ép cứng CSS để menu rớt xuống đúng chỗ
          Object.assign(drop.style, {
            position: "absolute",
            top: "100%",
            right: "0",
            backgroundColor: "#fff",
            minWidth: "180px",
            boxShadow: "0 8px 16px rgba(0,0,0,0.1)",
            borderRadius: "5px",
            zIndex: "9999",
            padding: "10px 0",
            marginTop: "10px",
          });
        } else {
          drop.style.display = "none";
        }
      }
    } else if (!e.target.closest(".account-dropdown")) {
      // Bấm ra ngoài thì ẩn sạch menu
      allDrop.forEach((d) => (d.style.display = "none"));
    }
  });
});
