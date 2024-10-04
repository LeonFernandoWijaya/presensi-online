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
        Schema::create('holiday_days', function (Blueprint $table) {
            $table->id();
            $table->string('holiday_name');
            $table->foreignId('holiday_id')->constrained('holidays')->onDelete('cascade');
            $table->date('holiday_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holiday_days');
    }
};
