<?php

use Illuminate\Database\Seeder;

class NutrientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Nutrient::class, 30)->create();
    }
}
