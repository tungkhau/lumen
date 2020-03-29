<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ImportTest extends TestCase
{
    use DatabaseTransactions;

    //TODO test create import

    public function testEdit ()
    {
        $inputs = ['import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'imported_item_pk' => '72773d4c-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => 3000,
            'comment' => 'bla bla',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['imported_quantity' => 3000,
            'comment' => 'bla bla'];
        $this->call('POST','edit_import',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('imported_items',$data);
    }
    public function testDelete ()
    {
        $temp = app('db') -> table('imported_items') -> where('import_pk','72774102-70df-11ea-bc55-0242ac130003') ->pluck('pk')->toArray();
        $imported_item_pks = array();
        foreach ($temp as $item) {
            $imported_item_pks[] = [
                'pk' => $item
            ];
        }
        $inputs = ['import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '72774102-70df-11ea-bc55-0242ac130003'];
        $this->call('DELETE','delete_import',$inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('imports',$data);
        foreach ($imported_item_pks as $imported_item_pk) {
            $this->notSeeInDatabase('imported_items',$imported_item_pk);
        }
    }
}
