# 📰 Info Seputar +62 - Modern CMS News Platform

[![Laravel Version](https://img.shields.io/badge/Laravel-v12.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-v4.0-38bdf8.svg)](https://tailwindcss.com)

**Info Seputar +62** adalah platform portal berita digital modern berbasis Laravel 12 yang dirancang khusus untuk kenyamanan membaca premium, manajemen konten efisien bagi jurnalis, serta tata letak iklan cerdas yang responsif.

---

## ✨ Fitur Unggulan

### 🔐 Panel Admin Premium (`/seputaradmin`)
*   **Role-Based Access Control (RBAC)**: Pembagian hak akses antara Administrator dan Super Admin.
*   **Manajemen Pengguna**: Pendaftaran, pengelolaan, dan pengaktifan/penonaktifan status penulis/editor.
*   **Profil Kustom**: Halaman edit profil personal yang aman.

### 📝 Manajemen Artikel & Kategori Hierarkis
*   **Sistem Draft & Publish**: Kontrol penuh atas status dan jadwal penayangan artikel.
*   **Hierarki Kategori**: Pengelompokan artikel dalam kategori induk (*Parent*) dan anak (*Sub-category*).
*   **Optimalisasi SEO**: Formulir khusus untuk meta title, deskripsi meta, dan kata kunci SEO untuk setiap artikel.

### 🔌 Auto-Fetch Artikel URL (Scraper)
*   **Impor Berita Otomatis**: Mendukung pengimporan berita dari tautan URL luar secara instan.
*   **Pembersih Tag Otomatis**: Secara otomatis membersihkan elemen HTML yang tidak perlu, menyaring gambar, dan merapikan format konten agar seragam.

### 🎯 Arsitektur Wing Advertisement (Iklan Sayap Melayang)
*   **Asimetris 4-Slot**: Tata letak vertikal bertingkat di luar container grid 1200px utama:
    *   *Sayap Kiri*: Slot Atas (160x380px) & Slot Bawah (160x204px)
    *   *Sayap Kanan*: Slot Atas (160x204px) & Slot Bawah (160x380px)
*   **Integrasi Penuh Database**: Atur URL tujuan, unggah berkas gambar, status (aktif/nonaktif), serta batas waktu tayang (`start_date` & `end_date`).
*   **Otomasi Pembersihan Cache & Fallback**:
    *   Menggunakan Eloquent Event Listeners pada model `Advertisement` untuk mengosongkan cache frontend secara otomatis saat ada pembaruan di admin.
    *   Tampilan otomatis beralih ke wireframe placeholder informatif jika iklan kosong.
*   **Desain Sangat Responsif**: Tersembunyi otomatis pada lebar monitor di bawah 1280px (Tablet & Mobile) demi menjaga kegunaan tata letak konten.

### 🌐 Pengalaman Pengguna (UX/UI) Publik
*   **Smart Sidebar Scroll**: Pembatas menu kategori sidebar desktop (`max-h-[240px]`) dilengkapi dengan scrollbar halus tersembunyi (*custom hide scrollbar*).
*   **Widget Real-Time**: Widget Hari, Tanggal format Indonesia, dan Jam Digital yang berdetik waktu lokal secara dinamis.
*   **Topik Populer**: Widget tagar topik terhangat dengan integrasi pencarian cepat.
*   **Jejaring & Corporate Pages**:
    *   Halaman landing page jaringan kustom berstatus *Coming Soon* dengan desain layar penuh (`h-screen overflow-hidden`) yang adaptif.
    *   Halaman statis korporat terintegrasi penuh (Tentang Kami, Pedoman Media Siber, Kebijakan Privasi).

---

## 🧱 Teknologi Utama

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Database**: MySQL
-   **Frontend**: Tailwind CSS v4.0 (Blade Templating)
-   **Autentikasi**: Laravel Breeze (Kustomisasi Admin Guard)
-   **Caching**: Cache::remember & Eloquent Observers

---

## ⚙️ Petunjuk Pemasangan

### 1. Kloning Repositori
```bash
git clone https://github.com/ilsetiawan1/info62-cms-news-platform.git
cd info62-cms-news-platform
```

### 2. Pemasangan Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan
Salin berkas contoh `.env` dan atur konfigurasi database Anda:
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Migrasi & Seed Database
```bash
php artisan migrate --seed
```

### 5. Jalankan Server Lokal
```bash
# Jalankan server Laravel
php artisan serve

# Jalankan kompilasi frontend (Tailwind)
npm run dev
```

---

## 📂 Struktur Direktori Utama

*   `app/Http/Controllers/PublicController.php` - Logika kontrol halaman publik dan pemetaan global.
*   `app/Models/Advertisement.php` - Model Iklan dengan Event Listener pembersih cache.
*   `resources/views/layouts/public.blade.php` - Layout utama publik (Navbar, Footer, Wing Ads).
*   `resources/views/public/` - Halaman-halaman publik (Home, Artikel, Kategori, Search, Coming-Soon).
*   `resources/views/admin/` - Panel Admin (Dashboard, Kelola Artikel, Pengguna, Kategori, Iklan).