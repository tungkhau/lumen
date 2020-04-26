<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ShelfTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = ['shelf_name' => '122'];
        $data = ['name' => '122'];
        $this->call('POST', 'create_shelf', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('shelves', $data);
    }

    public function testDelete()
    {
        $inputs = ['shelf_pk' => '3ad6f1da-7688-11ea-bc55-0242ac130003'];
        $data = ['pk' => '3ad6f1da-7688-11ea-bc55-0242ac130003'];
        $this->call('POST', 'delete_shelf', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('shelves', $data);
    }
}
