<?php

namespace App\Interfaces;

interface ConceptionInterface
{
    public function create($params);

    public function delete($key);

    public function deactivate($key);

    public function reactivate($key);

    public function link_accessory($params);

    public function unlink_accessory($params);

}
