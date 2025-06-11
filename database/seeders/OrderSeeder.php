<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy một số sản phẩm để tạo đơn hàng mẫu
        $products = Product::active()->limit(10)->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('Không có sản phẩm nào trong database. Hãy chạy ProductSeeder trước.');
            return;
        }        // Tạo 50 đơn hàng mẫu
        for ($i = 1; $i <= 50; $i++) {
            $orderDate = Carbon::now()->subDays(rand(0, 30));
            
            // Tạo order number unique cho từng ngày
            $prefix = 'HT';
            $dateStr = $orderDate->format('ymd');
            $existingOrders = Order::where('order_number', 'like', $prefix . $dateStr . '%')->count();
            $orderNumber = $prefix . $dateStr . str_pad($existingOrders + 1, 3, '0', STR_PAD_LEFT);
            
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => fake('vi_VN')->name(),
                'customer_email' => fake()->email(),
                'customer_phone' => '0' . rand(100000000, 999999999),
                'shipping_address' => fake('vi_VN')->streetAddress(),
                'city' => fake('vi_VN')->city(),
                'district' => 'Quận ' . rand(1, 12),
                'ward' => 'Phường ' . rand(1, 20),
                'payment_method' => fake()->randomElement(['cod', 'bank_transfer', 'momo']),
                'status' => fake()->randomElement(['pending', 'confirmed', 'processing', 'shipping', 'delivered', 'cancelled']),
                'subtotal' => 0,
                'shipping_fee' => 30000,
                'tax' => 0,
                'total' => 0,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Cập nhật timestamps dựa trên status
            switch ($order->status) {
                case 'confirmed':
                case 'processing':
                case 'shipping':
                case 'delivered':
                    $order->confirmed_at = $orderDate->copy()->addHours(rand(1, 4));
                    break;
            }

            switch ($order->status) {
                case 'shipping':
                case 'delivered':
                    $order->shipped_at = $orderDate->copy()->addHours(rand(6, 24));
                    break;
            }

            if ($order->status === 'delivered') {
                $order->delivered_at = $orderDate->copy()->addDays(rand(1, 5));
            }

            // Tạo 1-5 items cho mỗi đơn hàng
            $numItems = rand(1, 5);
            $subtotal = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $quantity = rand(1, 3);
                $price = $product->price;
                $total = $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_image' => $product->main_image,
                    'size' => fake()->randomElement(['S', 'M', 'L', 'XL', null]),
                    'color' => fake()->randomElement(['Đỏ', 'Xanh', 'Vàng', 'Trắng', 'Đen', null]),
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $total,
                ]);

                $subtotal += $total;
            }

            // Cập nhật tổng tiền đơn hàng
            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal + $order->shipping_fee + $order->tax,
            ]);

            $order->save();
        }

        $this->command->info('Đã tạo 50 đơn hàng mẫu thành công!');
    }
}
