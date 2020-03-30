<?php

namespace App\Preconditions;

class ImportPrecondition
{
    public function edit($params)
    {
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $params['import_pk'])->pluck('pk')->toArray();
        $received_groups = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->exists();
        $owner = app('db')->table('imports')->where('pk', $params['import_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        $unique = app('db')->table('imported_items')->where('pk', $params['imported_item_pk'])->value('import_pk') == $params['import_pk'] ? True : False;

        return $received_groups || !$owner || !$unique;
    }

    public function delete($params)
    {
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $params['import_pk'])->pluck('pk')->toArray();
        $received_groups = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->exists();
        $owner = app('db')->table('imports')->where('pk', $params['import_pk'])->value('user_pk') == $params['user_pk'] ? True : False;

        return $received_groups || !$owner;
    }

    public function turn_off($params)
    {
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $params['import_pk'])->pluck('pk')->toArray();
        $received_groups = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->exists();
        $owner = app('db')->table('imports')->where('pk', $params['import_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$received_groups || !$owner;
    }

    public function turn_on($params)
    {
        $imported_item_pks = app('db')->table('imported_items')->where('import_pk', $params['import_pk'])->pluck('pk')->toArray();
        $checked_or_counted = app('db')->table('received_groups')->whereIn('received_item_pk', $imported_item_pks)->where([['counting_session_pk', '!=', Null], ['checking_session_pk', '!=', Null]])->exists();
        $owner = app('db')->table('imports')->where('pk', $params['import_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        $classified = app('db')->table('imported_items')->where([['import_pk', $params['import_pk']], ['classified_item_pk', '!=', Null]])->exists();

        return !$owner || $classified || $checked_or_counted;
    }

    public function edit_receiving($params)
    {
        //If current user is its owner
        $owner = app('db')->table('receiving_sessions')->where('pk', $params['importing_session_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        //If all imported groups belong to an opened import
        $opened = false;
        $imported_item_pks = app('db')->table('received_groups')->where('receiving_session_pk', $params['importing_session_pk'])->distinct('received_item_pk')->pluck('received_item_pk')->toArray();
        $import_pks = app('db')->table('imported_items')->whereIn('pk', $imported_item_pks)->distinct('import_pk')->pluck('import_pk')->toArray(); //Expect only one import
        if (count($import_pks) == 1) $opened = app('db')->table('imports')->where('pk', $import_pks[0])->value('is_opened');

        return !$owner || !$opened;
    }

    public function delete_receiving($params)
    {
        //If all imported groups belong to an opened import
        $opened = false;
        $imported_item_pks = app('db')->table('received_groups')->where('receiving_session_pk', $params['importing_session_pk'])->distinct('received_item_pk')->pluck('received_item_pk')->toArray();
        $import_pks = app('db')->table('imported_items')->whereIn('pk', $imported_item_pks)->distinct('import_pk')->pluck('import_pk')->toArray(); //Expect only one import
        if (count($import_pks) == 1) $opened = app('db')->table('imports')->where('pk', $import_pks[0])->value('is_opened');
        //If current user is its owner
        $owner = app('db')->table('receiving_sessions')->where('pk', $params['importing_session_pk'])->value('user_pk') == $params['user_pk'] ? True : False;
        return !$opened || !$owner;
    }

    public function classify($params)
    {
        $imported_item = app('db')->table('imported_items')->where('pk', $params['imported_item_pk'])->select('import_pk', 'classified_item_pk')->first();
        $opened = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('is_opened');
        $classified = $imported_item->classified_item ? True : False;
        return $opened || $classified;
    }

    public function reclassify($params)
    {
        $classified_item = app('db')->table('classified_items')->where('pk', $params['classified_item_pk'])->first();
        if ($classified_item->quality_state == 'failed' && $classified_item->sendbacking_session_pk != Null) return True;
        if ($classified_item->quality_state == 'passed') {
            $imported_item_pk = app('db')->table('imported_items')->where('classified_item_pk', $classified_item->pk)->value('pk');
            if (app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->where('storing_session_pk', '!=', Null)->exists()) return True;
        }
        return False;
    }

    public function delete_classification($params)
    {
        $classified_item = app('db')->table('classified_items')->where('pk', $params['classified_item_pk'])->first();
        if ($classified_item->quality_state == 'failed' && $classified_item->sendbacking_session_pk != Null) return True;
        if ($classified_item->quality_state == 'passed') {
            $imported_item_pk = app('db')->table('imported_items')->where('classified_item_pk', $classified_item->pk)->value('pk');
            if (app('db')->table('received_groups')->where('received_item_pk', $imported_item_pk)->where('storing_session_pk', '!=', Null)->exists()) return True;
        }
        return False;
    }

    public function sendback($params)
    {
        return app('db')->table('classified_items')->where('pk', $params['failed_item_pk'])->value('sendbacking_session_pk') ? True : False;
    }
}
