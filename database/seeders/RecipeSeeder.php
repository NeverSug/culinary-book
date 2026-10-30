<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Сначала засейте пользователей (UserSeeder) и продукты (ProductSeeder).');
            return;
        }

        $recipesByCategory = [
            'Первые блюда' => ['Борщ', 'Куриный суп с лапшой', 'Грибной крем-суп', 'Солянка', 'Уха', 'Крем-суп из тыквы', 'Томатный суп', 'Суп с фрикадельками', 'Рыбный суп с рисом', 'Гороховый суп'],
            'Вторые блюда' => ['Плов с курицей', 'Паста карбонара', 'Запечённый лосось', 'Жаркое из говядины', 'Ризотто с грибами', 'Котлеты куриные', 'Тушёная капуста с мясом', 'Гречка по-купечески', 'Лазанья', 'Картофельное пюре с курицей'],
            'Салаты' => ['Цезарь', 'Оливье', 'Греческий салат', 'Винегрет', 'Салат с тунцом', 'Крабовый салат', 'Капрезе', 'Салат с кукурузой и сыром', 'Морковь по-корейски', 'Тёплый салат с курицей'],
            'Напитки' => ['Домашний лимонад', 'Молочный коктейль с бананом', 'Какао на молоке', 'Горячий шоколад', 'Ягодный смузи', 'Кефирный смузи', 'Яблочный фреш', 'Лимонный чай', 'Овсяный смузи', 'Гоголь-моголь'],
            'Десерты' => ['Чизкейк', 'Тирамису', 'Яблочный пирог', 'Панкейки с мёдом', 'Шоколадный фондан', 'Птичье молоко', 'Творожная запеканка', 'Медовик', 'Панна-котта', 'Мороженое домашнее'],
            'Выпечка' => ['Блины на молоке', 'Оладьи на кефире', 'Шарлотка с яблоками', 'Сырные булочки', 'Лимонный кекс', 'Банановый хлеб', 'Ватрушки с творогом', 'Пирог с капустой', 'Шоколадный кекс', 'Овсяное печенье'],
            'Закуски' => ['Сырные шарики', 'Фаршированные яйца', 'Творожная намазка с зеленью', 'Креветки в чесночном масле', 'Кабачковые рулетики с сыром', 'Грибная икра', 'Канапе с колбасой и сыром', 'Овощные палочки с соусом', 'Яичные рулетики с сыром', 'Помидоры, фаршированные творогом'],
        ];


        foreach ($recipesByCategory as $categoryName => $names) {
            $category = Category::firstOrCreate(['name' => $categoryName]);

            foreach ($names as $name) {
                $recipe = Recipe::create([
                    'user_id' => $users->random()->id,
                    'category_id' => $category->id,
                    'name' => $name,
                    'description' => fake()->paragraph(3),
                ]);


                $pivot = [];
                foreach ($products->random(rand(3, 6)) as $product) {
                    $pivot[$product->id] = [
                        'amount' => rand(10, 500),
                        'unit' => $product->unit,
                    ];
                }
                $recipe->products()->sync($pivot);

                $recipe->recalculateNutrition();

                $likers = $users->random(rand(0, min(5, $users->count())));
                $recipe->likes()->attach($likers->pluck('id')->all());
            }
        }

        $this->command->info('Засеяно ' . (count($recipesByCategory, COUNT_RECURSIVE) - count($recipesByCategory)) . ' рецептов.');
    }
}
