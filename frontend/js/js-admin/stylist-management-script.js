document.addEventListener("DOMContentLoaded", function () {
  // Lấy tham chiếu đến các phần tử cần thiết cho form thêm stylist
  const showFormBtn = document.getElementById("show-add-stylist-form-btn");
  const addStylistFormContainer = document.getElementById(
    "add-stylist-form-container",
  );
  const cancelFormBtn = document.getElementById("cancel-add-stylist-form-btn");
  const addStylistForm = document.getElementById("add-stylist-form"); // Tham chiếu đến form
  let editingRow = null; // Biến để nhớ xem đang sửa dòng nào
  const tableBody = document.querySelector(".stylist-list tbody");
  const formTitle = document.querySelector(".add-stylist-block h2");
  const submitBtn = document.querySelector("#add-stylist-form .primary-btn");
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
      addStylistFormContainer.classList.remove("visible");
      showFormBtn.style.display = "inline-block";
      addStylistForm.reset();

      editingRow = null; // Reset lại trạng thái
      if (formTitle) formTitle.textContent = "Thêm Stylist Mới"; // Trả lại tiêu đề cũ
      if (submitBtn)
        submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Thêm Stylist'; // Trả lại nút cũ

      console.log("Add stylist form hidden.");
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
    // --- BẮT ĐẦU ĐOẠN MỚI: LOGIC BẤM NÚT SỬA/XÓA TRÊN BẢNG ---
    if (tableBody) {
      tableBody.addEventListener("click", function (event) {
        const target = event.target;

        // Xử lý XÓA
        if (target.closest(".delete-btn")) {
          if (confirm("Bạn có chắc chắn muốn xóa Stylist này khỏi hệ thống?")) {
            target.closest("tr").remove();
          }
        }

        // Xử lý SỬA
        if (target.closest(".edit-btn")) {
          editingRow = target.closest("tr");
          const cells = editingRow.querySelectorAll("td");

          // Lấy dữ liệu từ bảng lên Form
          document.getElementById("stylist-name").value =
            cells[1].textContent.trim();
          document.getElementById("stylist-phone").value =
            cells[3].textContent.trim() !== "Trống"
              ? cells[3].textContent.trim()
              : "";

          // Lấy Trạng thái
          const statusSpan = cells[4].querySelector(".status-badge");
          if (statusSpan) {
            document.getElementById("stylist-status").value =
              statusSpan.textContent.trim();
          }

          // Lấy Chi nhánh
          const branchText = cells[2].textContent.trim();
          const branchSelect = document.getElementById("stylist-branch");
          for (let i = 0; i < branchSelect.options.length; i++) {
            if (branchSelect.options[i].text === branchText) {
              branchSelect.selectedIndex = i;
              break;
            }
          }

          // Đổi giao diện Form sang chế độ Cập nhật
          if (formTitle) formTitle.textContent = "Sửa Thông Tin Stylist";
          if (submitBtn)
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Cập nhật';

          showAddStylistForm();
        }
      });
    }

    // --- BẮT ĐẦU ĐOẠN MỚI: LOGIC LƯU HOẶC CẬP NHẬT TRÊN FORM ---
    addStylistForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const stylistName = document.getElementById("stylist-name").value;
      const branchSelect = document.getElementById("stylist-branch");
      const stylistBranch =
        branchSelect.options[branchSelect.selectedIndex].text;
      const stylistPhone =
        document.getElementById("stylist-phone").value || "Trống";
      const stylistStatus = document.getElementById("stylist-status").value;

      let statusClass = "status-active";
      if (stylistStatus === "Nghỉ phép") statusClass = "status-leave";
      if (stylistStatus === "Đã nghỉ") statusClass = "status-inactive";

      if (editingRow) {
        // CHẾ ĐỘ SỬA: Đè dữ liệu mới lên dòng cũ
        editingRow.cells[1].textContent = stylistName;
        editingRow.cells[2].textContent = stylistBranch;
        editingRow.cells[3].textContent = stylistPhone;
        editingRow.cells[4].innerHTML = `<span class="status-badge ${statusClass}">${stylistStatus}</span>`;

        alert("Đã cập nhật thông tin Stylist thành công!");
      } else {
        // CHẾ ĐỘ THÊM MỚI: Tạo dòng mới tinh
        const randomId =
          "ST" +
          Math.floor(Math.random() * 1000)
            .toString()
            .padStart(3, "0");
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
              <td>${randomId}</td>
              <td>${stylistName}</td>
              <td>${stylistBranch}</td>
              <td>${stylistPhone}</td>
              <td><span class="status-badge ${statusClass}">${stylistStatus}</span></td>
              <td>
                  <button type="button" class="btn edit-btn"><i class="fas fa-edit"></i> Sửa</button>
                  <button type="button" class="btn delete-btn"><i class="fas fa-trash"></i> Xóa</button>
              </td>
          `;
        if (tableBody) tableBody.appendChild(newRow);
        alert("Đã thêm Stylist mới thành công!");
      }

      hideAddStylistForm(); // Ẩn form đi và tự động reset
    });
    // --- KẾT THÚC ĐOẠN MỚI ---
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
