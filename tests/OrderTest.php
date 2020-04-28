<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = [
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'order_id' => 'OFTEST',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003',
            'ordered_items' => [
                [
                    'accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
                    'ordered_quantity' => '1213',
                    'comment' => 'nope'
                ],
                [
                    'accessory_pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
                    'ordered_quantity' => '3131',
                    'comment' => 'Ja'
                ]
            ]
        ];
        $order = [
            'supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'id' => 'OFTEST',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'
        ];
        $response = $this->call('POST', 'create_order', $inputs);
        $order_pk = app('db')->table('orders')->where('id', 'OFTEST')->value('pk');
        $ordered_items = array();
        foreach ($inputs['ordered_items'] as $input) {
            $ordered_items[] = [
                'ordered_quantity' => $input['ordered_quantity'],
                'comment' => $input['comment'],
                'order_pk' => $order_pk,
                'accessory_pk' => $input['accessory_pk']
            ];
        }
        foreach ($ordered_items as $ordered_item) {
            $this->seeInDatabase('ordered_items', $ordered_item);
        }
        $this->seeJsonEquals(['success' => 'Tạo đơn đặt thành công']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('orders', $order);
    }

    public function testEdit()
    {
        $inputs = ['order_pk' => 'b7d9aa28-70f8-11ea-bc55-0242ac130003',
            'ordered_item_pk' => 'f521c1d6-70f8-11ea-bc55-0242ac130003',
            'ordered_quantity' => 500,
            'comment' => 'bla',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['ordered_quantity' => 500,
            'comment' => 'bla'];
        $this->call('POST', 'edit_order', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('ordered_items', $data);
    }

    public function testDelete()
    {
        $temp = app('db')->table('ordered_items')->where('order_pk', 'b7d9aa28-70f8-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $ordered_item_pks = array();
        foreach ($temp as $item) {
            $ordered_item_pks[] = [
                'pk' => $item
            ];
        }
        $inputs = ['order_pk' => 'b7d9aa28-70f8-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => 'b7d9aa28-70f8-11ea-bc55-0242ac130003'];
        $this->call('POST', 'delete_order', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('orders', $data);
        foreach ($ordered_item_pks as $ordered_item_pk) {
            $this->notSeeInDatabase('ordered_items', $ordered_item_pk);
        }
    }

    public function testTurnOff()
    {
        $inputs = ['order_pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '727734be-70df-11ea-bc55-0242ac130003',
            'is_opened' => False];
        $this->call('POST', 'turn_off_order', $inputs);
        $this->seeStatusCode(200);
        $this->SeeInDatabase('orders', $data);
    }

    public function testTurnOn()
    {
        $inputs = ['order_pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '72773900-70df-11ea-bc55-0242ac130003',
            'is_opened' => True];
        $this->call('POST', 'turn_on_order', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('orders', $data);
    }
}
