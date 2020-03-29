<?php

namespace App\Http\Controllers;

use App\Preconditions\DemandPrecondition;
use App\Repositories\DemandRepository;
use App\Validators\DemandValidator;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    private $repository;
    private $validator;
    private $precondition;


    public function __construct(DemandValidator $validator, DemandRepository $repository, DemandPrecondition $precondition)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->precondition = $precondition;
    }

    public function create(Request $request)
    {

    }

    public function edit(Request $request)
    {

    }

    public function delete(Request $request)
    {

    }

    public function turn_off(Request $request)
    {

    }

    public function turn_on(Request $request)
    {

    }
}
