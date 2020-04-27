<?php

namespace App\Http\Controllers;

use App\ViewModels\Accessory;
use App\ViewModels\Block;
use App\ViewModels\Box;
use App\ViewModels\CheckingSession;
use App\ViewModels\ClassifyingSession;
use App\ViewModels\Conception;
use App\ViewModels\CountingSession;
use App\ViewModels\InCasedItem;
use App\ViewModels\IssuedGroup;
use App\ViewModels\IssuedItem;
use App\ViewModels\Issuing;
use App\ViewModels\ModifyingSession;
use App\ViewModels\Partner;
use App\ViewModels\ReceivedGroup;
use App\ViewModels\ReceivedItem;
use App\ViewModels\Receiving;
use App\ViewModels\ReceivingSession;
use App\ViewModels\Report;
use App\ViewModels\RootIssuedItem;
use App\ViewModels\RootIssuing;
use App\ViewModels\RootReceivedItem;
use App\ViewModels\RootReceiving;
use App\ViewModels\SendbackingSession;
use App\ViewModels\Shared;
use App\ViewModels\Shelf;
use App\ViewModels\StoringSession;
use Illuminate\Http\Request;

class AngularController extends Controller
{
    private $receiving;
    private $accessory;
    private $received_item;
    private $received_group;
    private $root_received_item;
    private $root_receiving;
    private $partner;
    private $root_issued_item;
    private $conception;
    private $root_issuing;
    private $shared;
    private $report;
    private $checking_session;
    private $counting_session;
    private $storing_session;
    private $receiving_session;
    private $classifying_session;
    private $in_cased_item;
    private $case;
    private $sendbacking_session;
    private $issuing;
    private $issued_item;
    private $issued_group;
    private $shelf;
    private $modifying_session;
    private $block;


    public function __construct(Block $block, ModifyingSession $modifying_session, Shelf $shelf, IssuedGroup $issued_group, IssuedItem $issued_item, Issuing $issuing, SendbackingSession $sendbacking_session, Box $case, InCasedItem $in_cased_item, ReceivingSession $receiving_session, StoringSession $storing_session, ClassifyingSession $classifying_session, CountingSession $counting_seesion, CheckingSession $checking_session, Report $report, Shared $shared, RootIssuing $root_issuing, RootIssuedItem $root_issued_item, Partner $partner, Receiving $receiving, Accessory $accessory, ReceivedItem $received_item, ReceivedGroup $received_group, RootReceivedItem $root_received_item, RootReceiving $root_receiving, Conception $conception)
    {
        $this->receiving = $receiving;
        $this->accessory = $accessory;
        $this->received_item = $received_item;
        $this->received_group = $received_group;
        $this->root_received_item = $root_received_item;
        $this->root_receiving = $root_receiving;
        $this->conception = $conception;
        $this->partner = $partner;
        $this->root_issued_item = $root_issued_item;
        $this->root_issuing = $root_issuing;
        $this->shared = $shared;
        $this->report = $report;
        $this->checking_session = $checking_session;
        $this->counting_session = $counting_seesion;
        $this->storing_session = $storing_session;
        $this->receiving_session = $receiving_session;
        $this->classifying_session = $classifying_session;
        $this->in_cased_item = $in_cased_item;
        $this->case = $case;
        $this->sendbacking_session = $sendbacking_session;
        $this->issuing = $issuing;
        $this->issued_item = $issued_item;
        $this->issued_group = $issued_group;
        $this->shelf = $shelf;
        $this->modifying_session = $modifying_session;
        $this->block = $block;
    }

    public function get_partner(Request $request)
    {
        $response = $this->partner->get($request);
        $response = array_values($response);
        return response()->json(['partners' => $response], 201);
    }

    public function get_receiving(Request $request)
    {
        $response = $this->receiving->get($request);
        $response = array_values($response);
        return response()->json(['receivings' => $response], 201);
    }

    public function get_accessory(Request $request)
    {
        $response = $this->accessory->get($request);
        $response = array_values($response);
        return response()->json(['accessories' => $response], 201);
    }

    public function get_received_item(Request $request)
    {
        $response = $this->received_item->get($request);
        $response = array_values($response);
        return response()->json(['received-items' => $response], 201);
    }

    public function get_received_group(Request $request)
    {
        $response = $this->received_group->get($request);
        $response = array_values($response);
        return response()->json(['received-groups' => $response], 201);
    }

    public function get_root_received_item(Request $request)
    {
        $response = $this->root_received_item->get($request);
        $response = array_values($response);
        return response()->json(['root-received-items' => $response], 201);
    }

    public function get_root_receiving(Request $request)
    {
        $response = $this->root_receiving->get($request);
        $response = array_values($response);
        return response()->json(['root-receivings' => $response], 201);
    }

    public function get_conception(Request $request)
    {
        $response = $this->conception->get($request);
        $response = array_values($response);
        return response()->json(['conceptions' => $response], 201);
    }

    public function get_root_issued_item(Request $request)
    {
        $response = $this->root_issued_item->get($request);
        $response = array_values($response);
        return response()->json(['root-issued-items' => $response], 201);
    }

    public function get_root_issuing(Request $request)
    {
        $response = $this->root_issuing->get($request);
        $response = array_values($response);
        return response()->json(['root-issuings' => $response], 201);
    }

    public function get_activity_log()
    {
        $response = $this->shared->get_activity_log();
        $response = array_values($response);
        return response()->json(['activity-logs' => $response], 201);
    }

    public function get_inventory()
    {
        $response = $this->shared->get_inventory();
        $response = array_values($response);
        return response()->json(['inventories' => $response], 201);
    }

    public function get_report(Request $request)
    {
        $response = $this->report->get($request);
        $response = array_values($response);
        return response()->json(['reports' => $response], 201);
    }

    public function get_block(Request $request)
    {
        $response = $this->block->get($request);
        $response = array_values($response);
        return response()->json(['blocks' => $response], 201);
    }

    public function get_history()
    {
        $response = $this->shared->get_history();
        $response = array_values($response);
        return response()->json(['histories' => $response], 201);
    }

    public function get_cased_received_group(Request $request)
    {
        $response = $this->received_group->get_cased_received_group($request);
        $response = array_values($response);
        return response()->json(['cased-received-groups' => $response], 201);
    }

    public function get_failed_item()
    {
        $response = $this->received_item->get_failed_item();
        $response = array_values($response);
        return response()->json(['failed-items' => $response], 201);
    }

    public function get_type()
    {
        $response = $this->shared->get_type();
        $response = array_values($response);
        return response()->json(['types' => $response], 201);
    }

    public function get_unit()
    {
        $response = $this->shared->get_unit();
        $response = array_values($response);
        return response()->json(['units' => $response], 201);
    }

    public function get_mediator()
    {
        $response = $this->shared->get_mediator();
        $response = array_values($response);
        return response()->json(['mediators' => $response], 201);
    }

    public function get_checking_session(Request $request)
    {
        $response = $this->checking_session->get($request);
        $response = array_values($response);
        return response()->json(['checking-sessions' => $response], 201);
    }

    public function get_counting_session(Request $request)
    {
        $response = $this->counting_session->get($request);
        $response = array_values($response);
        return response()->json(['counting-sessions' => $response], 201);
    }

    public function get_classifying_session(Request $request)
    {
        $response = $this->classifying_session->get($request);
        $response = array_values($response);
        return response()->json(['classifying-sessions' => $response], 201);
    }

    public function get_storing_session(Request $request)
    {
        $response = $this->storing_session->get($request);
        $response = array_values($response);
        return response()->json(['storing_sessions' => $response], 201);
    }

    public function get_receiving_session(Request $request)
    {
        $response = $this->receiving_session->get($request);
        $response = array_values($response);
        return response()->json(['receiving-sessions' => $response], 201);
    }

    public function get_in_cased_item(Request $request)
    {
        $response = $this->in_cased_item->get($request);
        $response = array_values($response);
        return response()->json(['in-cased-items' => $response], 201);
    }

    public function get_sendbacking_session(Request $request)
    {
        $response = $this->sendbacking_session->get($request);
        $response = array_values($response);
        return response()->json(['sendbacking-sessions' => $response], 201);
    }

    public function get_case(Request $request)
    {
        $response = $this->case->get($request);
        $response = array_values($response);
        return response()->json(['cases' => $response], 201);
    }

    public function get_issued_group(Request $request)
    {
        $response = $this->issued_group->get($request);
        $response = array_values($response);
        return response()->json(['issued-groups' => $response], 201);
    }

    public function get_issued_item(Request $request)
    {
        $response = $this->issued_item->get($request);
        $response = array_values($response);
        return response()->json(['issued-items' => $response], 201);
    }

    public function get_issuing(Request $request)
    {
        $response = $this->issuing->get($request);
        $response = array_values($response);
        return response()->json(['issuings' => $response], 201);
    }

    public function get_shelf(Request $request)
    {
        $response = $this->shelf->get($request);
        $response = array_values($response);
        return response()->json(['shelves' => $response], 201);
    }

    public function get_unstored_case()
    {
        $response = $this->case->get_unstored_case();
        $response = array_values($response);
        return response()->json(['unstored-cases' => $response], 201);
    }

    public function get_modifying_session(Request $request)
    {
        $response = $this->modifying_session->get($request);
        $response = array_values($response);
        return response()->json(['modifying-sessions' => $response], 201);
    }

    public function get_unverified_modifying_session(Request $request)
    {
        $response = $this->modifying_session->get_unverified_modifying_session($request);
        $response = array_values($response);
        return response()->json(['unverified-modifying-sessions' => $response], 201);
    }


}

