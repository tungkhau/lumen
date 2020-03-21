<?php

namespace App\Repositories;

class GroupedItemRepository
{

    public function count($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('counting_sessions')->insert([
                'pk' => $params['counting_session_pk'],
                'counted_quantity' => $params['counted_quantity'],
                'user_pk' => $params['user_pk']
            ]);
            app('db')->table('received_groups')->where('pk', $params['received_group_pk'])->update([
                'counting_session_pk' => $params['counting_session_pk']]);
        });
    }

    public function edit_counting($params)
    {
        app('db')->table('counting_sessions')->where('pk', $params['counting_session_pk'])->update([
            'counted_quantity' => $params['counted_quantity'],
            'created_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete_counting($key)
    {
        app('db')->transaction(function () use ($key) {
            app('db')->table('received_groups')->where('counting_session_pk', $key)->update([
                'counting_session_pk' => Null
            ]);
            app('db')->table('counting_sessions')->where('pk', $key)->delete();
        });
    }

    public function check($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('checking_sessions')->insert([
                'pk' => $params['checking_session_pk'],
                'checked_quantity' => $params['checked_quantity'],
                'unqualified_quantity' => $params['unqualified_quantity'],
                'user_pk' => $params['user_pk']
            ]);
            app('db')->table('received_groups')->where('pk', $params['imported_group_pk'])->update([
                'checking_session_pk' => $params['checking_session_pk']
            ]);
        });
    }

    public function edit_checking($params)
    {
        app('db')->table('checking_sessions')->where('pk', $params['checking_session_pk'])->update([
            'checked_quantity' => $params['checked_quantity'],
            'unqualified_quantity' => $params['unqualified_quantity'],
            'created_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function delete_checking($key)
    {
        app('db')->transaction(function () use ($key) {
            app('db')->table('received_groups')->where('checking_session_pk', $key)->update([
                'checking_session_pk' => Null
            ]);
            app('db')->table('checking_sessions')->where('pk', $key)->delete();
        });
    }

    public function arrange($params)
    {
        app('db')->transaction(function () use ($params) {
            app('db')->table('arranging_sessions')->insert([
                'pk' => $params['arranging_session_pk'],
                'start_case_pk' => $params['start_case_pk'],
                'end_case_pk' => $params['end_case_pk'],
                'user_pk' => $params['user_pk']
            ]);
            foreach ($params['received_groups'] as $received_group) {
                app('db')->table('received_groups_arranging_sessions')->insert([
                    'received_group_pk' => $received_group['received_group_pk'],
                    'arranging_session_pk' => $params['arranging_session_pk']
                ]);
                app('db')->table('received_groups')->update([
                    'case_pk' => $params['end_case_pk']
                ]);
            }
            app('db')->table('cases')->where('pk', $params['end_case_pk'])->update([
                'shelf_pk' => Null
            ]);
        }); //TODO Can optimize
    }
}
