<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ImportTest extends TestCase
{
    use DatabaseTransactions;

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
            'id' => '6666 66#01',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'order_pk' => 'a7d6665c-71a7-11ea-bc55-0242ac130003'
        ];
        $this->call('POST', 'create_import', $inputs);
        $import_pk = app('db')->table('imports')->where('id', '6666 66#01')->value('pk');
        $imported_items = array();
        foreach ($inputs['imported_items'] as $input) {
            $imported_items[] = [
                'imported_quantity' => $input['imported_quantity'],
                'comment' => $input['comment'],
                'import_pk' => $import_pk,
                'ordered_item_pk' => $input['ordered_item_pk']
            ];
        }
        $this->seeJsonEquals(['success' => 'Tạo phiếu nhập thành công']);
        $this->seeStatusCode(200);
        foreach ($imported_items as $imported_item) {
            $this->seeInDatabase('imported_items', $imported_item);
        }
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

    public function testReceive()
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
        $this->call('POST', 'receive_import', $inputs);
        $receiving_session_pk = app('db')->table('receiving_sessions')->orderBy('executed_date', 'desc')->first()->pk;

        $receiving_session = ['kind' => 'importing',
            'pk' => $receiving_session_pk];
        $received_groups = array();

        foreach ($inputs['imported_groups'] as $imported_group) {
            $received_groups[] = [
                'received_item_pk' => $imported_group['imported_item_pk'],
                'grouped_quantity' => $imported_group['grouped_quantity'],
                'kind' => 'imported',
                'receiving_session_pk' => $receiving_session_pk,
                'case_pk' => $inputs['case_pk']
            ];
        }
        $case = ['pk' => $inputs['case_pk'],
            'shelf_pk' => Null];
        $this->seeJsonEquals(['success' => 'Ghi nhận phiếu nhập thành công']);
        $this->seeStatusCode(200);
        $this->seeInDatabase('receiving_sessions', $receiving_session);
        $this->seeInDatabase('cases', $case);
        foreach ($received_groups as $received_group) {
            $this->seeInDatabase('received_groups', $received_group);
        }
    }

    public function testEditReceving()
    {
        $inputs = ['importing_session_pk' => '727745c6-70df-11ea-bc55-0242ac130003',
            'imported_groups' => [
                [
                    'imported_group_pk' => '727746b6-70df-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 200
                ],
                [
                    'imported_group_pk' => '727747ce-70df-11ea-bc55-0242ac130003',
                    'grouped_quantity' => 500
                ]
            ],
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ];

        $data = [
            [
                'pk' => '727746b6-70df-11ea-bc55-0242ac130003',
                'grouped_quantity' => 200,
            ],
            [
                'pk' => '727747ce-70df-11ea-bc55-0242ac130003',
                'grouped_quantity' => 500,
            ]
        ];
        $this->call('POST', 'edit_imported_receiving', $inputs);
        $this->seeJsonEquals(['success' => 'Sửa phiên ghi nhận thành công']);
        $this->seeStatusCode(200);
        foreach ($data as $item) {
            $this->seeInDatabase('received_groups', $item);
        }
    }

    public function testDeleteReceiving()
    {
        $inputs = ['importing_session_pk' => '727745c6-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ];

        $received_groups = ['727746b6-70df-11ea-bc55-0242ac130003', '727747ce-70df-11ea-bc55-0242ac130003'];

        $this->call('DELETE', 'delete_imported_receiving', $inputs);
        $this->seeJsonEquals(['success' => 'Xóa phiên ghi nhận thành công']);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('receiving_sessions', ['pk' => '727745c6-70df-11ea-bc55-0242ac130003']);
        foreach ($received_groups as $item) {
            $this->notSeeInDatabase('received_groups', ['pk' => $item]);
        }

    }

}
