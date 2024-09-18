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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->dateTime('clockInTime');
            $table->dateTime('clockOutTime')->nullable();
            $table->boolean('isOvertimeClockIn')->nullable();
            $table->boolean('isOvertimeClockOut')->nullable();
            $table->string('clockInPhoto');
            $table->string('clockOutPhoto')->nullable();
            $table->string('clockInLocation');
            $table->string('clockOutLocation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
