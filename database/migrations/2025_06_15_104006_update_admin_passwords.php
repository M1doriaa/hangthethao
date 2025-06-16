<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update admin password
        User::where('email', 'admin@hangthethao.com')->update([
            'password' => Hash::make('1')
        ]);
        
        // Update test user password
        User::where('email', 'user@hangthethao.com')->update([
            'password' => Hash::make('1')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally restore old passwords, but we'll leave empty for now
    }
};
