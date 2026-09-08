<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    
    public function run(): void
    {
        Product::create([
            'category_id' => 1, // Elektronik
            'name' => 'Kablosuz Kulaklık',
            'slug' => 'kablosuz-kulaklik',
            'description' => 'Bluetooth bağlantılı, gürültü önleyici kulaklık.',
            'price' => 1299.90,
            'stock' => 25,
            'brand' => 'SoundMax',
        ]);
        
        Product::create([
            'category_id' => 1, // Elektronik
            'name' => 'Akıllı Saat',
            'slug' => 'akilli-saat',
            'description' => 'Kalp ritmi ve adım takibi yapan akıllı saat.',
            'price' => 2499.00,
            'stock' => 10,
            'brand' => 'FitTrack',
        ]);
        
        Product::create([
            'category_id' => 2, // Giyim
            'name' => 'Pamuklu Tişört',
            'slug' => 'pamuklu-tisort',
            'description' => '%100 pamuklu, unisex tişört.',
            'price' => 199.90,
            'stock' => 50,
            'brand' => 'BasicWear',
        ]);
    }
}
