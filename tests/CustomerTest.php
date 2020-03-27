<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $inputs = ['supplier_name' => 'Jason',
            'supplier_id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $data = ['name' => 'Jason',
            'id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $this->call('POST', 'create_supplier', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('suppliers', $data);
    }

    public function testEdit()
    {
        $inputs = ['customer_pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0369764668'];
        $data = ['pk' => '59a6758a-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0369764668'];
        $this->call('PATCH','edit_customer', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('customers', $data);
    }
}
