<?php

namespace App\Preconditions;

class WorkplacePrecondition
{
    public function delete($params)
    {
        return app('db')->table('users')->where('workplace_pk', $params['workplace_pk'])->exists();
    }
}
