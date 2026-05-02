<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Nasional', 'children' => [
                'Politik',
                'Hukum & Kriminal',
                'Ekonomi',
                'Sosial & Budaya',
                'Pendidikan',
                'Kesehatan',
            ]],
            ['name' => 'Teknologi', 'children' => [
                'Gadget',
                'AI',
                'Startup',
                'Internet & Aplikasi',
                'Keamanan Siber',
                'Sains & Riset',
            ]],
            ['name' => 'Olahraga', 'children' => [
                'Sepak Bola',
                'Bulu Tangkis',
                'Otomotif',
                'E-Sport',
                'Renang & Atletik',
            ]],
            ['name' => 'Hiburan', 'children' => [
                'Film & Serial',
                'Musik',
                'Selebriti',
                'K-Pop & Korea',
            ]],
            ['name' => 'Bisnis', 'children' => [
                'Investasi & Saham',
                'UMKM',
                'Properti',
                'Perbankan',
            ]],
            ['name' => 'Internasional', 'children' => [
                'Asia Tenggara',
                'Amerika',
                'Eropa',
                'Timur Tengah',
            ]],
            ['name' => 'Gaya Hidup', 'children' => [
                'Kuliner',
                'Perjalanan & Wisata',
                'Fashion & Kecantikan',
                'Kesehatan & Kebugaran',
            ]],
        ];

        foreach ($data as $parentData) {
            // Upsert parent (don't duplicate)
            $parent = Category::firstOrCreate(
                ['name' => $parentData['name']],
                [
                    'slug'      => Str::slug($parentData['name']),
                    'parent_id' => null,
                ]
            );

            foreach ($parentData['children'] as $childName) {
                $slug = Str::slug($childName);
                // Ensure slug is unique
                $originalSlug = $slug;
                $i = 1;
                while (Category::where('slug', $slug)->where('id', '!=', $parent->id)->exists()) {
                    $slug = $originalSlug . '-' . $i++;
                }

                Category::firstOrCreate(
                    ['name' => $childName, 'parent_id' => $parent->id],
                    ['slug' => $slug]
                );
            }
        }

        $this->command->info('CategorySeeder: Berhasil menambahkan ' . count($data) . ' kategori utama beserta sub-kategorinya.');
    }
}
