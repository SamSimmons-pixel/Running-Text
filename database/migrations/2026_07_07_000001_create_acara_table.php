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
        Schema::create('acara', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('hari');         // e.g. Senin, Selasa, etc.
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('narasumber');   // denormalized name from narasumber table
            $table->string('tempat');       // denormalized name from tempat table
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acara');
    }
};
