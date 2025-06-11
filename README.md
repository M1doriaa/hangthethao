# 🏆 Hang Thể Thao - E-commerce Website

> Modern sports e-commerce platform built with Laravel 12, featuring complete order management system and shopping cart functionality.

## 🌟 Key Features

### 🛒 **Complete E-commerce System**
- ✅ **Shopping Cart** with session-based storage
- ✅ **Order Management System** for admin  
- ✅ **Product Management** with categories
- ✅ **Checkout Process** with multiple payment options
- ✅ **Inventory Management** with stock tracking

### 🎯 **Product Categories**
- **Áo CLB** - Club jerseys and team uniforms
- **Áo Đội Tuyển** - National team jerseys
- **Phụ Kiện** - Sports accessories and equipment

### 👨‍💼 **Admin Panel**
- Dashboard with comprehensive statistics
- Order status management workflow
- Product CRUD operations
- Real-time order tracking
- Revenue analytics and reporting

## 🛠️ Technology Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Bootstrap 5.3, JavaScript ES6+
- **Database:** SQLite/MySQL with Eloquent ORM
- **UI/UX:** Red/White theme (#C41E3A)
- **Features:** AJAX, CSRF protection, responsive design

## 🚀 Quick Start

### Prerequisites
- PHP >= 8.2
- Composer
- SQLite or MySQL

### Installation

1. **Clone and install dependencies:**
   ```bash
   git clone https://github.com/M1doriaa/hangthethao.git
   cd hangthethao
   composer install
   ```

2. **Environment setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database setup:**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Start development server:**
   ```bash
   php artisan serve
   ```

5. **Access the application:**
   - **Frontend:** http://localhost:8000
   - **Admin Panel:** http://localhost:8000/admin

## 📱 Demo Features

### Customer Experience
- Browse products by category (Áo CLB, Áo Đội Tuyển, Phụ Kiện)
- Add items to cart with size/color options
- Complete checkout process with shipping info
- Receive order confirmation with details

### Admin Experience  
- View dashboard with order statistics
- Manage orders through status workflow
- Track inventory and low stock alerts
- Generate revenue reports

## 🎯 Order Management Workflow

```
Pending → Confirmed → Processing → Shipping → Delivered
   ↓         ↓           ↓           ↓
Cancelled  Cancelled   Cancelled   Cancelled
```

## 📊 Database Schema

### Key Tables
- **Orders:** Customer info, shipping address, payment method, status
- **Order Items:** Product details, quantity, pricing at order time
- **Products:** Catalog with categories, pricing, inventory
- **Categories:** Fixed categories (Áo CLB, Áo Đội Tuyển, Phụ Kiện)

## 🔐 Security Features

- CSRF protection on all forms
- Input validation and sanitization
- Database transaction safety
- Session-based authentication
- SQL injection protection

## 📁 Project Structure

```
app/Http/Controllers/
├── Admin/              # Admin panel controllers
├── CartController      # Shopping cart logic  
├── CheckoutController  # Order processing
└── CategoryController  # Product categories

app/Models/
├── Order              # Order management
├── OrderItem          # Order line items
└── Product            # Product catalog

resources/views/
├── admin/             # Admin panel views
├── cart/              # Shopping cart pages
├── checkout/          # Checkout process
└── categories/        # Product listings
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 📞 Contact

- **Website:** [Hang Thể Thao](https://hangthethao.com)
- **Email:** hangthethao@gmail.com
- **Phone:** 0924 85 03 503

---

*Built with ❤️ for Vietnamese sports enthusiasts*
