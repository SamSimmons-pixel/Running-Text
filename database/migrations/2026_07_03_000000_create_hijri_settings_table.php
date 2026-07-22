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
        Schema::create('hijri_settings', function (Blueprint $table) {
            $table->id();
            $table->string('default_city')->default('Jakarta');
            $table->string('default_timezone')->default('Asia/Jakarta');
            $table->integer('hijri_offset_days')->default(0);
            $table->string('prayer_time_provider')->default('aladhan');
            $table->boolean('show_masehi_suffix')->default(false);
            $table->boolean('show_hijri_suffix')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hijri_settings', function (Blueprint $table) {
            $table->dropColumn(['show_masehi_suffix', 'show_hijri_suffix']);
        });
    }
};
