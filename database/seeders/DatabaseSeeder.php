<?php

namespace Database\Seeders;

use App\Models\CampusLocation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Data awal untuk LuckyStar Attendance System.
     */
    public function run(): void
    {
        // === Admin User ===
        User::create([
            'nip_nim' => 'ADMIN001',
            'name' => 'Administrator',
            'email' => 'admin@luckystar.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // === Dosen User ===
        User::create([
            'nip_nim' => '198501012010011001',
            'name' => 'Dr. Ahmad Fauzi',
            'email' => 'dosen@luckystar.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        // === Mahasiswa Users ===
        User::create([
            'nip_nim' => '2201020001',
            'name' => 'Budi Santoso',
            'email' => 'budi@mhs.unimal.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'nip_nim' => '2201020002',
            'name' => 'Siti Aminah',
            'email' => 'siti@mhs.unimal.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        User::create([
            'nip_nim' => '2201020003',
            'name' => 'Rizki Pratama',
            'email' => 'rizki@mhs.unimal.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        // === Lokasi Kampus (Universitas Malikussaleh - Kampus Reuleut) ===
        CampusLocation::create([
            'name_location' => 'Kampus Utama Unimal - Reuleut',
            'latitude' => 5.18090000,
            'longitude' => 97.14840000,
            'radius_tolerance' => 100, // 100 meter
            'is_active' => true,
        ]);

        CampusLocation::create([
            'name_location' => 'Gedung Teknik Informatika',
            'latitude' => 5.18120000,
            'longitude' => 97.14900000,
            'radius_tolerance' => 50, // 50 meter
            'is_active' => false,
        ]);
    }
}
