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
        Schema::table('carts', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
            $table->index(['user_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'session_id']);
            $table->dropColumn('session_id');
        });
    }
};
