<?php

use Illuminate\Database\Seeder;

class BlocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range('A', 'Z') as $id)
            app('db')->table('blocks')->insert([
                'id' => $id
            ]);
    }
}
