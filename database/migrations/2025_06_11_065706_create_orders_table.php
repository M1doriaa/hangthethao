<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Mã đơn hàng
            $table->string('customer_name'); // Tên khách hàng
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address'); // Địa chỉ giao hàng
            $table->string('city'); // Tỉnh/Thành phố
            $table->string('district'); // Quận/Huyện
            $table->string('ward'); // Phường/Xã
            $table->enum('payment_method', ['cod', 'bank_transfer', 'momo'])->default('cod');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'shipping', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 10, 2); // Tạm tính
            $table->decimal('shipping_fee', 10, 2)->default(0); // Phí vận chuyển
            $table->decimal('tax', 10, 2)->default(0); // Thuế
            $table->decimal('total', 10, 2); // Tổng cộng
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
