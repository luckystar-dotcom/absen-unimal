<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CampusLocation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Tampilkan halaman presensi mahasiswa.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Ambil data presensi hari ini untuk user yang sedang login
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->with('campusLocation')
            ->first();

        // Ambil riwayat 7 hari terakhir
        $recentHistory = Attendance::where('user_id', $user->id)
            ->with('campusLocation')
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();

        // Ambil lokasi kampus aktif
        $campusLocation = CampusLocation::where('is_active', true)->first();

        return view('mahasiswa.absensi', compact('todayAttendance', 'recentHistory', 'campusLocation'));
    }

    /**
     * Proses presensi mahasiswa (via AJAX).
     * Menerima koordinat dari Geolocation API, menghitung jarak Haversine,
     * dan menyimpan status presensi.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $user = Auth::user();

        // Cek apakah sudah absen hari ini (anti-duplikasi)
        $alreadyAttended = Attendance::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($alreadyAttended) {
            return response()->json([
                'success' => false,
                'status' => 'duplicate',
                'message' => 'Anda sudah melakukan presensi hari ini.',
            ], 409);
        }

        // Ambil lokasi kampus yang aktif
        $campusLocation = CampusLocation::where('is_active', true)->first();

        if (!$campusLocation) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'Tidak ada lokasi kampus yang aktif. Hubungi admin.',
            ], 500);
        }

        // Hitung jarak menggunakan Haversine Formula
        $distance = $this->calculateHaversine(
            $request->latitude,
            $request->longitude,
            $campusLocation->latitude,
            $campusLocation->longitude
        );

        // Tentukan status berdasarkan radius
        $status = 'hadir';
        if ($distance > $campusLocation->radius_tolerance) {
            $status = 'luar_radius';
        }

        // Simpan data presensi
        $attendance = Attendance::create([
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
     * Haversine Formula
     * Menghitung jarak melengkung (great-circle distance) antara dua titik
     * di permukaan bumi berdasarkan koordinat geografis.
     *
     * d = 2r * arcsin(√(sin²(Δφ/2) + cos(φ1) * cos(φ2) * sin²(Δλ/2)))
     *
     * @param float $lat1 Latitude titik 1 (perangkat mahasiswa)
     * @param float $lon1 Longitude titik 1
     * @param float $lat2 Latitude titik 2 (kampus)
     * @param float $lon2 Longitude titik 2
     * @return float Jarak dalam meter
     */
    private function calculateHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        // Jari-jari bumi dalam meter
        $earthRadius = 6371000;

        // Konversi derajat ke radian
        $phi1 = deg2rad($lat1);
        $phi2 = deg2rad($lat2);
        $deltaPhi = deg2rad($lat2 - $lat1);
        $deltaLambda = deg2rad($lon2 - $lon1);

        // Haversine Formula
        $a = sin($deltaPhi / 2) * sin($deltaPhi / 2)
           + cos($phi1) * cos($phi2)
           * sin($deltaLambda / 2) * sin($deltaLambda / 2);

        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }
}
