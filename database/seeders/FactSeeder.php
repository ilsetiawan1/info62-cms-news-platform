<?php

namespace Database\Seeders;

use App\Models\Fact;
use Illuminate\Database\Seeder;

class FactSeeder extends Seeder
{
    public function run(): void
    {
        $facts = [
            'Indonesia memiliki garis pantai terpanjang kedua di dunia setelah Kanada, membentang lebih dari 54.000 kilometer.',
            'Komodo adalah kadal terbesar dan terberat di dunia yang masih hidup, dan hanya dapat ditemukan di habitat aslinya di NTT, Indonesia.',
            'Candi Borobudur di Magelang, Jawa Tengah, merupakan candi Buddha terbesar di dunia dan salah satu monumen Buddha terbesar di bumi.',
            'Indonesia adalah negara kepulauan terbesar di dunia, dengan jumlah pulau mencapai lebih dari 17.000 pulau resmi.',
            'Danau Toba di Sumatera Utara merupakan danau vulkanik terbesar di dunia, terbentuk dari letusan supervolcano dahsyat ribuan tahun lalu.',
            'Rafflesia arnoldii, bunga tunggal terbesar di dunia dengan diameter mencapai 1 meter, tumbuh di hutan hujan Sumatra.',
            'Puncak Jaya di Papua adalah salah satu dari sedikit tempat di dekat garis khatulistiwa yang memiliki gletser es abadi.',
            'Indonesia merupakan salah satu negara megabiodiversitas terbesar, menampung sekitar 10-15% dari seluruh spesies tumbuhan, mamalia, dan burung di dunia.',
            'Garis imajiner Wallace membagi fauna Indonesia menjadi tipe Asiatis di bagian barat dan tipe Australis di bagian timur.',
            'Indonesia memiliki lebih dari 700 bahasa daerah aktif, menjadikannya salah satu negara dengan keragaman bahasa terbanyak di dunia.'
        ];

        foreach ($facts as $content) {
            Fact::create([
                'content'   => $content,
                'is_active' => true,
            ]);
        }
    }
}
