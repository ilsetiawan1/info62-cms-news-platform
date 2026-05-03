<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Article;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyPortalSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create User adminsuper1@gmail.com
        $superAdmin = User::firstOrCreate(
            ['email' => 'adminsuper1@gmail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('123123123'),
                'role' => 'super_admin',
                'status' => true,
            ]
        );

        // 2. Create Categories
        $categories = [
            ['name' => 'Nasional', 'slug' => 'nasional'],
            ['name' => 'Bisnis', 'slug' => 'bisnis'],
            ['name' => 'Teknologi', 'slug' => 'teknologi'],
            ['name' => 'Olahraga', 'slug' => 'olahraga'],
            ['name' => 'Hiburan', 'slug' => 'hiburan'],
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Create 20 Dummy Articles in Indonesian
        $articlesData = [
            // Nasional
            ['title' => 'Pemerintah Resmi Mengesahkan Undang-Undang Baru Terkait Perlindungan Data', 'cat' => 0],
            ['title' => 'Presiden Melakukan Kunjungan Kerja ke Kawasan Timur Indonesia', 'cat' => 0],
            ['title' => 'Pembangunan Infrastruktur Tol Baru Ditargetkan Selesai Tahun Depan', 'cat' => 0],
            ['title' => 'Kementerian Kesehatan Luncurkan Program Pemeriksaan Kesehatan Gratis', 'cat' => 0],
            // Bisnis
            ['title' => 'IHSG Ditutup Menguat Seiring Sentimen Positif Pasar Global', 'cat' => 1],
            ['title' => 'Startup Lokal Mendapat Pendanaan Seri B Senilai Rp 500 Miliar', 'cat' => 1],
            ['title' => 'Harga Emas Meroket, Banyak Investor Beralih ke Aset Safe Haven', 'cat' => 1],
            ['title' => 'Ekspor Kopi Indonesia Menembus Rekor Tertinggi di Kuartal Ini', 'cat' => 1],
            // Teknologi
            ['title' => 'Inovasi AI Terbaru Mampu Mempercepat Diagnosa Medis', 'cat' => 2],
            ['title' => 'Review Smartphone Flagship Terbaru: Performa Kamera Luar Biasa', 'cat' => 2],
            ['title' => 'Perusahaan Teknologi Raksasa Mulai Mengembangkan Kacamata AR', 'cat' => 2],
            ['title' => 'Cara Aman Melindungi Privasi Data Pribadi Anda di Media Sosial', 'cat' => 2],
            // Olahraga
            ['title' => 'Timnas Indonesia Siap Hadapi Pertandingan Kualifikasi Piala Dunia', 'cat' => 3],
            ['title' => 'Atlet Bulu Tangkis Indonesia Sabet Medali Emas di Kejuaraan Asia', 'cat' => 3],
            ['title' => 'Menyambut Gelaran Liga 1 Musim Depan dengan Format Baru', 'cat' => 3],
            ['title' => 'Tips Lari Marathon Bagi Pemula: Persiapan dan Pola Makan', 'cat' => 3],
            // Hiburan
            ['title' => 'Film Karya Sineas Indonesia Berhasil Menembus Festival Film Internasional', 'cat' => 4],
            ['title' => 'Konser Musik Band Legendaris Akan Digelar Bulan Depan di Jakarta', 'cat' => 4],
            ['title' => 'Daftar Serial Drama Terpopuler Minggu Ini yang Wajib Ditonton', 'cat' => 4],
            ['title' => 'Review Album Terbaru Artis Lokal: Eksplorasi Genre Baru yang Segar', 'cat' => 4],
        ];

        foreach ($articlesData as $index => $data) {
            Article::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']) . '-' . rand(100, 999),
                'content' => '<p>Jakarta, Info Seputar +62 &mdash; Ini adalah paragraf pertama dari artikel <strong>' . $data['title'] . '</strong>. Berita ini disajikan sebagai contoh data dummy untuk melihat bagaimana tampilan portal berita publik. Setiap artikel harus memiliki struktur yang baik untuk dibaca oleh pengguna.</p><p>Pemerintah dan berbagai pemangku kepentingan terus berupaya meningkatkan kualitas layanan di bidang terkait. "Kami berkomitmen untuk terus berinovasi," ungkap salah satu narasumber. Dengan adanya perkembangan ini, diharapkan masyarakat bisa merasakan dampaknya secara langsung dalam kehidupan sehari-hari.</p><p>Lebih lanjut, berbagai program telah disiapkan untuk mendukung inisiatif ini. Beberapa langkah strategis akan diumumkan dalam waktu dekat, seiring dengan persiapan matang dari berbagai pihak yang terlibat. Terus pantau <em>Info Seputar +62</em> untuk mendapatkan berita terkini dan paling akurat.</p>',
                'excerpt' => 'Ini adalah cuplikan berita untuk artikel ' . $data['title'] . ' yang disajikan sebagai data dummy.',
                'cover_image' => 'https://picsum.photos/seed/' . ($index + 100) . '/800/500',
                'category_id' => $categoryModels[$data['cat']]->id,
                'author_id' => $superAdmin->id,
                'status' => 'published',
                'published_at' => now()->subDays(rand(0, 10))->subHours(rand(1, 23)),
                'views_count' => rand(100, 5000)
            ]);
        }

        // 4. Create Dummy Ads
        Advertisement::truncate();
        Advertisement::create([
            'title' => 'Promo Diskon Lebaran',
            'image_path' => 'https://picsum.photos/seed/ad1/728/90',
            'url' => 'https://google.com',
            'position' => 'header',
            'status' => 'active',
        ]);
        Advertisement::create([
            'title' => 'Smartphone Baru 2026',
            'image_path' => 'https://picsum.photos/seed/ad2/300/250',
            'url' => 'https://google.com',
            'position' => 'sidebar_top',
            'status' => 'active',
        ]);
        Advertisement::create([
            'title' => 'Paket Liburan Murah',
            'image_path' => 'https://picsum.photos/seed/ad3/300/250',
            'url' => 'https://google.com',
            'position' => 'sidebar_mid',
            'status' => 'active',
        ]);
        Advertisement::create([
            'title' => 'Langganan Hosting Super Cepat',
            'image_path' => 'https://picsum.photos/seed/ad4/728/90',
            'url' => 'https://google.com',
            'position' => 'article_mid',
            'status' => 'active',
        ]);
        Advertisement::create([
            'title' => 'Belajar Coding Online',
            'image_path' => 'https://picsum.photos/seed/ad5/728/90',
            'url' => 'https://google.com',
            'position' => 'article_bottom',
            'status' => 'active',
        ]);
    }
}
