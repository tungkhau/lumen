<?php

namespace App\Repositories;

use App\Interfaces\CaseInterface;

class CaseRepository implements CaseInterface
{

    public function create($key)
    {
        app('db')->table('cases')->insert([
            'id' => $key
        ]);
    }

    public function disable($key)
    {
        app('db')->table('cases')->where('pk', $key)->update([
            'is_active' => false
        ]);
    }

    public function id()
    {
        $date = (string)date('dmy');
        $date_string = "%" . $date . "%";
        $latest_case = app('db')->table('cases')->where('id', 'like', $date_string)->latest()->first();
        if ($latest_case) {
            $key = substr($latest_case->id, -2, 2);
            $key++;
        } else $key = "AA";
        return (string)env('DEFAULT_SITE') . "-" . $date . "-" . $key;
    }
}
