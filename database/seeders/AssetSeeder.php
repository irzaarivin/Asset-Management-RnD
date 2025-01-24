<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asset;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Asset::insert([
            [
                'thumbnail' => null,
                'name' => 'SSD Samsung 1TB',
                'description' => 'SSD Eksternal merk Samsung dengan kapasitas 1 Terabyte',
                'category_id' => 1,
                'status' => 'available',
            ],
            [
                'thumbnail' => null,
                'name' => 'Keyboard Logitech',
                'description' => 'Keyboard merk Logitech berwarna Hitam dengan 150% Layout',
                'category_id' => 2,
                'status' => 'borrowed',
            ],
            [
                'thumbnail' => null,
                'name' => 'Folder Hitam',
                'description' => 'Folder berwarna Hitam dengan kapasitas sekitar 1000 lembar berbentuk kotak dengan ukuran 30cm x 50cm',
                'category_id' => 3,
                'status' => 'maintenance',
            ]
        ]);
    }
}
