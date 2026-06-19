# TỔNG KẾT TÍCH HỢP HỆ THỐNG API TÀI KHOẢN (ACCOUNT.NKS.VN)

Tài liệu này tổng hợp toàn bộ các phần việc đã thực hiện để tích hợp hệ thống quản lý tài khoản thành viên liên kết với API công ty tại địa chỉ `https://account.nks.vn`.

---

## 1. Danh sách API tích hợp thực tế
Qua quá trình kiểm dò và chạy thử nghiệm trực tiếp, dưới đây là danh mục API chính xác đã được tích hợp thành công:

| STT | Chức năng | Phương thức | URL chính xác | Nhận Token qua | Ghi chú |
| :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **Đăng nhập hệ thống** | `POST` | `/api/nks/user/login` | *Không yêu cầu* | Nhận diện tài khoản và trả về `access_token` |
| 2 | **Lấy thông tin thành viên** | `POST` | `/api/nks/user` | `access_token` trong Body | Đồng bộ dữ liệu người dùng về DB cục bộ |
| 3 | **Cập nhật thông tin** | `POST` | `/api/nks/user/updateInfo` | `access_token` trong Body | Cập nhật Họ tên, SĐT, Zalo |
| 4 | **Cập nhật mật khẩu** | `POST` | `/api/nks/user/updatePass` | `access_token` trong Body | Đổi mật khẩu tài khoản |
| 5 | **Cập nhật ảnh đại diện** | `POST` | `/api/nks/user/updateAvatar` | `access_token` trong Body | Chuyển đổi ảnh sang **Base64** trước khi gửi |
| 6 | **Cập nhật CCCD** | `POST` | `/api/nks/user/updateCccd` | `access_token` trong Body | Lưu số Căn cước công dân |

---

## 2. Các thay đổi và nâng cấp mã nguồn đã thực hiện

### A. Cơ sở dữ liệu & Model cục bộ
* **Bổ sung cột CCCD**: Tạo và chạy file migration để thêm cột `cccd` vào bảng `users` cục bộ.
* **Cập nhật Model User**: Khai báo cột `cccd` trong mảng `$fillable` để đồng bộ dữ liệu an toàn.

### B. Lớp dịch vụ API ([CompanyApiService.php](file:///e:/web%20Thu%C3%AA%20xe/app/Services/CompanyApiService.php))
* Thiết lập cổng kết nối Http Client tập trung của Laravel.
* Tích hợp cơ chế **Đăng nhập bằng API**.
* Tự động đính kèm tham số `access_token` trực tiếp vào thân (Body Payload) của request thay vì đính kèm qua Header (do API công ty yêu cầu nhận token qua Body).
* Mã hóa tệp ảnh đại diện (avatar) thành chuỗi dữ liệu **Base64 Data URI** trước khi gửi lên API cập nhật ảnh đại diện.
* Thiết lập hệ thống ghi log chi tiết mọi request và response của API vào `storage/logs/laravel.log` phục vụ mục đích gỡ lỗi và giám sát.

### C. Giao diện Đăng nhập Đồng nhất & Thông minh ([MemberLogin.php](file:///e:/web%20Thu%C3%AA%20xe/app/Filament/Pages/Auth/MemberLogin.php))
* **Gộp chung trang đăng nhập**: Cấu hình cả Admin Panel và Member Panel sử dụng chung một trang đăng nhập duy nhất. Xoá bỏ file đăng nhập admin riêng biệt.
* **Cơ chế Dự phòng Cục bộ (Local Fallback)**:
  * Khi nhập tài khoản, hệ thống ưu tiên gọi API của công ty trước.
  * Nếu API công ty báo tài khoản không tồn tại, hệ thống tự động kiểm tra tài khoản trong database nội bộ (giúp tài khoản `admin@nks.vn` hoặc các tài khoản hạt giống/test cục bộ đăng nhập bình thường).
* **Điều hướng thông minh**:
  * Khi đăng nhập thành công, người dùng (cả Admin và Member) sẽ được chuyển hướng thẳng về **Trang chủ (`/`)** thay vì đưa vào trang dashboard như mặc định của Filament.
  * Việc điều hướng quyền hạn giữa trang quản trị `/admin` và trang thành viên `/member` được kiểm soát an toàn bởi hệ thống Middleware điều hướng tự động dựa trên vai trò (`role`).

### D. Tích hợp Trang Hồ sơ cá nhân ([Profile.php](file:///e:/web%20Thu%C3%AA%20xe/app/Filament/Member/Pages/Profile.php))
* Bổ sung trường nhập số CCCD, Ảnh đại diện, Mật khẩu cũ/mới trên form của thành viên.
* Khi người dùng bấm **Lưu thay đổi**, hệ thống sẽ thực hiện tuần tự:
  1. Gọi API cập nhật thông tin cá nhân lên hệ thống `account.nks.vn`.
  2. Nếu đổi CCCD, gọi tiếp API cập nhật CCCD.
  3. Nếu đổi mật khẩu, gọi tiếp API cập nhật mật khẩu.
  4. Nếu tải lên ảnh đại diện mới, tự động convert sang Base64 rồi gọi API tải ảnh.
  5. Sau khi các API phản hồi thành công, tiến hành lưu các thông tin đồng bộ này xuống database cục bộ của website.

---

## 3. Công cụ kiểm thử trực tiếp trong VS Code ([api_tests.http](file:///e:/web%20Thu%C3%AA%20xe/api_tests.http))
Tôi đã tạo sẵn file cấu hình HTTP Request tại thư mục gốc của dự án. Để sử dụng:
1. Cài đặt tiện ích mở rộng (Extension) **REST Client** (tác giả *Huachao Mao*) trên VS Code.
2. Mở file `api_tests.http`.
3. Nhấp vào nút **`Send Request`** ngay phía trên mỗi API để gọi thử trực tiếp, kiểm tra tốc độ phản hồi và dữ liệu trả về từ server `account.nks.vn`.
