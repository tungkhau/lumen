<?php

namespace App\Interfaces;

interface ImportInterface
{
    public function create($params);

    public function edit($params);

    public function delete($key);

    public function turn_off($key);

    public function turn_on($key);

    public function receive($params);

    public function edit_receiving($params);

    public function delete_receiving($key);

    public function classify($params);

    public function reclassify($params);

    public function delete_classification($key);

    public function sendback($params);

}
