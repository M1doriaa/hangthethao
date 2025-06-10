# Hang The Thao - Website Thể Thao

Dự án website thương mại điện tử chuyên về các sản phẩm thể thao, được xây dựng bằng Laravel 12 với giao diện hiện đại và thân thiện người dùng.

## 🌟 Tính năng chính

### 🏠 Trang chủ
- Hero section với thiết kế gradient đẹp mắt
- Hiển thị danh mục sản phẩm (Đồ bóng đá, Phụ kiện, Đồ bóng rổ)
- Danh sách sản phẩm nổi bật với carousel
- Section sản phẩm phụ kiện và áo CLB
- Footer với thông tin liên hệ và social links

### 📱 Trang chi tiết sản phẩm
- Gallery ảnh với thumbnail và zoom
- Thông tin chi tiết sản phẩm (tên, giá, đánh giá, mô tả)
- Lựa chọn kích thước và màu sắc
- Điều chỉnh số lượng sản phẩm
- Nút thêm vào giỏ hàng và mua ngay
- Danh sách sản phẩm liên quan
- Breadcrumb navigation

### 🎨 Thiết kế UI/UX
- Màu sắc chủ đạo: Đỏ (#C41E3A) và Trắng
- Responsive design cho mọi thiết bị
- Animations và hover effects mượt mà
- Typography tối ưu cho tiếng Việt
- Loading states và feedback người dùng

## 🛠️ Công nghệ sử dụng

- **Backend:** Laravel 12
- **Frontend:** Bootstrap 5.3.3, jQuery, Font Awesome
- **Database:** SQLite (có thể chuyển sang MySQL/PostgreSQL)
- **Styling:** CSS3 với CSS Variables
- **JavaScript:** ES6+ với modules

## 📦 Cài đặt

### Yêu cầu hệ thống
- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite hoặc MySQL

### Các bước cài đặt

1. **Clone dự án và cài đặt dependencies:**
   ```bash
   git clone <repository-url>
   cd hangthethao
   composer install
   npm install
   ```

2. **Cấu hình môi trường:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Chạy migrations:**
   ```bash
   php artisan migrate
   ```

4. **Khởi động development server:**
   ```bash
   php artisan serve
   ```

5. **Truy cập website:**
   Mở trình duyệt và truy cập: `http://localhost:8000`

## 📁 Cấu trúc thư mục

```
hangthethao/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php      # Controller trang chủ
│   │   └── ProductController.php   # Controller chi tiết sản phẩm
├── public/
│   ├── css/
│   │   └── app.css                 # Custom CSS
│   └── js/
│       └── product-detail.js       # JavaScript cho trang sản phẩm
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php       # Layout chính
│       ├── products/
│       │   └── show.blade.php      # Trang chi tiết sản phẩm
│       └── home.blade.php          # Trang chủ
└── routes/
    └── web.php                     # Định nghĩa routes
```

## 🎯 Routes chính

- `GET /` - Trang chủ
- `GET /product/{id}` - Chi tiết sản phẩm

## 🎨 Customization

### Thay đổi màu sắc
Chỉnh sửa CSS variables trong `public/css/app.css`:
```css
:root {
    --primary-red: #C41E3A;
    --secondary-red: #A01729;
    --light-gray: #f8f9fa;
}
```

### Thêm sản phẩm mới
Chỉnh sửa dữ liệu mẫu trong `ProductController.php` hoặc tạo database models.

## 🚀 Tính năng nâng cao

### JavaScript Features
- **Image Gallery:** Zoom, thumbnail navigation
- **Product Options:** Size/color selection với validation
- **Cart System:** LocalStorage-based shopping cart
- **Notifications:** Toast notifications cho actions
- **Animations:** Smooth transitions và loading states
- **Accessibility:** ARIA labels, keyboard navigation

### CSS Features
- **Responsive Design:** Mobile-first approach
- **CSS Grid & Flexbox:** Modern layout techniques
- **Custom Animations:** Keyframe animations
- **Component System:** Modular CSS classes

## 📱 Responsive Breakpoints

- **Mobile:** < 576px
- **Tablet:** 576px - 768px
- **Desktop:** 768px - 1200px
- **Large Desktop:** > 1200px

## 🔧 Development

### Chạy trong môi trường development:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Build assets (nếu sử dụng Vite):
```bash
npm run dev          # Development
npm run build        # Production
```

## 📝 TODO List

- [ ] Thêm authentication system
- [ ] Tạo admin panel
- [ ] Tích hợp payment gateway
- [ ] Thêm shopping cart page
- [ ] Tạo checkout process
- [ ] Thêm order management
- [ ] SEO optimization
- [ ] Performance optimization

## 🤝 Đóng góp

1. Fork dự án
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## 📄 License

Dự án này được phân phối dưới MIT License. Xem file `LICENSE` để biết thêm chi tiết.

## 📞 Liên hệ

- **Website:** [Hang The Thao](http://localhost:8000)
- **Email:** hangthethao@gmail.com
- **Phone:** 0924 85 03 503
- **Facebook:** Fb.com/hangthethao48

---

*Được phát triển với ❤️ bởi Hang The Thao Team*

## 🛒 Cart System (NEW!)

### Hoàn thành 100% 
- ✅ **Modern Shopping Cart** với popup notifications
- ✅ **Product fly animations** khi thêm vào giỏ
- ✅ **Real-time cart updates** không reload trang
- ✅ **Session-based storage** với Laravel backend
- ✅ **Responsive design** cho mobile/desktop
- ✅ **AJAX API** với CSRF protection

### Quick Demo
1. Vào `/test-cart` để test các chức năng
2. Hoặc vào product detail và thử thêm sản phẩm
3. Mở Developer Console chạy: `demoAddToCart()`

📚 **[Chi tiết đầy đủ →](CART-DOCUMENTATION.md)**
