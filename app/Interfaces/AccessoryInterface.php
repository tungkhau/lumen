<?php

namespace App\Interfaces;

interface AccessoryInterface
{
    public function create($params);

    public function delete($key);

    public function deactivate($key);

    public function reactivate($key);

    public function upload_photo($params);

    public function delete_photo($key);
}
