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
        $inputs = ['shelf_pk' => '59a68228-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a68228-6dd8-11ea-bc55-0242ac130003'];
        $this->call('DELETE', 'delete_shelf', $inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('shelves', $data);
    }
}
