<?php
// database/migrations/xxxx_xx_xx_create_tasks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('deadline');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->enum('column', ['product-backlog', 'in-progress', 'pending', 'done', 'overdue'])
                ->default('product-backlog');
            $table->foreignId('bidang_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('completed_at')->nullable();
            $table->text('pending_reason')->nullable();
            $table->integer('order')->default(0); // untuk sorting
            $table->timestamps(); // ini otomatis bikin created_at & updated_at nullable
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
