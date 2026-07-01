<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\running_text_data;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'password' => 'Admin123',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'User',
            'password' => 'User123',
            'role' => 'user',
        ]);

        running_text_data::create([
            'Tanggal' => '2022-01-01',
            'Judul' => 'Judul',
            'Narasumber' => 'Narasumber',
            'Tempat' => 'Tempat',
            'Kontak' => 'Kontak',
            'Tampilkan' => true,
            'Logo' => 'Logo.png (ini masih dummy)'
        ]);
    }
}
