<?php

/**
 * $system_id - your project system ID (number)
 * $version - callback or API version
 * $args - array with API method or callback parameters. API parameters list you can find in API method description
 * $system_key - your system key
 */
function getSignature($system_id, $version, array $args, $system_key)
{
  $md5 = array();
  $md5[] = $system_id;
  $md5[] = $version;
  foreach ($args as $required_arg) {
    $arg = $required_arg;
    if (is_array($arg)) {
      if (count($arg)) {
        $recursive_arg = '';
        array_walk_recursive($arg, function ($item) use (&$recursive_arg) {
          if (!is_array($item)) {
            $recursive_arg .= ($item . ':');
          }
        });
        $md5[] = substr($recursive_arg, 0, strlen($recursive_arg) - 1); // get rid of last colon-sign
      } else {
        $md5[] = '';
      }
    } else {
      $md5[] = $arg;
    }
  };
  $md5[] = $system_key;
  $md5_str = implode('*', $md5);
  $md5 = md5($md5_str);
  $arr['md5'] = $md5;
  $arr['md5_str'] = $md5_str;
  // return $arr;
  return $md5;
}

function evpAPILog($req_data)
{
  global $mysqli;

  $member_no = explode('-', $req_data['token'])[0];
  $member_login = explode('-', $req_data['token'])[1];
  $action = $req_data['name'];
  $currency = 'THB';
  $status = 0;
  $sql = '';

  if ($action == 'init') {
    $sql = "INSERT INTO evp_api_log ";
    $sql .= " (action,member_no,callback_id,system_id,signature,arrival_date,status) VALUES ";
    $sql .= " ('" . $action . "'," . $member_no . ",'" . $req_data['callback_id'] . "','" . $req_data['system_id'] . "',";
    $sql .= "'" . $req_data['signature'] . "','" . date('Y-m-d H:i:s') . "'," . $status . ")";
  }

  if ($action == 'bet') {
    $status = 1;
  }
  if ($action == 'win') {
    $status = 2;
  }
  if ($action == 'refund') {
    $status = 3;
  }

  $value = $req_data['data'];
  $sql = "INSERT INTO evp_trx ";
  $sql .= " (action,member_no,callback_id,system_id,signature,round_id,action_id,amount,currency,details,arrival_date,status";

  if (($action == 'bet') || ($action == 'win')) {
    if (isset($value['final_action'])) {
      $sql .= ",final_action";
    }
    $sql .= ") VALUES ";
    $sql .= " ('" . $action . "'," . $member_no . ",'" . $req_data['callback_id'] . "','" . $req_data['system_id'] . "',";
    $sql .= "'" . $req_data['signature'] . "','" . $value['round_id'] . "','" . $value['action_id'] . "'," . $value['amount'] . ",";
    $sql .= "'" . $currency . "','" . $value['details'] . "','" . date('Y-m-d H:i:s') . "'," . $status;
    if (isset($req_data['data']['final_action'])) {
      $sql .= "," . $value['final_action'];
    }
    $sql .= ")";
  }

  if ($action == 'refund') {
    $sql .= ",refund_callback_id) VALUES ";
    $sql .= " ('" . $action . "'," . $member_no . ",'" . $req_data['callback_id'] . "','" . $req_data['system_id'] . "',";
    $sql .= "'" . $req_data['signature'] . "','" . $value['refund_round_id'] . "','" . $value['refund_action_id'] . "'," . $value['amount'] . ",";
    $sql .= "'" . $currency . "','" . $value['details'] . "','" . date('Y-m-d H:i:s') . "'," . $status . ",";
    $sql .= "'" . $value['refund_callback_id'] . "'";
    $sql .= ")";
  }

  $mysqli->query($sql);
}

function isDuplicatedCallbackID($req_data)
{
  global $mysqli;

  $callbackID = $req_data['callback_id'];
  $member_no = explode('-', $req_data['token'])[0];

  $sql = "SELECT * FROM evp_trx WHERE member_no=" . $member_no . " AND callback_id='" . $callbackID . "'";
  $res = $mysqli->query($sql);
  return ($res->num_rows > 0) ? true : false;
}

function isFoundBetForRefund($req_data)
{
  global $mysqli;

  $value = $req_data['data'];

  $refund_round_id = $value['refund_round_id'];
  $refund_action_id = $value['refund_action_id'];
  $refundCallbackID = $value['refund_callback_id'];
  $refundAmount = $value['amount'];
  $member_no = explode('-', $req_data['token'])[0];

  $sql = "SELECT * FROM evp_trx WHERE action='bet' AND member_no=" . $member_no . " AND callback_id='" . $refundCallbackID . "'";
  // $sql .= " AND round_id='" . $refund_round_id . "'";
  // $sql .= " AND action_id='" . $refund_action_id . "'";
  // $sql .= " AND amount=" . $refundAmount;

  $res = $mysqli->query($sql);
  return ($res->num_rows > 0) ? true : false;
}


function isRefundRepeat($req_data)
{
  global $mysqli;

  $value = $req_data['data'];

  $refundCallbackID = $value['refund_callback_id'];
  $member_no = explode('-', $req_data['token'])[0];

  $sql = "SELECT * FROM evp_trx WHERE action='refund' AND member_no=" . $member_no . " AND refund_callback_id='" . $refundCallbackID . "'";
  $res = $mysqli->query($sql);
  return ($res->num_rows > 0) ? true : false;
}

function hitsStatistic($gameID, $gameName)
{
  global $db;

  $sql = "UPDATE evp_hits_statistic SET hits_count=hits_count+1 WHERE game_id=?";
  $res = $db->query($sql, $gameID);
  if ($res->affectedRows() <= 0) {
    $sql = "INSERT INTO evp_hits_statistic (game_id,game_name,hits_count) VALUES (?,?,?)";
    $db->query($sql, $gameID, $gameName, 1);
  }
}
