<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => 'მთავარი',
                'endpoint' => null,
                'front_endpoint' => null
            ],
            [
                'title' => 'კონტაქტი',
                'endpoint' => null,
                'front_endpoint' => null
            ],
            [
                'title' => 'სიახლეები',
                'endpoint' => 'dashboard/blog/category',
                'front_endpoint' => 'blog'
            ]
        ];

        MenuCategory::insert($items);
    }
}
