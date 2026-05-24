<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\CampusLocation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Tampilkan halaman presensi mahasiswa.
     * Flow baru: Cari sesi aktif yang cocok dengan KRS mahasiswa.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Ambil jadwal yang di-enroll mahasiswa (KRS)
        $enrolledScheduleIds = $user->enrollments()->pluck('course_schedule_id');

        // Cari sesi pertemuan yang aktif DAN sesuai KRS mahasiswa
        $activeSession = AttendanceSession::where('is_active', true)
            ->whereIn('course_schedule_id', $enrolledScheduleIds)
            ->with(['courseSchedule.subject', 'courseSchedule.studyClass', 'courseSchedule.dosen', 'courseSchedule.campusLocation'])
            ->first();

        // Cek apakah sudah absen di sesi ini
        $todayAttendance = null;
        if ($activeSession) {
            $todayAttendance = Attendance::where('attendance_session_id', $activeSession->id)
                ->where('student_id', $user->id)
                ->first();
        }

        // Cek apakah sesi dalam rentang waktu
        $isSessionOpen = $activeSession ? $activeSession->isCurrentlyOpen() : false;
        $sessionMessage = '';

        if ($activeSession && !$isSessionOpen) {
            $now = now();
            if ($now->lt($activeSession->start_time)) {
                $sessionMessage = 'Sesi belum dimulai. Dibuka pukul ' . $activeSession->start_time->format('H:i') . '.';
            } elseif ($now->gt($activeSession->end_time)) {
                $sessionMessage = 'Sesi sudah berakhir sejak pukul ' . $activeSession->end_time->format('H:i') . '.';
            }
        }

        // Ambil lokasi kampus dari jadwal sesi aktif
        $campusLocation = $activeSession?->courseSchedule?->campusLocation;

        // Riwayat 7 absensi terakhir
        $recentHistory = Attendance::where('student_id', $user->id)
            ->with(['attendanceSession.courseSchedule.subject', 'attendanceSession.courseSchedule.studyClass'])
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();

        return view('mahasiswa.absensi', compact(
            'activeSession',
            'todayAttendance',
            'isSessionOpen',
            'sessionMessage',
            'campusLocation',
            'recentHistory'
        ));
    }

    /**
     * Proses presensi mahasiswa (via AJAX).
     * Flow baru: Validasi terhadap attendance_session_id.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'session_id' => ['required', 'exists:attendance_sessions,id'],
        ]);

        $user = Auth::user();

        // Cari sesi
        $session = AttendanceSession::with('courseSchedule.campusLocation')->find($request->session_id);

        if (!$session || !$session->is_active) {
            return response()->json([
                'success' => false,
                'status' => 'closed',
                'message' => 'Sesi pertemuan tidak aktif atau tidak ditemukan.',
            ], 403);
        }

        // Validasi KRS: apakah mahasiswa terdaftar di jadwal ini?
        $isEnrolled = $user->enrollments()
            ->where('course_schedule_id', $session->course_schedule_id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json([
                'success' => false,
                'status' => 'unauthorized',
                'message' => 'Anda tidak terdaftar di mata kuliah ini.',
            ], 403);
        }

        // Cek duplikasi per sesi
        $alreadyAttended = Attendance::where('attendance_session_id', $session->id)
            ->where('student_id', $user->id)
            ->exists();

        if ($alreadyAttended) {
            return response()->json([
                'success' => false,
                'status' => 'duplicate',
                'message' => 'Anda sudah melakukan presensi untuk sesi ini.',
            ], 409);
        }

        // Cek waktu sesi
        if (!$session->isCurrentlyOpen()) {
            $now = now();
            $statusMsg = $now->lt($session->start_time)
                ? 'Sesi belum dimulai. Dibuka pukul ' . $session->start_time->format('H:i') . '.'
                : 'Sesi sudah berakhir sejak pukul ' . $session->end_time->format('H:i') . '.';

            return response()->json([
                'success' => false,
                'status' => 'closed',
                'message' => $statusMsg,
            ], 403);
        }

        // Ambil lokasi kampus dari jadwal
        $campusLocation = $session->courseSchedule->campusLocation;

        if (!$campusLocation) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Lokasi kampus belum diset untuk jadwal ini. Hubungi dosen/admin.',
            ], 500);
        }

        // Hitung jarak Haversine
        $distance = $this->calculateHaversine(
            $request->latitude,
            $request->longitude,
            $campusLocation->latitude,
            $campusLocation->longitude
        );

        // Tentukan status
        $status = $distance > $campusLocation->radius_tolerance ? 'luar_radius' : 'hadir';

        // Simpan presensi
        $attendance = Attendance::create([
            'attendance_session_id' => $session->id,
            'student_id' => $user->id,
            'user_id' => $user->id,
            'campus_location_id' => $campusLocation->id,
            'capture_lat' => $request->latitude,
            'capture_long' => $request->longitude,
            'distance_meters' => (int) round($distance),
            'status' => $status,
            'user_agent' => $request->header('User-Agent'),
        ]);

        $messages = [
            'hadir' => '✅ Presensi berhasil! Anda berada dalam radius kampus.',
            'luar_radius' => '❌ Presensi ditolak! Anda berada di luar radius kampus (' . round($distance) . 'm dari titik referensi).',
        ];

        return response()->json([
            'success' => $status === 'hadir',
            'status' => $status,
            'message' => $messages[$status],
            'data' => [
                'distance' => (int) round($distance),
                'radius_tolerance' => $campusLocation->radius_tolerance,
                'timestamp' => $attendance->created_at->format('d M Y - H:i:s'),
            ],
        ]);
    }

    /**
     * Proses pengajuan izin/sakit mahasiswa (via AJAX).
     */
    public function submitPermit(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'in:izin,sakit'],
            'proof_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'session_id' => ['required', 'exists:attendance_sessions,id'],
        ], [
            'type.required' => 'Jenis pengajuan harus dipilih.',
            'type.in' => 'Jenis pengajuan harus izin atau sakit.',
            'proof_file.required' => 'File bukti wajib diunggah.',
            'proof_file.mimes' => 'Format file harus JPG, JPEG, PNG, atau PDF.',
            'proof_file.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $user = Auth::user();

        $session = AttendanceSession::with('courseSchedule.campusLocation')->find($request->session_id);

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi pertemuan tidak ditemukan.',
            ], 404);
        }

        // Cek duplikasi
        $alreadyAttended = Attendance::where('attendance_session_id', $session->id)
            ->where('student_id', $user->id)
            ->exists();

        if ($alreadyAttended) {
            return response()->json([
                'success' => false,
                'status' => 'duplicate',
                'message' => 'Anda sudah melakukan presensi atau pengajuan untuk sesi ini.',
            ], 409);
        }

        // Simpan file bukti
        $proofPath = $request->file('proof_file')->store('proofs', 'public');

        // Buat record
        $attendance = Attendance::create([
            'attendance_session_id' => $session->id,
            'student_id' => $user->id,
            'user_id' => $user->id,
            'campus_location_id' => $session->courseSchedule->campus_location_id,
            'capture_lat' => 0,
            'capture_long' => 0,
            'distance_meters' => 0,
            'status' => $request->type,
            'user_agent' => $request->header('User-Agent'),
            'proof_file' => $proofPath,
        ]);

        $typeLabel = $request->type === 'izin' ? 'Izin' : 'Sakit';

        return response()->json([
            'success' => true,
            'status' => $request->type,
            'message' => "Pengajuan {$typeLabel} berhasil direkam.",
            'data' => [
                'timestamp' => $attendance->created_at->format('d M Y - H:i:s'),
            ],
        ]);
    }

    /**
     * Tampilkan halaman riwayat presensi mahasiswa.
     */
    public function history(): View
    {
        $user = Auth::user();

        $attendances = Attendance::where('student_id', $user->id)
            ->with(['attendanceSession.courseSchedule.subject', 'attendanceSession.courseSchedule.studyClass'])
            ->latest()
            ->get();

        return view('mahasiswa.riwayat', compact('attendances'));
    }

    /**
     * Haversine Formula
     */
    private function calculateHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $deltaPhi = deg2rad($lat2 - $lat1);
        $deltaLambda = deg2rad($lon2 - $lon1);

        $a = sin($deltaPhi / 2) * sin($deltaPhi / 2)
           + cos($phi1) * cos($phi2)
           * sin($deltaLambda / 2) * sin($deltaLambda / 2);

        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }
}
