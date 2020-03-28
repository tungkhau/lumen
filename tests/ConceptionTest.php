<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ConceptionTest extends TestCase
{
    use DatabaseTransactions;

    public function testLinkConceptionAccessory()
    {
        $inputs = ['accessory_pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'];
        $data = ['accessory_pk' => '2884ffbe-70a1-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'];
        $this->call('POST', 'link_conception_accessory', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('accessories_conceptions', $data);
    }

    public function testUnlinkConceptionAccessory()
    {
        $inputs = ['accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'];
        $data = ['accessory_pk' => '59a67b8e-6dd8-11ea-bc55-0242ac130003',
            'conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'];
        $this->call('DELETE', 'unlink_conception_accessory',$inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('accessories_conceptions', $data);
    }
    public function testCreateConception()
    {
        $inputs = ['customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'conception_id' => '125759',
            'year' => 2019,
            'conception_name' => 'AUK123',
            'comment' => 'something'];
        $data = ['customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'id' => '125759',
            'year' => 2019,
            'name' => 'AUK123',
            'comment' => 'something'];
        $this->call('POST','create_conception',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('conceptions',$data);
    }
    public function testDeleteConception()
    {
        $inputs = ['conception_pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003'];
        $data  = ['pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003'];
        $this->call('DELETE','delete_conception',$inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('conceptions',$data);
    }
    public function testDeactivateComception()
    {
        $inputs = ['conception_pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67d0a-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False ];
        $this->call('PATCH','deactivate_conception',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('conceptions',$data);
    }
    public function testReactivateConception()
    {
        $inputs = ['conception_pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67f08-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True ];
        $this->call('PATCH','reactivate_conception',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('conceptions',$data);
    }
}
