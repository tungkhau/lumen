<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function testCreate()
    {
        $inputs = ['customer_name' => 'Jason',
            'customer_id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $data = ['name' => 'Jason',
            'id' => 'JAS',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $this->call('POST', '/create_customer', $inputs);
        $this->seeStatusCode(201);
        $this->seeInDatabase('customers', $data);
    }
}
