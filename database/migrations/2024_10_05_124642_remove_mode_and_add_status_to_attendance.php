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
            //
            $table->dropColumn('clockInMode');
            $table->dropColumn('clockOutMode');
            $table->foreignId('clockInStatusId')->nullable()->constrained('statuses')->onDelete('set null');
            $table->foreignId('clockOutStatusId')->nullable()->constrained('statuses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
            $table->string('clockInMode');
            $table->string('clockOutMode');
            $table->dropForeign(['clockInStatusId']);
            $table->dropForeign(['clockOutStatusId']);
        });
    }
};
