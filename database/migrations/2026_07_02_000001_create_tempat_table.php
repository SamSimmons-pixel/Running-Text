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
        Schema::create('tempat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi_alamat')->nullable();
            $table->string('author')->nullable();
            $table->string('last_modified_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tempat');
        Schema::table('tempat', function (Blueprint $table) {
            $table->dropColumn('deskripsi_alamat');
        });
    }
};
