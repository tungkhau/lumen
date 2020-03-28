<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class WorkplaceTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = ['workplace_name' => 'site 2'];
        $data   = ['name' => 'site 2'];
        $this->call('POST','create_workplace',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('workplaces',$data);
    }
    public function testDelete()
    {
        $input  = ['workplace_pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'];
        $data   = ['pk' => 'cdbe8122-70b9-11ea-bc55-0242ac130003'];
        $this->call('DELETE','delete_workplace',$input);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('workplaces',$data);
    }
}
