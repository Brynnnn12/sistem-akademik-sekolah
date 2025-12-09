# Sistem Akademik Sekolah Dasar (SD)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

<p align="center">
  <strong>🧮 Alat Bantu Hitung & Arsip Nilai Akademik Sekolah Dasar dengan Laravel</strong>
</p>

## 📖 Daftar Isi

-   [📋 Tentang Sistem](#-tentang-sistem)
-   [🏗️ Arsitektur Sistem](#️-arsitektur-sistem)
-   [🗄️ Struktur Database](#️-struktur-database)
-   [🔄 Alur Kerja Sistem](#-alur-kerja-sistem-workflow)
-   [💻 Persyaratan Sistem](#-persyaratan-sistem)
-   [🚀 Panduan Instalasi](#-panduan-instalasi)
-   [⚙️ Konfigurasi Awal](#️-konfigurasi-awal)
-   [🚀 Production Deployment](#-production-deployment)
-   [🔧 Troubleshooting](#-troubleshooting)
-   [👤 User Default](#-user-default)
-   [📊 Status Pengembangan](#-status-pengembangan)
-   [🔍 Fitur Detail](#-fitur-detail)
-   [🧪 Testing](#-testing)
-   [📚 Teknologi](#-teknologi)
-   [📋 Changelog](#-changelog)
-   [🤝 Kontribusi](#-kontribusi)
-   [🆘 Dukungan & Bantuan](#-dukungan--bantuan)
-   [📄 Lisensi](#-lisensi)

---

## 📖 Daftar Isi

-   [📋 Tentang Sistem](#-tentang-sistem)
-   [🏗️ Arsitektur Sistem](#️-arsitektur-sistem)
-   [🗄️ Struktur Database](#️-struktur-database)
-   [🔄 Alur Kerja Sistem](#-alur-kerja-sistem-workflow)
-   [💻 Persyaratan Sistem](#-persyaratan-sistem)
-   [🚀 Panduan Instalasi](#-panduan-instalasi)
-   [⚙️ Konfigurasi Awal](#️-konfigurasi-awal)
-   [🚀 Production Deployment](#-production-deployment)
-   [🔧 Troubleshooting](#-troubleshooting)
-   [👤 User Default](#-user-default)
-   [📊 Status Pengembangan](#-status-pengembangan)
-   [🔍 Fitur Detail](#-fitur-detail)
-   [🧪 Testing](#-testing)
-   [📚 Teknologi](#-teknologi)
-   [📋 Changelog](#-changelog)
-   [🤝 Kontribusi](#-kontribusi)
-   [🆘 Dukungan & Bantuan](#-dukungan--bantuan)
-   [📄 Lisensi](#-lisensi)

---

## 📋 Tentang Sistem

Sistem Akademik Sekolah Dasar adalah **alat bantu hitung dan arsip nilai** berbasis web yang dirancang khusus untuk sekolah dasar di Indonesia. Sistem ini membantu guru menghitung nilai rapor secara otomatis dan akurat, serta menyediakan arsip digital nilai siswa.

### 🎯 Pendekatan Sistem

**Input → Proses → Tampilkan Leger → Guru Salin ke Buku Rapor**

Sistem ini **TIDAK** menghasilkan PDF rapor siap cetak, melainkan fokus pada:

-   **Kalkulator Nilai Otomatis**: Hitung rata-rata dengan bobot yang tepat
-   **Leger Nilai Digital**: Tabel rekap nilai semua siswa per kelas
-   **Generator Deskripsi**: Saran narasi rapor otomatis
-   **Arsip Digital**: Backup nilai jika buku rapor hilang/rusak

### 💡 Mengapa Pendekatan Ini?

Banyak SD di Indonesia masih menggunakan **buku rapor manual** (buku hijau/merah) dimana guru harus menulis tangan. Sistem ini menjadi **contekan digital** untuk:

-   Menghindari salah hitung rata-rata
-   Menyediakan arsip digital sebagai backup
-   Memudahkan monitoring kepala sekolah
-   Menghemat waktu guru dalam menghitung nilai

### 👥 Pengguna Sistem

-   **Administrator**: Setup sistem dan data master
-   **Guru Mapel**: Input nilai harian, UTS, UAS
-   **Wali Kelas**: Lihat leger nilai, ranking siswa
-   **Kepala Sekolah**: Monitoring nilai dan ranking siswa

### ✨ Fitur Utama

-   🧮 **Kalkulator Nilai Otomatis** - Hitung rata-rata dengan bobot (UH/UTS/UAS)
-   📊 **Leger Nilai Digital** - Tabel rekap nilai semua siswa per kelas
-   📝 **Generator Deskripsi** - Saran narasi rapor otomatis berdasarkan nilai
-   🏆 **Ranking Otomatis** - Tentukan juara kelas dalam hitungan detik
-   🔐 **Authentication & Authorization** - Laravel Breeze + Spatie Permission
-   👨‍🏫 **Manajemen Profil** - Biodata guru/kepsek dengan upload foto
-   📚 **Data Master** - Tahun Ajaran, Mata Pelajaran, Siswa, Kelas (CRUD)
-   👥 **Rombongan Belajar** - Plotting siswa ke kelas per tahun ajaran
-   📅 **Penugasan Mengajar** - Assignment guru-mapel-kelas
-   📋 **Jadwal Mengajar** - Penjadwalan otomatis per hari
-   📝 **Presensi Mapel** - Absensi per mata pelajaran dengan jurnal
-   👁️ **Dashboard Wali Kelas** - Monitoring kehadiran dan nilai siswa
-   🎨 **UI Modern** - Responsive dengan Tailwind CSS + Alpine.js
-   📊 **Dashboard Analytics** - Charts & statistik real-time
-   🧪 **Testing Lengkap** - Unit & Feature tests dengan Pest PHP

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

    %% HASIL AKHIR (NILAI RAPOR)
    siswa ||--o{ nilai_akhir : "Punya Nilai Akhir"
    mata_pelajarans ||--o{ nilai_akhir : "Nilai Mapel"

    %% ABSENSI
    kelas ||--o{ kehadiran : "Lokasi Absen Harian"
    siswa ||--o{ kehadiran : "Data Kehadiran Harian"
    siswa ||--o{ presensi_mapel : "Data Kehadiran Mapel"
    mata_pelajarans ||--o{ presensi_mapel : "Mapel"
    pengguna ||--o{ presensi_mapel : "Guru"
```

### Penjelasan Detail Tabel

#### A. Inti & Pengguna

-   **users**: Akun login (Admin, Guru, Kepsek).
-   **profiles**: Biodata detail (NIP, Alamat, Foto).
-   **sekolahs**: Data sekolah (Nama, Alamat, Logo) & Link ke users (Kepsek).

#### B. Operasional (Jantung Sistem)

-   **tahun_ajarans**: Tahun akademik (2024/2025 Ganjil).
-   **kelas**: Nama kelas fisik (1A, 6B) & Link ke users (Wali Kelas).
-   **mata_pelajarans**: List pelajaran (MTK, IPA).
-   **siswas**: Data murid.
-   **kelas_siswas** (Pivot): Menentukan siswa ada di kelas mana pada tahun ini.
-   **penugasan_mengajars**: Menentukan Guru A mengajar Mapel B di Kelas C.
-   **jadwal_mengajars**: Jam mengajar berdasarkan penugasan (Senin 07:00).

#### C. Harian (Guru Mapel)

-   **presensi_mapels**: Absensi siswa per jam pelajaran & Jurnal guru. (Menggantikan kehadirans harian).

#### D. Penilaian (Guru Mapel)

-   **komponen_nilais**: Wadah nilai (UH 1, UTS) + Bobot. Terhubung ke penugasan_mengajars.
-   **nilai_siswas**: Angka nilai mentah (0-100).

#### E. Output (Wali Kelas & Leger)

-   **nilai_akhirs**: Hasil hitungan rata-rata mapel. Ini yang tampil di Leger.
-   **catatan_wali_kelas**: Sikap Sosial & Spiritual, Status Naik Kelas, Rekap Absensi (Sakit, Izin, Alpha) → Hasil hitungan dari presensi_mapels.
-   **prestasi_siswas**: Data lomba/juara untuk pelengkap.

### Alur Data (Data Flow)

1. **Input**: Guru Mapel mengisi nilai_siswas dan presensi_mapels.
2. **Proses**: Sistem menghitung rata-rata nilai berdasarkan bobot di komponen_nilais → Disimpan ke nilai_akhirs.
3. **Proses**: Sistem menghitung total S/I/A dari presensi_mapels → Disimpan ke catatan_wali_kelas.
4. **Output (Leger)**: Wali kelas membuka halaman Leger. Sistem menarik data dari nilai_akhirs + catatan_wali_kelas.
5. **Final**: Wali kelas menyalin angka dari layar laptop ke Buku Rapor fisik.

**ERD ini sudah sangat efisien, tidak ada data ganda (redundant), dan mencakup seluruh kebutuhan sekolah dasar modern.**

## 🗄️ Struktur Database

### Migrasi Laravel (Urutan Pembuatan)

#### 1. Pengguna & Profil

```php
// users (Laravel default - untuk login)
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});

// profiles (Biodata lengkap guru/kepsek)
Schema::create('profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('nip')->nullable()->unique();
    $table->string('nama_lengkap');
    $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
    $table->date('tanggal_lahir')->nullable();
    $table->string('telepon')->nullable();
    $table->text('alamat')->nullable();
    $table->string('foto')->nullable();
    $table->timestamps();
});
```

#### 2. Data Master Sekolah

```php
// tahun_ajarans
Schema::create('tahun_ajarans', function (Blueprint $table) {
    $table->id();
    $table->string('nama'); // "2024/2025"
    $table->boolean('aktif')->default(false);
    $table->timestamps();
    $table->softDeletes();
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

// siswas
Schema::create('siswas', function (Blueprint $table) {
    $table->id();
    $table->string('nis')->unique();
    $table->string('nisn')->nullable();
    $table->string('nama');
    $table->enum('jenis_kelamin', ['L', 'P']);
    $table->date('tanggal_lahir');
    $table->text('alamat')->nullable();
    $table->enum('status', ['aktif', 'lulus', 'pindah', 'keluar'])->default('aktif');
    $table->date('tanggal_lulus')->nullable();
    $table->timestamps();
    $table->softDeletes();
});

// kelas
Schema::create('kelas', function (Blueprint $table) {
    $table->id();
    $table->string('nama'); // "1A", "6B"
    $table->integer('tingkat_kelas'); // 1 sampai 6
    $table->foreignId('wali_kelas_id')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
    $table->softDeletes();
});
```

#### 3. Absensi Harian

```php
// kehadirans (Absen harian wali kelas - hanya menyimpan yang tidak hadir)
Schema::create('kehadirans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
    $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();

    $table->date('tanggal');
    $table->enum('status', ['S', 'I', 'A']); // Sakit, Izin, Alpha (Hadir tidak disimpan)
    $table->string('keterangan')->nullable();

    $table->timestamps();
});
```

#### 4. Operasional Akademik

```php
// kelas_siswas (Siswa masuk kelas per tahun ajaran)
Schema::create('kelas_siswas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
    $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
    $table->timestamps();
    $table->unique(['siswa_id', 'tahun_ajaran_id']);
});

// penugasan_mengajars (Guru mengajar mapel di kelas tertentu)
Schema::create('penugasan_mengajars', function (Blueprint $table) {
    $table->id();
    $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
    $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
    $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->cascadeOnDelete();
    $table->timestamps();
    $table->softDeletes();
});

// jadwal_mengajars (Jadwal pelajaran per hari)
Schema::create('jadwal_mengajars', function (Blueprint $table) {
    $table->id();
    $table->foreignId('penugasan_mengajar_id')->constrained('penugasan_mengajars')->cascadeOnDelete();
    $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->timestamps();
});
```

#### 4. Presensi & Jurnal Mengajar

```php
// presensi_mapels (Presensi per mata pelajaran)
Schema::create('presensi_mapels', function (Blueprint $table) {
    $table->id();
    $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete();
    $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
    $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->cascadeOnDelete();
    $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
    $table->date('tanggal');
    $table->time('jam_mulai')->nullable();
    $table->enum('status', ['H', 'S', 'I', 'A', 'B'])->default('H');
    $table->text('materi')->nullable();
    $table->string('catatan')->nullable();
    $table->timestamps();
    $table->unique(['siswa_id', 'mata_pelajaran_id', 'tanggal', 'jam_mulai'], 'unique_presensi_mapel');
});

// komponen_nilai (Wadah Nilai dengan Bobot)
Schema::create('komponen_nilai', function (Blueprint $table) {
    $table->id();
    // Relasi ke penugasan_mengajar agar spesifik (Guru A, Mata Pelajaran B, Kelas C)
    $table->foreignId('penugasan_mengajar_id')->constrained('penugasan_mengajar')->onDelete('cascade');
    $table->string('nama'); // "PR Bab 1", "UH 1"
    $table->enum('jenis', ['tugas', 'uh', 'uts', 'uas']);
    $table->integer('bobot')->default(1); // Bobot penilaian
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

### 📝 **Pola Kerja: Input → Hitung → Leger → Salin Manual**

Sistem ini dirancang sebagai **alat bantu hitung** yang bekerja sama dengan **buku rapor manual** (buku hijau/merah) yang masih digunakan di banyak SD Indonesia.

### Guru Mapel:

1. **Input Nilai Harian**: Masukkan nilai UH, tugas, praktek
2. **Input Ujian**: Masukkan nilai UTS dan UAS
3. **Sistem Otomatis**: Hitung rata-rata dengan bobot (contoh: UH 40% + UTS 30% + UAS 30%)

### Wali Kelas:

1. **Login ke Sistem**: Akses dashboard wali kelas
2. **Buka Leger Nilai**: Lihat tabel rekap nilai semua siswa
3. **Ambil Buku Rapor**: Buku rapor asli (manual)
4. **Salin ke Buku**: Copy angka dari layar ke buku rapor tulisan tangan
5. **Generator Deskripsi**: Copy saran narasi rapor yang dihasilkan sistem

### Kepala Sekolah:

1. **Monitoring Real-time**: Pantau perkembangan nilai siswa
2. **Cek Ranking**: Lihat peringkat siswa otomatis
3. **Validasi Data**: Pastikan tidak ada kesalahan input

### 💡 **Keuntungan Sistem Ini:**

-   ✅ **Akurasi**: Tidak ada salah hitung rata-rata manual
-   ✅ **Arsip Digital**: Backup jika buku rapor hilang/rusak/terbakar
-   ✅ **Transparansi**: Kepsek bisa monitor tanpa pinjam buku
-   ✅ **Efisiensi**: Ranking dan statistik dalam hitungan detik
-   ✅ **Fleksibilitas**: Tetap menggunakan buku rapor tradisional

### Admin:

-   **Setup Awal**: Buat user guru & kepsek, data master (tahun ajaran, mapel, siswa)
-   **Plotting Tahunan**: Tentukan siswa masuk kelas mana dan guru mengajar apa

## 💻 Persyaratan Sistem

### Minimum Requirements

-   **OS**: Windows 10+, Linux (Ubuntu 18.04+), macOS 10.14+
-   **RAM**: 2GB minimum, 4GB recommended
-   **Storage**: 500MB free space
-   **CPU**: Dual-core processor

### Software Requirements

-   **PHP**: 8.3 atau lebih tinggi
-   **Composer**: 2.x
-   **Node.js**: 18.x atau lebih tinggi (LTS)
-   **NPM**: 8.x atau lebih tinggi
-   **MySQL**: 8.0 atau MariaDB 10.5+
-   **Web Server**: Apache 2.4+ atau Nginx 1.20+

### Browser Support

-   Chrome 90+
-   Firefox 88+
-   Safari 14+
-   Edge 90+

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

## ⚙️ Konfigurasi Awal

### 1. Setup Database

Pastikan database MySQL sudah dibuat dan konfigurasi di `.env` sudah benar.

### 2. Jalankan Seeder

```bash
php artisan db:seed
```

Seeder akan membuat:

-   User default (admin, guru, kepsek)
-   Data master dasar (tahun ajaran, mata pelajaran)
-   Permissions dan roles

### 3. Setup Storage Link

```bash
php artisan storage:link
```

### 4. Konfigurasi Permissions

Pastikan folder berikut memiliki permission write:

-   `storage/app/public`
-   `storage/logs`
-   `bootstrap/cache`

### 5. Akses Sistem

Gunakan akun default untuk login pertama kali:

-   **Admin**: `admin@sekolah.com` / `password`
-   **Guru**: `guru@sekolah.com` / `password`
-   **Kepsek**: `kepsek@sekolah.com` / `password`

### 6. Setup Data Master

Login sebagai Admin dan lakukan:

1. Buat tahun ajaran aktif
2. Tambahkan data mata pelajaran
3. Input data siswa
4. Buat kelas dan wali kelas
5. Assign guru ke mata pelajaran dan kelas

## 📖 Cara Penggunaan

### Untuk Administrator

1. **Login** dengan akun admin
2. **Setup Tahun Ajaran**: Buat tahun ajaran baru dan set sebagai aktif
3. **Kelola Data Master**: Input mata pelajaran, siswa, dan kelas
4. **Penugasan**: Assign guru ke mata pelajaran dan kelas
5. **Monitoring**: Pantau aktivitas sistem melalui dashboard

### Untuk Guru Mapel

1. **Login** dengan akun guru
2. **Lihat Jadwal**: Cek jadwal mengajar hari ini
3. **Input Presensi Mapel**: Klik jadwal dan input presensi siswa dengan jurnal
4. **Penilaian**: Buat komponen nilai dengan bobot dan input nilai siswa
5. **Sistem Hitung Otomatis**: Sistem hitung rata-rata berdasarkan bobot

### Untuk Wali Kelas

1. **Login** dengan akun wali kelas
2. **Input Absensi Harian**: Catat siswa yang sakit/izin/alpha
3. **Buka Leger Nilai**: Lihat tabel rekap nilai semua siswa per kelas
4. **Ambil Buku Rapor**: Buku rapor manual (buku hijau/merah)
5. **Salin Manual**: Copy angka dari leger digital ke buku rapor tulisan tangan
6. **Generator Deskripsi**: Copy saran narasi rapor untuk deskripsi manual

### Untuk Kepala Sekolah

1. **Login** dengan akun kepsek
2. **Dashboard Overview**: Lihat statistik keseluruhan sekolah
3. **Monitoring Guru**: Pantau aktivitas mengajar dan nilai siswa
4. **Cek Leger**: Validasi nilai dan ranking siswa
5. **Laporan**: Generate laporan statistik sekolah

## 🚀 Production Deployment

### 1. Server Requirements

-   PHP 8.3+
-   MySQL 8.0+
-   Composer
-   Node.js & NPM
-   Web Server (Apache/Nginx)

### 2. Upload & Install

```bash
# Upload files ke server
# SSH ke server
cd /path/to/your/app

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build

# Set permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chown -R www-data:www-data /path/to/your/app
```

### 3. Web Server Configuration

**Apache (.htaccess sudah tersedia):**

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /path/to/your/app/public

    <Directory /path/to/your/app/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx:**

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/app/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 4. SSL Certificate (Opsional)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Generate SSL
sudo certbot --apache -d your-domain.com
```

## 🔧 Troubleshooting

### Common Issues

**1. Permission Denied pada Storage:**

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

**2. Class Not Found setelah Update:**

```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

**3. Assets tidak ter-load:**

```bash
npm run build
php artisan cache:clear
```

**4. Database Connection Error:**

-   Pastikan `.env` database config benar
-   Jalankan `php artisan config:cache` setelah perubahan
-   Cek MySQL service aktif

**5. Route Not Found:**

```bash
php artisan route:clear
php artisan route:cache
```

**6. File Upload Error:**

-   Cek folder `storage/app/public` permissions
-   Jalankan `php artisan storage:link`

### Debug Mode

Untuk development, aktifkan debug di `.env`:

```env
APP_DEBUG=true
APP_ENV=local
```

### Logs

Cek logs aplikasi di `storage/logs/laravel.log`

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

### ✅ Sudah Selesai (v1.0.0)

#### 🔐 Core System

-   [x] Sistem autentikasi dengan role (Admin/Guru/Wali Kelas/Kepsek)
-   [x] Manajemen profil pengguna (terpisah dari login)
-   [x] Upload foto profil dengan validasi
-   [x] Clean Architecture (Controller → Service → Repository)
-   [x] UI responsif dengan Tailwind CSS & Alpine.js

#### 📚 Data Master Management

-   [x] Tahun Ajaran CRUD (aktif/non-aktif, soft delete)
-   [x] Mata Pelajaran CRUD (kode unik, KKM)
-   [x] Siswa CRUD (NIS/NISN, status siswa)
-   [x] Kelas CRUD (nama, tingkat, wali kelas)

#### 👥 Operational Management

-   [x] Penugasan Mengajar (Guru-Mapel-Kelas assignment)
-   [x] Rombongan Belajar (Siswa per kelas per tahun ajaran)
-   [x] Jadwal Mengajar (Penjadwalan otomatis per hari)
-   [x] Presensi Mapel & Harian (Bulk input, auto-detection)
-   [x] Dashboard akademik dengan statistik real-time
-   [x] Modern pagination dengan Tailwind CSS
-   [x] Tom Select untuk dropdown searchable

#### 🧮 Kalkulasi & Leger

-   [x] Kalkulator nilai otomatis dengan bobot
-   [x] Leger nilai digital (tabel rekap per kelas)
-   [x] Ranking siswa otomatis
-   [x] Generator deskripsi rapor otomatis

#### 🎨 UI/UX Enhancements

-   [x] ApexCharts untuk visualisasi data
-   [x] Dashboard cards dengan hover effects
-   [x] Modern table design dengan sorting
-   [x] Responsive mobile-first design
-   [x] Component-based architecture

#### 🧪 Testing & Quality

-   [x] Testing lengkap untuk fitur profil
-   [x] Unit & Feature tests dengan Pest PHP
-   [x] PSR-12 coding standards
-   [x] Clean code principles

### 🚧 Dalam Pengembangan

-   [ ] Export Leger ke Excel/CSV
-   [ ] Template generator deskripsi yang lebih variatif
-   [ ] Dashboard ranking siswa antar kelas
-   [ ] Notifikasi reminder input nilai

### 📋 Rencana Pengembangan Selanjutnya

-   [ ] API untuk integrasi dengan aplikasi mobile guru
-   [ ] Sistem backup otomatis database nilai
-   [ ] Multi-tenant untuk jaringan sekolah
-   [ ] QR Code untuk absensi siswa
-   [ ] Template rapor digital untuk sekolah yang sudah siap
-   [ ] Integration dengan sistem pembayaran SPP
-   [ ] Parent portal untuk monitoring nilai anak
-   [ ] Sistem voting untuk pemilihan ketua kelas

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Test spesifik
php artisan test tests/Feature/ProfileTest.php
```

## 📚 Teknologi

### Backend Stack

-   **Framework**: Laravel 12.x
-   **PHP**: 8.3+
-   **Database**: MySQL 8.0+
-   **Authentication**: Laravel Breeze + Spatie Permission
-   **Testing**: Pest PHP (Unit & Feature tests)
-   **Architecture**: Clean Architecture (Controller → Service → Repository)

### Frontend Stack

-   **Template Engine**: Blade Components
-   **CSS Framework**: Tailwind CSS 3.x
-   **JavaScript**: Alpine.js 3.x
-   **Charts**: ApexCharts (modern, responsive)
-   **Form Enhancement**: Tom Select (searchable dropdowns)
-   **Icons**: Font Awesome 6.x

### Development Tools

-   **Package Manager**: Composer + NPM
-   **Build Tool**: Vite
-   **Code Style**: PSR-12
-   **Version Control**: Git

### Key Features

-   📱 **Responsive Design**: Mobile-first approach
-   🎨 **Component-Based UI**: Reusable Blade components
-   🔐 **Role-Based Access**: Admin, Guru, Kepsek roles
-   📊 **Real-time Dashboard**: Live statistics & charts
-   🔍 **Advanced Search**: Searchable select dropdowns
-   📄 **Modern Pagination**: Custom styled with Tailwind
-   🌐 **SEO Friendly**: Meta tags & semantic HTML

## 📋 Changelog

### v1.0.0 - Production Ready (2024)

-   **Kalkulator Nilai Otomatis**: Hitung rata-rata dengan bobot yang tepat
-   **Leger Nilai Digital**: Tabel rekap nilai semua siswa per kelas
-   **Generator Deskripsi**: Saran narasi rapor otomatis
-   **Ranking Otomatis**: Tentukan juara kelas dalam hitungan detik
-   **Absensi Harian & Mapel**: Sistem absensi lengkap
-   **Arsip Digital**: Backup nilai sebagai arsip sekolah
-   **Modern UI/UX**: Responsive design dengan Tailwind CSS + Alpine.js
-   **Clean Architecture**: Controller → Service → Repository pattern
-   **Comprehensive Testing**: Unit & Feature tests dengan Pest PHP
-   **Production Deployment**: Ready-to-deploy dengan konfigurasi lengkap

### v0.9.0 - Core Features (2024)

-   Authentication & Authorization (Laravel Breeze + Spatie Permission)
-   Master Data Management (Tahun Ajaran, Mata Pelajaran, Siswa, Kelas)
-   Teaching Assignment & Scheduling
-   Class Student Management
-   Profile Management dengan upload foto

### v0.8.0 - Foundation (2024)

-   Laravel 12.x setup dengan clean architecture
-   Database migrations & relationships
-   Basic CRUD operations
-   Role-based access control
-   Responsive UI foundation

## 🔍 Fitur Detail

### 🧮 Kalkulator Nilai Otomatis

-   **Rumus Bobot**: Hitung rata-rata dengan bobot (UH 40% + UTS 30% + UAS 30%)
-   **Konversi Predikat**: Otomatis konversi angka ke A/B/C/D
-   **Ranking Otomatis**: Tentukan juara kelas dalam hitungan detik
-   **Validasi**: Mencegah kesalahan input dan perhitungan

### 📊 Leger Nilai Digital

-   **Tabel Rekap**: Nama siswa ke bawah, mata pelajaran ke samping
-   **Format Standar**: No, Nama, Nilai per Mapel, Total, Rata-rata, Rank
-   **Filter Kelas**: Lihat leger per kelas dan tahun ajaran
-   **Export**: Export tabel ke Excel untuk arsip tambahan

#### 📋 Contoh Tampilan Leger Nilai

| No  | Nama Siswa   | PAI    | PKN    | B.INDO | MTK    | IPA    | IPS    | B.SUNDA | SBK    | Total | Rata-rata | Rank |
| --- | ------------ | ------ | ------ | ------ | ------ | ------ | ------ | ------- | ------ | ----- | --------- | ---- |
| 1   | Ahmad Surya  | 85 (B) | 90 (A) | 88 (B) | 95 (A) | 87 (B) | 82 (B) | 90 (A)  | 88 (B) | 705   | 88.1      | 1    |
| 2   | Siti Aminah  | 90 (A) | 88 (B) | 92 (A) | 78 (C) | 85 (B) | 90 (A) | 85 (B)  | 87 (B) | 695   | 86.9      | 2    |
| 3   | Budi Santoso | 80 (B) | 85 (B) | 78 (C) | 88 (B) | 82 (B) | 75 (C) | 88 (B)  | 80 (B) | 656   | 82.0      | 3    |

**Keterangan:**

-   Angka dalam kurung adalah predikat otomatis (90-100 = A, 80-89 = B, 70-79 = C, <70 = D)
-   Total = Jumlah semua nilai
-   Rata-rata = Total ÷ Jumlah Mata Pelajaran
-   Rank = Peringkat berdasarkan rata-rata tertinggi

### 📝 Generator Deskripsi Otomatis

-   **Narasi Pintar**: Generate deskripsi berdasarkan nilai akhir
-   **Template SD**: Sesuai format rapor SD Indonesia
-   **Copy-Paste Ready**: Siap disalin ke buku rapor manual
-   **Customizable**: Bisa disesuaikan dengan kebutuhan sekolah

#### 📝 Contoh Generator Deskripsi

**Untuk nilai 95 (A):**
"Ananda sangat menguasai materi Matematika. Kemampuan ananda dalam menghitung pecahan dan geometri sangat baik. Pertahankan prestasi ini dengan terus berlatih."

**Untuk nilai 82 (B):**
"Ananda sudah cukup baik dalam memahami materi Matematika. Ananda perlu lebih teliti dalam menghitung dan memahami konsep geometri agar bisa lebih baik lagi."

**Untuk nilai 65 (C):**
"Ananda perlu bimbingan khusus dalam Matematika terutama dalam konsep pecahan dan geometri. Ananda disarankan untuk lebih banyak berlatih dan bertanya kepada guru jika ada kesulitan."

### 📋 Absensi Harian & Mapel

-   **Absensi Harian**: Input wali kelas (S/I/A) - hemat database
-   **Absensi Mapel**: Jurnal mengajar per mata pelajaran
-   **Status Lengkap**: H/S/I/A/B dengan catatan spesifik
-   **Validasi**: Mencegah duplikasi absen

### 📊 Dashboard Analytics

-   **Real-time Stats**: Statistik kehadiran siswa
-   **Charts & Graphs**: Visualisasi data dengan ApexCharts
-   **Wali Kelas Focus**: Dashboard khusus untuk monitoring kelas
-   **Ranking Siswa**: Peringkat berdasarkan nilai rata-rata

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat issue atau pull request.

### Panduan Kontribusi

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan Anda (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Panduan Development

-   Ikuti PSR-12 coding standards
-   Tambahkan tests untuk fitur baru
-   Update dokumentasi jika diperlukan
-   Pastikan semua tests pass sebelum submit PR
-   Gunakan conventional commits untuk commit messages

## 🆘 Dukungan & Bantuan

### Masalah Umum

Jika mengalami masalah, cek:

1. [Troubleshooting Guide](#-troubleshooting) di atas
2. Logs aplikasi di `storage/logs/laravel.log`
3. Pastikan semua dependencies terinstall dengan benar

### Laporkan Bug

Gunakan GitHub Issues untuk melaporkan bug:

-   Deskripsikan langkah reproduksi
-   Sertakan error messages dan logs
-   Spesifikasikan environment (PHP version, OS, dll.)

### Request Fitur

Untuk request fitur baru:

-   Cek apakah fitur sudah ada di [Status Pengembangan](#-status-pengembangan)
-   Buat issue dengan label "enhancement"
-   Jelaskan use case dan benefit fitur tersebut

### Kontak Developer

-   **Repository**: [GitHub](https://github.com/Brynnnn12/sistem-akademik-sekolah)
-   **Issues**: [GitHub Issues](https://github.com/Brynnnn12/sistem-akademik-sekolah/issues)

## 📄 Lisensi

MIT License - lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

---

<p align="center">
  <strong>Dibuat dengan ❤️ untuk memudahkan pengelolaan sekolah dasar</strong>
</p>

<p align="center">
  <a href="#sistem-akademik-sekolah-dasar-sd">Kembali ke Atas</a>
</p>
````
