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
            $table->string('WaktuSelesai')->nullable();
            $table->string('Judul');
            $table->string('Narasumber');
            $table->string('Tempat');
            $table->string('Kontak');
            $table->longText('Informasi')->nullable();
            $table->boolean('Tampilkan')->default(false);
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
        Schema::table('kajian', function (Blueprint $table) {
            $table->dropColumn('Informasi');
            $table->dropColumn('WaktuSelesai');
            $table->dropColumn(['author', 'last_modified_by']);
        });
    }
};
