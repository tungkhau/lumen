<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class SupplierTest extends TestCase
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

    public function testEdit() {
        $inputs = ['supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $data = ['pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'address' => 'HCM',
            'phone' => '0946008197'];
        $this->call('PATCH', 'edit_supplier', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('suppliers', $data);
    }
    public function testDelete()
    {
        $inputs = ['supplier_pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003'];
        $this->call('DELETE','delete_supplier',$inputs);
        $this->seeStatusCode(200);
        $this->notSeeInDatabase('suppliers',$data);
    }
    public function testDeactivate()
    {
        $inputs = ['supplier_pk' => '59a67724-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a67724-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False ];
        $this->call('PATCH','deactivate_supplier',$inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('suppliers',$data);
    }
    public function testReactivate()
    {
        $inputs = ['supplier_pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a677ec-6dd8-11ea-bc55-0242ac130003',
            'is_active' => True ];
         $this->call('PATCH','reactivate_supplier',$inputs);
         $this->seeStatusCode(200);
         $this->seeInDatabase('suppliers',$data);
    }
}
