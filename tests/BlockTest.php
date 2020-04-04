<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class BlockTest extends TestCase
{
    use DatabaseTransactions;

    public function testOpen()
    {
        $inputs = ['block_pk' => '59a682e6-6dd8-11ea-bc55-0242ac130003',
            'row' => 5,
            'col' => 10];

        $block_id = app('db')->table('blocks')->where('pk', '59a682e6-6dd8-11ea-bc55-0242ac130003')->value('id');
        $shelves = array();
        for ($col = 1; $col < 10; $col++) {
            for ($row = 1; $row < 5; $row++)
                $shelves[] = [
                    'name' => $block_id . "-" . $row . "-" . $col,
                    'block_pk' => '59a682e6-6dd8-11ea-bc55-0242ac130003'
                ];
        }
        $block = ['col' => 10,
            'row' => 5,
            'is_active' => True];
        $this->call('PATCH', 'open_block', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('blocks', $block);

        foreach ($shelves as $shelf) {
            $this->seeInDatabase('shelves', $shelf);
        }
    }

    public function testClose()
    {
        $blocks = ['col' => null,
            'row' => null,
            'is_active' => False];
        $temp = app('db')->table('shelves')->where('block_pk', '3ad6f2f2-7688-11ea-bc55-0242ac130003')->pluck('pk')->toArray();
        $shelves = array();
        foreach ($temp as $key => $value) {
            $shelves[] = [
                'pk' => $value
            ];
        }
        $inputs = ['block_pk' => '3ad6f2f2-7688-11ea-bc55-0242ac130003'];
        $this->call('PATCH', 'close_block', $inputs);
        $this->seeStatusCode(200);
        $this->seeInDatabase('blocks', $blocks);
        foreach ($shelves as $shelf) {
            $this->notSeeInDatabase('shelves', $shelf);
        }

    }
}
