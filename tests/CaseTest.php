<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class CaseTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $data = ['id' => 'QT-280320-AA'];
        $this->call('POST', 'create_case');
        $this->seeStatusCode(200);
        $this->SeeInDatabase('cases', $data);
    }

    public function testDisableCase()
    {
        $inputs = ['case_pk' => '59a68160-6dd8-11ea-bc55-0242ac130003'];
        $data = ['pk' => '59a68160-6dd8-11ea-bc55-0242ac130003',
            'is_active' => False];
        $this->call('PATCH', 'disable_case', $inputs);
//        $this->seeStatusCode(200);
        $this->seeJson(['success' => 'Xóa đơn vị chứa thành công']);
        $this->seeInDatabase('cases', $data);
    }
}
