# 🚀 PROJECT BRIEF: LUCKYSTAR

**Nama Proyek:** LuckyStar - Location-Based Smart Attendance System

**Konteks:** Sistem Absensi Mahasiswa berbasis *Geofencing* & *Web Tracking* untuk keperluan akademik (Pemrograman Web Lanjut).

**Platform:** Web Application (Monolithic)

**Target Pengguna:** Mahasiswa (sebagai *End-User* presensi) dan Dosen/Admin (sebagai *Manager* dan *Viewer* data).

### Deskripsi Eksekutif

LuckyStar adalah aplikasi presensi modern yang mengeliminasi kebutuhan alat absensi fisik dengan memanfaatkan perangkat pintar (*smartphone*) milik pengguna. Aplikasi ini mengandalkan *HTML5 Geolocation API* untuk menangkap koordinat presensi dan melakukan komputasi trigonometri di sisi *backend* untuk memvalidasi radius mahasiswa terhadap titik referensi kampus.

---

# 📋 PRODUCT REQUIREMENTS DOCUMENT (PRD)

## 1. Objektif Produk

* Menyediakan antarmuka presensi satu klik (1-click) yang minimalis dan responsif untuk mahasiswa.
* Memastikan data lokasi tidak dapat dipalsukan (*anti-spoofing* dasar) melalui validasi komputasi *server-side* dan *Server Timestamp*.
* Menyediakan dasbor analitik dan rekapitulasi kehadiran *real-time* yang sangat cepat (bebas isu N+1 *Query*) untuk Dosen/Admin.

## 2. Tech Stack & Ekosistem

* **Backend & Core Framework:** Laravel
* **Admin Panel & Dashboard:** Filament PHP
* **Frontend (Halaman Mahasiswa):** Laravel Blade + Tailwind CSS (Native/Utility-first) + Vanilla JavaScript
* **Database:** MySQL / MariaDB
* **Algoritma Geofencing:** Haversine Formula

## 3. Scope MVP (Minimum Viable Product)

Berdasarkan pengerucutan fitur *core*, berikut adalah ruang lingkup yang akan dikembangkan:

### A. Mahasiswa (End-User)

1. **Autentikasi Mahasiswa:** Halaman *login* sederhana menggunakan kredensial (Email/NIM dan *Password*).
2. **Geolocation Capture:** Halaman utama dengan tombol "Hadir" yang secara asinkron (AJAX) meminta akses GPS dan mengirim koordinat *Latitude* dan *Longitude*.
3. **Status Feedback:** Notifikasi instan (Sukses, Luar Radius, Terlambat) setelah sistem *backend* memproses koordinat.

### B. Dosen / Admin (Manager)

1. **Filament Dashboard Authentication:** Akses masuk ke panel kontrol administratif.
2. **Monitoring Table:** Tabel presensi harian dengan fitur pencarian dan filter (Berdasarkan tanggal, status kehadiran, dan nama mahasiswa).
3. **Data Export:** Mengunduh rekap laporan kehadiran dalam format `.csv` atau `.xlsx`.

## 4. Perhitungan Matematis (Haversine Formula)

Untuk menghitung jarak melengkung antara koordinat kampus dengan koordinat perangkat, sistem akan menggunakan rumus Haversine di *backend* (PHP).

Rumus matematis yang akan diimplementasikan ke dalam logika *Controller*:


$$d = 2r \arcsin\left(\sqrt{\sin^2\left(\frac{\Delta\phi}{2}\right) + \cos(\phi_1)\cos(\phi_2)\sin^2\left(\frac{\Delta\lambda}{2}\right)}\right)$$

Dimana:

* $d$ = Jarak antara dua titik (dalam satuan meter atau kilometer).
* $r$ = Jari-jari bumi (rata-rata 6.371 km atau 6.371.000 meter).
* $\phi_1, \phi_2$ = Garis lintang (*latitude*) titik 1 dan titik 2 dalam radian.
* $\Delta\phi$ = Selisih garis lintang.
* $\Delta\lambda$ = Selisih garis bujur (*longitude*).

---

# 🗄️ ENTITY RELATIONSHIP DIAGRAM (ERD) & INDEXING STRATEGY

Untuk memastikan panel Filament dapat memuat ribuan data log absensi dalam hitungan milidetik tanpa membebani *server*, arsitektur *database* harus didesain dengan strategi **Indexing** yang presisi.

## 1. Skema Tabel (Database Schema)

### Table: `users`

Menyimpan data identitas pengguna (Dosen dan Mahasiswa).

* `id` (Primary Key, BigInt, Auto Increment)
* `nip_nim` (String, Unique) -> Nomor Induk.
* `name` (String)
* `email` (String, Unique)
* `password` (String)
* `role` (Enum: 'admin', 'dosen', 'mahasiswa')
* `created_at`, `updated_at` (Timestamp)

### Table: `campus_locations`

Menyimpan titik koordinat acuan (kampus/gedung fakultas) untuk pengaturan radius.

* `id` (Primary Key, BigInt)
* `name_location` (String) -> Contoh: "Gedung Teknik Informatika"
* `latitude` (Decimal 10,8)
* `longitude` (Decimal 11,8)
* `radius_tolerance` (Integer) -> Dalam satuan meter (Contoh: 50).
* `is_active` (Boolean)

### Table: `attendances`

Tabel transaksional utama untuk mencatat log presensi setiap hari.

* `id` (Primary Key, BigInt, Auto Increment)
* `user_id` (Foreign Key -> `users.id`)
* `campus_location_id` (Foreign Key -> `campus_locations.id`)
* `capture_lat` (Decimal 10,8)
* `capture_long` (Decimal 11,8)
* `distance_meters` (Integer) -> Jarak absolut hasil kalkulasi Haversine.
* `status` (Enum: 'hadir', 'luar_radius', 'terlambat')
* `created_at` (Timestamp) -> **Ini adalah Server Timestamp mutlak.**
* `updated_at` (Timestamp)

---

## 2. Strategi Indexing (Optimasi Database)

Tanpa *index*, *database* akan melakukan *Full Table Scan* (memeriksa data satu per satu dari atas ke bawah) setiap kali Filament memuat data. Berikut rancangan *Index* tingkat lanjut pada struktur *Migration* Laravel:

**A. Index pada Tabel `attendances`:**

1. **Foreign Key Index:**
`$table->index('user_id');`
*Alasan:* Dosen akan sangat sering melakukan pencarian (filter) presensi berdasarkan nama/ID mahasiswa.
2. **Timestamp Index:**
`$table->index('created_at');`
*Alasan:* *Dashboard* akan secara *default* menampilkan data "Hari Ini" atau merentang dari "Tanggal X ke Y". Meng-index kolom tanggal akan mempercepat *query* filter harian hingga 90%.
3. **Composite Index (Gabungan):**
`$table->index(['user_id', 'created_at']);`
*Alasan:* Untuk optimasi validasi agar mahasiswa tidak melakukan absen ganda. *Backend* akan melakukan *query*: "Apakah user_id = X sudah memiliki data di created_at = Hari Ini?". *Composite index* membuat pengecekan ini terjadi dalam orde mikro-detik.

**B. Index pada Tabel `users`:**

1. **Identity Index:**
`$table->unique('nip_nim');`
`$table->index('role');`
*Alasan:* Validasi *login* dan pemisahan akses rute membutuhkan pencarian level *role* yang cepat.

---

# ⚡ NON-FUNCTIONAL REQUIREMENTS & OPTIMIZATION

## 1. Strategi Anti N+1 Problem (Filament Eager Loading)

Karena kita memisahkan tabel `attendances` dan `users`, panel dasbor Dosen rentan terhadap isu N+1 jika tidak ditangani. Di dalam *Filament Resource* (`AttendanceResource.php`), pastikan *query* tabel dimodifikasi dengan *Eager Loading* Eloquent:

```php
public static function table(Table $table): Table
{
    return $table
        // Menerapkan Eager Loading -> Hanya mengeksekusi 2 Query secara keseluruhan
        ->modifyQueryUsing(fn (Builder $query) => $query->with('user', 'campus_location')) 
        ->columns([
            TextColumn::make('user.name')->searchable(), // Relasi dari eagerly loaded data
            TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'hadir' => 'success',
                    'luar_radius' => 'danger',
                    'terlambat' => 'warning',
                }),
            TextColumn::make('distance_meters')
                ->formatStateUsing(fn ($state) => $state . ' m'),
            TextColumn::make('created_at')
                ->dateTime('d M Y - H:i:s'), // Server Time absolut
        ]);
}

```

## 2. Keamanan Absensi Tambahan

* **Device Context:** *Controller* Laravel akan menyimpan tipe perangkat (`User-Agent`) beserta koordinat. Ini membantu audit data jika ada manipulasi tingkat lanjut.
* **CSRF Protection:** Setiap form pengiriman lokasi via AJAX wajib menyertakan token *Cross-Site Request Forgery* yang merupakan fitur bawaan Laravel.
* **HTTPS Requirement:** API Geolocation HTML5 pada *browser* modern (Chrome, Safari) **hanya** akan berfungsi jika situs diakses menggunakan protokol yang aman (HTTPS) atau *localhost* saat tahap *development*.