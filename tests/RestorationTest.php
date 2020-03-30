<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class RestorationTest extends TestCase
{
    use DatabaseTransactions;

    public function testRegister()
    {
        $inputs = [
            'restored_items' =>
            [
                'accessory_pk' => '72773130-70df-11ea-bc55-0242ac130003',
                'restored_quantity' => 500
            ],
            [
                'accessory_pk' => '72773234-70df-11ea-bc55-0242ac130003',
                'restored_quantity' => 200
            ],
            'comment' => 'bla',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'
        ];
        $this->call('POST','register_restoration',$inputs);
        $pk = app('db')->table('restorations')->where('id','RN-300320-A')->value('pk');
        $restoration = ['id' => 'RN-300320-A',
            'pk' => $pk,
            'comment' => 'bla',
            'user_pk' => 'cec3ac24-7194-11ea-bc55-0242ac130003'];
        $this->seeStatusCode(200);
        $this->seeInDatabase('restorations',$restoration);
        $restored_items = array();
        foreach ($inputs['restored_items'] as $input) {
            $restored_items[] = [
                'restored_quantity' => $input['restored_quantity'],
                'restoration_pk' =>  $pk,
                'accessory_pk' => $input['accessory_pk']
            ];
        }
        foreach ($restored_items    as $restored_item){
            $this->seeInDatabase('restored_items',$restored_item);
        };
    }
    public function testDelete ()
    {
        $temp = app('db')->table('restored_items')->where('restoration_pk', '0756cd6e-71d6-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $restored_item_pks = array();
        foreach ($temp as $item) {
            $restored_item_pks[] = [
                'pk' => $item
            ];
        }
        $inputs = ['restoration_pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003'];
        $data = ['pk' => '0756cd6e-71d6-11ea-bc55-0242ac130003'];
        $this->call('DELETE','delete_restoration',$inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('restorations',$data);
        foreach ($restored_item_pks as $restored_item_pk) {
            $this->notSeeInDatabase('restored_items', $restored_item_pk);
        }
    }
}
