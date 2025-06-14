# Makefile cho dự án Hang The Thao
# Sử dụng: make <command>

.PHONY: help install serve build test fresh cache-clear

# Hiển thị help
help:
	@echo "📖 Các lệnh có sẵn:"
	@echo "  make install     - Cài đặt dự án"
	@echo "  make serve       - Chạy development server"
	@echo "  make build       - Build assets cho production"
	@echo "  make test        - Chạy tests"
	@echo "  make fresh       - Reset database với dữ liệu mẫu"
	@echo "  make cache-clear - Xóa tất cả cache"
	@echo "  make optimize    - Optimize cho production"

# Cài đặt dự án
install:
	@echo "📦 Cài đặt dependencies..."
	composer install
	npm install
	@if [ ! -f .env ]; then cp .env.example .env; fi
	@if [ ! -f database/database.sqlite ]; then touch database/database.sqlite; fi
	php artisan key:generate
	php artisan migrate --seed
	php artisan storage:link
	npm run build
	@echo "✅ Cài đặt hoàn tất!"

# Chạy development server
serve:
	@echo "🚀 Khởi động server..."
	php artisan serve

# Build assets
build:
	@echo "🎨 Building assets..."
	npm run build

# Build assets cho development
dev:
	@echo "🎨 Building assets for development..."
	npm run dev

# Chạy tests
test:
	@echo "🧪 Chạy tests..."
	php artisan test

# Reset database
fresh:
	@echo "🗄️  Reset database..."
	php artisan migrate:fresh --seed
	@echo "✅ Database đã được reset!"

# Xóa cache
cache-clear:
	@echo "🧹 Xóa cache..."
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	@echo "✅ Cache đã được xóa!"

# Optimize cho production
optimize:
	@echo "⚡ Optimize cho production..."
	composer install --no-dev --optimize-autoloader
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
	npm run build
	@echo "✅ Optimization hoàn tất!"

# Khởi động lại development
restart: cache-clear serve

# Backup database
backup:
	@echo "💾 Backup database..."
	@mkdir -p backups
	@cp database/database.sqlite backups/database_$(shell date +%Y%m%d_%H%M%S).sqlite
	@echo "✅ Database đã được backup!"

# Cập nhật dependencies
update:
	@echo "📦 Cập nhật dependencies..."
	composer update
	npm update
	@echo "✅ Dependencies đã được cập nhật!"
