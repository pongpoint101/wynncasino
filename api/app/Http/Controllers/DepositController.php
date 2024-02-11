<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    function playerDeposit()
    {
        $player_id = isset($_GET['player_id']) ? $_GET['player_id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        $data = array();

        $ld = DB::table('log_deposit')->select(DB::raw('id as _id,member_no as player_id, sum(CASE when channel in (1,2,3,5) then amount else 0 end) as deposit_amount,sum(CASE when channel not in (1,2,3,5) then amount else 0 end) as credit_add_amount,trx_date as date'));
        if ($player_id) {
            $ld = $ld->where('member_no', $player_id);
        }
        $ld = $ld->where('status', 1)->groupby('trx_date', 'member_no')->orderBy('trx_date', 'desc');
        if ($start) {
            $ld = $ld->offset($start);
        }
        if ($end) {
            $ld = $ld->limit($end);
        }

        $ld = $ld->get();

        if (count($ld) > 0) {
            $data = [
                'statusCode' => 200,
                'statusMessage' => "success",
                'data' => $ld
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

    function playerDepositRollback()
    {

        $player_id = isset($_GET['player_id']) ? $_GET['player_id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        $data = array();
        $ld = DB::table('log_deposit')->select(DB::raw('id as _id,member_no as player_id, sum(CASE when channel in (1,2,3,5) then amount else 0 end) as deposit_amount,sum(CASE when channel not in (1,2,3,5) then amount else 0 end) as credit_add_amount,trx_date as date'));
        if ($player_id) {
            $ld = $ld->where('member_no', $player_id);
        }
        $ld = $ld->where('status', 1)->where('trx_date', '<', Carbon::now()->format('Y-m-d'))->where('trx_date', '>=', Carbon::now()->subDays(30)->format('Y-m-d'))->groupby('trx_date', 'member_no')->orderBy('trx_date', 'desc');
        if ($start) {
            $ld = $ld->offset($start);
        }
        if ($end) {
            $ld = $ld->limit($end);
        }

        $ld = $ld->get();

        if (count($ld) > 0) {
            $data = [
                'statusCode' => 200,
                'statusMessage' => "success",
                'data' => $ld
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
    function proDeposit()
    {
        $player_id = isset($_GET['player_id']) ? $_GET['player_id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        $data = array();

        $ld = DB::table('log_deposit')->select(DB::raw('id as _id,member_no as player_id ,sum(amount) as amount,trx_date as date'));
        if ($player_id) {
            $ld = $ld->where('member_no', $player_id);
        }
        $ld = $ld->where('status', 1)->whereNotIn('channel',[1,2,3,5])->groupby('trx_date', 'member_no')->orderBy('trx_date', 'desc');
        if ($start) {
            $ld = $ld->offset($start);
        }
        if ($end) {
            $ld = $ld->limit($end);
        }

        $ld = $ld->get();

        if (count($ld) > 0) {
            $data = [
                'statusCode' => 200,
                'statusMessage' => "success",
                'data' => $ld
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
