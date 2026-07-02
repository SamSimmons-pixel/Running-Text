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
        Schema::create('kajian', function (Blueprint $table) {
            $table->id();
            $table->DATETIME('Tanggal');
            $table->string('Judul');
            $table->string('Narasumber');
            $table->string('Tempat');
            $table->string('Kontak');
            $table->boolean('Tampilkan')->default(false);
            $table->string('Logo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kajian', function (Blueprint $table) {
        });
    }
};
