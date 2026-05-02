# 🗺️ ROADMAP PENGEMBANGAN
## Info Seputar +62
> Disusun berdasarkan PRD · Laravel 12 · MySQL · Tailwind CSS

---

## ✅ PHASE 0 — Setup & Project Foundation
**Status: SELESAI**

- [x] Install Laravel 12
- [x] Setup database & import schema SQL
- [x] Konfigurasi `.env`
- [x] Install Tailwind CSS
- [x] Install Laravel Breeze (Blade)
- [x] Setup routing awal `/` dan `/seputaradmin`
- [x] Hapus fitur register (admin-only access)

---

## 🔐 PHASE 1 — Authentication & Authorization
**Status: SELESAI**
**Goal:** Sistem login admin yang aman dengan role-based access control

### Tasks
- [x] Update `User` model — tambah helper `isAdmin()`, `isSuperAdmin()`, `isActive()`
- [x] Buat `AdminMiddleware` — cek login + role + status aktif
- [x] Buat `SuperAdminMiddleware` — khusus fitur super_admin
- [x] Register middleware alias di `bootstrap/app.php`
- [x] Update `AuthenticatedSessionController` — redirect ke dashboard + validasi role
- [x] Update `routes/web.php` — admin routes + middleware protection
- [x] Buat `AdminUserSeeder` — seed super admin pertama
- [x] Update `tailwind.config.js` — aktifkan `darkMode: 'class'`
- [x] Redesign `auth/login.blade.php` — custom admin login page
- [x] Buat `layouts/admin.blade.php` — admin layout (sidebar, topbar, dark mode)
- [x] Buat `Admin/DashboardController.php`
- [x] Buat `admin/dashboard.blade.php` — halaman dashboard awal

### Output
- Login admin di `/seputaradmin/login` dengan desain custom
- Middleware `admin` dan `super_admin` aktif
- Dashboard `/seputaradmin/dashboard` terproteksi
- Dark mode toggle berfungsi

---

## 👥 PHASE 2 — User Management
**Status: SELESAI**
**Goal:** Admin bisa kelola pengguna dengan batasan sesuai role

### Tasks
- [x] Buat `Admin/UserController.php` — CRUD + toggle status + update role
- [x] Buat `admin/users/index.blade.php` — tabel daftar user
- [x] Buat `admin/users/create.blade.php` — form tambah user
- [x] Buat `admin/users/edit.blade.php` — form edit user
- [x] Tambahkan route user management di `routes/web.php`
- [x] Batasan akses:
  - Admin biasa: bisa lihat, tambah, edit, toggle status
  - Super admin: + bisa hapus & ubah role

### Output
- CRUD pengguna lengkap
- Super admin bisa hapus & ubah role
- Admin biasa tidak bisa hapus & ubah role

---

## 🗂️ PHASE 3 — Category Management
**Status: SELESAI**
**Goal:** Buat hierarki kategori untuk artikel (mendukung parent-child)

### Tasks
- [x] Buat `Category` model + relasi `parent()` dan `children()`
- [x] Buat `Admin/CategoryController.php` — CRUD
- [x] Buat `admin/categories/index.blade.php` — tabel + tree view
- [x] Buat `admin/categories/create.blade.php` — form + dropdown parent
- [x] Buat `admin/categories/edit.blade.php`
- [x] Auto-generate slug dari nama kategori
- [x] Validasi slug unik

### Output
- Kategori parent & child berfungsi
- Slug otomatis & unik
- Siap digunakan sebagai relasi artikel

---

## 📝 PHASE 4 — Article Management (CRUD)
**Status: SELESAI**
**Goal:** Fitur utama CMS — manajemen artikel lengkap

### Tasks
- [x] Buat `Article` model + relasi ke `Category` & `User`
- [x] Buat `Admin/ArticleController.php` — CRUD + publish/draft
- [x] Buat `admin/articles/index.blade.php` — tabel artikel + filter status
- [x] Buat `admin/articles/create.blade.php`:
  - Form judul, konten (rich text/textarea), kategori
  - Upload cover image (resize & simpan)
  - Draft / Publish / Archived toggle
  - SEO fields: meta_title, meta_description, keywords
  - Auto-generate slug dari judul
- [x] Buat `admin/articles/edit.blade.php`
- [ ] Validasi:
  - Judul max 70 karakter
  - Konten min 300 kata
- [ ] Storage: simpan cover image ke `storage/app/public/covers`

### Output
- CRUD artikel lengkap
- Draft & publish system berjalan
- Upload cover image berfungsi
- SEO fields tersimpan

---

## 🔍 PHASE 5 — SEO Integration
**Status: SELESAI**
**Goal:** Artikel SEO-friendly, meta tags ter-inject ke HTML head

### Tasks
- [x] Meta title, description, keywords di `public/article.blade.php`
- [x] Canonical URL per artikel
- [x] Open Graph tags (og:title, og:image, og:description)
- [x] Twitter Card tags

### Output
- Meta tags SEO lengkap di setiap halaman artikel

---

## 🌐 PHASE 6 — Public Pages (User View)
**Status: SELESAI**
**Goal:** Halaman publik yang bisa dibaca siapa saja tanpa login

### Tasks

#### Homepage (`/`)
- [x] Buat `PublicController.php`
- [x] Layout `layouts/public.blade.php` — glassmorphism navbar, footer
- [x] `public/home.blade.php` — Hero + Grid + Pagination

#### Detail Artikel (`/artikel/{slug}`)
- [x] `public/article.blade.php` dengan konten, breadcrumb, share buttons
- [x] Tracking views (IP + 24h cooldown + increment views_count)
- [x] Rekomendasi artikel (3 serupa + 3 random)
- [x] Sidebar populer

#### Kategori (`/kategori/{slug}`)
- [x] `public/category.blade.php` + sub-kategori chips + pagination

### Output
- Website publik fully usable
- Views tracking berjalan
- Share artikel berfungsi (WA, X, Facebook, Copy Link)
- Rekomendasi muncul

---

## 🔥 PHASE 7 — Auto Fetch Article
**Goal:** Import & auto-draft artikel dari URL eksternal

### Tasks
- [ ] Buat `Admin/ArticleFetchController.php`
- [ ] Buat `app/Services/ArticleFetcherService.php`:
  - Ambil HTML dari URL (Guzzle)
  - Parsing judul, konten, gambar (DOMDocument / Symfony DomCrawler)
  - Cleaning: hapus tag `<a>`, `<script>`, `<style>`, rapikan paragraf
  - Download & simpan gambar cover
- [ ] Buat `admin/articles/fetch.blade.php` — form input URL + preview hasil
- [ ] Simpan sebagai draft, admin bisa edit sebelum publish
- [ ] Support minimal: Kompas.com, Liputan6.com, Detik.com

### Output
- Admin input URL → artikel otomatis ter-draft
- Konten sudah bersih dari link & script
- Cover image otomatis tersimpan

---

## 📊 PHASE 8 — Dashboard & Analytics
**Goal:** Dashboard informatif dengan statistik real

### Tasks
- [ ] Update `DashboardController` — query stats dari DB:
  - Total artikel, published, draft
  - Total pengguna
  - Views hari ini, bulan ini, all time
- [ ] Artikel terpopuler (filter: hari ini / 7 hari / 30 hari / all time)
- [ ] Update `admin/dashboard.blade.php`:
  - Stat cards
  - Tabel artikel terpopuler
  - Chart traffic (Chart.js — views per hari, 7 hari terakhir)
- [ ] Middleware penghitungan views: simpan IP + timestamp ke `article_views`

### Output
- Dashboard dengan data real
- Chart traffic (Chart.js)
- Tabel artikel terpopuler

---

## ⚙️ PHASE 9 — Website Settings
**Goal:** Konfigurasi website dinamis tanpa ubah kode

### Tasks
- [ ] Buat `Admin/SettingController.php` — CRUD settings
- [ ] Buat `app/Helpers/SettingHelper.php` — fungsi `setting('key')`
- [ ] Buat `admin/settings/index.blade.php`:
  - Upload logo
  - Nama website
  - Meta description global
  - Link social media (Facebook, Instagram, Twitter/X, YouTube)
- [ ] Integrasi: gunakan `setting('site_name')` di layout & SEO component
- [ ] Register helper di `composer.json` autoload

### Output
- Settings bisa diubah dari panel tanpa deploy ulang
- Logo, nama, social link ter-apply ke semua halaman

---

## 🎨 PHASE 10 — UI/UX Polish & PWA
**Goal:** Meningkatkan kualitas visual dan pengalaman pengguna

### Tasks

#### UI Polish
- [ ] Audit konsistensi komponen Blade di seluruh halaman
- [ ] Tambahkan animasi transisi halus (hover, card, button)
- [ ] Responsif sempurna di mobile & desktop
- [ ] Dark mode konsisten di semua halaman (public + admin)
- [ ] Komponen reusable: `<x-alert>`, `<x-badge>`, `<x-card>`, `<x-pagination>`

#### PWA
- [ ] Buat `manifest.json`
- [ ] Buat `service-worker.js` — cache halaman & artikel
- [ ] Installable (Add to Home Screen)
- [ ] Offline support untuk halaman yang sudah di-cache
- [ ] (Opsional) Push notification untuk artikel terbaru

### Output
- UI modern & konsisten
- Bisa diinstall sebagai app di HP
- Offline support berjalan

---

## 🔀 Urutan Eksekusi yang Disarankan

```
Phase 0  ✅ Done
Phase 1  → Auth & Authorization         (sekarang)
Phase 2  → User Management
Phase 3  → Category Management
Phase 4  → Article Management
Phase 5  → SEO Integration
Phase 6  → Public Pages
Phase 7  → Auto Fetch
Phase 8  → Dashboard Analytics
Phase 9  → Settings
Phase 10 → UI Polish + PWA
```

## ⚡ Yang Bisa Paralel
- Phase 5 (SEO) bisa dikerjakan bersamaan Phase 4 (Article)
- Phase 10 (UI Polish) bisa sedikit-sedikit tiap phase

## ❌ Yang Tidak Boleh Dulu
- Phase 7 (Auto Fetch) sebelum Phase 4 (Article) selesai
- Phase 6 (Public Pages) sebelum Phase 4 (Article) ada datanya
- Phase 8 (Analytics) sebelum Phase 6 (views tracking) jalan

---

> Dokumen ini menjadi acuan utama. Ikuti urutan ini dan project akan **terstruktur, scalable, dan tidak chaos**.