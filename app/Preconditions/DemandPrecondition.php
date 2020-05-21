<?php

namespace App\Preconditions;

use App\Http\Controllers\EntryController;
use App\Http\Controllers\ReceivedGroupController;
use Illuminate\Support\Facades\DB;

class DemandPrecondition
{
    public function create($params)
    {
        $accessory_pks = array();
        foreach ($params['demanded_items'] as $demanded_item) {
            array_push($accessory_pks, $demanded_item['accessory_pk']);
        }
        $conception_pks = app('db')->table('accessories_conceptions')->whereIn('accessory_pk', $accessory_pks)->distinct('conception_pk')->pluck('conception_pk');
        foreach ($conception_pks as $conception_pk) {
            if ($params['conception_pk'] == $conception_pk) return False;
        }
        return True;
    }

    public function edit($params)
    {
        $consuming_sessions = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->exists();
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'];
        return $consuming_sessions || !$owner;
    }

    public function delete($params)
    {
        $consuming_sessions = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->exists();
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'];
        return $consuming_sessions || !$owner;

    }

    public function turn_off($params)
    {
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'];
        $issued = app('db')->table('issuing_sessions')->where('container_pk', $params['demand_pk'])->count() >= 1;
        return !$owner || !$issued;

    }

    public function turn_on($params)
    {
        $owner = app('db')->table('demands')->where('pk', $params['demand_pk'])->value('user_pk') == $params['user_pk'];
        return !$owner;

    }

    public function issue($params)
    {
        //Two arrays must be the same after simplifying
        $issued_groups = collect($params['issued_groups']);
        $inCased_items = collect($params['inCased_items']);
        $issued_groups = $issued_groups->mapToGroups(function ($item, $key) {
            return [$item['received_item_pk'] => $item['grouped_quantity']];
        });
        $inCased_items = $inCased_items->mapToGroups(function ($item, $key) {
            return [$item['received_item_pk'] => $item['issued_quantity']];
        });

        $tmp1 = array();
        foreach ($inCased_items as $received_item_pk => $grouped_quantities) {
            $tmp1[$received_item_pk] = $grouped_quantities->sum();
        }
        $tmp2 = array();
        foreach ($issued_groups as $received_item_pk => $grouped_quantities) {
            $tmp2[$received_item_pk] = $grouped_quantities->sum();
        }
        $check_point1 = count(array_diff_assoc($tmp1, $tmp2)) == 0;
        //Each inCased_item's issued_quantity is lower than its current quantity
        $check_point2 = true;
        $inCased_items = $params['inCased_items'];
        foreach ($inCased_items as $inCased_item) {
            $current_quantity = EntryController::inCased_quantity($inCased_item['received_item_pk'], $inCased_item['case_pk']);
            if (!$current_quantity || $current_quantity < $inCased_item['issued_quantity']) $check_point2 = false;
        }
        //Cannot issue more than the difference of demanded_quantity from issued_quantity
        $issued_items = app('db')->table('demanded_items')->where('demand_pk', $params['demand_pk'])
            ->leftJoin('issued_items', 'demanded_items.pk', '=', 'issued_items.end_item_pk')
            ->where('issued_items.is_returned', false)
            ->select((array('demanded_items.accessory_pk', DB::raw('SUM(issued_items.issued_quantity) as issued_quantity'))))
            ->groupBy('demanded_items.accessory_pk')->get();
        $tmp1 = array();
        foreach ($issued_items as $issued_item) {
            $tmp1[$issued_item->accessory_pk] = (int)$issued_item->issued_quantity;
        }
        $demanded_items = app('db')->table('demanded_items')->where('demand_pk', $params['demand_pk'])->select('accessory_pk', 'demanded_quantity')->get();
        $tmp2 = array();
        foreach ($demanded_items as $demanded_item) {
            $tmp2[$demanded_item->accessory_pk] = $demanded_item->demanded_quantity;
        }
        $difference = array();
        foreach ($tmp2 as $a => $c) {
            $q = $c;
            foreach ($tmp1 as $b => $d) {
                if ($a == $b) $q = $c - $d;
            }
            $difference[$a] = $q;
        }
        $issued_groups = $params['issued_groups'];
        $tmp1 = array();
        foreach ($issued_groups as $issued_group) {
            $tmp1[] = [
                'accessory_pk' => ReceivedGroupController::accessory_pk($issued_group['received_item_pk']),
                'grouped_quantity' => $issued_group['grouped_quantity']
            ];
        }
        $tmp2 = collect($tmp1)->mapToGroups(function ($item, $key) {
            return [$item['accessory_pk'] => $item['grouped_quantity']];
        });
        $tmp3 = array();
        foreach ($tmp2 as $accessory_pk => $grouped_quantities) {
            $tmp3[$accessory_pk] = $grouped_quantities->sum();
        }
        $check_point3 = true;
        foreach ($difference as $a => $c) {
            foreach ($tmp3 as $b => $d) {
                if ($a == $b && $c < $d) {
                    $check_point3 = false;
                    break 2;
                }
            }
        }
        return !$check_point1 || !$check_point2 || !$check_point3;
    }

    public function confirm_issuing($params)
    {
        $consuming_session = app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->first();
        $returned = $consuming_session->returning_session_pk == Null ? False : True;
        $confirmed = $consuming_session->progressing_session_pk == Null ? False : True;
        $container_pk = app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->value('container_pk');
        $temp = app('db')->table('demands')->where('pk', $container_pk)->value('workplace_pk');
        $workplace = app('db')->table('users')->where('pk', $params['user_pk'])->value('workplace_pk') == $temp;
        return $returned || $confirmed || !$workplace;
    }

    public function return_issuing($params)
    {
        $consuming_session = app('db')->table('issuing_sessions')->where('pk', $params['consuming_session_pk'])->first();
        $returned = $consuming_session->returning_session_pk == Null ? False : True;
        $confirmed = $consuming_session->progressing_session_pk == Null ? False : True;
        $tmp1 = array();
        foreach ($params['pairs'] as $pair) {
            array_push($tmp1, $pair['case_pk']);
        }
        $tmp2 = app('db')->table('issued_groups')->where('issuing_session_pk', $params['consuming_session_pk'])->distinct('case_pk')->pluck('case_pk')->toArray();
        $all_cases = array_diff($tmp1, $tmp2) == null;
        return $returned || $confirmed || !$all_cases;
    }
}
