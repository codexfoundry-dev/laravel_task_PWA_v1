<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['todo', 'doing', 'done'])->default('todo');
            $table->enum('priority', ['low', 'med', 'high'])->default('med');
            $table->timestamp('due_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->unsignedInteger('estimated_minutes')->default(0);
            $table->unsignedInteger('actual_minutes')->default(0);
            $table->foreignId('assignee_id')->nullable()->constrained('users');
            $table->json('tags')->nullable();
            $table->json('reminders')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->text('recurrence_rule')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};