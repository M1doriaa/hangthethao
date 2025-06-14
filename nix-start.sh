#!/usr/bin/env bash

# Script khởi động nhanh cho Nix
# Chạy: ./nix-start.sh

echo "🚀 Khởi động Hang The Thao với Nix..."

# Kiểm tra file dev.nix
if [ ! -f dev.nix ]; then
    echo "❌ File dev.nix không tồn tại!"
    exit 1
fi

# Kiểm tra .env
if [ ! -f .env ]; then
    echo "⚠️  File .env chưa tồn tại. Chạy install-nix.sh trước."
    read -p "Bạn có muốn chạy cài đặt không? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        chmod +x install-nix.sh
        ./install-nix.sh
    else
        exit 1
    fi
fi

echo "🌟 Vào Nix development environment và khởi động server..."

# Vào Nix environment và chạy server
nix develop -c bash << 'EOF'
echo "🔥 Starting Laravel development server..."
echo "🌐 Server sẽ chạy tại: http://localhost:8000"
echo "🔧 Admin panel: http://localhost:8000/admin"
echo ""
echo "📋 Tài khoản mặc định:"
echo "   Admin: admin@hangthethao.com / password"
echo ""
echo "Press Ctrl+C để dừng server"
echo ""

php artisan serve
EOF
