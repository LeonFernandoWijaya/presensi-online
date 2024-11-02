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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign(['shift_id']);
            // Drop the column
            $table->dropColumn('shift_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('shift_id')->nullable();
            // Add the foreign key constraint back
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
        });
    }
};
