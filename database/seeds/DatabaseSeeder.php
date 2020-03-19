<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $this->call('WorkplacesTableSeeder');
        $this->call('CustomersTableSeeder');
        $this->call('UsersTableSeeder');
        $this->call('BlocksTableSeeder');
    }
}
