<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('/inside', function () use ($router) {
        echo phpinfo();
    });
    $router->group(['middleware' => 'role:merchandiser'], function () use ($router) {
        //Group 2
        //Customer
        $router->post('create_customer', 'CustomerController@create');
        $router->post('edit_customer', 'CustomerController@edit');
        $router->post('delete_customer', 'CustomerController@delete');
        $router->post('deactivate_customer', 'CustomerController@deactivate');
        $router->post('reactivate_customer', 'CustomerController@reactivate');
        //Supplier
        $router->post('create_supplier', 'SupplierController@create');
        $router->post('edit_supplier', 'SupplierController@edit');
        $router->post('delete_supplier', 'SupplierController@delete');
        $router->post('deactivate_supplier', 'SupplierController@deactivate');
        $router->post('reactivate_supplier', 'SupplierController@reactivate');
        //Accessory
        $router->post('create_accessory', 'AccessoryController@create');
        $router->post('delete_accessory', 'AccessoryController@delete');
        $router->post('deactivate_accessory', 'AccessoryController@deactivate');
        $router->post('reactivate_accessory', 'AccessoryController@reactivate');
        $router->post('upload_accessory_photo', 'AccessoryController@upload_photo');
        $router->post('delete_accessory_photo', 'AccessoryController@delete_photo');
        //Conception
        $router->post('create_conception', 'ConceptionController@create');
        $router->post('delete_conception', 'ConceptionController@delete');
        $router->post('deactivate_conception', 'ConceptionController@deactivate');
        $router->post('reactivate_conception', 'ConceptionController@reactivate');
        $router->post('link_conception_accessory', 'ConceptionController@link_accessory');
        $router->post('unlink_conception_accessory', 'ConceptionController@unlink_accessory');
        //Group 4
        //Order
        $router->post('create_order', 'OrderController@create');
        $router->post('edit_order', 'OrderController@edit');
        $router->post('delete_order', 'OrderController@delete');
        $router->post('turn_off_order', 'OrderController@turn_off');
        $router->post('turn_on_order', 'OrderController@turn_on');
        //Group 7
        //Demand
        $router->post('create_demand', 'DemandController@create');
        $router->post('edit_demand', 'DemandController@edit');
        $router->post('delete_demand', 'DemandController@delete');
        $router->post('turn_off_demand', 'DemandController@turn_off');
        $router->post('turn_on_demand', 'DemandController@turn_on');
    });
    $router->group(['middleware' => 'role:manager'], function () use ($router) {
        //Group 3
        //Case
        $router->post('create_case', 'CaseController@create');
        $router->post('disable_case', 'CaseController@disable');
        //Shelf
        $router->post('create_shelf', 'ShelfController@create');
        $router->post('delete_shelf', 'ShelfController@delete');
        //Block
        $router->post('open_block', 'BlockController@open');
        $router->post('close_block', 'BlockController@close');
        //Group 4
        //Import
        $router->post('create_import', 'ImportController@create');
        $router->post('edit_import', 'ImportController@edit');
        $router->post('delete_import', 'ImportController@delete');
        $router->post('turn_off_import', 'ImportController@turn_off');
        $router->post('turn_on_import', 'ImportController@turn_on');
        $router->post('receive_import', 'ImportController@receive');
        $router->post('edit_imported_receiving', 'ImportController@edit_receiving');
        $router->post('delete_imported_receiving', 'ImportController@delete_receiving');
        //Restoration
        $router->post('register_restoration', 'RestorationController@register');
        $router->post('delete_restoration', 'RestorationController@delete');
        $router->post('confirm_restoration', 'RestorationController@confirm');
        $router->post('cancel_restoration', 'RestorationController@cancel');
        $router->post('receive_restoration', 'RestorationController@receive');
        //Group 6
        $router->post('verify_adjusting', 'EntryController@verify_adjusting');
        $router->post('verify_discarding', 'EntryController@verify_discarding');

    });
    $router->group(['middleware' => 'role:staff'], function () use ($router) {
        //Group 5
        //Received Group
        $router->post('count', 'ReceivedGroupController@count');
        $router->post('edit_counting', 'ReceivedGroupController@edit_counting');
        $router->post('delete_counting', 'ReceivedGroupController@delete_counting');
        $router->post('arrange', 'ReceivedGroupController@arrange');
        //Group 6
        //Received Group
        $router->post('store_received_groups', 'ReceivedGroupController@store');
        //Entry
        $router->post('adjust', 'EntryController@ajdust');
        $router->post('discard', 'EntryController@discard');
        $router->post('move', 'EntryController@move');
        //Case
        $router->post('store_case', 'CaseController@store');
        $router->post('replace', 'CaseController@replace');

    });
    $router->group(['middleware' => 'role:inspector'], function () use ($router) {
        //Group 5
        //Received Group
        $router->post('check', 'ReceivedGroupController@check');
        $router->post('edit_checking', 'ReceivedGroupController@edit_checking');
        $router->post('delete_checking', 'ReceivedGroupController@delete_checking');
        //Import
        $router->post('classify', 'ImportController@classify');
        $router->post('reclassify', 'ImportController@reclassify');
        $router->post('delete_classification', 'ImportController@delete_classification');
        $router->post('sendback', 'ImportController@sendback');
    });
    $router->group(['middleware' => 'role:admin'], function () use ($router) {
        //Group 1
        //User
        $router->post('create_user', 'UserController@create');
        $router->post('reset_user_password', 'UserController@reset_password');
        $router->post('deactivate_user', 'UserController@deactivate');
        $router->post('reactivate_user', 'UserController@reactivate');
        $router->post('change_user_workplace', 'UserController@change_workplace');
        //Device
        $router->post('register_device', 'DeviceController@register');
        $router->post('delete_device', 'DeviceController@delete');
        //Workplace
        $router->post('create_workplace', 'WorkplaceController@create');
        $router->post('delete_workplace', 'WorkplaceController@delete');
    });
    /* ANGULAR */
    $router->post('accessories', 'AngularController@get_accessory');
    $router->post('activity-logs', 'AngularController@get_activity_log');
    $router->post('arranging-sessions', 'AngularController@get_arranging_session');
    $router->post('histories', 'AngularController@get_history');
    $router->post('verifying-sessions', 'AngularController@get_verifying_session');
    $router->post('reports', 'AngularController@get_report');
    $router->post('receivings', 'AngularController@get_receiving');
    $router->post('partners', 'AngularController@get_partner');
    $router->post('received-items', 'AngularController@get_received_item');
    $router->post('received-groups', 'AngularController@get_received_group');
    $router->post('root-received-items', 'AngularController@get_root_received_item');
    $router->post('root-receivings', 'AngularController@get_root_receiving');
    $router->post('cases', 'AngularController@get_case');
    $router->post('conceptions', 'AngularController@get_conception');
    $router->post('root-issued-items', 'AngularController@get_root_issued_item');
    $router->post('root-issuings', 'AngularController@get_root_issuing');
    $router->post('inventories', 'AngularController@get_inventory');
    $router->post('blocks', 'AngularController@get_block');
    $router->post('checking-sessions', 'AngularController@get_checking_session');
    $router->post('classifying-sessions', 'AngularController@get_classifying_session');
    $router->post('confirming-sessions', 'AngularController@get_confirming_session');
    $router->post('counting-sessions', 'AngularController@get_counting_session');
    $router->post('in-blocked-items', 'AngularController@get_in_blocked_item');
    $router->post('in-cased-items', 'AngularController@get_in_cased_item');
    $router->post('in-shelved-items', 'AngularController@get_in_shelved_item');
    $router->post('issued-groups', 'AngularController@get_issued_group');
    $router->post('issued-items', 'AngularController@get_issued_item');
    $router->post('issuings', 'AngularController@get_issuing');
    $router->post('modifying-sessions', 'AngularController@get_modifying_session');
    $router->post('moving-sessions', 'AngularController@get_moving_session');
    $router->post('cased-received-groups', 'AngularController@get_cased_received_group');
    $router->post('failed-items', 'AngularController@get_failed_item');
    $router->post('receiving-sessions', 'AngularController@get_receiving_session');
    $router->post('replacing-sessions', 'AngularController@get_replacing_session');
    $router->post('sendbacking-sessions', 'AngularController@get_sendbacking_session');
    $router->post('shelves', 'AngularController@get_shelf');
    $router->post('storing-sessions', 'AngularController@get_storing_session');
    $router->post('types', 'AngularController@get_type');
    $router->post('units', 'AngularController@get_unit');
    $router->post('users', 'AngularController@get_user');
    $router->post('mediators', 'AngularController@get_mediator');
    $router->post('unstored-cases', 'AngularController@get_unstored_case');
    $router->post('unverified-modifying-sessions', 'AngularController@get_unverified_modifying_session');
    $router->post('received-workplaces', 'AngularController@get_received_workplace');
    $router->post('workplaces', 'AngularController@get_workplace');
    $router->post('linkable-accessories', 'AngularController@get_linkable_accessory');
});

$router->get('/outside', function () use ($router) {
    echo phpinfo();
});

//Group 0
//$router->get('/', function () use ($router) {
//    echo phpinfo();
////    return \Illuminate\Support\Str::random(32);
//});

////Group 9
$router->post('login_desktop', 'AuthController@login_desktop');
$router->post('login_mobile', 'AuthController@login_mobile');
$router->post('logout', 'AuthController@logout');
$router->post('change_password', 'UserController@change_password');

//$router->post('', '');
//$router->post('', '');
//$router->post('', '');




