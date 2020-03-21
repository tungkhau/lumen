<?php

class OrderTest extends TestCase
{

    public function create()
    {
        $params = [
            'supplier_pk' => 'cc98fd05-69bd-11ea-aa82-305a3a8512c6',
            'order_id' => 'OFTEST',
            'user_pk' => 'd93aada0-69bd-11ea-aa82-305a3a8512c6',
            'ordered_items' => [
                [
                    'accessory_pk' => 'dbbb499f-69bd-11ea-aa82-305a3a8512c6',
                    'ordered_quantity' => '1213',
                    'comment' => 'nope'
                ],
                [
                    'accessory_pk' => 'dbbe075b-69bd-11ea-aa82-305a3a8512c6',
                    'ordered_quantity' => '414',
                    'comment' => 'nein'
                ],
                [
                    'accessory_pk' => 'dbbf7ade-69bd-11ea-aa82-305a3a8512c6',
                    'ordered_quantity' => '3131',
                    'comment' => 'Ja'
                ]
            ]
        ];

        $response = $this->call('POST', '/create_order', $params);
        $this->seeJsonEquals(['success' => 'Tạo đơn đặt thành công']);
        $this->assertEquals(200, $response->status());
    }
}
