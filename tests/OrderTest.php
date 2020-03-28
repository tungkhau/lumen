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

        $this->seeJsonEquals(['success' => 'Tạo đơn đặt thành công']);
        $this->assertEquals(200, $response->status());
        $this->seeInDatabase('orders', $order);
    }
}
