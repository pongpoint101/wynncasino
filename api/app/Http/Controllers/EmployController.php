<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
class EmployController extends Controller
{
    function list()
    {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $start = isset($_GET['start']) ? $_GET['start'] : null;
        $end = isset($_GET['end']) ? $_GET['end'] : null;

        $data = array();
        $ld = DB::table('employees')->select('id','username','fname','lname','lastaccess','password_change','ip','status');
        if ($id) {
            $ld = $ld->where('id', $id);
        }
        $ld = $ld->where('status', 1);
        if ($start && $end) {
            $ld = $ld->offset($start);
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
    function checkToken(){
        $data = array();
        $auth = isset($_GET['auth']) ? $_GET['auth'] : null;
        $id = base64_decode($auth);
        $em = DB::table('employees')->where('id',$id)->select('id','username','fname','lname','lastaccess','password_change','ip','status')->first();
        
        if($em){
            if($em->status==1){
                $data = [
                    'statusCode' => 200,
                    'statusMessage' => "success",
                    'data' => $em
                ];

            }else{
                $data = [
                    'statusCode' => 204,
                    'statusMessage' => "suspended",
                    'data' => null
                ];
            }
            
        }else{
            $data = [
                'statusCode' => 204,
                'statusMessage' => "no data.",
                'data' => null
            ];
        }
        
        return response()->json($data);
    }
    function postCheckToken(Request $req){
        $data = array();
        $auth = isset($req->auth) ? $req->auth : null;
        $id = base64_decode($auth);
        $em = DB::table('employees')->where('id',$id)->select('id','username','fname','lname','lastaccess','password_change','ip','status')->first();
        
        if($em){
            if($em->status==1){
                $data = [
                    'statusCode' => 200,
                    'statusMessage' => "success",
                    'data' => $em
                ];

            }else{
                $data = [
                    'statusCode' => 204,
                    'statusMessage' => "suspended",
                    'data' => null
                ];
            }
            
        }else{
            $data = [
                'statusCode' => 204,
                'statusMessage' => "no data.",
                'data' => null
            ];
        }
        
        return response()->json($data);
    }
}
