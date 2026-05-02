<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create the author
        $author = User::firstOrCreate(
            ['email' => 'isyandi@info62.com'],
            [
                'name'     => 'Isyandi',
                'password' => bcrypt('123123123'),
                'role'     => 'admin',
                'status'   => true,
            ]
        );

        $categories = Category::all();

        $articlesByCategory = [
            'Nasional' => [
                [
                    'title'            => 'Pemerintah Siapkan Anggaran Triliunan untuk Infrastruktur 2025',
                    'excerpt'          => 'Pemerintah Indonesia mengumumkan paket anggaran besar untuk percepatan pembangunan infrastruktur di seluruh pelosok nusantara tahun 2025.',
                    'content'          => '<p>Jakarta — Pemerintah Republik Indonesia resmi mengalokasikan anggaran sebesar Rp 400 triliun untuk pembangunan infrastruktur nasional pada tahun anggaran 2025. Hal ini disampaikan langsung oleh Menteri Keuangan dalam konferensi pers yang digelar di kantor Kemenkeu, Senin (01/05).</p><p>Anggaran tersebut akan difokuskan pada pembangunan jalan tol baru di Kalimantan dan Sulawesi, revitalisasi pelabuhan, serta pengembangan jaringan kereta api cepat antar kota. "Ini adalah bagian dari komitmen kami untuk pemerataan pembangunan," ujar Menteri Keuangan.</p><p>Program ini diharapkan menyerap lebih dari 2 juta tenaga kerja baru dan meningkatkan konektivitas antardaerah secara signifikan hingga akhir tahun 2025.</p>',
                    'meta_title'       => 'Anggaran Infrastruktur 2025: Pemerintah Siapkan Rp 400 Triliun',
                    'meta_description' => 'Pemerintah Indonesia mengalokasikan Rp 400 triliun untuk infrastruktur 2025, fokus pada jalan tol, pelabuhan, dan kereta cepat.',
                    'keywords'         => 'infrastruktur, anggaran 2025, pembangunan nasional, jalan tol',
                ],
                [
                    'title'            => 'Badan Pusat Statistik Rilis Data Pertumbuhan Ekonomi Q1 2025',
                    'excerpt'          => 'BPS merilis data resmi pertumbuhan ekonomi Indonesia pada kuartal pertama 2025 yang melampaui ekspektasi berbagai lembaga keuangan internasional.',
                    'content'          => '<p>Jakarta — Badan Pusat Statistik (BPS) Indonesia merilis laporan resmi yang menunjukkan pertumbuhan ekonomi nasional sebesar 5,3% pada kuartal pertama 2025 (Q1 2025) secara tahunan (year-on-year). Angka ini melampaui perkiraan berbagai lembaga keuangan internasional seperti World Bank dan IMF yang memproyeksikan kisaran 5,0%.</p><p>Kepala BPS menyatakan bahwa sektor konsumsi rumah tangga menjadi motor utama pertumbuhan, disusul oleh ekspor komoditas yang mulai pulih. Sektor digital dan e-commerce juga turut berkontribusi signifikan dengan pertumbuhan 18% secara tahunan.</p><p>"Capaian ini menunjukkan ketahanan ekonomi kita di tengah ketidakpastian global," kata Kepala BPS dalam keterangan pers-nya.</p>',
                    'meta_title'       => 'Pertumbuhan Ekonomi Q1 2025: Indonesia Tumbuh 5.3 Persen',
                    'meta_description' => 'BPS mengumumkan pertumbuhan ekonomi Indonesia Q1 2025 sebesar 5,3%, melampaui proyeksi berbagai lembaga internasional.',
                    'keywords'         => 'BPS, pertumbuhan ekonomi, Q1 2025, ekonomi Indonesia',
                ],
            ],
            'Teknologi' => [
                [
                    'title'            => 'Startup Lokal Raih Pendanaan Seri B Senilai 50 Juta Dolar AS',
                    'excerpt'          => 'Sebuah startup teknologi asal Bandung berhasil meraih pendanaan Seri B senilai 50 juta dolar AS, menjadi salah satu yang terbesar tahun ini.',
                    'content'          => '<p>Bandung — Startup teknologi asal Bandung, TechNusa, berhasil menutup putaran pendanaan Seri B senilai USD 50 juta yang dipimpin oleh firma modal ventura Singapura, GreenCapital Asia. Pendanaan ini menjadi salah satu yang terbesar untuk startup tahap awal dari Indonesia pada tahun 2025.</p><p>CEO TechNusa, Reza Aditya, menyatakan bahwa dana segar ini akan digunakan untuk ekspansi ke pasar Asia Tenggara, terutama Vietnam dan Thailand, serta memperkuat tim riset dan pengembangan produk di Bandung.</p><p>TechNusa sendiri bergerak di bidang logistik berbasis AI, membantu ribuan UMKM mengoptimalkan rantai pasok mereka dengan teknologi machine learning yang telah dipatenkan.</p>',
                    'meta_title'       => 'TechNusa Raih Pendanaan Seri B USD 50 Juta untuk Ekspansi Asia',
                    'meta_description' => 'Startup teknologi asal Bandung, TechNusa, berhasil meraih pendanaan Seri B senilai USD 50 juta dari investor Singapura untuk ekspansi Asia Tenggara.',
                    'keywords'         => 'startup Indonesia, pendanaan Seri B, TechNusa, teknologi logistik',
                ],
                [
                    'title'            => 'Indonesia Targetkan 1 Juta Programmer Perempuan di 2026',
                    'excerpt'          => 'Kementerian Komunikasi meluncurkan program ambisius untuk mencetak 1 juta programmer perempuan Indonesia yang kompeten di bidang teknologi hingga tahun 2026.',
                    'content'          => '<p>Jakarta — Kementerian Komunikasi dan Informatika (Kominfo) resmi meluncurkan program "Perempuan Maju Digital" yang menargetkan lahirnya 1 juta programmer perempuan Indonesia yang terampil dan bersertifikat hingga tahun 2026.</p><p>Program ini akan dijalankan bekerja sama dengan lebih dari 200 universitas, 50 bootcamp teknologi, dan puluhan perusahaan teknologi multinasional yang beroperasi di Indonesia. Peserta akan mendapatkan pelatihan gratis dalam bidang pemrograman web, mobile development, dan data science.</p><p>"Kami percaya bahwa kesetaraan gender di dunia teknologi bukan hanya soal keadilan, tapi juga soal kemajuan bangsa," ujar Menteri Kominfo dalam peluncuran program tersebut.</p>',
                    'meta_title'       => 'Program 1 Juta Programmer Perempuan Indonesia 2026 Diluncurkan',
                    'meta_description' => 'Kominfo luncurkan program Perempuan Maju Digital yang menargetkan 1 juta programmer perempuan Indonesia terampil pada 2026.',
                    'keywords'         => 'programmer perempuan, Kominfo, teknologi, digital Indonesia',
                ],
            ],
            'Olahraga' => [
                [
                    'title'            => 'Timnas Indonesia Lolos ke Babak Final Piala AFF 2025',
                    'excerpt'          => 'Setelah perjuangan panjang, Timnas Senior Indonesia akhirnya berhasil lolos ke babak final Piala AFF 2025 usai mengalahkan Thailand dengan skor 2-1.',
                    'content'          => '<p>Kuala Lumpur — Timnas Senior Indonesia menorehkan sejarah dengan berhasil melaju ke babak final Piala AFF 2025 setelah mengalahkan Thailand dengan skor tipis 2-1 di leg kedua semifinal yang berlangsung di Stadion Rajamangala, Bangkok.</p><p>Gol kemenangan dicetak oleh striker andalan, Marcelino Ferdinand, pada menit ke-87 lewat tendangan bebas yang tak terbendung penjaga gawang Thailand. Hasil ini membuat Indonesia unggul agregat 3-2 setelah di leg pertama bermain imbang 1-1 di Jakarta.</p><p>Di final, Garuda akan berhadapan dengan Vietnam yang berhasil menyingkirkan Malaysia. Laga final dijadwalkan berlangsung pada 15 Mei 2025 di Jakarta.</p>',
                    'meta_title'       => 'Timnas Indonesia Lolos Final Piala AFF 2025 Kalahkan Thailand 2-1',
                    'meta_description' => 'Indonesia lolos ke final Piala AFF 2025 usai mengalahkan Thailand 2-1, akan berhadapan dengan Vietnam di final tanggal 15 Mei.',
                    'keywords'         => 'Timnas Indonesia, Piala AFF 2025, sepak bola, final',
                ],
                [
                    'title'            => 'Atlet Renang Indonesia Pecahkan Rekor Asia di Kejuaraan Dunia Junior',
                    'excerpt'          => 'Atlet renang muda Indonesia berhasil memecahkan rekor Asia untuk nomor 100 meter gaya bebas putra di Kejuaraan Dunia Renang Junior yang digelar di Jepang.',
                    'content'          => '<p>Tokyo — Atlet renang muda Indonesia, Farrel Atmadja (18 tahun), membuat seluruh Indonesia bangga setelah berhasil memecahkan rekor Asia untuk nomor 100 meter gaya bebas putra pada Kejuaraan Dunia Renang Junior FINA yang berlangsung di Tokyo Aquatics Centre, Jepang.</p><p>Farrel mencatatkan waktu 47,82 detik, memecahkan rekor Asia yang sebelumnya dipegang oleh perenang asal Tiongkok sejak 2019. Prestasi luar biasa ini sekaligus mengantarkannya meraih medali emas pertama Indonesia di kejuaraan bergengsi ini.</p><p>"Saya dedikasikan medali ini untuk Indonesia dan kedua orang tua saya," ujar Farrel dengan mata berkaca-kaca di atas podium. Pelatihnya mengungkapkan bahwa Farrel adalah kandidat kuat untuk tampil di Olimpiade 2028 Los Angeles.</p>',
                    'meta_title'       => 'Farrel Atmadja Pecahkan Rekor Asia Renang 100m Bebas di Kejuaraan Dunia Junior',
                    'meta_description' => 'Perenang muda Indonesia Farrel Atmadja memecahkan rekor Asia di nomor 100m gaya bebas dengan waktu 47.82 detik di Kejuaraan Dunia Junior.',
                    'keywords'         => 'renang Indonesia, rekor Asia, Farrel Atmadja, FINA, olahraga',
                ],
            ],
            'Politik' => [
                [
                    'title'            => 'DPR Sahkan Undang-Undang Data Pribadi yang Baru',
                    'excerpt'          => 'Dewan Perwakilan Rakyat resmi mengesahkan Undang-Undang Perlindungan Data Pribadi yang diperbarui dengan berbagai ketentuan baru yang lebih ketat.',
                    'content'          => '<p>Jakarta — Dewan Perwakilan Rakyat (DPR) Republik Indonesia resmi mengesahkan revisi Undang-Undang Perlindungan Data Pribadi (UU PDP) dalam sidang paripurna yang berlangsung Selasa (30/04). UU yang baru ini hadir dengan sejumlah ketentuan yang jauh lebih ketat dibandingkan versi sebelumnya.</p><p>Beberapa poin krusial dalam UU PDP baru ini antara lain: kewajiban bagi perusahaan untuk mendapat persetujuan eksplisit sebelum memproses data pengguna, sanksi denda hingga 2% dari pendapatan tahunan bagi perusahaan yang melanggar, serta pembentukan Komisi Perlindungan Data yang independen.</p><p>UU ini disambut positif oleh berbagai kalangan, namun beberapa pelaku industri startup menyatakan perlunya masa transisi yang lebih panjang untuk adaptasi.</p>',
                    'meta_title'       => 'DPR Sahkan UU Perlindungan Data Pribadi Baru yang Lebih Ketat',
                    'meta_description' => 'DPR RI mengesahkan revisi UU Perlindungan Data Pribadi dengan sanksi denda hingga 2% pendapatan tahunan bagi perusahaan yang melanggar.',
                    'keywords'         => 'UU PDP, perlindungan data pribadi, DPR, regulasi digital',
                ],
                [
                    'title'            => 'Kabinet Baru Resmi Dilantik, Tiga Menteri Wajah Baru',
                    'excerpt'          => 'Presiden resmi melantik kabinet yang telah dirombak dengan memasukkan tiga nama baru di posisi kementerian strategis yang menjadi sorotan publik.',
                    'content'          => '<p>Jakarta — Istana Negara menjadi pusat perhatian pada hari Senin (29/04) saat Presiden Republik Indonesia resmi melantik kabinet yang telah dirombak. Tiga nama baru menghiasi posisi kementerian strategis, yakni Kementerian Ekonomi, Kementerian Pendidikan, dan Kementerian Lingkungan Hidup.</p><p>Perombakan kabinet ini dilakukan menyusul evaluasi kinerja 100 hari pemerintahan periode kedua. Presiden menegaskan bahwa penggantian ini bukan bentuk ketidakpercayaan, melainkan upaya penyegaran untuk mendorong akselerasi program-program prioritas nasional.</p><p>Ketiga menteri baru yang dilantik memiliki latar belakang profesional yang kuat di bidang masing-masing, dengan pengalaman lebih dari 20 tahun di sektor publik dan swasta.</p>',
                    'meta_title'       => 'Presiden Lantik Kabinet Baru, Tiga Wajah Baru di Kementerian Strategis',
                    'meta_description' => 'Presiden Indonesia melantik kabinet rombakan dengan tiga menteri baru di posisi strategis Ekonomi, Pendidikan, dan Lingkungan Hidup.',
                    'keywords'         => 'kabinet Indonesia, pelantikan menteri, reshuffle kabinet, politik Indonesia',
                ],
            ],
            'Gadget' => [
                [
                    'title'            => 'Review: Smartphone Flagship Terbaru dengan Chip AI Generasi Tiga',
                    'excerpt'          => 'Kami telah menguji secara eksklusif smartphone flagship terbaru yang hadir dengan chip AI generasi ketiga yang diklaim mampu menggantikan peran laptop untuk pekerjaan sehari-hari.',
                    'content'          => '<p>Setelah dua minggu pengujian intensif, kami siap membagikan review komprehensif atas smartphone flagship terbaru yang menjadi pembicaraan di dunia teknologi. Perangkat ini hadir dengan chip AI generasi ketiga yang diklaim pabrikannya mampu memproses tugas-tugas kompleks secara real-time tanpa bergantung pada cloud.</p><p><strong>Performa:</strong> Dalam pengujian benchmark, chip baru ini mengungguli pendahulunya hingga 40%. Multitasking dengan 20 aplikasi berat berjalan sangat mulus tanpa hambatan berarti. Kamera AI-nya mampu menghasilkan foto DSLR-quality bahkan dalam kondisi cahaya rendah ekstrem.</p><p><strong>Baterai:</strong> Dengan kapasitas 5500 mAh dan optimasi AI yang cerdas, daya tahan baterai bisa mencapai 2 hari untuk penggunaan normal. Pengisian cepat 100W mengisi penuh baterai hanya dalam 28 menit.</p><p><strong>Kesimpulan:</strong> Ini adalah smartphone terbaik yang pernah kami uji. Jika Anda mencari perangkat yang benar-benar bisa menggantikan laptop untuk pekerjaan ringan hingga menengah, inilah jawabannya. Nilai: 9.5/10.</p>',
                    'meta_title'       => 'Review Smartphone Flagship Chip AI Gen 3: Bisa Gantikan Laptop?',
                    'meta_description' => 'Review eksklusif smartphone flagship terbaru dengan chip AI generasi ketiga. Performa luar biasa, baterai 2 hari, kamera setara DSLR.',
                    'keywords'         => 'review smartphone, flagship, chip AI, gadget terbaru',
                ],
                [
                    'title'            => 'Smartwatch Generasi Terbaru Kini Bisa Deteksi Gula Darah Tanpa Jarum',
                    'excerpt'          => 'Sebuah produsen smartwatch global mengumumkan terobosan revolusioner: jam tangan pintar yang mampu memantau kadar gula darah secara non-invasif menggunakan teknologi sensor inframerah.',
                    'content'          => '<p>San Francisco — Sebuah produsen smartwatch global yang berbasis di Silicon Valley mengumumkan terobosan teknologi yang sudah lama dinantikan oleh jutaan penderita diabetes di seluruh dunia: sebuah smartwatch yang mampu memantau kadar gula darah secara real-time tanpa perlu menusuk jari.</p><p>Teknologi ini menggunakan sensor inframerah near-field yang canggih yang dipasang di bagian belakang jam. Cahaya inframerah akan menembus kulit dan membaca kadar glukosa dalam darah melalui dinding pembuluh darah tipis di pergelangan tangan. Akurasi pengukurannya diklaim mencapai 96%, yang sudah mendapatkan persetujuan awal dari FDA Amerika Serikat.</p><p>Smartwatch ini juga dilengkapi dengan fitur deteksi aritmia, pemantauan tekanan darah, dan kadar oksigen darah yang sudah ada di generasi sebelumnya. Rencananya, perangkat ini akan mulai dipasarkan secara global pada Q3 2025 dengan kisaran harga USD 499.</p>',
                    'meta_title'       => 'Smartwatch Deteksi Gula Darah Tanpa Jarum: Revolusi Kesehatan Digital',
                    'meta_description' => 'Produsen smartwatch global umumkan teknologi sensor inframerah yang mampu memantau gula darah non-invasif dengan akurasi 96%.',
                    'keywords'         => 'smartwatch, gula darah, sensor inframerah, kesehatan digital, gadget',
                ],
            ],
            'AI' => [
                [
                    'title'            => 'ChatGPT Versi Terbaru Kini Bisa Berinteraksi Secara Real-Time dengan Dunia Nyata',
                    'excerpt'          => 'OpenAI merilis versi terbaru ChatGPT yang dilengkapi kemampuan untuk berinteraksi langsung dengan lingkungan nyata melalui koneksi ke berbagai sensor dan perangkat IoT.',
                    'content'          => '<p>OpenAI kembali mengejutkan dunia dengan merilis pembaruan besar pada ChatGPT yang memungkinkan model AI tersebut untuk berinteraksi secara real-time dengan lingkungan fisik di sekitarnya melalui integrasi dengan perangkat IoT (Internet of Things) dan berbagai sensor eksternal.</p><p>Kemampuan baru ini memungkinkan ChatGPT untuk, misalnya, membaca data suhu ruangan dari sensor tertentu dan langsung menyesuaikan pengaturan AC, atau membaca data lalu lintas real-time untuk memberikan rekomendasi rute terbaik. Integrasi ini dilakukan melalui API baru yang disebut "OpenAI Realtime Actions".</p><p>CEO OpenAI menyatakan bahwa ini adalah langkah pertama menuju "Agentic AI" yang sesungguhnya, di mana AI bukan hanya menjawab pertanyaan, tetapi benar-benar bertindak dan membuat keputusan berdasarkan data dunia nyata secara otonom.</p><p>Para peneliti AI menyambut positif sekaligus mengingatkan tentang pentingnya kerangka keamanan yang kuat sebelum teknologi ini diimplementasikan secara luas di masyarakat.</p>',
                    'meta_title'       => 'ChatGPT Terbaru Bisa Interaksi Real-Time dengan Dunia Nyata via IoT',
                    'meta_description' => 'OpenAI rilis pembaruan ChatGPT dengan kemampuan interaksi real-time dunia nyata melalui integrasi IoT dan API Realtime Actions.',
                    'keywords'         => 'ChatGPT, OpenAI, AI, IoT, kecerdasan buatan, Agentic AI',
                ],
                [
                    'title'            => 'Indonesia Luncurkan Model Bahasa Besar (LLM) Pertama Berbahasa Indonesia',
                    'excerpt'          => 'Sebuah konsorsium universitas dan perusahaan teknologi Indonesia berhasil mengembangkan dan meluncurkan model bahasa besar (LLM) pertama yang sepenuhnya berbahasa Indonesia.',
                    'content'          => '<p>Jakarta — Dalam sebuah momen bersejarah bagi ekosistem teknologi Tanah Air, konsorsium yang terdiri dari Institut Teknologi Bandung, Universitas Indonesia, dan beberapa perusahaan teknologi lokal terkemuka resmi meluncurkan "NusaGPT", model bahasa besar (Large Language Model / LLM) pertama yang sepenuhnya dilatih menggunakan data berbahasa Indonesia.</p><p>NusaGPT dilatih menggunakan dataset masif yang terdiri dari lebih dari 100 miliar kata dalam bahasa Indonesia, mencakup berbagai dialek daerah, bahasa gaul, dan terminologi khas Indonesia. Model ini mampu memahami konteks budaya dan idiom lokal yang selama ini menjadi kelemahan model AI berbahasa Inggris ketika digunakan untuk konten Indonesia.</p><p>Dalam pengujian awal, NusaGPT mengungguli GPT-4 dan Claude dalam tugas-tugas yang memerlukan pemahaman mendalam tentang budaya dan bahasa Indonesia, seperti penerjemahan teks daerah dan pembuatan konten yang kontekstual.</p><p>Model ini akan tersedia secara gratis untuk peneliti dan tersedia melalui API berbayar untuk penggunaan komersial mulai Juni 2025.</p>',
                    'meta_title'       => 'NusaGPT: Model Bahasa Besar Pertama Berbahasa Indonesia Resmi Diluncurkan',
                    'meta_description' => 'Konsorsium ITB, UI, dan perusahaan teknologi lokal luncurkan NusaGPT, LLM pertama yang sepenuhnya berbahasa Indonesia dengan dataset 100 miliar kata.',
                    'keywords'         => 'NusaGPT, LLM Indonesia, AI Indonesia, kecerdasan buatan, bahasa Indonesia',
                ],
            ],
        ];

        foreach ($categories as $category) {
            $articles = $articlesByCategory[$category->name] ?? [];

            foreach ($articles as $articleData) {
                $title = $articleData['title'];
                $slug  = Str::slug($title);

                // Ensure slug uniqueness
                $originalSlug = $slug;
                $counter = 1;
                while (Article::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                Article::create([
                    'title'            => $title,
                    'slug'             => $slug,
                    'excerpt'          => $articleData['excerpt'],
                    'content'          => $articleData['content'],
                    'category_id'      => $category->id,
                    'author_id'        => $author->id,
                    'status'           => 'published',
                    'published_at'     => now()->subDays(rand(1, 30)),
                    'meta_title'       => $articleData['meta_title'],
                    'meta_description' => $articleData['meta_description'],
                    'keywords'         => $articleData['keywords'],
                    'views_count'      => rand(50, 5000),
                ]);
            }
        }

        $this->command->info('ArticleSeeder: Successfully seeded articles for all categories.');
    }
}
