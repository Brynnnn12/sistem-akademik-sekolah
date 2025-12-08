# Sistem Akademik Sekolah Dasar (SD)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

<p align="center">
  <strong>🏫 Sistem Manajemen Akademik Lengkap untuk Sekolah Dasar dengan Laravel</strong>
</p>

## 📋 Tentang Sistem

Sistem Akademik Sekolah Dasar adalah aplikasi web berbasis Laravel yang dirancang khusus untuk mengelola operasional sekolah dasar (SD) secara menyeluruh. Sistem ini mencakup manajemen profil pengguna, data siswa, kelas, jadwal mengajar, penilaian harian, absensi, hingga pembuatan rapor akhir semester.

### ✨ Fitur Utama

-   🔐 **Pemisahan Akun & Profil** - User login terpisah dari biodata guru/kepsek
-   👨‍🏫 **Manajemen Guru & Kepsek** - Profil lengkap dengan NIP, foto, dll
-   📚 **Sistem Kelas & Rombel** - Plotting siswa ke kelas per tahun ajaran
-   📅 **Jadwal Mengajar** - Assignment guru-mapel-kelas
-   📊 **Penilaian Terstruktur** - Nilai harian (UH/Tugas) vs rapor akhir
-   📝 **Absensi Siswa** - Tracking kehadiran harian
-   📄 **Pembuatan Rapor** - Kalkulasi nilai akhir otomatis
-   👥 **Role-Based Access** - Admin, Guru, Kepsek dengan permission berbeda
-   🎨 **UI Modern** - Responsive dengan Tailwind CSS
-   🧪 **Testing Lengkap** - Unit & Feature tests dengan Pest

## 🏗️ Arsitektur Sistem

### ERD (Diagram Hubungan Entitas)

Berikut adalah peta hubungan antar tabel menggunakan istilah Indonesia.

````mermaid
erDiagram
    %% MANAJEMEN PENGGUNA
    pengguna ||--|| profil : "1 Akun punya 1 Biodata"

    %% DATA UTAMA
    tahun_ajaran ||--o{ kelas_siswa : "Mengatur Periode"
    tahun_ajaran ||--o{ penugasan_mengajar : "Mengatur Jadwal"
    tahun_ajaran ||--o{ nilai_akhir : "Periode Nilai"

    %% MANAJEMEN KELAS
    pengguna ||--o{ kelas : "Wali Kelas (Guru)"
    kelas ||--o{ kelas_siswa : "Menampung Siswa"
    siswa ||--o{ kelas_siswa : "Masuk Kelas"

    %% KEGIATAN MENGAJAR
    pengguna ||--o{ penugasan_mengajar : "Guru Mengajar"
    kelas ||--o{ penugasan_mengajar : "Kelas Diajar"
    mata_pelajarans ||--o{ penugasan_mengajar : "Mapel Diajarkan"

    %% PROSES PENILAIAN HARIAN
    penugasan_mengajar ||--o{ komponen_nilai : "Punya Kategori (UH/Tugas)"
    komponen_nilai ||--o{ nilai_siswa : "Detail Nilai"
    siswa ||--o{ nilai_siswa : "Milik Siswa"

    %% HASIL AKHIR (RAPOR)
    siswa ||--o{ nilai_akhir : "Punya Rapor"
    mata_pelajarans ||--o{ nilai_akhir : "Nilai Mapel"

    %% ABSENSI
    kelas ||--o{ kehadiran : "Lokasi Absen"
    siswa ||--o{ kehadiran : "Data Kehadiran"
```## 🗄️ Struktur Database

### Migrasi Laravel (Urutan Pembuatan)

#### 1. Pengguna & Profil

```php
// pengguna (Hanya Login)
Schema::create('pengguna', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();
    $table->string('kata_sandi');
    $table->enum('peran', ['admin', 'guru', 'kepsek']); // Admin tidak punya profil
    $table->timestamps();
});

// profil (Biodata Guru & Kepsek)
Schema::create('profil', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
    $table->string('nip')->nullable()->unique();
    $table->string('nama');
    $table->string('telepon')->nullable();
    $table->text('alamat')->nullable();
    $table->string('foto')->nullable();
    $table->timestamps();
});
````

#### 2. Data Master Sekolah

```php
// tahun_ajaran
Schema::create('tahun_ajaran', function (Blueprint $table) {
    $table->id();
    $table->string('nama'); // "2024/2025"
    $table->enum('semester', ['ganjil', 'genap']);
    $table->boolean('aktif')->default(false);
    $table->timestamps();
});

// mata_pelajarans
Schema::create('mata_pelajarans', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('kode')->unique(); // MATH101, SCI201
    $table->integer('kkm')->default(75); // Kriteria Ketuntasan Minimal
    $table->softDeletes();
    $table->timestamps();
});

// siswa
Schema::create('siswa', function (Blueprint $table) {
    $table->id();
    $table->string('nis')->unique();
    $table->string('nisn')->nullable();
    $table->string('nama');
    $table->enum('jenis_kelamin', ['L', 'P']);
    $table->date('tanggal_lahir');
    $table->text('alamat')->nullable();
    $table->timestamps();
});

// kelas (Kelas Fisik)
Schema::create('kelas', function (Blueprint $table) {
    $table->id();
    $table->string('nama'); // "1A", "6B"
    $table->integer('tingkat_kelas'); // 1 sampai 6
    // Wali Kelas diambil dari pengguna (peran guru)
    $table->foreignId('guru_id')->nullable()->constrained('pengguna')->onDelete('set null');
    $table->timestamps();
});
```

#### 3. Operasional (Plotting Kelas & Jadwal)

```php
// kelas_siswa (Siswa X masuk Kelas Y tahun ini)
Schema::create('kelas_siswa', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
    $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
    $table->timestamps();
});

// penugasan_mengajar (Guru X mengajar Mata Pelajaran Y di Kelas Z)
Schema::create('penugasan_mengajar', function (Blueprint $table) {
    $table->id();
    $table->foreignId('guru_id')->constrained('pengguna')->onDelete('cascade');
    $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
    $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
    $table->timestamps();
});
```

#### 4. Transaksi Harian (Absensi & Input Nilai)

```php
// kehadiran
Schema::create('kehadiran', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
    $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
    $table->date('tanggal');
    $table->enum('status', ['H', 'S', 'I', 'A']);
    $table->string('catatan')->nullable();
    $table->timestamps();
});

// komponen_nilai (Wadah Nilai: Guru buat "Slot" nilai dulu)
Schema::create('komponen_nilai', function (Blueprint $table) {
    $table->id();
    // Relasi ke penugasan_mengajar agar spesifik (Guru A, Mata Pelajaran B, Kelas C)
    $table->foreignId('penugasan_mengajar_id')->constrained('penugasan_mengajar')->onDelete('cascade');
    $table->string('nama'); // "PR Bab 1", "UH 1"
    $table->enum('jenis', ['tugas', 'uh', 'uts', 'uas']);
    $table->timestamps();
});

// nilai_siswa (Isi Nilai Siswa)
Schema::create('nilai_siswa', function (Blueprint $table) {
    $table->id();
    $table->foreignId('komponen_nilai_id')->constrained('komponen_nilai')->onDelete('cascade');
    $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
    $table->float('nilai'); // 0 - 100
    $table->timestamps();
});
```

#### 5. Laporan (Nilai Akhir Rapor)

```php
// nilai_akhir (Hasil kalkulasi untuk Rapor)
Schema::create('nilai_akhir', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
    $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');

    $table->float('nilai_akhir_angka'); // Nilai Akhir Angka
    $table->string('predikat')->nullable(); // Predikat (A/B/C)
    $table->text('deskripsi')->nullable(); // Deskripsi Capaian

    $table->timestamps();

    // Constraint: 1 Siswa, 1 Mata Pelajaran, 1 Tahun = Cuma boleh 1 Nilai Akhir
    $table->unique(['siswa_id', 'mata_pelajaran_id', 'tahun_ajaran_id']);
});
```

## 🔄 Alur Kerja Sistem (Workflow)

### Admin:

-   **Setup Awal**: Buat user guru & kepsek, data master (tahun ajaran, mapel, siswa)
-   **Plotting Tahunan**:
    -   Tentukan siswa masuk kelas mana (`kelas_siswa`)
    -   Tentukan guru mengajar apa di kelas mana (`penugasan_mengajar`)

### Guru:

-   **Absensi Harian**: Input ke `kehadiran`
-   **Penilaian Harian**:
    -   Buat "slot nilai" (UH/Tugas) → `komponen_nilai`
    -   Input nilai siswa → `nilai_siswa`
-   **Akhir Semester**: Generate rapor (hitung rata-rata → simpan ke `nilai_akhir`)

### Kepsek:

-   **Monitoring**: Dashboard statistik
-   **Rapor**: Lihat/cetak rapor siswa dari `nilai_akhir`

## 🚀 Panduan Instalasi

### Prasyarat

-   PHP 8.3+
-   Composer
-   Node.js & NPM
-   MySQL

### Langkah Instalasi

1. **Clone Repository**

    ```bash
    git clone <repository-url>
    cd sistem-akademik-sekolah
    ```

2. **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3. **Environment Setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Database Configuration**
   Edit `.env`:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sekolah_sd
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Run Migrations & Seeders**

    ```bash
    php artisan migrate --seed
    ```

6. **Build Assets**

    ```bash
    npm run build
    ```

7. **Start Server**
    ```bash
    php artisan serve
    ```

Kunjungi `http://localhost:8000` untuk mulai menggunakan sistem!

## 👤 User Default

**Admin:**

-   Email: `admin@sekolah.com`
-   Password: `password`

**Guru:**

-   Email: `guru@sekolah.com`
-   Password: `password`

**Kepsek:**

-   Email: `kepsek@sekolah.com`
-   Password: `password`

## 📊 Status Pengembangan

### ✅ Sudah Selesai

-   [x] Sistem autentikasi dengan role (Admin/Guru/Kepsek)
-   [x] Manajemen profil pengguna (terpisah dari login)
-   [x] Upload foto profil
-   [x] Testing lengkap untuk fitur profil
-   [x] Clean Architecture (Controller → Service → Repository)
-   [x] UI responsif dengan Tailwind CSS
-   [x] Tahun Ajaran management
-   [x] Mata Pelajaran management
-   [ ] Master data siswa
-   [ ] Manajemen kelas & rombel
-   [ ] Jadwal mengajar
-   [ ] Sistem absensi
-   [ ] Penilaian harian
-   [ ] Generate rapor akhir

### 📋 Rencana Selanjutnya

-   Implementasi CRUD untuk semua entitas
-   Dashboard dengan statistik
-   Export rapor ke PDF
-   API untuk mobile app
-   Multi-tenant untuk multiple sekolah

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Test spesifik
php artisan test tests/Feature/ProfileTest.php
```

## 📚 Teknologi

-   **Backend**: Laravel 12
-   **Frontend**: Blade + Tailwind CSS + Alpine.js
-   **Database**: MySQL
-   **Testing**: Pest PHP
-   **Authentication**: Laravel Breeze + Spatie Permission

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat issue atau pull request.

## 📄 Lisensi

MIT License

---

<p align="center">
  <strong>Dibuat untuk memudahkan pengelolaan sekolah dasar ❤️</strong>
</p>

## 📚 Teknologi

-   **Backend**: Laravel 12
-   **Frontend**: Blade + Tailwind CSS + Alpine.js
-   **Database**: MySQL
-   **Testing**: Pest PHP
-   **Authentication**: Laravel Breeze + Spatie Permission
    -   Button styles
    -   Form validation display
-   **UI Components** (`resources/views/components/ui/`)
    -   Cards dan panels
    -   Tables siap pakai
    -   Modals dan dialogs
-   **Home Components** (`resources/views/components/home/`)
    -   Hero section
    -   Feature highlights
    -   Call-to-action buttons

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat issue atau pull request.

## 📄 Lisensi

MIT License

---

<p align="center">
  <strong>Dibuat untuk memudahkan pengelolaan sekolah dasar ❤️</strong>
</p>
