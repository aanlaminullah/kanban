<?php
// database/migrations/xxxx_xx_xx_add_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar', 5)->nullable()->after('email'); // JD, AS, dll
            $table->string('color', 7)->default('#4299e1')->after('avatar');
            $table->enum('role', ['admin', 'manager', 'user'])->default('user')->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'color', 'role']);
        });
    }
};
