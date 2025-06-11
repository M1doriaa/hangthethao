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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Tên sản phẩm tại thời điểm đặt hàng
            $table->string('product_image')->nullable(); // Ảnh sản phẩm
            $table->string('size')->nullable(); // Kích thước
            $table->string('color')->nullable(); // Màu sắc
            $table->decimal('price', 10, 2); // Giá tại thời điểm đặt hàng
            $table->integer('quantity'); // Số lượng
            $table->decimal('total', 10, 2); // Tổng tiền (price * quantity)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
