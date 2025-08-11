<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->morphs('eventable');
            $table->string('title');
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->boolean('all_day')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};