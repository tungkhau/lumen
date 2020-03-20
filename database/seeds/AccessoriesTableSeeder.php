<?php

use Illuminate\Database\Seeder;

class AccessoriesTableSeeder extends Seeder
{

    public function run()
    {
        factory(App\Models\Accessory::class, 1000)->create();
    }
}
