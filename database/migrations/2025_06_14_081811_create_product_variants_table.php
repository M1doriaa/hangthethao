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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('size')->nullable(); // S, M, L, XL, XXL
            $table->string('color')->nullable(); // Đỏ, Xanh, Vàng, etc.
            $table->string('color_code')->nullable(); // #FF0000, #0000FF, etc.
            $table->decimal('price', 10, 2); // Giá cho variant này
            $table->decimal('sale_price', 10, 2)->nullable(); // Giá sale
            $table->integer('stock_quantity')->default(0); // Số lượng tồn kho
            $table->string('sku')->unique()->nullable(); // Mã SKU riêng cho variant
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index for better performance
            $table->index(['product_id', 'size', 'color']);
            $table->index(['is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
