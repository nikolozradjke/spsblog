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
                'front_endpoint' => null,
                'column' => null,
                'multi_lang' => 0
            ],
            [
                'title' => 'კონტაქტი',
                'endpoint' => null,
                'front_endpoint' => null,
                'column' => null,
                'multi_lang' => 0
            ],
            [
                'title' => 'სიახლეები',
                'endpoint' => 'dashboard/blog/category',
                'front_endpoint' => 'blog',
                'column' => 'model_id',
                'multi_lang' => 0
            ],
            [
                'title' => 'ტექსტური გვერდი',
                'endpoint' => null,
                'front_endpoint' => null,
                'column' => 'text',
                'multi_lang' => 1
            ]
        ];

        MenuCategory::insert($items);
    }
}
