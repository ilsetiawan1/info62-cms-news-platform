🚀 1. Project Overview
Name :
Info Seputar +62
Description :
Platform portal berita digital yang memungkinkan admin membuat, mengelola, dan mempublikasikan artikel secara terstruktur, sementara pengguna umum dapat membaca berita tanpa perlu login.
Goal :
Menyediakan sistem manajemen artikel yang efisien untuk admin sekaligus memberikan pengalaman membaca berita yang cepat, rapi, dan SEO-friendly bagi pengguna.
Target Users :
•	Admin (internal tim redaksi) → membuat & mengelola artikel
•	Pengunjung (public user) → membaca berita tanpa login
________________________________________
🧱 2. Tech Stack
Language : PHP
Framework : Laravel 12
Styling : Tailwind CSS
UI Library : Blade Components (custom reusable components)
Database : MySQL
________________________________________
🏗️ 3. Arsitektur & Struktur Folder
• Menggunakan pendekatan MVC (Model - View - Controller)
• Model: representasi data & relasi database (Eloquent)
• View: Blade / tampilan frontend (user & admin)
• Controller: logic penghubung antara model & view
• Struktur folder rapi, modular, dan scalable
________________________________________
⚙️ 4. Fitur Utama
🧑‍💼 Admin Panel
📊 Dashboard
•	Statistik pengunjung (hari ini, bulan ini, total)
•	Artikel terpopuler (filter: hari ini, 7 hari, 30 hari, all time)
•	Grafik traffic (opsional: Chart.js)
________________________________________
📝 Kelola Artikel
•	Tambah artikel manual
•	Auto-draft (Fetch dari URL)
•	Edit artikel
•	Hapus artikel
•	Draft & publish system
•	Upload cover image
•	SEO fields:
o	meta title
o	meta description
o	keyword
________________________________________
🔗 Auto Fetch Artikel (🔥 fitur keren)
•	Input URL (Kompas, Liputan6, dll)
•	Auto ambil:
o	judul
o	gambar
o	isi artikel
•	Auto cleaning:
o	hapus <a> tag
o	rapikan paragraf
________________________________________
🗂️ Kategori
•	Kategori utama (parent)
•	Sub kategori (child)
•	Struktur hierarki (mirip Kompas)
________________________________________
👥 Kelola Pengguna
• Daftar pengguna (nama, email, role, status)
• Tambah & edit pengguna
• Aktivasi / nonaktifkan akun
• Role: admin & super_admin
• Super admin: full akses (termasuk hapus & ubah role)
• Admin: tidak bisa hapus user & ubah role
________________________________________
⚙️ Pengaturan Website
•	Logo website
•	Nama website
•	Meta deskripsi global
•	Social media links
•	(future) posisi adsense
________________________________________
🌐 User (Public)
📰 Halaman Beranda
•	List artikel terbaru
•	Pagination
•	Highlight artikel
________________________________________
📄 Detail Artikel
•	Judul (SEO friendly)
•	Cover image (1200x675)
•	Isi artikel (rapi & readable)
•	Struktur:
o	paragraf pendek
o	heading (h2, h3)
________________________________________
🔁 Rekomendasi Artikel
•	2 jenis:
o	berdasarkan kategori yang sama
o	random dari kategori lain
________________________________________
🔗 Share Artikel
•	Copy URL
•	Share ke social media
________________________________________
📱 SEO & UX Rules (penting banget)
•	Judul max 60–70 karakter
•	Minimal 300 kata
•	Keyword:
o	di judul
o	paragraf pertama
o	meta description
•	Paragraf max 2–3 kalimat
•	Internal linking tiap 3–4 paragraf

________________________________________
🎨 Tampilan (UI/UX)
• Desain modern clean ala iOS (minimal & fokus konten)
• Dark mode & light mode (auto system + toggle manual)
• Typography rapi & readable
• Card layout dengan rounded & soft shadow
• Spacing lega (mobile & desktop friendly)
• Warna netral + aksen primary
• Transisi halus (hover, button, modal)
• Konsisten di user & admin dashboard
________________________________________
🌗 Dark Mode
• Auto detect dari system (prefers-color-scheme)
• Toggle manual oleh user
• Konsisten di semua halaman
• Kontras tetap nyaman (tidak terlalu gelap/terang)
________________________________________
🎯 Style Guideline
• Banyak white space
• Border tipis & clean
• Shadow halus
• Warna tidak terlalu kontras
________________________________________
📱 PWA (Progressive Web App)
• Installable (bisa ditambahkan ke home screen)
• Offline support (cache halaman/artikel)
• Fast loading & app-like experience
• Push notification (opsional – untuk update artikel terbaru)
