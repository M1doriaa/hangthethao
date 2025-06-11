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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->string('sku')->unique();
            $table->string('category');
            $table->string('brand')->nullable();
            $table->json('images')->nullable(); // Lưu nhiều ảnh dưới dạng JSON
            $table->json('sizes')->nullable(); // Lưu sizes dưới dạng JSON
            $table->json('colors')->nullable(); // Lưu colors dưới dạng JSON
            $table->integer('stock_quantity')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('reviews_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('active'); // active, inactive, out_of_stock
            $table->json('specifications')->nullable(); // Thông số kỹ thuật
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
            
            // Indexes
            $table->index('category');
            $table->index('brand');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
