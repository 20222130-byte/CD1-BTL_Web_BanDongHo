# 🕐 Website Bán Đồng Hồ (Watch Shop)

Ứng dụng web quản lý cửa hàng bán đồng hồ trực tuyến được xây dựng bằng **Laravel 11**. Cung cấp trải nghiệm mua sắm hoàn chỉnh cho khách hàng và công cụ quản lý hiệu quả cho quản trị viên.

---

## 📋 Tính Năng

### 👤 Cho Người Dùng Thông Thường
- 🔍 **Duyệt sản phẩm**: Xem danh sách đồng hồ theo danh mục
- 🔎 **Tìm kiếm & lọc**: Tìm kiếm theo tên, danh mục, khoảng giá
- 📄 **Chi tiết sản phẩm**: Xem mô tả, hình ảnh, giá, đánh giá
- 🛒 **Giỏ hàng**: Quản lý sản phẩm (thêm, xoá, cập nhật số lượng)
- 💳 **Thanh toán**: Quy trình checkout đơn giản và an toàn
- 👤 **Quản lý tài khoản**: Đăng ký, đăng nhập, sửa hồ sơ cá nhân
- 📦 **Lịch sử đơn hàng**: Xem chi tiết, trạng thái các đơn hàng đã mua
- 💰 **Theo dõi thanh toán**: Xem trạng thái thanh toán các đơn hàng

### 🔧 Cho Quản Trị Viên
- ⚙️ **Quản lý sản phẩm**: Thêm, chỉnh sửa, xoá sản phẩm
- 📂 **Quản lý danh mục**: Tạo và tổ chức danh mục sản phẩm
- 📋 **Quản lý đơn hàng**: Xem, cập nhật trạng thái, hủy đơn hàng
- 💵 **Quản lý thanh toán**: Theo dõi, xác nhận giao dịch
- 📊 **Dashboard thống kê**: Biểu đồ doanh số, đơn hàng, khách hàng
- 📈 **Báo cáo**: Xuất báo cáo bán hàng, doanh thu theo thời kỳ
- 👥 **Quản lý người dùng**: Xem, khoá/mở khoá tài khoản khách hàng

---

## 🛠️ Công Nghệ Sử Dụng

| Công Nghệ | Phiên Bản | Mục Đích |
|-----------|-----------|---------|
| **Laravel** | 11 | Framework backend |
| **PHP** | 8.0+ | Ngôn ngữ lập trình |
| **MySQL** | 5.7+ | Database |
| **Blade** | - | Template engine |
| **Vite** | - | Asset bundler |
| **JavaScript** | ES6+ | Frontend interactivity |
| **Composer** | - | PHP package manager |
| **NPM** | - | Node package manager |
| **PHPUnit** | - | Testing framework |

---

## 📁 Cấu Trúc Dự Án

```
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Xử lý logic request/response
│   │   └── Middleware/           # Middleware xác thực, phân quyền
│   ├── Models/
│   │   ├── User.php             # Model người dùng
│   │   ├── Product.php          # Model sản phẩm
│   │   ├── Category.php         # Model danh mục
│   │   ├── Order.php            # Model đơn hàng
│   │   ├── Payment.php          # Model thanh toán
│   │   └── ...
│   └── Providers/               # Service providers
├── database/
│   ├── migrations/              # Schema database
│   └── seeders/                 # Dữ liệu khởi tạo
├── resources/
│   ├── views/                   # Blade templates
│   ├── css/                     # Stylesheets
│   └── js/                      # JavaScript
├── routes/
│   └── web.php                  # Web routes
├── public/
│   └── images/                  # Hình ảnh sản phẩm
├── storage/                     # File lưu trữ
├── config/                      # Configuration
└── tests/                       # Unit & Feature tests
```

---

## 💾 Cơ Sở Dữ Liệu

### Các Bảng Chính

| Bảng | Mô Tả |
|------|-------|
| `users` | Thông tin người dùng, tài khoản |
| `categories` | Danh mục sản phẩm (VIP, Thể Thao, Kinh Doanh...) |
| `products` | Chi tiết đồng hồ (tên, giá, hình ảnh, mô tả) |
| `orders` | Đơn hàng (ID, ngày tạo, trạng thái, tổng tiền) |
| `order_details` | Chi tiết sản phẩm trong từng đơn hàng |
| `cart` | Giỏ hàng người dùng |
| `cart_items` | Các item trong giỏ hàng |
| `payments` | Thông tin thanh toán (phương thức, trạng thái) |
| `reports` | Báo cáo bán hàng |

---

## 🚀 Cài Đặt & Chạy Ứng Dụng

### Yêu Cầu Hệ Thống
- **PHP** 8.0 trở lên
- **Composer** (PHP dependency manager)
- **Node.js** & **NPM** (JavaScript dependencies)
- **MySQL** 5.7+ hoặc **MariaDB**
- **XAMPP** hoặc máy chủ localhost

### 📖 Hướng Dẫn Cài Đặt

#### 1️⃣ Clone hoặc tải dự án
```bash
cd d:\xampp\htdocs\ChuyenDe1\CD1-BTL_Web_BanDongHo
```

#### 2️⃣ Cài đặt PHP dependencies
```bash
composer install
```

#### 3️⃣ Cài đặt Node.js dependencies
```bash
npm install
```

#### 4️⃣ Tạo file .env
```bash
cp .env.example .env
```
Hoặc tạo file `.env` từ nội dung của `.env.example`

#### 5️⃣ Tạo Application Key
```bash
php artisan key:generate
```

#### 6️⃣ Cấu hình Database
Mở file `.env` và cập nhật:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=watch_shop
DB_USERNAME=root
DB_PASSWORD=
```

#### 7️⃣ Chạy Database Migrations
```bash
php artisan migrate
```

#### 8️⃣ Seed dữ liệu mẫu (tùy chọn)
```bash
php artisan db:seed
```

#### 9️⃣ Build Frontend Assets
```bash
npm run build
```

#### 🔟 Khởi động Development Server
```bash
php artisan serve
```

#### 1️⃣1️⃣ Khởi động Vite Dev Server (tuỳ chọn)
Mở terminal khác chạy:
```bash
npm run dev
```

✅ Truy cập ứng dụng tại: **http://localhost:8000**

---

## 🔐 Tài Khoản Mặc Định

Sau khi chạy `php artisan db:seed`, bạn có thể đăng nhập:

**Tài khoản Admin:**
- Email: `admin@example.com`
- Password: `password`

**Tài khoản Người Dùng (nếu có):**
- Email: `user@example.com`
- Password: `password`

---

## 📚 Hướng Dẫn Sử Dụng

### Cho Người Dùng Thông Thường
1. Truy cập trang chủ
2. Duyệt danh mục hoặc sử dụng tìm kiếm
3. Xem chi tiết sản phẩm quan tâm
4. Thêm sản phẩm vào giỏ hàng
5. Xem giỏ hàng và điều chỉnh số lượng
6. Tiến hành thanh toán
7. Xem lịch sử đơn hàng trong phần "My Orders"

### Cho Quản Trị Viên
1. Đăng nhập với tài khoản admin
2. Vào **Admin Dashboard** từ menu
3. **Quản lý Sản phẩm**: Thêm/sửa/xoá đồng hồ
4. **Quản lý Danh mục**: Tổ chức danh mục sản phẩm
5. **Quản lý Đơn hàng**: Xem và cập nhật trạng thái
6. **Quản lý Thanh toán**: Xác nhận giao dịch
7. **Xem Báo cáo**: Phân tích doanh số và doanh thu

---

## 🔌 Routes Chính

Các route chính được định nghĩa trong [routes/web.php](routes/web.php):

- `/` - Trang chủ
- `/products` - Danh sách sản phẩm
- `/products/{id}` - Chi tiết sản phẩm
- `/cart` - Giỏ hàng
- `/checkout` - Thanh toán
- `/login` - Đăng nhập
- `/register` - Đăng ký
- `/admin/dashboard` - Dashboard quản trị viên
- `/admin/products` - Quản lý sản phẩm
- `/admin/orders` - Quản lý đơn hàng

---

## 🐛 Troubleshooting

| Vấn Đề | Giải Pháp |
|--------|----------|
| **Lỗi "Application key" không được set** | Chạy `php artisan key:generate` |
| **Lỗi database connection** | Kiểm tra cấu hình `.env` và MySQL server đang chạy |
| **Assets không load** | Chạy `npm run build` hoặc `npm run dev` |
| **Permission denied** | Kiểm tra quyền thư mục `storage` và `bootstrap/cache` |
| **Lỗi 404 khi truy cập route** | Kiểm tra file `routes/web.php` |

---

---

## 📄 License

Dự án này được tạo cho mục đích **học tập và nghiên cứu**.
