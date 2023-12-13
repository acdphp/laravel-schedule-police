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
        Schema::create('stopped_scheduled_events', function (Blueprint $table) {
            $table->id();
            $table->json('key');
            $table->timestamp('created_at');
            $table->string('by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stopped_scheduled_events');
    }
};
