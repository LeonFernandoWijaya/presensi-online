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
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('clockInMode');
            $table->dropColumn('clockOutMode');
            $table->boolean('isClockInAtOffice')->nullable()->default(null);
            $table->boolean('isClockOutAtOffice')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('clockInMode');
            $table->string('clockOutMode');
            $table->dropColumn('isClockInAtOffice');
            $table->dropColumn('isClockOutAtOffice');
        });
    }
};
