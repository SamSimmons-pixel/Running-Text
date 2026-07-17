<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Step 1: Add nullable FK columns to kajian ──────────────────────
        Schema::table('kajian', function (Blueprint $table) {
            $table->unsignedBigInteger('narasumber_id')->nullable()->after('Judul');
            $table->unsignedBigInteger('tempat_id')->nullable()->after('narasumber_id');
            $table->unsignedBigInteger('kontak_id')->nullable()->after('tempat_id');
        });

        // ── Step 2: Migrate existing text data → IDs for kajian ────────────
        $kajianList = DB::table('kajian')->get();
        foreach ($kajianList as $k) {
            $updates = [];

            if (!empty($k->Narasumber)) {
                $nara = DB::table('narasumber')->where('nama', $k->Narasumber)->first();
                $updates['narasumber_id'] = $nara?->id;
            }
            if (!empty($k->Tempat)) {
                $temp = DB::table('tempat')->where('nama', $k->Tempat)->first();
                $updates['tempat_id'] = $temp?->id;
            }
            if (!empty($k->Kontak)) {
                // Kontak stored as nomor_kontak value
                $kon = DB::table('kontak')->where('nomor_kontak', $k->Kontak)->first();
                $updates['kontak_id'] = $kon?->id;
            }

            if (!empty($updates)) {
                DB::table('kajian')->where('id', $k->id)->update($updates);
            }
        }

        // ── Step 3: Drop old text columns from kajian ──────────────────────
        Schema::table('kajian', function (Blueprint $table) {
            $table->dropColumn(['Narasumber', 'Tempat', 'Kontak']);
        });

        // ── Step 4: Add nullable FK columns to acara ──────────────────────
        Schema::table('acara', function (Blueprint $table) {
            $table->unsignedBigInteger('narasumber_id')->nullable()->after('judul');
            $table->unsignedBigInteger('tempat_id')->nullable()->after('narasumber_id');
        });

        // ── Step 5: Migrate existing text data → IDs for acara ────────────
        $acaraList = DB::table('acara')->get();
        foreach ($acaraList as $a) {
            $updates = [];

            if (!empty($a->narasumber)) {
                $nara = DB::table('narasumber')->where('nama', $a->narasumber)->first();
                $updates['narasumber_id'] = $nara?->id;
            }
            if (!empty($a->tempat)) {
                $temp = DB::table('tempat')->where('nama', $a->tempat)->first();
                $updates['tempat_id'] = $temp?->id;
            }

            if (!empty($updates)) {
                DB::table('acara')->where('id', $a->id)->update($updates);
            }
        }

        // ── Step 6: Drop old text columns from acara ──────────────────────
        Schema::table('acara', function (Blueprint $table) {
            $table->dropColumn(['narasumber', 'tempat']);
        });

        // ── Step 7: Add FK constraints with SET NULL on delete ─────────────
        Schema::table('kajian', function (Blueprint $table) {
            $table->foreign('narasumber_id')->references('id')->on('narasumber')->nullOnDelete();
            $table->foreign('tempat_id')->references('id')->on('tempat')->nullOnDelete();
            $table->foreign('kontak_id')->references('id')->on('kontak')->nullOnDelete();
        });

        Schema::table('acara', function (Blueprint $table) {
            $table->foreign('narasumber_id')->references('id')->on('narasumber')->nullOnDelete();
            $table->foreign('tempat_id')->references('id')->on('tempat')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // Drop FK constraints
        Schema::table('acara', function (Blueprint $table) {
            $table->dropForeign(['narasumber_id']);
            $table->dropForeign(['tempat_id']);
            $table->dropColumn(['narasumber_id', 'tempat_id']);
            // Re-add text columns
            $table->string('narasumber')->default('');
            $table->string('tempat')->default('');
        });

        Schema::table('kajian', function (Blueprint $table) {
            $table->dropForeign(['narasumber_id']);
            $table->dropForeign(['tempat_id']);
            $table->dropForeign(['kontak_id']);
            $table->dropColumn(['narasumber_id', 'tempat_id', 'kontak_id']);
            // Re-add text columns
            $table->string('Narasumber')->default('');
            $table->string('Tempat')->default('');
            $table->string('Kontak')->default('');
        });
    }
};
