<?php

use Illuminate\Database\Seeder;

class TestingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = app('db')->select('SHOW TABLES');
        app('db')->statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($tables as $table) {
            app('db')->table($table->Tables_in_main)->truncate();
        }
        app('db')->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
