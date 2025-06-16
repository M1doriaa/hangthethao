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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // Mã tỉnh/thành phố
            $table->string('name'); // Tên tỉnh/thành phố
            $table->string('name_en')->nullable(); // Tên tiếng Anh
            $table->string('full_name'); // Tên đầy đủ
            $table->string('full_name_en')->nullable(); // Tên đầy đủ tiếng Anh  
            $table->string('code_name'); // Tên viết tắt (slug)
            $table->integer('administrative_unit_id')->nullable(); // ID đơn vị hành chính
            $table->integer('administrative_region_id')->nullable(); // ID vùng hành chính
            $table->timestamps();
            
            $table->index(['code', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces');
    }
};
