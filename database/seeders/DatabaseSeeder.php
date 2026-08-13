<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kajian;
use App\Models\Narasumber;
use App\Models\Tempat;
use App\Models\Kontak;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Skip seeding if data already exists (idempotent — safe to run multiple times)
        if (User::count() > 0) {
            echo "  Database already seeded, skipping.\n";
            return;
        }

        User::create([
            'name'     => 'Admin',
            'password' => 'Admin123',
            'role'     => 'admin_operator',
        ]);

        User::create([
            'name'     => 'User',
            'password' => 'User123',
            'role'     => 'operator',
        ]);

        $narasumber = Narasumber::create([
            'nama' => 'Narasumber',
        ]);

        $tempat = Tempat::create([
            'nama' => 'Tempat',
        ]);

        $kontak = Kontak::create([
            'nama' => 'Abu Ahmad',
            'nomor_kontak' => '0812-3456-7890',
        ]);

        Kajian::create([
            'Tanggal'       => '2022-01-01',
            'Judul'         => 'Judul',
            'narasumber_id' => $narasumber->id,
            'tempat_id'     => $tempat->id,
            'kontak_id'     => $kontak->id,
            'Tampilkan'     => true,
        ]);
    }
}
