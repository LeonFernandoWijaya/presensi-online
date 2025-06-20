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
            $table->foreignId('activity_category_id')->nullable()->constrained('activity_categories')->onDelete('set null');
            $table->foreignId('activity_type_id')->nullable()->constrained('activity_types')->onDelete('set null');
            $table->string('customer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
            $table->dropForeign(['activity_category_id']);
            $table->dropColumn('activity_category_id');
            $table->dropForeign(['activity_type_id']);
            $table->dropColumn('activity_type_id');
            $table->dropColumn('customer');
        });
    }
};
