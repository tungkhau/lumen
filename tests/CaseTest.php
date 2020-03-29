<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class CaseTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $data = ['id' => 'QT-290320-AA'];
        $this->call('POST', 'create_case');
        $this->seeStatusCode(200);
        $this->SeeInDatabase('cases', $data);
    }

    public function testDisable()
    {
        $inputs = ['case_pk' => 'd993f450-7190-11ea-bc55-0242ac130003'];
        $data = ['pk' => 'd993f450-7190-11ea-bc55-0242ac130003',
            'is_active' => False];
        $this->call('PATCH', 'disable_case', $inputs);
//        $this->seeStatusCode(200);
        $this->seeJson(['success' => 'Xóa đơn vị chứa thành công']);
        $this->seeInDatabase('cases', $data);
    }
}
