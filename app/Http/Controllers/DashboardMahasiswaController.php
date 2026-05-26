<?php

namespace App\Http\Controllers;

use App\Models\CourseSchedule;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardMahasiswaController extends Controller
{
    /**
     * Dashboard Utama — Menampilkan KRS mahasiswa (jadwal yang di-enroll).
     */
    public function index(): View
    {
        $user = Auth::user();
        $today = Carbon::now();
        $dayName = $today->translatedFormat('l'); // Senin, Selasa, etc.

        // Mapping nama hari Inggris → Indonesia
        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
        $hariIni = $dayMap[$today->format('l')] ?? $today->format('l');

        // Ambil semua KRS (enrolled schedules) dengan relasi
        $krs = $user->enrolledSchedules()
            ->with(['subject', 'studyClass', 'dosen', 'campusLocation'])
            ->get();

        // Jadwal hari ini (filter dari KRS berdasarkan day_of_week)
        $jadwalHariIni = $krs->filter(function ($schedule) use ($hariIni) {
            return $schedule->day_of_week === $hariIni;
        })->sortBy('start_time')->values();

        // Total SKS
        $totalSks = $krs->sum(function ($schedule) {
            return $schedule->subject->sks ?? 0;
        });

        // Ambil semua sesi presensi aktif untuk jadwal kuliah dalam KRS mahasiswa
        $activeSessions = \App\Models\AttendanceSession::where('is_active', true)
            ->whereIn('course_schedule_id', $krs->pluck('id'))
            ->get()
            ->keyBy('course_schedule_id');

        return view('mahasiswa.dashboard', compact(
            'user',
            'krs',
            'jadwalHariIni',
            'hariIni',
            'totalSks',
            'today',
            'activeSessions'
        ));
    }

    /**
     * Katalog Mata Kuliah — Semua MK dikelompokkan per semester.
     */
    public function katalog(): View
    {
        $user = Auth::user();

        // Ambil semua mata kuliah, grupkan per semester
        $subjects = Subject::orderBy('semester')
            ->orderBy('code')
            ->get()
            ->groupBy(function ($subject) {
                return $subject->semester ?? 0; // 0 = belum ditentukan
            });

        return view('mahasiswa.katalog', compact('user', 'subjects'));
    }

    /**
     * Direktori Dosen — Daftar semua dosen beserta MK yang diampu.
     */
    public function dosen(): View
    {
        $user = Auth::user();

        $dosenList = User::where('role', 'dosen')
            ->with(['courseSchedulesAsDosen.subject'])
            ->orderBy('name')
            ->get();

        return view('mahasiswa.dosen', compact('user', 'dosenList'));
    }
}
