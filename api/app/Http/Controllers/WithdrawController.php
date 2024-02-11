<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    function playerWithdraw()
    {
        $player_id = isset($_GET['player_id']) ? $_GET['player_id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        $data = array();

        $lw = DB::table('log_withdraw')->select(DB::raw('id as _id,member_no as player_id,sum(amount) as withdraw_amount,channel as channel_withdraw,trx_date as date'));
        if ($player_id) {
            $lw = $lw->where('member_no', $player_id);
        }
        $lw = $lw->where('status', 1)->groupby('trx_date', 'member_no', 'channel')->orderBy('trx_date', 'desc');
        if ($start) {
            $lw = $lw->offset($start);
        }
        if ($end) {
            $lw = $lw->limit($end);
        }

        $lw = $lw->get();
        
        if (count($lw) > 0) {
            $data = [
                'statusCode' => 200,
                'statusMessage' => "success",
                'data' => $lw
            ];
        } else {
            $data = [
                'statusCode' => 204,
                'statusMessage' => "no data.",
                'data' => null
            ];
        }

        return response()->json($data);
    }
}
