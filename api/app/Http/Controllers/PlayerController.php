<?php

namespace App\Http\Controllers;

use App\Models\WinLoss;
use App\Models\Members;
use Carbon\Carbon;
use App\Models\Provider;
use App\Models\GameList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    function list()
    {

        $player_id = isset($_GET['player_id']) ? $_GET['player_id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        $data = array(
            'statusCode' => 204,
            'statusMessage' => 'no data.',
            'data' => null
        );
        $data_player = null;
        $member = Members::select('id', 'username', 'telephone', 'fname', 'lname', 'create_at', 'group_af_l1', 'group_af_l2', 'partner');
        if ($player_id) {
            $member = $member->where('id', $player_id);
        }
        $member = $member->with(['hasOneWallet' => function ($query) {
            $query->select('member_no', 'main_wallet');
        }])->orderby('id', 'asc');

        if ($start) {
            $member = $member->offset($start);
        }
        if ($end) {
            $member = $member->limit($end);
        }
        $member = $member->get();
        if ($member) {
            foreach ($member as $key => $value) {
                $summary_win = WinLoss::select(DB::raw('sum(wl_amount) as win'))->where('member_no', $value->id)->where('wl_amount', '>=', 0)->first();
                $summary_loss = WinLoss::select(DB::raw('sum(wl_amount) as loss'))->where('member_no', $value->id)->where('wl_amount', '<', 0)->first();
                // return $summary_loss->loss;
                $data_player[]  = array(
                    'player_id' => $value->id,
                    'player_telephone' => $value->telephone,
                    'player_name' => $value->fname . ' ' . $value->lname,
                    'player_balance' => $value->hasOneWallet->main_wallet,
                    'player_register_date' => $value->create_at,
                    'player_aff' => $value->group_af_l1,
                    'player_partner' => $value->partner,
                    'player_summary_win' => ($summary_win->win) ? $summary_win->win : '0',
                    'player_summary_loss' => ($summary_loss->loss) ? $summary_loss->loss : '0',
                );
            }
            $data = array(
                'statusCode' => 200,
                'statusMessage' => 'success',
                'data' => $data_player
            );
        }

        return response()->json($data);
    }

    function history_bet()
    {
        $player_id = isset($_GET['player_id']) ? $_GET['player_id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        if ($start == 0 and $end == 0) {
            return array(
                'statusCode' => 200,
                'statusMessage' => 'success',
                'data' => []
            );;
            exit();
        }

        $data = array(
            'statusCode' => 204,
            'statusMessage' => 'no data.',
            'data' => null
        );
        $history = WinLoss::select(DB::raw('id as _id ,member_no as player_id, bet_amount as amount, settle_amount , wl_amount as win_loss, issue_date as date, platform_code'))->whereDate('issue_date','!=',date("Y-m-d"));
        
        if ($player_id) {
            $history = $history->where('member_no', $player_id);
        }
        $history = $history->orderby('issue_date', 'desc');
        if ($start) {
            $history = $history->offset($start);
        }
        if ($end) {
            $history = $history->limit($end);
        }
        $history = $history->get();
        if ($history) {
            $data = array(
                'statusCode' => 200,
                'statusMessage' => 'success',
                'data' => $history
            );
        }

        return response()->json($data);
    }

    function partner_list()
    {
        $data = array();

        if (!isset($_GET['partner_id'])) {
            $data = [
                'statusCode' => 204,
                'statusMessage' => "no data.",
                'data' => null
            ];
        }else{


        $pn = Members::select('id', 'username', 'telephone', 'fname', 'lname', 'create_at', 'group_af_l1', 'group_af_l2', 'partner')->where('partner', $_GET['partner_id'])
            ->with(['hasOneWallet' => function ($query) {
                $query->select('member_no', 'main_wallet');
            }])->orderby('id', 'asc')->get();

            if ($pn) {
                foreach ($pn as $key => $value) {
                    $summary_win = WinLoss::select(DB::raw('sum(wl_amount) as win'))->where('member_no', $value->id)->where('wl_amount', '>=', 0)->first();
                    $summary_loss = WinLoss::select(DB::raw('sum(wl_amount) as loss'))->where('member_no', $value->id)->where('wl_amount', '<', 0)->first();
                    // return $summary_loss->loss;
                    $data_player[]  = array(
                        'player_id' => $value->id,
                        'player_telephone' => $value->telephone,
                        'player_name' => $value->fname . ' ' . $value->lname,
                        'player_balance' => $value->hasOneWallet->main_wallet,
                        'player_register_date' => $value->create_at,
                        'player_aff' => $value->group_af_l1,
                        'player_partner' => $value->partner,
                        'player_summary_win' => ($summary_win->win) ? $summary_win->win : '0',
                        'player_summary_loss' => ($summary_loss->loss) ? $summary_loss->loss : '0',
                    );
                }
            }

        if (count($pn) > 0) {
            $data = [
                'statusCode' => 200,
                'statusMessage' => "success",
                'data' => $data_player
            ];
        } else {
            $data = [
                'statusCode' => 204,
                'statusMessage' => "no data.",
                'data' => null
            ];
        }
    }

        return response()->json($data);
    }
    function updatePartner(Request $req)
    {
        $player_id_update = isset($req->update_player) ? $req->update_player : null;
        $player_id = isset($req->change_to_partner) ? $req->change_to_partner : null;
        // $player_id_update = [1000001,1000002,1000003,1000004];
        // $player_id = 1000005;

        $data = array(
            'statusCode' => 204,
            'statusMessage' => 'no data.',
            'data' => null
        );
        $data_player = null;
        $member = Members::select('id', 'username', 'telephone', 'fname', 'lname', 'create_at', 'group_af_l1', 'group_af_l2', 'partner');
        
        $member = $member->with(['hasOneWallet' => function ($query) {
            $query->select('member_no', 'main_wallet');
        }])->orderby('id', 'asc');
        if ($player_id_update && $player_id) {
            $member = $member->whereIn('id', $player_id_update)->update(['partner'=>$player_id]);
        }
        if ($member) {
            $member = Members::select('id', 'username', 'telephone', 'fname', 'lname', 'create_at', 'group_af_l1', 'group_af_l2', 'partner');
            if ($player_id_update && $player_id) {
                $member = $member->whereIn('id', $player_id_update)->get();
            }
            foreach ($member as $key => $value) {
                $summary_win = WinLoss::select(DB::raw('sum(wl_amount) as win'))->where('member_no', $value->id)->where('wl_amount', '>=', 0)->first();
                $summary_loss = WinLoss::select(DB::raw('sum(wl_amount) as loss'))->where('member_no', $value->id)->where('wl_amount', '<', 0)->first();
                // return $summary_loss->loss;
                $data_player[]  = array(
                    'player_id' => $value->id,
                    'player_telephone' => $value->telephone,
                    'player_name' => $value->fname . ' ' . $value->lname,
                    'player_balance' => $value->hasOneWallet->main_wallet,
                    'player_register_date' => $value->create_at,
                    'player_aff' => $value->group_af_l1,
                    'player_partner' => $value->partner,
                    'player_summary_win' => ($summary_win->win) ? $summary_win->win : '0',
                    'player_summary_loss' => ($summary_loss->loss) ? $summary_loss->loss : '0',
                );
            }
            $data = array(
                'statusCode' => 200,
                'statusMessage' => 'success',
                'data' => $data_player
            );
        }

        return response()->json($data);
    }
    function playerLog(){
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        $data = array(
            'statusCode' => 204,
            'statusMessage' => 'no data.',
            'data' => null
        );
        $data_player = null;
        $member = Members::select('id', 'username', 'telephone', 'fname', 'lname', 'create_at', 'group_af_l1', 'group_af_l2', 'partner');
        if ($id) {
            $member = $member->where('id', $id);
        }
        $member = $member->with(['hasOneWallet' => function ($query) {
            $query->select('member_no', 'main_wallet');
        }])->orderby('id', 'asc');

        if ($start) {
            $member = $member->offset($start);
        }
        if ($end) {
            $member = $member->limit($end);
        }
        $member = $member->get();
        if ($member) {
            foreach ($member as $key => $value) {
                $summary_win = WinLoss::select(DB::raw('sum(wl_amount) as win'))->where('member_no', $value->id)->where('wl_amount', '>=', 0)->first();
                $summary_loss = WinLoss::select(DB::raw('sum(wl_amount) as loss'))->where('member_no', $value->id)->where('wl_amount', '<', 0)->first();
                // return $summary_loss->loss;
                $data_player[]  = array(
                    'id' => $value->id,
                    'username' => $value->username,
                    'name' => $value->fname . ' ' . $value->lname,
                    'telephone' => $value->telephone,
                    'main_wallet' => $value->hasOneWallet->main_wallet,
                    'register_date' => $value->create_at,
                    'player_aff' => $value->group_af_l1,
                    'player_partner' => $value->partner,
                    'player_summary_win' => ($summary_win->win) ? $summary_win->win : '0',
                    'player_summary_loss' => ($summary_loss->loss) ? $summary_loss->loss : '0',
                );
            }
            $data = array(
                'statusCode' => 200,
                'statusMessage' => 'success',
                'data' => $data_player
            );
        }

        return response()->json($data);

    }
    function updateLogPartner(Request $req)
    {
        $id = isset($req->id) ? $req->id : null;
        $partner = isset($req->partner) ? $req->partner : null;

        $data = array(
            'statusCode' => 204,
            'statusMessage' => 'no data.',
            'data' => null
        );
        $data_player = null;
        $member = Members::select('id', 'username', 'telephone', 'fname', 'lname', 'create_at', 'group_af_l1', 'group_af_l2', 'partner');
        
        $member = $member->with(['hasOneWallet' => function ($query) {
            $query->select('member_no', 'main_wallet');
        }])->orderby('id', 'asc');
        
        if ($id) {
            $member = $member->where('id', $id)->update(['partner'=>$partner]);
        }
        
        
        if ($member) {
            $member = Members::select('id', 'username', 'telephone', 'fname', 'lname', 'create_at', 'group_af_l1', 'group_af_l2', 'partner');
        
            $member = $member->with(['hasOneWallet' => function ($query) {
                $query->select('member_no', 'main_wallet');
            }])->where('id', $id)->first();

            $summary_win = WinLoss::select(DB::raw('sum(wl_amount) as win'))->where('member_no', $member->id)->where('wl_amount', '>=', 0)->first();
            $summary_loss = WinLoss::select(DB::raw('sum(wl_amount) as loss'))->where('member_no', $member->id)->where('wl_amount', '<', 0)->first();
            // return $summary_loss->loss;
            
            $data_player[]  = array(
                'id' => $member->id,
                'username' => $member->username,
                'name' => $member->fname . ' ' . $member->lname,
                'telephone' => $member->telephone,
                'main_wallet' => $member->hasOneWallet->main_wallet,
                'register_date' => $member->create_at,
                'player_aff' => $member->group_af_l1,
                'player_partner' => $member->partner,
                'player_summary_win' => ($summary_win->win) ? $summary_win->win : '0',
                'player_summary_loss' => ($summary_loss->loss) ? $summary_loss->loss : '0',
            );
    
            $data = array(
                'statusCode' => 200,
                'statusMessage' => 'success',
                'data' => $data_player
            );
        }

        return response()->json($data);
    }
    function provider(){
        $data = array(
            'statusCode' => 204,
            'statusMessage' => 'no data.',
            'data' => null
        );
        $provider = Provider::select('provider_id','provider_code', 'provider_name','status','update_date')->orderBy('provider_id','asc')->get();
        if($provider){
            foreach ($provider as $key => $value) {
                $data_provider[] =array(
                    'provider_id' => $value->provider_id,
                    'provider_code' => $value->provider_code,
                    'provider_name' => $value->provider_name,
                    'status' => $value->status,
                    'update_date' => $value->update_date,
                );
            }

        }
        $data = array(
            'statusCode' => 200,
            'statusMessage' => 'success',
            'data' => $data_provider
        );
        return response()->json($data);
    }
    function games(){
        $data = array(
            'statusCode' => 204,
            'statusMessage' => 'no data.',
            'data' => null
        );
        $games = GameList::orderBy('id','asc')->get();
        if($games){
            foreach ($games as $key => $value) {
                $cate_name = 'ไม่พบข้อมูล';
                $gameCode = $value->gameCode;
				if ($value->slug != '') {
					$cate_name = $value->slug;
				} else if ($value->name != '') {
					$cate_name = $value->name;
				}  
				if($value->gameCode>0){
					$gameCode=$value->name."({$value->gameCode})";
				}

                $data_games[] =array(
                    'id' => $value->id,
                    'game_code' => $gameCode,
                    'game_name' => $cate_name,
                    'update_at' => $value->updatedAt,
                    'provider_id' => $value->platformId
                );
            }

        }
        $data = array(
            'statusCode' => 200,
            'statusMessage' => 'success',
            'data' => $data_games
        );
        return response()->json($data);
    }
}
