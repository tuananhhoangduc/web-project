document.addEventListener("DOMContentLoaded", function () {
  // Lấy tham chiếu đến các phần tử cần thiết cho form thêm stylist
  const showFormBtn = document.getElementById("show-add-stylist-form-btn");
  const addStylistFormContainer = document.getElementById(
    "add-stylist-form-container",
  );
  const cancelFormBtn = document.getElementById("cancel-add-stylist-form-btn");
  const addStylistForm = document.getElementById("add-stylist-form"); // Tham chiếu đến form

  // Kiểm tra xem các phần tử form có tồn tại trên trang không
  if (
    showFormBtn &&
    addStylistFormContainer &&
    cancelFormBtn &&
    addStylistForm
  ) {
    console.log(
      "Stylist management form elements found. Initializing form toggle logic.",
    );

    // Hàm hiển thị form
    function showAddStylistForm() {
      addStylistFormContainer.classList.add("visible"); // Thêm class 'visible' để hiển thị
      showFormBtn.style.display = "none"; // Ẩn nút "Thêm Stylist"
      console.log("Add stylist form shown.");
    }

    // Hàm ẩn form
    function hideAddStylistForm() {
      addStylistFormContainer.classList.remove("visible"); // Xóa class 'visible' để ẩn
      showFormBtn.style.display = "inline-block"; // Hiển thị lại nút "Thêm Stylist" (hoặc block tùy style ban đầu)
      addStylistForm.reset(); // Reset form về trạng thái ban đầu
      console.log("Add stylist form hidden.");
    }

    // Lắng nghe sự kiện click vào nút "Thêm Stylist"
    showFormBtn.addEventListener("click", showAddStylistForm);

    // Lắng nghe sự kiện click vào nút "Hủy" trong form
    cancelFormBtn.addEventListener("click", hideAddStylistForm);

    // Tùy chọn: Lắng nghe sự kiện submit form
    addStylistForm.addEventListener("submit", function (event) {
      event.preventDefault(); // Ngăn chặn submit form mặc định

      // --- Logic xử lý dữ liệu form tại đây ---
      const stylistName = document.getElementById("stylist-name").value;
      const stylistBranch = document.getElementById("stylist-branch").value;
      const stylistPhone = document.getElementById("stylist-phone").value;
      const stylistSpecialty =
        document.getElementById("stylist-specialty").value;
      // const stylistEmail = document.getElementById('stylist-email').value; // Nếu có
      // const stylistPhoto = document.getElementById('stylist-photo').files[0]; // Lấy file ảnh

      console.log("Form submitted:", {
        name: stylistName,
        branch: stylistBranch,
        phone: stylistPhone,
        specialty: stylistSpecialty,
        // email: stylistEmail,
        // photo: stylistPhoto ? stylistPhoto.name : 'No file'
      });

      // --- Gửi dữ liệu đến backend (sử dụng fetch API) ---
      // Sử dụng FormData nếu có file ảnh
      // const formData = new FormData(addStylistForm);
      // fetch('/api/stylists', {
      //     method: 'POST',
      //     // Nếu gửi FormData, không cần set Content-Type header, trình duyệt tự làm
      //     // headers: {
      //     //     'Authorization': 'Bearer ' + localStorage.getItem('userToken')
      //     // },
      //     body: formData // Gửi FormData
      // })
      // .then(response => {
      //     if (!response.ok) {
      //         throw new Error('Network response was not ok ' + response.statusText);
      //     }
      //     return response.json(); // Hoặc response.text()
      // })
      // .then(data => {
      //     console.log('Stylist added successfully:', data);
      //     // Tùy chọn: Cập nhật lại bảng danh sách stylist
      //     // fetchStylists(); // Gọi hàm để tải lại danh sách stylist
      //     hideAddStylistForm(); // Ẩn form sau khi lưu thành công
      //     // Tùy chọn: Hiển thị thông báo thành công cho người dùng
      // })
      // .catch(error => {
      //     console.error('Error adding stylist:', error);
      //     // Tùy chọn: Hiển thị thông báo lỗi cho người dùng
      // });

      // --- Mô phỏng lưu thành công và ẩn form ---
      // Nếu không dùng backend, chỉ ẩn form và reset
      hideAddStylistForm();
      // Tùy chọn: Cập nhật bảng giả định hoặc hiển thị thông báo
      alert("Stylist đã được thêm (mô phỏng)."); // Sử dụng alert tạm thời
    });
  } else {
    console.log("Stylist management form elements not found on this page.");
  }

  // Tùy chọn: Logic để tải danh sách stylist khi trang tải
  // function fetchStylists() {
  //     console.log("Fetching stylists...");
  //     // fetch('/api/stylists')
  //     // .then(response => response.json())
  //     // .then(stylists => {
  //     //     console.log("Stylists fetched:", stylists);
  //     //     // Code để điền dữ liệu vào bảng .stylist-list tbody
  //     //     const tbody = document.querySelector('.stylist-list tbody');
  //     //     if (tbody) {
  //     //         tbody.innerHTML = ''; // Xóa dữ liệu cũ
  //     //         stylists.forEach(stylist => {
  //     //             const row = `
  //     //                 <tr>
  //     //                     <td>${stylist.id}</td> {/* Giả định có ID */}
  //     //                     <td><img src="${stylist.photoUrl || 'placeholder-stylist.jpg'}" alt="${stylist.name}" class="stylist-photo-thumb"></td> {/* Ảnh thumbnail */}
  //     //                     <td>${stylist.name}</td>
  //     //                     <td>${stylist.branchName || stylist.branchId}</td> {/* Hiển thị tên chi nhánh */}
  //     //                     <td>${stylist.phone || 'N/A'}</td>
  //     //                     <td>${stylist.specialty || 'N/A'}</td>
  //     //                     <td>
  //     //                         <button class="btn edit-btn" data-id="${stylist.id}"><i class="fas fa-edit"></i> Sửa</button>
  //     //                         <button class="btn delete-btn" data-id="${stylist.id}"><i class="fas fa-trash"></i> Xóa</button>
  //     //                     </td>
  //     //                 </tr>
  //     //             `;
  //     //             tbody.innerHTML += row;
  //     //         });
  //     //     }
  //     // })
  //     // .catch(error => {
  //     //     console.error("Error fetching stylists:", error);
  //     // });
  // }

  // fetchStylists(); // Gọi hàm khi trang tải xong (nếu sử dụng backend)

  // Tùy chọn: Logic để điền dropdown Chi nhánh khi tải form
  // function populateBranchDropdown() {
  //      const branchSelect = document.getElementById('stylist-branch');
  //      if (branchSelect) {
  //          // Lấy dữ liệu chi nhánh từ API hoặc biến global (nếu có)
  //          // fetch('/api/branches')
  //          // .then(response => response.json())
  //          // .then(branches => {
  //          //     branchSelect.innerHTML = '<option value="">-- Chọn chi nhánh --</option>'; // Reset options
  //          //     branches.forEach(branch => {
  //          //         const option = document.createElement('option');
  //          //         option.value = branch.id;
  //          //         option.textContent = branch.name;
  //          //         branchSelect.appendChild(option);
  //          //     });
  //          // })
  //          // .catch(error => {
  //          //     console.error("Error populating branch dropdown:", error);
  //          // });

  //          // --- Ví dụ điền options giả định ---
  //          const dummyBranches = [
  //              { id: 'salon-1', name: 'Salon A' },
  //              { id: 'salon-2', name: 'Salon B' },
  //              { id: 'salon-3', name: 'Salon C' }
  //          ];
  //          branchSelect.innerHTML = '<option value="">-- Chọn chi nhánh --</option>'; // Reset options
  //          dummyBranches.forEach(branch => {
  //              const option = document.createElement('option');
  //              option.value = branch.id;
  //              option.textContent = branch.name;
  //              branchSelect.appendChild(option);
  //          });
  //          console.log("Branch dropdown populated.");
  //      }
  // }

  // populateBranchDropdown(); // Gọi hàm khi trang tải xong
}); // End DOMContentLoaded
