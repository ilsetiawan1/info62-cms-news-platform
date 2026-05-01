# 🚀 ROADMAP PENGEMBANGAN

## Info Seputar +62

Dokumen ini berisi pembagian phase pengembangan berdasarkan:

* PRD (Product Requirement)
* Struktur Database
* Arsitektur MVC Laravel

Tujuan: agar development **terstruktur, scalable, dan bisa dikerjakan paralel tanpa chaos**.

---

# 🧱 PHASE 0 — Setup & Project Foundation

## 🎯 Goal

Menyiapkan environment dan fondasi project.

## ⚙️ Task

* Install Laravel 12
* Setup database (import schema SQL)
* Konfigurasi `.env`
* Install Tailwind CSS
* Install Laravel Breeze (auth admin)
* Setup layout dasar:

  * `layouts/admin.blade.php`
  * `layouts/app.blade.php`
* Setup routing awal:

  * `/` (public)
  * `/admin` (dashboard)

## 📦 Output

* Project running
* Auth login berfungsi
* Struktur folder sesuai MVC

---

# 🔐 PHASE 1 — Authentication & Authorization

## 🎯 Goal

Membangun sistem login + role (admin & super_admin).

## ⚙️ Task

* Implement role dari database
* Middleware:

  * `AdminMiddleware`
  * `SuperAdminMiddleware`
* Protect admin routes
* Batasi akses:

  * Admin tidak bisa hapus user
  * Admin tidak bisa ubah role

## 📦 Output

* Sistem auth aman
* Role-based access berjalan

---

# 🗂️ PHASE 2 — Category Management (Hierarchical)

## 🎯 Goal

Struktur kategori sebagai fondasi konten.

## ⚙️ Task

* CRUD kategori
* Support parent-child
* Dropdown parent category
* Validasi slug unik

## 📦 Output

* Struktur kategori siap digunakan artikel

---

# 📝 PHASE 3 — Article Core (CRUD)

## 🎯 Goal

Fitur utama: manajemen artikel.

## ⚙️ Task

* CRUD artikel
* Relasi:

  * category_id
  * author_id
* Upload cover image
* Slug otomatis
* Draft & publish system

## 📦 Output

* Artikel bisa dibuat, diedit, dihapus

---

# 🔍 PHASE 4 — SEO & Content Structuring

## 🎯 Goal

Artikel SEO-friendly sesuai PRD.

## ⚙️ Task

* Meta fields:

  * meta_title
  * meta_description
  * keywords
* Validasi:

  * min 300 kata
  * title max 70 karakter
* Inject meta ke HTML head

## 📦 Output

* Artikel siap SEO

---

# 🌐 PHASE 5 — Public Pages (User View)

## 🎯 Goal

User bisa membaca berita dengan UX optimal.

## 🎨 Reference Layout

Gunakan referensi dari:

* **Kompas.com** (struktur berita)
* **Detik.com** (headline & list cepat)

⚠️ Bukan meniru, tapi mengambil pola:

* Hero headline besar
* List artikel vertikal
* Sidebar rekomendasi

## ⚙️ Task

### Homepage

* Hero artikel utama
* List artikel terbaru
* Pagination

### Detail Artikel

* Judul
* Cover image (1200x675)
* Konten rapi (paragraf pendek)

### Rekomendasi

* Berdasarkan kategori
* Random artikel

### Views Tracking

* Increment `views_count`
* Simpan ke `article_views`

## 📦 Output

* Website publik fully usable

---

# 🔥 PHASE 6 — Auto Fetch Article

## 🎯 Goal

Import artikel dari URL eksternal.

## ⚙️ Task

* Input URL
* Scraping:

  * title
  * content
  * image
* Cleaning HTML:

  * remove `<a>`
  * normalize paragraph
* Simpan sebagai draft

## 📦 Output

* Artikel bisa di-generate otomatis

---

# 📊 PHASE 7 — Dashboard & Analytics

## 🎯 Goal

Memberikan insight ke admin.

## ⚙️ Task

* Statistik:

  * hari ini
  * bulanan
  * total
* Artikel populer
* Chart (Chart.js optional)

## 📦 Output

* Dashboard informatif

---

# ⚙️ PHASE 8 — Website Settings

## 🎯 Goal

Dynamic configuration.

## ⚙️ Task

* CRUD settings:

  * site_name
  * logo
  * meta_description
  * social links
* Helper function

## 📦 Output

* Website configurable tanpa ubah code

---

# 🎨 PHASE 9 — UI/UX & Design System

## 🎯 Goal

Meningkatkan kualitas tampilan.

## 🎨 Reference

* Apple (clean spacing)
* Medium (typography)

## ⚙️ Task

* Dark mode (system + toggle)
* Komponen reusable (Blade components)
* Konsistensi spacing

## 📦 Output

* UI modern & clean

---

# 📱 PHASE 10 — PWA (Optional)

## 🎯 Goal

App-like experience.

## ⚙️ Task

* Service worker
* Offline cache
* Installable

## 📦 Output

* Bisa install ke device

---

# 🔀 STRATEGI PARALLEL DEVELOPMENT

## ✅ Bisa Paralel

* Phase 2 (Kategori) ↔ Phase 1 (Auth)
* Phase 4 (SEO) ↔ Phase 3 (Artikel)
* Phase 9 (UI) ↔ semua phase

## ❌ Jangan Paralel

* Auto Fetch sebelum Article selesai
* Public page sebelum data artikel ada

---

# 🎯 REKOMENDASI URUTAN EKSEKUSI

1. Phase 0 → Setup
2. Phase 1 → Auth
3. Phase 2 & 3 → Core CMS
4. Phase 5 → Public View
5. Phase 4 → SEO improvement
6. Phase 6 → Auto Fetch
7. Phase 7–10 → Enhancement

---

# 🧠 CATATAN PENTING

* Jangan numpuk logic di Controller → gunakan Service
* Gunakan Repository untuk query kompleks
* Fokus ke **readability & maintainability**
* Bangun fitur secara incremental, bukan sekaligus

---

Dokumen ini menjadi acuan utama development.
Jika mengikuti phase ini dengan disiplin, project akan:
✅ Terstruktur
✅ Mudah dikembangkan
✅ Tidak chaos saat scaling
