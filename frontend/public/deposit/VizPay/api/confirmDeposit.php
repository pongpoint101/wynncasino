<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../config/Database.php';
include_once '../models/Qr_Deposite_Log.php';
include_once '../models/Log_Deposite_Vizpay.php';
include_once '../models/Member_Wallet.php';

try{
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //for log_deposit table
    $qr_deposite_log = new Qr_Deposite_Log($db);
    //for member wallet table
    $member_wallet = new Member_Wallet($db);
    //for log_deposit_vizpay
    $log_deposite_vizpay = new Log_Deposite_Vizpay($db);
    
    $data = json_decode(file_get_contents("php://input"));
    syslog(LOG_INFO, json_encode($data));
    closelog();
    $trx_id = $data->txn_order_id;
    $member_no = $data->member_no;
    $amount = $data->amount;

    
    //Check if trx_id exists in log_deposit table
    if(!$qr_deposite_log->check_trx($trx_id)){
        $return = json_encode(["success" => false, "message"=>"Transaction id {$trx_id} is not found or has been confirmed. (log_deposit table) "]);
        echo $return;
        syslog(LOG_WARNING, $return);
        closelog();
        die();
        return;
    }
    //Check if trx_id exists in log_deposit_vizpay table
    if(!$log_deposite_vizpay->check_trx($trx_id)){
        $return = json_encode(["success" => false, "message"=>"Transaction id {$trx_id} is not found or has been confirmed. (log_deposit_vizpay table) "]);
        echo $return;
        syslog(LOG_WARNING, $return);
        closelog();
        die();
        return;
    }
    //Check if member_no exists in member_wallet table
    if(!$member_wallet->check_member_no($member_no)){
        $return = json_encode(["success" => false, "message"=>"Member number {$member_no} is not found. (member_wallet table)"]);
        echo $return;
        syslog(LOG_WARNING, $return);
        closelog();
        die();
        return;
    }
    //Update main_wallt of member_no  in member_wallet table
    if(!$member_wallet->deposite($member_no, $amount)){
        $return = json_encode(["success" => false, "message"=>"Member wallet {$member_no} deposite failed. (member_wallet table)"]);
        echo $return;
        syslog(LOG_WARNING, $return);
        closelog();
        die();
        return;
    }
    //Update deposite status to "SUCCESS in log_deposit_vizpay table "
    if(!$log_deposite_vizpay->confirm($trx_id)){
        $return = json_encode(["success" => false, "message"=>"Transaction id {$trx_id} confirmation failed. (log_deposit_vizpay table)"]);
        echo $return;
        syslog(LOG_WARNING, $return);
        closelog();
        die();
        return;
    }
    //Update deposite status to "SUCCESS in log_deposit table "
    if(!$qr_deposite_log->confirm($trx_id)){
        $return = json_encode(["success" => false, "message"=>"Transaction id {$trx_id} confirmation failed. (log_deposit table)"]);
        echo $return;
        syslog(LOG_WARNING, $return);
        closelog();
        die();
        return;
    }
    
    $return = json_encode(["success" => true, "message"=>"{$member_no} wallet has been deposited with amount {$amount} and trx {$trx_id}."]);
    echo $return;
    syslog(LOG_INFO,$return);
    closelog();

} catch (Exception $e) {
    header('X-PHP-Response-Code: 500', true, 500);
    error_log($e->getMessage(), 0);
    echo json_encode(
        array("Caught exception" => $e->getMessage())
    );
    die();
}    