<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AngularController extends Controller
{

    public function get_orders(Request $request)
    {
        $orders = app('db')->table('orders')
            ->join('users', 'users.pk', '=', 'orders.user_pk')
            ->join('suppliers', 'suppliers.pk', '=', 'orders.supplier_pk')
            ->select('orders.pk', 'orders.id', 'orders.created_date', 'orders.is_opened', 'suppliers.name', 'users.name', 'users.id')
            ->where($request->all())->get();

        echo dd($orders);
    }
}

