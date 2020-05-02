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
//        factory(App\ViewModels\User::class, 10)->create();
        app('db')->table('users')->insert([
            'pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '123456',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'TEST',
            'role' => 'Mediator',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => '4fb05f3f-8843-11ea-abd7-305a3a8512c6',
            'is_active' => True,
            'id' => '12',
            'password' => app('hash')->make('2'),
            'name' => 'Manager',
            'role' => 'Manager',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => '59a67242-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False,
            'id' => '234567',
            'password' => app('hash')->make(env('DEFAULT_PASSWORD')),
            'name' => 'TEST',
            'role' => 'Mediator',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'ce09946d-87d9-11ea-a4ee-305a3a8512c6',
            'is_active' => True,
            'id' => '11',
            'password' => app('hash')->make('1'),
            'name' => 'Quản lý mặt hàng',
            'role' => 'Merchandiser',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);

// Available User
        app('db')->table('users')->insert([
            'pk' => 'cec3a882-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '1',
            'password' => app('hash')->make('1'),
            'name' => 'User merchandiser',
            'role' => 'Merchandiser',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3ab2a-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '2',
            'password' => app('hash')->make('2'),
            'name' => 'user manager',
            'role' => 'Manager',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '3',
            'password' => app('hash')->make('3'),
            'name' => 'user mediator 1',
            'role' => 'Mediator',
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => '4aa81136-7a73-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '4',
            'password' => app('hash')->make('4'),
            'name' => 'user mediator 2',
            'role' => 'Mediator',
            'workplace_pk' => '4aa80ee8-7a73-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3acf6-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '5',
            'password' => app('hash')->make('5'),
            'name' => 'user staff',
            'role' => 'Staff',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3adbe-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '6',
            'password' => app('hash')->make('6'),
            'name' => 'user inspector',
            'role' => 'Inspector',
            'workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('users')->insert([
            'pk' => 'cec3afc6-7194-11ea-bc55-0242ac130003',
            'is_active' => True,
            'id' => '7',
            'password' => app('hash')->make('7'),
            'name' => 'user admin',
            'role' => 'Admin',
            'workplace_pk' => '38eced6a-6dd8-11ea-bc55-0242ac130003'
        ]);

    }
}
