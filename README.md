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

-   🔐 **Authentication & Authorization** - Laravel Breeze + Spatie Permission (Admin, Guru, Kepsek)
-   👨‍🏫 **Manajemen Profil** - Biodata lengkap guru/kepsek dengan upload foto
-   📚 **Data Master** - Tahun Ajaran, Mata Pelajaran, Siswa, Kelas (CRUD lengkap)
-   👥 **Rombongan Belajar** - Plotting siswa ke kelas per tahun ajaran
-   📅 **Penugasan Mengajar** - Assignment guru-mapel-kelas per tahun ajaran
-   📋 **Jadwal Mengajar** - Penjadwalan otomatis per hari (Senin-Sabtu)
-   📝 **Presensi Mapel** - Absensi per mata pelajaran dengan jurnal mengajar
-   👁️ **Dashboard Wali Kelas** - Monitoring kehadiran siswa dengan statistik detail
-   🎯 **Promotion & Graduation** - Kenaikan kelas otomatis + kelulusan siswa
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
````

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

#### 3. Operasional Akademik

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

### ✅ Sudah Selesai

#### 🔐 Core System

-   [x] Sistem autentikasi dengan role (Admin/Guru/Kepsek)
-   [x] Manajemen profil pengguna (terpisah dari login)
-   [x] Upload foto profil
-   [x] Clean Architecture (Controller → Service → Repository)
-   [x] UI responsif dengan Tailwind CSS & Alpine.js

#### 📚 Master Data

-   [x] Tahun Ajaran management (CRUD lengkap)
-   [x] Mata Pelajaran management (CRUD + soft delete)
-   [x] Siswa management (CRUD + soft delete)
-   [x] Kelas management (CRUD + wali kelas)

#### 👥 Operational Management

-   [x] Penugasan Mengajar (Guru - Mapel - Kelas assignment)
-   [x] Rombongan Belajar (Siswa per kelas per tahun ajaran)
-   [x] **Jadwal Mengajar** (Penjadwalan otomatis berdasarkan hari & jam)
    -   Jadwal per hari (Senin - Sabtu)
    -   Jam mulai & selesai pembelajaran
    -   Integrasi dengan penugasan mengajar
-   [x] **Presensi Mapel & Jurnal Mengajar** (Absensi per mata pelajaran dengan bulk input)
    -   **Deteksi Otomatis Jadwal**: Sistem otomatis menampilkan jadwal mengajar hari ini
    -   **Quick Access**: Guru langsung klik jadwal tanpa input manual
    -   **Smart Detection**: Tanggal & jam otomatis terisi sesuai jadwal
    -   Bulk input presensi dengan status: Hadir, Sakit, Izin, Alpha, Bolos
    -   Jurnal mengajar untuk mencatat materi pembelajaran
    -   Validasi otomatis berdasarkan penugasan mengajar
    -   Riwayat jurnal dengan filter kelas, mapel, dan tanggal
-   [x] Dashboard akademik dengan statistik real-time
-   [x] Modern pagination dengan Tailwind
-   [x] Tom Select untuk dropdown searchable

#### 🎨 UI/UX Enhancements

-   [x] ApexCharts untuk visualisasi data
-   [x] Dashboard cards dengan hover effects
-   [x] Modern table design dengan sorting
-   [x] Responsive mobile-first design
-   [x] Component-based architecture

#### 🧪 Testing

-   [x] Testing lengkap untuk fitur profil
-   [x] Unit & Feature tests dengan Pest PHP

### 🚧 Dalam Proses

-   [ ] Penilaian harian (komponen nilai)
-   [ ] Input nilai siswa per komponen
-   [ ] Kalkulasi nilai akhir
-   [ ] Generate rapor akhir (PDF)

### 📋 Rencana Selanjutnya

-   [ ] Dashboard dengan statistik lanjutan
-   [ ] Export data ke Excel/CSV
-   [ ] API untuk mobile app
-   [ ] Multi-tenant untuk multiple sekolah
-   [ ] Notifikasi email/SMS
-   [ ] Backup & restore otomatis

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

### Backend Stack

-   **Framework**: Laravel 12.x
-   **PHP**: 8.3+
-   **Database**: MySQL
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

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat issue atau pull request.

## 📄 Lisensi

MIT License

---

<p align="center">
  <strong>Dibuat untuk memudahkan pengelolaan sekolah dasar ❤️</strong>
</p>
# #     C h a n g e l o g 
 
 # # #   v 1 . 0 . 0   -   P r o d u c t i o n   R e a d y   ( 2 0 2 4 ) 
 
 -     * * C o m p l e t e   A t t e n d a n c e   S y s t e m * * :   P r e s e n s i   m a p e l   d e n g a n   j u r n a l   m e n g a j a r   o t o m a t i s 
 -     * * D a s h b o a r d   A n a l y t i c s * * :   R e a l - t i m e   s t a t i s t i c s   u n t u k   w a l i   k e l a s 
 -     * * M o d e r n   U I / U X * * :   R e s p o n s i v e   d e s i g n   d e n g a n   T a i l w i n d   C S S   +   A l p i n e . j s 
 -     * * C l e a n   A r c h i t e c t u r e * * :   C o n t r o l l e r     S e r v i c e     R e p o s i t o r y   p a t t e r n 
 -     * * C o m p r e h e n s i v e   T e s t i n g * * :   U n i t   &   F e a t u r e   t e s t s   d e n g a n   P e s t   P H P 
 -     * * P r o d u c t i o n   D e p l o y m e n t * * :   R e a d y - t o - d e p l o y   d e n g a n   k o n f i g u r a s i   l e n g k a p 
 
 # # #   v 0 . 9 . 0   -   C o r e   F e a t u r e s   ( 2 0 2 4 ) 
 
 -     A u t h e n t i c a t i o n   &   A u t h o r i z a t i o n   ( L a r a v e l   B r e e z e   +   S p a t i e   P e r m i s s i o n ) 
 -     M a s t e r   D a t a   M a n a g e m e n t   ( T a h u n   A j a r a n ,   M a t a   P e l a j a r a n ,   S i s w a ,   K e l a s ) 
 -     T e a c h i n g   A s s i g n m e n t   &   S c h e d u l i n g 
 -     C l a s s   S t u d e n t   M a n a g e m e n t 
 -     P r o f i l e   M a n a g e m e n t   d e n g a n   u p l o a d   f o t o 
 
 # # #   v 0 . 8 . 0   -   F o u n d a t i o n   ( 2 0 2 4 ) 
 
 -     L a r a v e l   1 2 . x   s e t u p   d e n g a n   c l e a n   a r c h i t e c t u r e 
 -     D a t a b a s e   m i g r a t i o n s   &   r e l a t i o n s h i p s 
 -     B a s i c   C R U D   o p e r a t i o n s 
 -     R o l e - b a s e d   a c c e s s   c o n t r o l 
 -     R e s p o n s i v e   U I   f o u n d a t i o n 
 
 
