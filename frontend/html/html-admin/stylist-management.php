<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý Stylist - Barber Shop Admin</title>
    <link rel="stylesheet" href="../../css/css-admin/style.css" />
    <link rel="stylesheet" href="../../css/css-admin/stylist-management-styles.css" />
    <link rel="stylesheet" href="../../css/css-admin/admin-dashboard-styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  </head>
  <body>
    <header class="site-header">
      <div class="container header-container">
        <div class="logo"><img src="../../image/logo.png" alt="Barber Shop Logo" /></div>
        <div class="hamburger-icon"><i class="fas fa-bars"></i></div>
        <nav class="desktop-nav">
          <ul>
            <li><a href="admin-dashboard.html">Trang chủ</a></li>
            <li><a href="customer-management.php">Khách hàng</a></li>
            <li><a href="salon-appointments.php">Lịch hẹn</a></li>
            <li><a href="branch-management.php">Chi nhánh</a></li>
            <li><a href="service-management.php">Dịch vụ</a></li>
            <li><a href="stylist-management.php">Stylist</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <main class="page-main">
      <div class="container">
        <h1>Quản lý Stylist</h1>
        <div class="stylist-management-container">
          
          <div class="leave-requests-block" style="margin-bottom: 40px; background: #fffaf0; padding: 20px; border-radius: 8px; border-left: 5px solid #ff7f00;">
            <h2 style="color: #ff7f00; margin-top: 0;"><i class="fas fa-envelope-open-text"></i> Đơn Xin Nghỉ Phép (Chờ Duyệt)</h2>
            <table>
              <thead>
                <tr><th>Ngày nộp</th><th>Tên Thợ</th><th>Ngày xin nghỉ</th><th>Loại nghỉ</th><th>Lý do</th><th>Hành động</th></tr>
              </thead>
              <tbody id="leave-table-body">
                 <tr><td colspan='6' style='text-align:center;'>Đang tải dữ liệu...</td></tr>
              </tbody>
            </table>
          </div>

          <div class="add-stylist-block">
            <h2 id="form-title">Thêm Stylist Mới</h2>
            <button class="btn primary-btn" id="show-add-stylist-form-btn"><i class="fas fa-user-plus"></i> Thêm Stylist</button>

            <div id="add-stylist-form-container" class="add-stylist-form-container">
              <form id="add-stylist-form">
                <input type="hidden" id="stylist-id" name="stylist_id" value="">
                <div class="form-group"><label>Tên Thợ:</label><input type="text" id="stylist-name" name="stylist_name" required /></div>
                <div class="form-group"><label>Số điện thoại:</label><input type="tel" id="stylist-phone" name="stylist_phone" required /></div>
                <div class="form-group"><label>Email:</label><input type="email" id="stylist-email" name="stylist_email" /></div>
                <div class="form-group">
                    <label>Thuộc Chi nhánh:</label>
                    <select id="stylist-branch" name="branch_id" required><option value="">-- Đang tải Chi nhánh --</option></select>
                </div>
                <div class="form-group">
                    <label>Trạng thái:</label>
                    <select id="stylist-status" name="stylist_status"><option value="active">Đang làm việc</option><option value="inactive">Nghỉ phép</option></select>
                </div>
                <button type="submit" class="btn primary-btn" id="submit-btn"><i class="fas fa-save"></i> Lưu Thợ Cắt</button>
                <button type="button" class="btn secondary-btn" id="cancel-add-stylist-form-btn"><i class="fas fa-times"></i> Làm mới</button>
              </form>
            </div>
          </div>

          <div class="stylist-list">
            <h2>Danh sách Stylist</h2>
            <table>
              <thead><tr><th>Mã Stylist</th><th>Tên Stylist</th><th>Chi nhánh</th><th>Số điện thoại</th><th>Trạng thái</th><th>Hành động</th></tr></thead>
              <tbody id="stylist-table-body"><tr><td colspan='6' style='text-align:center;'>Đang tải dữ liệu...</td></tr></tbody>
            </table>
          </div>

        </div>
      </div>
    </main>

    <footer style="background-color: #1a1a1a; color: #fff; text-align: center; padding: 20px;"><p>Barber Shop. All rights reserved.</p></footer>

    <script src="../../js/js-client/script.js"></script>
    <script src="../../js/js-admin/admin-auth.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        loadBranches();
        loadStylists();
        loadLeaveRequests(); 

        document.getElementById('add-stylist-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('stylist-id').value;
            const endpoint = id ? '../../../backend/update_stylist.php' : '../../../backend/add_stylist.php';
            
            fetch(endpoint, { method: 'POST', body: new FormData(this) })
            .then(res => res.json())
            .then(res => {
                alert(res.message);
                if(res.status === 'success') {
                    this.reset(); document.getElementById('stylist-id').value = '';
                    document.getElementById('form-title').innerText = 'Thêm Stylist Mới';
                    document.getElementById('submit-btn').innerHTML = '<i class="fas fa-save"></i> Lưu Thợ Cắt';
                    loadStylists();
                }
            });
        });

        document.getElementById('cancel-add-stylist-form-btn').addEventListener('click', function() {
            document.getElementById('add-stylist-form').reset();
            document.getElementById('stylist-id').value = '';
            document.getElementById('form-title').innerText = 'Thêm Stylist Mới';
            document.getElementById('submit-btn').innerHTML = '<i class="fas fa-save"></i> Lưu Thợ Cắt';
        });
    });

    function loadLeaveRequests() {
        fetch('../../../backend/api/get_leave_requests.php')
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('leave-table-body');
            tbody.innerHTML = '';
            if(res.status === 'success' && res.data.length > 0) {
                res.data.forEach(req => {
                    let ds = req.start_date.split('-'); let fStart = `${ds[2]}/${ds[1]}/${ds[0]}`;
                    let de = req.end_date.split('-'); let fEnd = `${de[2]}/${de[1]}/${de[0]}`;
                    let leaveRange = fStart === fEnd ? fStart : `Từ ${fStart} đến ${fEnd}`;
                    let cDate = req.created_at.split(' ')[0].split('-'); let fCreated = `${cDate[2]}/${cDate[1]}/${cDate[0]}`;

                    tbody.innerHTML += `
                    <tr>
                        <td>${fCreated}</td>
                        <td style="font-weight:bold; color:#ff7f00;">${req.full_name}</td>
                        <td>${leaveRange}</td>
                        <td>${req.leave_type}</td>
                        <td>${req.reason || 'Không có'}</td>
                        <td>
                            <button class="btn" onclick="updateLeaveStatus(${req.request_id}, 'approved')" style="background-color: #28a745; color: white; padding: 5px 10px; margin-right: 5px;"><i class="fas fa-check"></i> Duyệt</button>
                            <button class="btn" onclick="updateLeaveStatus(${req.request_id}, 'rejected')" style="background-color: #dc3545; color: white; padding: 5px 10px;"><i class="fas fa-times"></i> Từ chối</button>
                        </td>
                    </tr>`;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='6' style='text-align:center;'>Không có đơn xin nghỉ nào cần duyệt.</td></tr>";
            }
        });
    }

    function updateLeaveStatus(id, status) {
        let actionName = status === 'approved' ? 'DUYỆT' : 'TỪ CHỐI';
        if (confirm(`Bạn có chắc chắn muốn ${actionName} đơn xin nghỉ này không?`)) {
            fetch(`../../../backend/update_leave_status.php?id=${id}&status=${status}`)
            .then(res => res.json())
            .then(res => {
                alert(res.message);
                if(res.status === 'success') {
                    loadLeaveRequests(); 
                    loadStylists(); 
                }
            });
        }
    }

    function loadBranches() {
        fetch('../../../backend/api/get_branches_admin.php').then(res => res.json())
        .then(res => {
            const select = document.getElementById('stylist-branch');
            select.innerHTML = '<option value="">-- Chọn Chi nhánh --</option>';
            if(res.status === 'success') res.data.forEach(b => select.innerHTML += `<option value="${b.branch_id}">${b.branch_name}</option>`);
        });
    }

    function loadStylists() {
        fetch('../../../backend/api/get_stylists_admin.php').then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('stylist-table-body');
            tbody.innerHTML = '';
            if(res.status === 'success' && res.data.length > 0) {
                res.data.forEach(st => {
                    let statusColor = (st.status === 'Đang làm việc') ? 'green' : 'red';
                    let statusVal = (st.status === 'Đang làm việc') ? 'active' : 'inactive';
                    
                    // Mã hóa an toàn 100% để gắn vào data-attribute
                    let safeName = (st.full_name || '').replace(/"/g, '&quot;');
                    let safePhone = (st.phone || '').replace(/"/g, '&quot;');

                    tbody.innerHTML += `
                    <tr>
                        <td>ST${String(st.stylist_id).padStart(3, '0')}</td>
                        <td style="font-weight: bold;">${st.full_name}</td>
                        <td>${st.branch_name || 'Chưa phân bổ'}</td>
                        <td>${st.phone}</td>
                        <td><span style="color: ${statusColor}; font-weight: bold; background: #f0f0f0; padding: 5px 10px; border-radius: 4px;">${st.status}</span></td>
                        <td>
                            <button class="btn edit-btn" 
                                data-id="${st.stylist_id}"
                                data-name="${safeName}"
                                data-phone="${safePhone}"
                                data-branch="${st.branch_id || ''}"
                                data-status="${statusVal}"
                                onclick="editStylist(this)" 
                                style="background-color: #FFC107; color: #000;">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="btn delete-btn" onclick="deleteStylist(${st.stylist_id})"><i class="fas fa-trash"></i> Xóa</button>
                        </td>
                    </tr>`;
                });
            } else {
                tbody.innerHTML = "<tr><td colspan='6' style='text-align:center;'>Chưa có thợ cắt nào.</td></tr>";
            }
        });
    }

    // Hàm gọi khi bấm nút Sửa (Bắt data từ attribute của chính nút đó)
    function editStylist(btn) {
        document.getElementById('stylist-id').value = btn.getAttribute('data-id');
        document.getElementById('stylist-name').value = btn.getAttribute('data-name');
        document.getElementById('stylist-phone').value = btn.getAttribute('data-phone');
        document.getElementById('stylist-branch').value = btn.getAttribute('data-branch');
        document.getElementById('stylist-status').value = btn.getAttribute('data-status');

        document.getElementById('form-title').innerText = 'Cập nhật Thợ cắt';
        document.getElementById('submit-btn').innerHTML = '<i class="fas fa-check"></i> Cập nhật ngay';
        
        const formContainer = document.getElementById('add-stylist-form-container');
        formContainer.style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function deleteStylist(id) {
        if (confirm('Bạn có chắc chắn muốn xóa thợ cắt này không?')) {
            fetch(`../../../backend/delete_stylist.php?id=${id}`).then(res => res.json())
            .then(res => { alert(res.message); if(res.status === 'success') loadStylists(); });
        }
    }
    </script>
  </body>
</html>