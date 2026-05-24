<?php

namespace Database\Seeders;

use App\Models\AttendanceSession;
use App\Models\CampusLocation;
use App\Models\CourseSchedule;
use App\Models\StudentEnrollment;
use App\Models\StudyClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Data awal untuk Sistem Absensi Universitas Malikussaleh.
     */
    public function run(): void
    {
        // ========================================
        // USERS
        // ========================================

        $admin = User::create([
            'nip_nim' => 'ADMIN001',
            'name' => 'Administrator',
            'email' => 'admin@luckystar.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $dosen1 = User::create([
            'nip_nim' => '198501012010011001',
            'name' => 'Dr. Ahmad Fauzi',
            'email' => 'dosen@luckystar.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        $dosen2 = User::create([
            'nip_nim' => '198703152012012002',
            'name' => 'Sari Indah, M.Kom',
            'email' => 'sari@luckystar.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
        ]);

        $mhs1 = User::create([
            'nip_nim' => '2201020001',
            'name' => 'Budi Santoso',
            'email' => 'budi@mhs.unimal.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $mhs2 = User::create([
            'nip_nim' => '2201020002',
            'name' => 'Siti Aminah',
            'email' => 'siti@mhs.unimal.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        $mhs3 = User::create([
            'nip_nim' => '2201020003',
            'name' => 'Rizki Pratama',
            'email' => 'rizki@mhs.unimal.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);

        // ========================================
        // CAMPUS LOCATIONS
        // ========================================

        $loc1 = CampusLocation::create([
            'name_location' => 'Kampus Utama Unimal - Reuleut',
            'latitude' => 5.18090000,
            'longitude' => 97.14840000,
            'radius_tolerance' => 100,
            'is_active' => true,
            'start_time' => '07:00',
            'end_time' => '17:00',
        ]);

        $loc2 = CampusLocation::create([
            'name_location' => 'Gedung Teknik Informatika',
            'latitude' => 5.18120000,
            'longitude' => 97.14900000,
            'radius_tolerance' => 50,
            'is_active' => true,
            'start_time' => '08:00',
            'end_time' => '16:00',
        ]);

        // ========================================
        // SUBJECTS (Mata Kuliah)
        // ========================================

        $subj1 = Subject::create([
            'code' => 'TIF301',
            'name' => 'Pemrograman Web',
            'sks' => 3,
        ]);

        $subj2 = Subject::create([
            'code' => 'TIF302',
            'name' => 'Basis Data Lanjut',
            'sks' => 3,
        ]);

        $subj3 = Subject::create([
            'code' => 'TIF201',
            'name' => 'Algoritma & Struktur Data',
            'sks' => 4,
        ]);

        $subj4 = Subject::create([
            'code' => 'TIF401',
            'name' => 'Kecerdasan Buatan',
            'sks' => 3,
        ]);

        // ========================================
        // STUDY CLASSES (Kelas)
        // ========================================

        $classA = StudyClass::create(['name' => 'A']);
        $classB = StudyClass::create(['name' => 'B']);
        $classC = StudyClass::create(['name' => 'C']);

        // ========================================
        // COURSE SCHEDULES (Jadwal)
        // ========================================

        $sched1 = CourseSchedule::create([
            'subject_id' => $subj1->id,
            'study_class_id' => $classA->id,
            'dosen_id' => $dosen1->id,
            'campus_location_id' => $loc1->id,
        ]);

        $sched2 = CourseSchedule::create([
            'subject_id' => $subj2->id,
            'study_class_id' => $classA->id,
            'dosen_id' => $dosen1->id,
            'campus_location_id' => $loc2->id,
        ]);

        $sched3 = CourseSchedule::create([
            'subject_id' => $subj3->id,
            'study_class_id' => $classB->id,
            'dosen_id' => $dosen2->id,
            'campus_location_id' => $loc1->id,
        ]);

        $sched4 = CourseSchedule::create([
            'subject_id' => $subj4->id,
            'study_class_id' => $classA->id,
            'dosen_id' => $dosen2->id,
            'campus_location_id' => $loc1->id,
        ]);

        // ========================================
        // STUDENT ENROLLMENTS (KRS)
        // ========================================

        // Budi: Pemweb A, BasDat A, AI A
        StudentEnrollment::create(['course_schedule_id' => $sched1->id, 'student_id' => $mhs1->id]);
        StudentEnrollment::create(['course_schedule_id' => $sched2->id, 'student_id' => $mhs1->id]);
        StudentEnrollment::create(['course_schedule_id' => $sched4->id, 'student_id' => $mhs1->id]);

        // Siti: Pemweb A, ASD B, AI A
        StudentEnrollment::create(['course_schedule_id' => $sched1->id, 'student_id' => $mhs2->id]);
        StudentEnrollment::create(['course_schedule_id' => $sched3->id, 'student_id' => $mhs2->id]);
        StudentEnrollment::create(['course_schedule_id' => $sched4->id, 'student_id' => $mhs2->id]);

        // Rizki: BasDat A, ASD B
        StudentEnrollment::create(['course_schedule_id' => $sched2->id, 'student_id' => $mhs3->id]);
        StudentEnrollment::create(['course_schedule_id' => $sched3->id, 'student_id' => $mhs3->id]);

        // ========================================
        // ATTENDANCE SESSIONS (Sesi Pertemuan Demo)
        // ========================================

        // Sesi aktif: Pemweb A - Pertemuan 1 (hari ini, buka sekarang)
        AttendanceSession::create([
            'course_schedule_id' => $sched1->id,
            'meeting_number' => 1,
            'start_time' => now()->startOfDay()->addHours(7),
            'end_time' => now()->startOfDay()->addHours(23),
            'is_active' => true,
        ]);

        // Sesi nonaktif: Pemweb A - Pertemuan 2 (besok)
        AttendanceSession::create([
            'course_schedule_id' => $sched1->id,
            'meeting_number' => 2,
            'start_time' => now()->addDay()->startOfDay()->addHours(8),
            'end_time' => now()->addDay()->startOfDay()->addHours(10),
            'is_active' => false,
        ]);

        // Sesi nonaktif: BasDat A - Pertemuan 1
        AttendanceSession::create([
            'course_schedule_id' => $sched2->id,
            'meeting_number' => 1,
            'start_time' => now()->addDay()->startOfDay()->addHours(10),
            'end_time' => now()->addDay()->startOfDay()->addHours(12),
            'is_active' => false,
        ]);
    }
}
