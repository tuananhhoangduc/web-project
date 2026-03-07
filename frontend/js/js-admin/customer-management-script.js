document.addEventListener("DOMContentLoaded", function () {
  // --- Logic cho Tìm Kiếm Khách hàng ---
  const searchInput = document.getElementById("customer-search-input");
  const searchButton = document.getElementById("customer-search-btn");
  const customerTableBody = document.querySelector(".customer-list tbody"); // Lấy tbody của bảng khách hàng

  // --- Logic cho Form Thêm Khách hàng ---
  const showAddFormBtn = document.getElementById("show-add-customer-form-btn");
  const addCustomerFormContainer = document.getElementById(
    "add-customer-form-container",
  );
  const cancelAddFormBtn = document.getElementById(
    "cancel-add-customer-form-btn",
  );
  const addCustomerForm = document.getElementById("add-customer-form");

  // --- Logic cho Form Chỉnh sửa Khách hàng ---
  const editCustomerFormContainer = document.getElementById(
    "edit-customer-form-container",
  );
  const editCustomerForm = document.getElementById("edit-customer-form");
  const cancelEditFormBtn = document.getElementById(
    "cancel-edit-customer-form-btn",
  );

  // Lấy các input trong form chỉnh sửa
  const editCustomerIdInput = document.getElementById("edit-customer-id");
  const editCustomerNameInput = document.getElementById("edit-customer-name");
  const editCustomerPhoneInput = document.getElementById("edit-customer-phone");
  const editCustomerEmailInput = document.getElementById("edit-customer-email");
  const editCustomerRegDateInput = document.getElementById(
    "edit-customer-reg-date",
  ); // Input ngày đăng ký (readonly)

  // Kiểm tra xem các phần tử cần thiết có tồn tại không
  if (
    searchInput &&
    customerTableBody &&
    showAddFormBtn &&
    addCustomerFormContainer &&
    cancelAddFormBtn &&
    addCustomerForm &&
    editCustomerFormContainer &&
    editCustomerForm &&
    cancelEditFormBtn
  ) {
    console.log("Customer management elements found. Initializing logic.");

    // --- Cấu trúc dữ liệu khách hàng giả định (Thay thế bằng dữ liệu thực tế từ backend) ---
    // Lưu ý: Trong thực tế, bạn sẽ tải dữ liệu này từ backend khi trang load
    let allCustomers = [
      // Sử dụng `let` để có thể cập nhật mảng này sau khi sửa/xóa
      {
        id: "KH001",
        name: "Nguyễn Văn A",
        phone: "0912345678",
        email: "nguyen.a@example.com",
        registrationDate: "01/01/2023",
      },
      {
        id: "KH002",
        name: "Trần Thị B",
        phone: "0987654321",
        email: "tran.b@example.com",
        registrationDate: "15/03/2023",
      },
      {
        id: "KH003",
        name: "Lê Văn C",
        phone: "0901112223",
        email: "le.c@example.com",
        registrationDate: "20/05/2023",
      },
      {
        id: "KH004",
        name: "Phạm Thị D",
        phone: "0977778888",
        email: "pham.d@example.com",
        registrationDate: "10/07/2023",
      },
      // Thêm các khách hàng giả định khác
    ];
    console.log("Dummy customer data loaded.");
    // -----------------------------------------------------------------------------------

    // Hàm hiển thị danh sách khách hàng trong bảng
    function displayCustomers(customersToDisplay) {
      customerTableBody.innerHTML = ""; // Xóa các hàng hiện có

      if (customersToDisplay.length === 0) {
        // Hiển thị thông báo không tìm thấy
        const noResultsRow = `
                    <tr>
                        <td colspan="6" style="text-align: center;">Không tìm thấy khách hàng nào phù hợp.</td> 
                    </tr>
                `;
        customerTableBody.innerHTML = noResultsRow;
        return; // Dừng hàm nếu không có khách hàng nào
      }

      // Điền dữ liệu vào bảng
      customersToDisplay.forEach((customer) => {
        const row = `
                    <tr data-id="${customer.id}"> 
                        <td>${customer.id}</td>
                        <td>${customer.name}</td>
                        <td>${customer.phone || "N/A"}</td> 
                        <td>${customer.email || "N/A"}</td> 
                        <td>${customer.registrationDate}</td>
                        <td>
                            <button class="btn secondary-btn view-details-btn" data-id="${customer.id}"><i class="fas fa-info-circle"></i> Chi tiết</button> 
                            <button class="btn edit-btn" data-id="${customer.id}"><i class="fas fa-edit"></i> Sửa</button> 
                            <button class="btn delete-btn" data-id="${customer.id}"><i class="fas fa-trash"></i> Xóa</button>
                        </td>
                    </tr>
                `;
        customerTableBody.innerHTML += row; // Thêm hàng vào tbody
      });
      console.log(
        "Customer table updated with",
        customersToDisplay.length,
        "customers.",
      );
    }

    // Hàm thực hiện tìm kiếm và lọc khách hàng
    function performSearch() {
      const searchTerm = searchInput.value.toLowerCase().trim(); // Lấy giá trị tìm kiếm, chuyển sang chữ thường, bỏ khoảng trắng

      if (!searchTerm) {
        // Nếu ô tìm kiếm rỗng, hiển thị lại toàn bộ danh sách
        displayCustomers(allCustomers);
        console.log("Search term is empty. Displaying all customers.");
        return;
      }

      // Lọc danh sách khách hàng dựa trên searchTerm
      const filteredCustomers = allCustomers.filter((customer) => {
        // Tìm kiếm trong tên, SĐT, Email (hoặc các trường khác nếu cần)
        return (
          customer.name.toLowerCase().includes(searchTerm) ||
          (customer.phone && customer.phone.includes(searchTerm)) || // Kiểm tra SĐT có tồn tại trước khi includes
          (customer.email && customer.email.toLowerCase().includes(searchTerm))
        ); // Kiểm tra Email có tồn tại trước khi includes
      });

      // Hiển thị danh sách đã lọc
      displayCustomers(filteredCustomers);
      console.log(
        "Search performed for:",
        searchTerm,
        "- Found",
        filteredCustomers.length,
        "customers.",
      );
    }

    // Lắng nghe sự kiện click vào nút Tìm kiếm
    searchButton.addEventListener("click", performSearch);

    // Lắng nghe sự kiện khi người dùng nhấn Enter trong ô tìm kiếm
    searchInput.addEventListener("keypress", function (event) {
      if (event.key === "Enter") {
        event.preventDefault(); // Ngăn chặn hành vi mặc định (submit form nếu có)
        performSearch(); // Thực hiện tìm kiếm
      }
    });

    // Tùy chọn: Lắng nghe sự kiện input để lọc ngay khi gõ
    // searchInput.addEventListener('input', performSearch);

    // --- Logic cho Form Thêm Khách hàng ---

    // Hàm hiển thị form thêm khách hàng
    function showAddCustomerForm() {
      addCustomerFormContainer.classList.add("visible");
      showAddFormBtn.style.display = "none";
      // Ẩn form chỉnh sửa nếu nó đang hiển thị
      if (editCustomerFormContainer.classList.contains("visible")) {
        hideEditCustomerForm();
      }
      console.log("Add customer form shown.");
    }

    // Hàm ẩn form thêm khách hàng
    function hideAddCustomerForm() {
      addCustomerFormContainer.classList.remove("visible");
      showAddFormBtn.style.display = "inline-block"; // Hoặc block tùy style
      addCustomerForm.reset(); // Reset form
      console.log("Add customer form hidden.");
    }

    // Lắng nghe sự kiện click vào nút "Thêm Khách hàng"
    showAddFormBtn.addEventListener("click", showAddCustomerForm);

    // Lắng nghe sự kiện click vào nút "Hủy" trong form thêm khách hàng
    cancelAddFormBtn.addEventListener("click", hideAddCustomerForm);

    // Tùy chọn: Lắng nghe sự kiện submit form thêm khách hàng
    addCustomerForm.addEventListener("submit", function (event) {
      event.preventDefault();
      // --- Logic xử lý dữ liệu form thêm khách hàng tại đây ---
      const customerName = document.getElementById("add-customer-name").value;
      const customerPhone = document.getElementById("add-customer-phone").value;
      const customerEmail = document.getElementById("add-customer-email").value;

      console.log("Add customer form submitted (simulated) with data:", {
        name: customerName,
        phone: customerPhone,
        email: customerEmail,
      });

      // --- Mô phỏng thêm khách hàng mới vào mảng giả định ---
      const newCustomerId =
        "KH" + (allCustomers.length + 1).toString().padStart(3, "0"); // Tạo ID giả định
      const newCustomer = {
        id: newCustomerId,
        name: customerName,
        phone: customerPhone,
        email: customerEmail,
        registrationDate: new Date().toLocaleDateString("vi-VN"), // Ngày hiện tại
      };
      allCustomers.push(newCustomer); // Thêm khách hàng mới vào mảng

      // Cập nhật lại bảng hiển thị
      displayCustomers(allCustomers);

      // Ẩn form và reset
      hideAddCustomerForm();
      alert("Khách hàng đã được thêm (mô phỏng)."); // Sử dụng alert tạm thời

      // --- Gửi dữ liệu đến backend (sử dụng fetch API) ---
      // fetch('/api/customers', {
      //     method: 'POST',
      //     headers: {
      //         'Content-Type': 'application/json',
      //         // Thêm header Authorization nếu cần xác thực
      //         // 'Authorization': 'Bearer ' + localStorage.getItem('userToken')
      //     },
      //     body: JSON.stringify({
      //         name: customerName,
      //         phone: customerPhone,
      //         email: customerEmail
      //     })
      // })
      // .then(response => {
      //     if (!response.ok) {
      //         throw new Error('Network response was not ok ' + response.statusText);
      //     }
      //     return response.json(); // Hoặc response.text()
      // })
      // .then(data => {
      //     console.log('Customer added successfully:', data);
      //     // Tùy chọn: Cập nhật lại bảng danh sách khách hàng
      //     // fetchCustomers(); // Gọi hàm để tải lại danh sách khách hàng
      //     hideAddCustomerForm(); // Ẩn form sau khi lưu thành công
      //     // Tùy chọn: Hiển thị thông báo thành công cho người dùng
      // })
      // .catch(error => {
      //     console.error('Error adding customer:', error);
      //     // Tùy chọn: Hiển thị thông báo lỗi cho người dùng
      // });
    });
    // -------------------------------------------------------------------

    // --- Logic cho Form Chỉnh sửa Khách hàng ---

    // Hàm hiển thị form chỉnh sửa và điền dữ liệu
    function showEditCustomerForm(customerData) {
      // Điền dữ liệu vào form
      editCustomerIdInput.value = customerData.id;
      editCustomerNameInput.value = customerData.name;
      editCustomerPhoneInput.value = customerData.phone || ""; // Điền giá trị hoặc chuỗi rỗng nếu null/undefined
      editCustomerEmailInput.value = customerData.email || "";
      editCustomerRegDateInput.value = customerData.registrationDate || ""; // Ngày đăng ký

      // Hiển thị form
      editCustomerFormContainer.classList.add("visible");

      // Ẩn nút "Thêm Khách hàng" nếu nó tồn tại và đang hiển thị
      if (showAddFormBtn && showAddFormBtn.style.display !== "none") {
        showAddFormBtn.style.display = "none";
      }
      // Ẩn form thêm khách hàng nếu nó đang hiển thị
      if (addCustomerFormContainer.classList.contains("visible")) {
        hideAddCustomerForm();
      }

      console.log("Edit customer form shown for ID:", customerData.id);
    }

    // Hàm ẩn form chỉnh sửa
    function hideEditCustomerForm() {
      editCustomerFormContainer.classList.remove("visible");
      editCustomerForm.reset(); // Reset form
      // Hiển thị lại nút "Thêm Khách hàng" nếu nó tồn tại
      if (showAddFormBtn) {
        showAddFormBtn.style.display = "inline-block"; // Hoặc block tùy style
      }
      console.log("Edit customer form hidden.");
    }

    // Lắng nghe sự kiện click trên bảng để bắt nút "Sửa"
    customerTableBody.addEventListener("click", function (event) {
      const targetBtn = event.target.closest(".edit-btn"); // Tìm nút có class 'edit-btn'
      if (targetBtn) {
        event.preventDefault(); // Ngăn chặn hành vi mặc định của nút (nếu là link)

        const customerId = targetBtn.getAttribute("data-id"); // Lấy ID khách hàng từ data-id của nút

        // --- Tìm dữ liệu khách hàng trong mảng allCustomers (Mô phỏng) ---
        const customerToEdit = allCustomers.find(
          (customer) => customer.id === customerId,
        );

        if (customerToEdit) {
          console.log("Edit button clicked for customer ID:", customerId);
          // Hiển thị form chỉnh sửa và điền dữ liệu
          showEditCustomerForm(customerToEdit);
        } else {
          console.error("Customer with ID", customerId, "not found in data.");
          // Tùy chọn: Hiển thị thông báo lỗi
        }
      }
      // Thêm logic cho nút Chi tiết và Xóa tại đây nếu cần
      const viewDetailsBtn = event.target.closest(".view-details-btn");
      if (viewDetailsBtn) {
        const customerId = viewDetailsBtn.getAttribute("data-id");
        console.log("View details button clicked for customer ID:", customerId);
        // Logic xem chi tiết
      }
      const deleteBtn = event.target.closest(".delete-btn");
      if (deleteBtn) {
        const customerId = deleteBtn.getAttribute("data-id");
        console.log("Delete button clicked for customer ID:", customerId);
        // Logic xóa
        if (confirm("Bạn có chắc chắn muốn xóa khách hàng này?")) {
          // --- Xóa khách hàng khỏi mảng giả định ---
          allCustomers = allCustomers.filter(
            (customer) => customer.id !== customerId,
          );
          // Cập nhật lại bảng hiển thị
          displayCustomers(allCustomers);
          console.log("Customer with ID", customerId, "deleted (simulated).");
          // Tùy chọn: Gửi yêu cầu xóa đến backend
          // fetch('/api/customers/' + customerId, { method: 'DELETE' })
          // .then(response => {
          //     if (response.ok) {
          //         console.log("Customer deleted successfully on backend:", customerId);
          //         // Nếu xóa thành công trên backend, có thể tải lại danh sách
          //         // fetchCustomers(); // Nếu bạn có hàm fetchCustomers thực tế
          //     } else {
          //         console.error("Error deleting customer on backend:", customerId);
          //     }
          // })
          // .catch(error => {
          //     console.error("Error sending delete request:", error);
          // });
        }
      }
    });

    // Lắng nghe sự kiện click cho nút "Hủy" trong form chỉnh sửa
    cancelEditFormBtn.addEventListener("click", hideEditCustomerForm);

    // Lắng nghe sự kiện submit của form chỉnh sửa
    editCustomerForm.addEventListener("submit", function (event) {
      event.preventDefault(); // Ngăn chặn submit form mặc định

      // --- Lấy dữ liệu đã chỉnh sửa từ form ---
      const customerId = editCustomerIdInput.value; // Lấy ID từ input ẩn
      const updatedName = editCustomerNameInput.value;
      const updatedPhone = editCustomerPhoneInput.value;
      const updatedEmail = editCustomerEmailInput.value;
      // Không lấy ngày đăng ký vì readonly

      console.log("Edit form submitted for ID:", customerId, "with data:", {
        name: updatedName,
        phone: updatedPhone,
        email: updatedEmail,
      });

      // --- Gửi dữ liệu đã chỉnh sửa đến backend (sử dụng fetch API) ---
      // fetch('/api/customers/' + customerId, {
      //     method: 'PUT', // Hoặc PATCH
      //     headers: {
      //         'Content-Type': 'application/json',
      //         // Thêm header Authorization nếu cần xác thực
      //         // 'Authorization': 'Bearer ' + localStorage.getItem('userToken')
      //     },
      //     body: JSON.stringify({
      //         name: updatedName,
      //         phone: updatedPhone,
      //         email: updatedEmail
      //         // Gửi các trường khác nếu có
      //     })
      // })
      // .then(response => {
      //     if (!response.ok) {
      //         throw new Error('Network response was not ok ' + response.statusText);
      //     }
      //     return response.json(); // Hoặc response.text()
      // })
      // .then(data => {
      //     console.log('Customer updated successfully:', data);
      //     // Tùy chọn: Cập nhật lại dữ liệu trong mảng allCustomers
      //     const index = allCustomers.findIndex(customer => customer.id === customerId);
      //     if (index !== -1) {
      //         // Cập nhật các trường có thể sửa
      //         allCustomers[index].name = updatedName;
      //         allCustomers[index].phone = updatedPhone;
      //         allCustomers[index].email = updatedEmail;
      //         // Giữ nguyên các trường không sửa như ID, ngày đăng ký
      //     }
      //     // Tải lại bảng để hiển thị dữ liệu mới
      //     displayCustomers(allCustomers);
      //     hideEditCustomerForm(); // Ẩn form sau khi cập nhật thành công
      //     // Tùy chọn: Hiển thị thông báo thành công cho người dùng
      // })
      // .catch(error => {
      //     console.error('Error updating customer:', error);
      //     // Tùy chọn: Hiển thị thông báo lỗi cho người dùng
      // });

      // --- Mô phỏng cập nhật thành công và ẩn form ---
      // Nếu không dùng backend, chỉ cập nhật mảng giả định và hiển thị lại bảng
      const index = allCustomers.findIndex(
        (customer) => customer.id === customerId,
      );
      if (index !== -1) {
        allCustomers[index].name = updatedName;
        allCustomers[index].phone = updatedPhone;
        allCustomers[index].email = updatedEmail;
      }
      displayCustomers(allCustomers); // Hiển thị lại bảng
      hideEditCustomerForm(); // Ẩn form
      alert("Thông tin khách hàng đã được cập nhật (mô phỏng)."); // Sử dụng alert tạm thời
    });

    // Khởi tạo: Hiển thị toàn bộ danh sách khách hàng khi trang tải lần đầu
    displayCustomers(allCustomers);
    console.log("Initial customer list displayed.");

    // Tùy chọn: Logic cho nút Chi tiết và Xóa (đã thêm listener ở trên, chỉ cần thêm logic xử lý)
    // Logic cho nút Chi tiết, Sửa, Xóa đã được thêm vào listener của customerTableBody ở trên.
  } else {
    console.log(
      "Required elements for customer management logic not found on this page.",
    );
  }
}); // End DOMContentLoaded
