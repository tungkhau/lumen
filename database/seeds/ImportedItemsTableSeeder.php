<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ImportedItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app('db')->table('imported_items')->insert([
            'pk' => '72773d4c-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '11',
            'import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773612-70df-11ea-bc55-0242ac130003',
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '72773ed2-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '21',
            'import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '727736da-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '72773f9a-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '31',
            'import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773842-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '727741ca-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '12',
            'import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '727739be-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '727742d8-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '22',
            'import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773b12-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '72774396-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => '32',
            'import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773bda-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '24f53a5e-7389-11ea-bc55-0242ac130003',
            'imported_quantity' => '13',
            'import_pk' => '178ef7ba-7389-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '727739be-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => 'a4ce862a-8155-11ea-bc55-0242ac130003',
            'imported_quantity' => '501',
            'import_pk' => 'a4ce8364-8155-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773612-70df-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => '7310c708-815d-11ea-bc55-0242ac130003',
            'imported_quantity' => '601',
            'import_pk' => '7310c4ba-815d-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773612-70df-11ea-bc55-0242ac130003',
            'classified_item_pk' => '1cfd5bfc-72a2-11ea-bc55-0242ac130003'
        ]);
        app('db')->table('imported_items')->insert([
            'pk' => 'd05a23e4-811e-11ea-bc55-0242ac130003',
            'imported_quantity' => '14',
            'import_pk' => 'd05a2178-811e-11ea-bc55-0242ac130003',
            'ordered_item_pk' => '72773612-70df-11ea-bc55-0242ac130003',
            'classified_item_pk' => '1cfd5cec-72a2-11ea-bc55-0242ac130003'
        ]);

    }
}
