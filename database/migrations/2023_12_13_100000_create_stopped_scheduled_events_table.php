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
        Schema::create('stopped_scheduled_events', static function (Blueprint $table) {
            $table->id();
            $table->string('key', 500);
            $table->string('expression', 50);
            $table->timestamps();
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
