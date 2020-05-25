<?php

namespace App\ViewModels;

use Illuminate\Support\Facades\Crypt;

class History extends ViewModel
{
    public function get_history()
    {
        $history = array();

        $receiving_sessions = app('db')->table('receiving_sessions')->get();
        foreach ($receiving_sessions as $receiving_session) {
            if ($receiving_session->kind == 'importing') {
                $imported_item_pk = app('db')->table('received_groups')->where('receiving_session_pk', $receiving_session->pk)->first()->received_item_pk;
                $import_pk = app('db')->table('imported_items')->where('pk', $imported_item_pk)->value('import_pk');
                $import_id = app('db')->table('imports')->where('pk', $import_pk)->value('id');
                $history[] = [
                    'pk' => $receiving_session->pk,
                    'type' => 'Ghi nhận phiếu nhập',
                    'executedDate' => $receiving_session->executed_date,
                    'content' => "Ghi nhận phiếu nhập $import_id",
                    'user_pk' => $receiving_session->user_pk
                ];
            } elseif ($receiving_session->kind == 'restoring') {
                $restored_item_pk = app('db')->table('received_groups')->where('receiving_session_pk', $receiving_session->pk)->first()->received_item_pk;
                $restoration_pk = app('db')->table('restored_items')->where('pk', $restored_item_pk)->value('restoration_pk');
                $restoration_id = app('db')->table('restorations')->where('pk', $restoration_pk)->value('id');
                $history[] = [
                    'pk' => $receiving_session->pk,
                    'type' => 'Ghi nhận phiếu trả',
                    'executedDate' => $receiving_session->executed_date,
                    'content' => "Ghi nhận phiếu nhập $restoration_id",
                    'user_pk' => $receiving_session->user_pk
                ];
            }
        }

        $counting_sessions = app('db')->table('counting_sessions')->get();
        foreach ($counting_sessions as $counting_session) {
            $received_group = app('db')->table('received_groups')->where('counting_session_pk', $counting_session->pk)->first();
            if ($received_group->kind == 'imported') {
                $import_pk = app('db')->table('imported_items')->where('pk', $received_group->received_item_pk)->value('import_pk');
                $import_id = app('db')->table('imports')->where('pk', $import_pk)->value('id');
                $history[] = [
                    'pk' => $counting_session->pk,
                    'type' => 'Kiểm tra số lượng',
                    'executedDate' => $counting_session->executed_date,
                    'content' => "Kiểm tra số lượng cụm phụ liệu thuộc phiếu nhận $import_id",
                    'user_pk' => $counting_session->user_pk
                ];
            } elseif ($received_group->kind == 'restored') {
                $restoration_pk = app('db')->table('restored_items')->where('pk', $received_group->received_item_pk)->value('restoration_pk');
                $restoration_id = app('db')->table('restorations')->where('pk', $restoration_pk)->value('id');
                $history[] = [
                    'pk' => $counting_session->pk,
                    'type' => 'Kiểm tra số lượng',
                    'executedDate' => $counting_session->executed_date,
                    'content' => "Kiểm tra số lượng cụm phụ liệu thuộc phiếu nhận $restoration_id",
                    'user_pk' => $counting_session->user_pk
                ];
            }
        }

        $checking_sessions = app('db')->table('checking_sessions')->get();
        foreach ($checking_sessions as $checking_session) {
            $received_group = app('db')->table('received_groups')->where('checking_session_pk', $checking_session->pk)->first();
            $import_pk = app('db')->table('imported_items')->where('pk', $received_group->received_item_pk)->value('import_pk');
            $import_id = app('db')->table('imports')->where('pk', $import_pk)->value('id');
            $history[] = [
                'pk' => $checking_session->pk,
                'type' => 'Kiểm tra chất lượng',
                'executedDate' => $checking_session->executed_date,
                'content' => "Kiểm tra chất lượng cụm phụ liệu thuộc phiếu nhận $import_id",
                'user_pk' => $checking_session->user_pk
            ];
        }

        $arranging_sessions = app('db')->table('arranging_sessions')->get();
        foreach ($arranging_sessions as $arranging_session) {
            $start_case_id = app('db')->table('cases')->where('pk', $arranging_session->start_case_pk)->value('id');
            $end_case_id = app('db')->table('cases')->where('pk', $arranging_session->end_case_pk)->value('id');
            $history[] = [
                'pk' => $arranging_session->pk,
                'type' => 'Sắp xếp cụm phụ liệu',
                'executedDate' => $arranging_session->executed_date,
                'content' => "Sắp xếp cụm phụ liệu từ đơn vị chứa $start_case_id sang $end_case_id",
                'user_pk' => $arranging_session->user_pk
            ];
        }

        $storing_sessions = app('db')->table('storing_sessions')->get();
        foreach ($storing_sessions as $storing_session) {
            $count = app('db')->table('received_groups')->where('storing_session_pk', $storing_session->pk)->count();
            $history[] = [
                'pk' => $storing_session->pk,
                'type' => 'Lưu kho cụm phụ liệu',
                'executedDate' => $storing_session->executed_date,
                'content' => "Lưu kho $count cụm phụ liệu ",
                'user_pk' => $storing_session->user_pk
            ];
        }

        $adjusting_sessions = app('db')->table('adjusting_sessions')->get();
        foreach ($adjusting_sessions as $adjusting_session) {
            $entry = app('db')->table('entries')->where('session_pk', $adjusting_session->pk)->first();
            if ($entry->kind == 'imported') {
                $import_pk = app('db')->table('imported_items')->where('pk', $entry->received_item_pk)->value('import_pk');
                $import_id = app('db')->table('imports')->where('pk', $import_pk)->value('id');
                $accessory_id = app('db')->table('accessories')->where('pk', $entry->accessory_pk)->value('name');
                $quantity = $adjusting_session->quantity > 0 ? (string)'+' . $adjusting_session->quantity : (string)$adjusting_session->quantity;
                $history[] = [
                    'pk' => $adjusting_session->pk,
                    'type' => 'Hiệu chỉnh tồn kho',
                    'executedDate' => $adjusting_session->executed_date,
                    'content' => "Đăng kí hiệu chỉnh tồn kho $quantity phụ liêu $accessory_id thuộc phiếu nhận  $import_id",
                    'user_pk' => $adjusting_session->user_pk
                ];
            } elseif ($entry->kind == 'restored') {
                $restoration_pk = app('db')->table('restored_items')->where('pk', $entry->received_item_pk)->value('restoration_pk');
                $restoration_id = app('db')->table('restorations')->where('pk', $restoration_pk)->value('id');
                $accessory_id = app('db')->table('accessories')->where('pk', $entry->accessory_pk)->value('name');
                $quantity = $adjusting_session->quantity > 0 ? (string)'+' . $adjusting_session->quantity : (string)$adjusting_session->quantity;
                $history[] = [
                    'pk' => $adjusting_session->pk,
                    'type' => 'Hiệu chỉnh tồn kho',
                    'executedDate' => $adjusting_session->executed_date,
                    'content' => "Đăng kí hiệu chỉnh tồn kho $quantity phụ liêu $accessory_id thuộc phiếu nhận  $restoration_id",
                    'user_pk' => $adjusting_session->user_pk
                ];
            }
        }

        $discarding_sessions = app('db')->table('discarding_sessions')->get();
        foreach ($discarding_sessions as $discarding_session) {
            $entry = app('db')->table('entries')->where('session_pk', $discarding_session->pk)->first();
            if ($entry->kind == 'imported') {
                $import_pk = app('db')->table('imported_items')->where('pk', $entry->received_item_pk)->value('import_pk');
                $import_id = app('db')->table('imports')->where('pk', $import_pk)->value('id');
                $accessory_id = app('db')->table('accessories')->where('pk', $entry->accessory_pk)->value('name');
                $quantity = abs($discarding_session->quantity);
                $history[] = [
                    'pk' => $discarding_session->pk,
                    'type' => 'Hủy tồn kho',
                    'executedDate' => $discarding_session->executed_date,
                    'content' => "Đăng kí hủy tồn kho $quantity phụ liêu $accessory_id thuộc phiếu nhận $import_id",
                    'user_pk' => $discarding_session->user_pk
                ];
            } elseif ($entry->kind == 'restored') {
                $restoration_pk = app('db')->table('restored_items')->where('pk', $entry->received_item_pk)->value('restoration_pk');
                $restoration_id = app('db')->table('restorations')->where('pk', $restoration_pk)->value('id');
                $accessory_id = app('db')->table('accessories')->where('pk', $entry->accessory_pk)->value('name');
                $quantity = abs($discarding_session->quantity);
                $history[] = [
                    'pk' => $discarding_session->pk,
                    'type' => 'Hủy tồn kho',
                    'executedDate' => $discarding_session->executed_date,
                    'content' => "Đăng kí hủy tồn kho $quantity phụ liêu $accessory_id thuộc phiếu nhận  $restoration_id",
                    'user_pk' => $discarding_session->user_pk
                ];
            }
        }

        $issuing_sessions = app('db')->table('issuing_sessions')->get();
        foreach ($issuing_sessions as $issuing_session) {
            if ($issuing_session->kind == 'consuming') {
                $demand_id = app('db')->table('demands')->where('pk', $issuing_session->container_pk)->value('id');
                $history[] = [
                    'pk' => $issuing_session->pk,
                    'type' => 'Xuất kho',
                    'executedDate' => $issuing_session->executed_date,
                    'content' => "Xuất kho phụ liệu theo đơn yêu cầu $demand_id",
                    'user_pk' => $issuing_session->user_pk
                ];
            }
        }

        $returning_sessions = app('db')->table('returning_sessions')->get();
        foreach ($returning_sessions as $returning_session) {
            $issuing_session = app('db')->table('issuing_sessions')->where('returning_session_pk', $returning_session->pk)->select('container_pk', 'kind')->first();
            if ($issuing_session->kind == 'consuming') {
                $demand_id = app('db')->table('demands')->where('pk', $issuing_session->container_pk)->value('id');
                $history[] = [
                    'pk' => $returning_session->pk,
                    'type' => 'Lưu kho phụ liệu xuất',
                    'executedDate' => $returning_session->executed_date,
                    'content' => "Lưu kho lại phụ liệu đã xuất theo đơn yêu cầu $demand_id",
                    'user_pk' => $returning_session->user_pk
                ];
            }

        }

        $classifying_sessions = app('db')->table('classifying_sessions')->get();
        foreach ($classifying_sessions as $classifying_session) {
            $classified_item_pk = app('db')->table('classifying_sessions')->where('pk', $classifying_session->pk)->value('classified_item_pk');
            $imported_item = app('db')->table('imported_items')->where('classified_item_pk', $classified_item_pk)->select('ordered_item_pk', 'import_pk')->first();
            $import_id = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('id');
            $accessory_pk = app('db')->table('ordered_items')->where('pk', $imported_item->import_pk)->value('accessory_pk');
            $accessory_id = app('db')->table('imports')->where('pk', $accessory_pk)->value('id');
            $history[] = [
                'pk' => $classifying_session->pk,
                'type' => 'Đánh giá chất lượng',
                'executedDate' => $classifying_session->executed_date,
                'content' => "Đánh giá chất lượng phụ liệu $accessory_id thuộc phiếu nhập $import_id ",
                'user_pk' => $classifying_session->user_pk
            ];
        }

        $sendbacking_sessions = app('db')->table('sendbacking_sessions')->get();
        foreach ($sendbacking_sessions as $sendbacking_session) {
            $classified_item_pk = app('db')->table('classified_items')->where('sendbacking_session_pk', $sendbacking_session->pk)->value('pk');
            $imported_item = app('db')->table('imported_items')->where('classified_item_pk', $classified_item_pk)->select('ordered_item_pk', 'import_pk')->first();
            $import_id = app('db')->table('imports')->where('pk', $imported_item->import_pk)->value('id');
            $accessory_pk = app('db')->table('ordered_items')->where('pk', $imported_item->import_pk)->value('accessory_pk');
            $accessory_id = app('db')->table('imports')->where('pk', $accessory_pk)->value('id');
            $history[] = [
                'pk' => $sendbacking_session->pk,
                'type' => 'Gửi trả phụ liệu không đạt',
                'executedDate' => $sendbacking_session->executed_date,
                'content' => "Gửi trả phụ liệu $accessory_id không đạt chất lượng từ phiếu nhập $import_id ",
                'user_pk' => $sendbacking_session->user_pk
            ];
        }

        $restorations = app('db')->table('restorations')->where('is_confirmed', True)->get();
        foreach ($restorations as $restoration) {
            $history[] = [
                'pk' => $restoration->pk,
                'type' => 'Trả phụ liệu',
                'executedDate' => $restoration->created_date,
                'content' => "Đăng kí phiếu trả $restoration->id",
                'user_pk' => $restoration->user_pk
            ];
        }

        $confirming_sessions = app('db')->table('progressing_sessions')->where('kind', 'confirming')->get();
        foreach ($confirming_sessions as $confirming_session) {
            $demand_pk = app('db')->table('issuing_sessions')->where('progressing_session_pk', $confirming_session->pk)->value('container_pk');
            $demand_id = app('db')->table('demands')->where('pk', $demand_pk)->value('id');
            $history[] = [
                'pk' => $confirming_session->pk,
                'type' => 'Xác nhận nhận phụ liệu',
                'executedDate' => $confirming_session->executed_date,
                'content' => "Xác nhận nhận phụ liệu xuất thuộc phiếu xuất $demand_id",
                'user_pk' => $confirming_session->user_pk
            ];
        }

        $moving_sessions = app('db')->table('moving_sessions')->get();
        foreach ($moving_sessions as $moving_session) {
            $start_case_id = app('db')->table('cases')->where('pk', $moving_session->start_case_pk)->value('id');
            $end_case_id = app('db')->table('cases')->where('pk', $moving_session->end_case_pk)->value('id');
            $history[] = [
                'pk' => $moving_session->pk,
                'type' => 'Sắp xếp phụ liệu tồn',
                'executedDate' => $moving_session->executed_date,
                'content' => "Chuyển phụ liệu tồn kho từ đơn vị chứa $start_case_id sang $end_case_id",
                'user_pk' => $moving_session->user_pk
            ];
        }

        $replacing_sessions = app('db')->table('replacing_sessions')->get();
        foreach ($replacing_sessions as $replacing_session) {
            $start_shelf_id = app('db')->table('shelves')->where('pk', $replacing_session->start_shelf_pk)->value('name');
            $end_shelf_id = app('db')->table('shelves')->where('pk', $replacing_session->end_shelf_pk)->value('name');
            $case_id = app('db')->table('cases')->where('pk', $replacing_session->case_pk)->value('id');
            $history[] = [
                'pk' => $replacing_session->pk,
                'type' => 'Chuyển đơn vị chứa',
                'executedDate' => $replacing_session->executed_date,
                'content' => "Chuyển đơn vị chứa $case_id từ ô kệ $start_shelf_id sang $end_shelf_id",
                'user_pk' => $replacing_session->user_pk
            ];
        }

        return $this::user_translation($this::sort_response($history, 'executedDate'));
    }

    public function get_short_history($params)
    {
        $api = $params->header('api_token');
        $user_pk = Crypt::decrypt($api)['pk'];
        $history = array();

        $receiving_sessions = app('db')->table('receiving_sessions')->where('user_pk', $user_pk)->get();
        foreach ($receiving_sessions as $receiving_session) {
            if ($receiving_session->kind == 'importing') {
                $history[] = [
                    'pk' => $receiving_session->pk,
                    'type' => 'Ghi nhận phiếu nhập',
                    'executedDate' => $receiving_session->executed_date,
                ];
            } elseif ($receiving_session->kind == 'restoring') {
                $history[] = [
                    'pk' => $receiving_session->pk,
                    'type' => 'Ghi nhận phiếu trả',
                    'executedDate' => $receiving_session->executed_date,
                ];
            }
        }

        $counting_sessions = app('db')->table('counting_sessions')->where('user_pk', $user_pk)->get();
        foreach ($counting_sessions as $counting_session) {
            $history[] = [
                'pk' => $counting_session->pk,
                'type' => 'Kiểm tra số lượng',
                'executedDate' => $counting_session->executed_date,
            ];
        }

        $checking_sessions = app('db')->table('checking_sessions')->where('user_pk', $user_pk)->get();
        foreach ($checking_sessions as $checking_session) {
            $history[] = [
                'pk' => $checking_session->pk,
                'type' => 'Kiểm tra chất lượng',
                'executedDate' => $checking_session->executed_date,
            ];
        }

        $arranging_sessions = app('db')->table('arranging_sessions')->where('user_pk', $user_pk)->get();
        foreach ($arranging_sessions as $arranging_session) {
            $history[] = [
                'pk' => $arranging_session->pk,
                'type' => 'Sắp xếp cụm phụ liệu',
                'executedDate' => $arranging_session->executed_date,
            ];
        }

        $storing_sessions = app('db')->table('storing_sessions')->where('user_pk', $user_pk)->get();
        foreach ($storing_sessions as $storing_session) {
            $history[] = [
                'pk' => $storing_session->pk,
                'type' => 'Lưu kho cụm phụ liệu',
                'executedDate' => $storing_session->executed_date,
            ];
        }

        $adjusting_sessions = app('db')->table('adjusting_sessions')->where('user_pk', $user_pk)->get();
        foreach ($adjusting_sessions as $adjusting_session) {
            $history[] = [
                'pk' => $adjusting_session->pk,
                'type' => 'Hiệu chỉnh tồn kho',
                'executedDate' => $adjusting_session->executed_date,
            ];
        }

        $discarding_sessions = app('db')->table('discarding_sessions')->where('user_pk', $user_pk)->get();
        foreach ($discarding_sessions as $discarding_session) {
            $history[] = [
                'pk' => $discarding_session->pk,
                'type' => 'Hủy tồn kho',
                'executedDate' => $discarding_session->executed_date,
            ];
        }

        $issuing_sessions = app('db')->table('issuing_sessions')->where('user_pk', $user_pk)->get();
        foreach ($issuing_sessions as $issuing_session) {
            $history[] = [
                'pk' => $issuing_session->pk,
                'type' => 'Xuất kho',
                'executedDate' => $issuing_session->executed_date,
            ];
        }

        $returning_sessions = app('db')->table('returning_sessions')->where('user_pk', $user_pk)->get();
        foreach ($returning_sessions as $returning_session) {
            $history[] = [
                'pk' => $returning_session->pk,
                'type' => 'Hồi kho phụ liệu xuất',
                'executedDate' => $returning_session->executed_date,
            ];
        }

        $classifying_sessions = app('db')->table('classifying_sessions')->where('user_pk', $user_pk)->get();
        foreach ($classifying_sessions as $classifying_session) {
            $history[] = [
                'pk' => $classifying_session->pk,
                'type' => 'Đánh giá chất lượng',
                'executedDate' => $classifying_session->executed_date,
            ];
        }

        $sendbacking_sessions = app('db')->table('sendbacking_sessions')->where('user_pk', $user_pk)->get();
        foreach ($sendbacking_sessions as $sendbacking_session) {
            $history[] = [
                'pk' => $sendbacking_session->pk,
                'type' => 'Gửi trả phụ liệu không đạt',
                'executedDate' => $sendbacking_session->executed_date,
            ];
        }

        $confirming_sessions = app('db')->table('progressing_sessions')->where('kind', 'confirming')->where('user_pk', $user_pk)->get();
        foreach ($confirming_sessions as $confirming_session) {
            $history[] = [
                'pk' => $confirming_session->pk,
                'type' => 'Xác nhận nhận phụ liệu',
                'executedDate' => $confirming_session->executed_date,
            ];
        }

        $restorations = app('db')->table('restorations')->where('is_confirmed', True)->where('user_pk', $user_pk)->get();
        foreach ($restorations as $restoration) {
            $history[] = [
                'pk' => $restoration->pk,
                'type' => 'Trả phụ liệu',
                'executedDate' => $restoration->created_date,
            ];
        }

        $moving_sessions = app('db')->table('moving_sessions')->where('user_pk', $user_pk)->get();
        foreach ($moving_sessions as $moving_session) {
            $history[] = [
                'pk' => $moving_session->pk,
                'type' => 'Sắp xếp phụ liệu tồn',
                'executedDate' => $moving_session->executed_date,
            ];
        }

        $replacing_sessions = app('db')->table('replacing_sessions')->where('user_pk', $user_pk)->get();
        foreach ($replacing_sessions as $replacing_session) {
            $history[] = [
                'pk' => $replacing_session->pk,
                'type' => 'Chuyển đơn vị chứa',
                'executedDate' => $replacing_session->executed_date,
            ];
        }

        return $this::sort_response($history, 'executedDate');
    }

}
