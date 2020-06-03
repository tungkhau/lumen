<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('users')->insert([
            'pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '000000',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'Admin',
            'role' => 'Admin',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
    }
}
