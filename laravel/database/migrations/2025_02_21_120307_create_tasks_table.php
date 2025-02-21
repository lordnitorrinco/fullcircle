<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Task;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id(); // Primary key with auto-increment
            $table->string('title'); // Task title, required
            $table->text('description')->nullable(); // Task description, optional
            $table->enum(
                'status',
                [
                    Task::STATUS_PENDING,
                    Task::STATUS_IN_PROGRESS,
                    Task::STATUS_COMPLETED
                ]
            )->default(Task::STATUS_PENDING); // Task status with default value 'pending'
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
