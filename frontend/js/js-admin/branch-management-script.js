document.addEventListener("DOMContentLoaded", function () {
  // Lấy tham chiếu đến các phần tử cần thiết cho form thêm chi nhánh
  const showFormBtn = document.getElementById("show-add-branch-form-btn");
  const addBranchFormContainer = document.getElementById(
    "add-branch-form-container",
  );
  const cancelFormBtn = document.getElementById("cancel-add-branch-form-btn");
  const addBranchForm = document.getElementById("add-branch-form"); // Tham chiếu đến form

  // Kiểm tra xem các phần tử form có tồn tại trên trang không
  if (showFormBtn && addBranchFormContainer && cancelFormBtn && addBranchForm) {
    console.log(
      "Branch management form elements found. Initializing form toggle logic.",
    );

    // Hàm hiển thị form
    function showAddBranchForm() {
      addBranchFormContainer.classList.add("visible"); // Thêm class 'visible' để hiển thị
      showFormBtn.style.display = "none"; // Ẩn nút "Thêm Chi nhánh"
      console.log("Add branch form shown.");
    }

    // Hàm ẩn form
    function hideAddBranchForm() {
      addBranchFormContainer.classList.remove("visible"); // Xóa class 'visible' để ẩn
      showFormBtn.style.display = "inline-block"; // Hiển thị lại nút "Thêm Chi nhánh" (hoặc block tùy style ban đầu)
      addBranchForm.reset(); // Reset form về trạng thái ban đầu
      console.log("Add branch form hidden.");
    }

    // Lắng nghe sự kiện click vào nút "Thêm Chi nhánh"
    showFormBtn.addEventListener("click", showAddBranchForm);

    // Lắng nghe sự kiện click vào nút "Hủy" trong form
    cancelFormBtn.addEventListener("click", hideAddBranchForm);

    // Tùy chọn: Lắng nghe sự kiện submit form
    addBranchForm.addEventListener("submit", function (event) {
      event.preventDefault(); // Ngăn chặn submit form mặc định

      // --- Logic xử lý dữ liệu form tại đây ---
      const branchName = document.getElementById("branch-name").value;
      const branchAddress = document.getElementById("branch-address").value;
      const branchPhone = document.getElementById("branch-phone").value;
      const branchEmail = document.getElementById("branch-email").value;
      // const branchHours = document.getElementById('branch-hours').value; // Nếu có

      console.log("Form submitted:", {
        name: branchName,
        address: branchAddress,
        phone: branchPhone,
        email: branchEmail,
        // hours: branchHours
      });

      // --- Gửi dữ liệu đến backend (sử dụng fetch API) ---
      // fetch('/api/branches', {
      //     method: 'POST',
      //     headers: {
      //         'Content-Type': 'application/json',
      //         // Thêm header Authorization nếu cần xác thực
      //         // 'Authorization': 'Bearer ' + localStorage.getItem('userToken')
      //     },
      //     body: JSON.stringify({
      //         name: branchName,
      //         address: branchAddress,
      //         phone: branchPhone,
      //         email: branchEmail,
      //         // hours: branchHours
      //     })
      // })
      // .then(response => {
      //     if (!response.ok) {
      //         throw new Error('Network response was not ok ' + response.statusText);
      //     }
      //     return response.json(); // Hoặc response.text()
      // })
      // .then(data => {
      //     console.log('Branch added successfully:', data);
      //     // Tùy chọn: Cập nhật lại bảng danh sách chi nhánh
      //     // fetchBranches(); // Gọi hàm để tải lại danh sách chi nhánh
      //     hideAddBranchForm(); // Ẩn form sau khi lưu thành công
      //     // Tùy chọn: Hiển thị thông báo thành công cho người dùng
      // })
      // .catch(error => {
      //     console.error('Error adding branch:', error);
      //     // Tùy chọn: Hiển thị thông báo lỗi cho người dùng
      // });

      // --- Mô phỏng lưu thành công và ẩn form ---
      // Nếu không dùng backend, chỉ ẩn form và reset
      hideAddBranchForm();
      // Tùy chọn: Cập nhật bảng giả định hoặc hiển thị thông báo
      alert("Chi nhánh đã được thêm (mô phỏng)."); // Sử dụng alert tạm thời
    });
  } else {
    console.log("Branch management form elements not found on this page.");
  }

  // Tùy chọn: Logic để tải danh sách chi nhánh khi trang tải
  // function fetchBranches() {
  //     console.log("Fetching branches...");
  //     // fetch('/api/branches')
  //     // .then(response => response.json())
  //     // .then(branches => {
  //     //     console.log("Branches fetched:", branches);
  //     //     // Code để điền dữ liệu vào bảng .branch-list tbody
  //     //     const tbody = document.querySelector('.branch-list tbody');
  //     //     if (tbody) {
  //     //         tbody.innerHTML = ''; // Xóa dữ liệu cũ
  //     //         branches.forEach(branch => {
  //     //             const row = `
  //     //                 <tr>
  //     //                     <td>${branch.id}</td> {/* Giả định có ID */}
  //     //                     <td>${branch.name}</td>
  //     //                     <td>${branch.address}</td>
  //     //                     <td>${branch.phone || 'N/A'}</td>
  //     //                     <td>${branch.email || 'N/A'}</td>
  //     //                     <td>
  //     //                         <button class="btn edit-btn" data-id="${branch.id}"><i class="fas fa-edit"></i> Sửa</button>
  //     //                         <button class="btn delete-btn" data-id="${branch.id}"><i class="fas fa-trash"></i> Xóa</button>
  //     //                     </td>
  //     //                 </tr>
  //     //             `;
  //     //             tbody.innerHTML += row;
  //     //         });
  //     //     }
  //     // })
  //     // .catch(error => {
  //     //     console.error("Error fetching branches:", error);
  //     // });
  // }

  // fetchBranches(); // Gọi hàm khi trang tải xong (nếu sử dụng backend)
}); // End DOMContentLoaded
