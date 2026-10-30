<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Первые блюда' => 'Горячие супы, борщи и бульоны на любой вкус.',
            'Вторые блюда' => 'Основные блюда: каши, рагу, котлеты и всё, что подают с гарниром.',
            'Закуски'      => 'Быстрые холодные и горячие закуски для любого стола.',
            'Салаты'       => 'Свежие и сытные салаты от классики до экспериментов.',
            'Напитки'      => 'Компоты, смузи, чаи и другие согревающие и освежающие напитки.',
            'Десерты'      => 'Сладкое завершение трапезы: торты, муссы, пудинги.',
            'Выпечка'      => 'Пироги, кексы, печенье и домашний хлеб.',
        ];

        foreach ($categories as $name => $description) {
            Category::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }
    }
}
