<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ImportTest extends TestCase
{
    use DatabaseTransactions;

    //TODO fix bug test case
    public function testCreate()
    {
        $inputs = [
            'order_pk' => 'a7d6665c-71a7-11ea-bc55-0242ac130003',
            'imported_items' => [
                [
                    'ordered_item_pk' => '7043b34c-71a8-11ea-bc55-0242ac130003',
                    'imported_quantity' => 200,
                    'comment' => '1-4'
                ],
                [
                    'ordered_item_pk' => '24b29244-71a9-11ea-bc55-0242ac130003',
                    'imported_quantity' => 200,
                    'comment' => '2-4'
                ]
            ],
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            ];

        $import = [
            'id' => '666666#1',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'order_pk' => 'a7d6665c-71a7-11ea-bc55-0242ac130003'
        ];
        $this->call('POST', 'create_import', $inputs);
        $import_pk = app('db')->table('imports')->where('id', '666666#1')->value('pk');
        $imported_items = array();
        foreach ($inputs['imported_items'] as $input) {
            $imported_items[] = [
                'imported_quantity' => $input['imported_quantity'],
                'comment' => $input['comment'],
                'import_pk' => $import_pk,
                'ordered_item_pk' => $input['ordered_item_pk']
            ];
        }
        foreach ($imported_items as $imported_item) {
            $this->seeInDatabase('imported_items', $imported_item);
        }
        $this->seeStatusCode(200);
        $this->seeInDatabase('imports', $import);
    }

    public function testEdit()
    {
        $inputs = ['import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'imported_item_pk' => '727741ca-70df-11ea-bc55-0242ac130003',
            'imported_quantity' => 3000,
            'comment' => 'bla bla',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['imported_quantity' => 3000,
            'comment' => 'bla bla'];
        $this->call('POST', 'edit_import', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('imported_items', $data);
    }

    public function testDelete()
    {
        $temp = app('db')->table('imported_items')->where('import_pk', '72774102-70df-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $imported_item_pks = array();
        foreach ($temp as $item) {
            $imported_item_pks[] = [
                'pk' => $item
            ];
        }
        $inputs = ['import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '72774102-70df-11ea-bc55-0242ac130003'];
        $this->call('DELETE', 'delete_import', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('imports', $data);
        foreach ($imported_item_pks as $imported_item_pk) {
            $this->notSeeInDatabase('imported_items', $imported_item_pk);
        }
    }

    public function testTurnOff()
    {
        $inputs = ['import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'is_opened' => False];
        $this->call('PATCH', 'turn_off_import', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('imports', $data);
    }

    public function testTurnOn()
    {
        $inputs = ['import_pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '72774102-70df-11ea-bc55-0242ac130003',
            'is_opened' => True];
        $this->call('PATCH', 'turn_on_import', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('imports', $data);
    }
    public function testReceive ()
    {
        $inputs = ['import_pk' => '72773c8e-70df-11ea-bc55-0242ac130003',
            'imported_groups' => [
                [
                    'imported_item_pk' => '72773d4c-70df-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 200
                ],
                [
                    'imported_item_pk' => '72773ed2-70df-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 500
                ]
            ],
            'case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ];
        $this->call('POST','receive_import',$inputs);
        $receiving_session = ['kind' => 'importing',
            'pk' => app('db')->table('')];
        $import_pk = app('db')->table('imports')->where('id', '666666#1')->value('pk');
    }


    //TODO Edit imported receiving
}
