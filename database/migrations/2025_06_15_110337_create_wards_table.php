<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Mã phường/xã
            $table->string('name'); // Tên phường/xã
            $table->string('name_en')->nullable(); // Tên tiếng Anh
            $table->string('full_name'); // Tên đầy đủ
            $table->string('full_name_en')->nullable(); // Tên đầy đủ tiếng Anh
            $table->string('code_name'); // Tên viết tắt (slug)
            $table->string('district_code', 20); // Mã quận/huyện
            $table->integer('administrative_unit_id')->nullable(); // ID đơn vị hành chính
            $table->timestamps();
            
            $table->index(['code', 'name', 'district_code']);
            $table->foreign('district_code')->references('code')->on('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wards');
    }
};
