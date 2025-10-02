<?php
// database/migrations/xxxx_xx_xx_create_subtasks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->text('text');
            $table->boolean('completed')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
