<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class DemandTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = ['workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c0107dc-74b8-11ea-bc55-0242ac130003',
            'product_quantity' => 200,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'demanded_items' => [
                [
                    'accessory_pk' => '5c01055c-74b8-11ea-bc55-0242ac130003',
                    'demanded_quantity' => 400
                ],
                [
                    'accessory_pk' => '5c01069c-74b8-11ea-bc55-0242ac130003',
                    'demanded_quantity' => 600,
                ]
            ]
        ];
        $this->call('POST','create_demand',$inputs);
        $pk = app('db')->table('demands')->where('id','DN-020421-A')->value('pk');
        $demand = ['id' => 'DN-020421-A',
            'workplace_pk' => 'c00516d6-7195-11ea-bc55-0242ac130003',
            'conception_pk' => '5c0107dc-74b8-11ea-bc55-0242ac130003',
            'product_quantity' => 200,
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003',
            'pk' => $pk];
        $this->seeStatusCode(200);
        $this->seeInDatabase('demands',$demand);
        $demanded_items = array();
        foreach ($inputs['demanded_items'] as $input) {
            $demanded_items[] = [
                'demanded_quantity' => $input['demanded_quantity'],
                'demand_pk' => $pk,
                'accessory_pk' => $input['accessory_pk']
            ];
        }
        foreach ($demanded_items as $demanded_item) {
            $this->seeInDatabase('demanded_items', $demanded_item);
        }
    }
    public function testEdit ()
    {
        $inputs = ['demand_pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'demanded_item_pk' => '5c0102dc-74b8-11ea-bc55-0242ac130003',
            'demanded_quantity' => 300,
            'comment' => 'edit',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $data = ['demanded_quantity' => 300,
            'comment' => 'edit'];
        $this->call('PATCH','edit_demand',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('demanded_items',$data);
    }
    public function testDelete ()
    {
        $temp = app('db')->table('demanded_items')->where('pk', '5c010192-74b8-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $demanded_item_pks = array();
        foreach ($temp as $item) {
            $demanded_item_pks[] = [
                'pk' => $item
            ];
        }
        $inputs = ['demand_pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $data = ['pk' => '5c010192-74b8-11ea-bc55-0242ac130003'];
        $this->call('DELETE','delete_demand',$inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('demands',$data);
        foreach ($demanded_item_pks as $demanded_item_pk) {
            $this->notSeeInDatabase('demanded_items', $demanded_item_pk);
        }
    }
    public function testTurnOff ()
    {
        $inputs = ['demand_pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '5c010192-74b8-11ea-bc55-0242ac130003',
            'is_opened' => false];
        $this->call('PATCH','turn_off_demand',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('demands',$data);
    }
    public function testTurnOn ()
    {
        $inputs = ['demand_pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'user_pk' => '511f4482-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '523c055c-74ff-11ea-bc55-0242ac130003',
            'is_opened' => true];
        $this->call('PATCH','turn_on_demand',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('demands',$data);
    }
}
