<?php

namespace App\Http\Controllers;

use App\Models\WinLoss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    function summary()
    {
        $sumplayer = DB::table('members')->count();
        $sumbet = WinLoss::count();
        $sumdeposit = DB::table('log_deposit')->where('status',1)->count();
        $sumwithdraw = DB::table('log_withdraw')->where('status',1)->count();

        $data = [
            'summary_player' => $sumplayer,
            'summary_bet' => $sumbet,
            'summary_deposit' => $sumdeposit,
            'summary_withdraw' => $sumwithdraw,
        ];

        return response()->json([
            'statusCode' => 200,
            'statusMessage' => "success",
            'data' => $data
        ]);
    }
}
