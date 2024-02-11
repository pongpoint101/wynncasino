<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

$db = new DB();
$site = GetWebSites();

$logged_in = FALSE;

if (!isset($_SESSION['member_no'])) {
  $logged_in = TRUE;
} elseif (empty($_SESSION['member_no'])) {
  $logged_in = TRUE;
} elseif (!isset($_SESSION['member_login'])) {
  $logged_in = TRUE;
} elseif (empty($_SESSION['member_login'])) {
  $logged_in = TRUE;
}

if ($logged_in) {
  session_destroy();
  header('refresh:0;url=' . $site['host']);
  exit();
}

$memberNo = $_SESSION['member_no'];
$memberLogin = $_SESSION['member_login'];
$memberPromo = $_SESSION['member_promo'];

$platformID = $_POST['platform_id'];
// $platformID = 21;
$gameID = $_POST['game_id'];
$gameCode = $_POST['game_code'];
$productType = $_POST['product_type'];
$memberUsername = $_POST['username'];
$product = $_POST['product'];

$res = $db->query("SELECT * FROM members WHERE id=?", $memberNo)->fetchArray();
if ($res['status'] != 1) {
  echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง(A)']);
  exit();
}

$memberInfo = $res;

// $passwordText = $res['passwordText'];

// // $productType = 0;
if (!isset($_POST['status'])) {

  if ($productType != '') {
    $res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND status=1 AND product_type=? ORDER BY product_type", $platformID, $productType);
  } else {
    $res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND status=1 ORDER BY product_type LIMIT 1", $platformID);
  }

  if ($res->numRows() <= 0) {
    echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง(B)']);
    exit();
  } else {
    $res = $res->fetchArray();
    if (($res['product_type'] == 4) && $memberPromo > 0) {
      echo json_encode(['error' => 1, 'gameURL' => 'โปรฯ ของท่านไม่สามารถเข้าเล่นได้']);
      exit();
    }
    $lobbyURL = $res['lobby_url'];
  }

  $res = $db->query("SELECT * FROM game_hit_count WHERE provider=? AND game_code=?", $platformID, $gameID);
  if ($res->numRows() > 0) {
    $db->query("UPDATE game_hit_count SET hit_count=hit_count+1 WHERE provider=? AND game_code=?", $platformID, $gameID);
  } else {
    $db->query("INSERT INTO game_hit_count (provider,game_code,hit_count) VALUES (?,?,?)", $platformID, $gameID, 1);
  }

  $res = $db->query("SELECT * FROM autosystem_game_list WHERE platformId=? AND gameCode=?", 21, $gameID);
  if ($res->numRows() > 0) {
    $res = $res->fetchArray();
    $res = $db->query("SELECT * FROM autosystem_gamelist WHERE platformId=? AND game_id=? AND isActive=0", 21, $res['id']);
    if ($res->numRows() > 0) {
      echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (A)']);
      exit();
    }
  } else {
    echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (B)']);
    exit();
  }
} else {
  $platformID = 100;
  if ($productType != '') {
    $res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND status=1 AND product_type=? ORDER BY product_type", $platformID, $productType);
  } else {
    $res = $db->query("SELECT * FROM lobby_control WHERE provider_id=? AND status=1 ORDER BY product_type LIMIT 1", $platformID);
  }

  if ($res->numRows() <= 0) {
    echo json_encode(['error' => 1, 'gameURL' => 'อยู่ในระหว่างปิดปรับปรุง(B)']);
    exit();
  } else {
    $res = $res->fetchArray();
    if (($res['product_type'] == 4) && $memberPromo > 0) {
      echo json_encode(['error' => 1, 'gameURL' => 'โปรฯ ของท่านไม่สามารถเข้าเล่นได้']);
      exit();
    }
    $lobbyURL = $res['lobby_url'];
  }
  $sql = "SELECT * FROM pg_list_games_mix WHERE game_code=? AND status=0";
  $res = $db->query($sql, $gameCode);
  if ($res->numRows()) {
    echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (C)']);
    exit();
  }
}

$keydata = 'MB:PGGameList';
$Cachedata = GetCachedata($key);
if ($Cachedata) {
  $data = json_decode($Cachedata, true);
  foreach ($data as $key => $value) {
    if ($value['id'] == $gameID && $value['status'] == 0) {
      echo json_encode(['error' => 1, 'gameURL' => 'เกมส์ปิดปรับปรุง (C)']);
      exit();
    }
  }
}

// Prepare GuzzleHttp
$sql = "SELECT * FROM m_providers WHERE provider_code=?";
$providerConfig = $db->query($sql, 'pgsl')->fetchArray();
$providerConfig = json_decode($providerConfig['config_string'], true);
$apiKey = $providerConfig['secret_key'];
$client = new GuzzleHttp\Client([
  'exceptions'       => false,
  'verify'           => false,
  'headers'          => [
    'Content-Type'   => 'application/json',
    'x-api-key' => $apiKey
  ]
]);

// PG2 default setting - Prepare
$arrPGPreset = [
  "gameCode" => $gameCode,
  "username" => strtolower($memberUsername),
  "isPlayerSetting" => true,
  "setting" => [
    [
      "name" => "normal-spin",
      "output" => "normal-spin",
      "percent" => 400
    ],
    [
      "name" => "less-bet",
      "output" => "less-bet",
      "percent" => 400,
      "option" => [
        "from" => 0,
        "to" => 1
      ]
    ],
    [
      "name" => "more-bet",
      "output" => "more-bet",
      "percent" => 175,
      "option" => [
        "from" => 2,
        "to" => 6
      ]
    ],
    [
      "name" => "freespin-less-bet",
      "output" => "freespin-less-bet",
      "percent" => 4,
      "option" => [
        "from" => 0,
        "to" => 1
      ]
    ],
    [
      "name" => "freespin-more-bet",
      "output" => "freespin-more-bet",
      "percent" => 1,
      "option" => [
        "from" => 0,
        "to" => 1
      ]
    ]
  ],
  "buyFeatureSetting" => [
    [
      "name" => "buy-feature-less-bet",
      "output" => "freespin-less-bet",
      "percent" => 70
    ],
    [
      "name" => "buy-feature-more-bet",
      "output" => "freespin-more-bet",
      "percent" => 30
    ]
  ]
];

$sqlDefault = "SELECT * FROM pg_preset WHERE id=1";
// $res = $db->query($sqlDefault);

// PG setting - user
$res = $db->query("SELECT * FROM pg_setting_user WHERE member_no=?", $memberNo);
if ($res->numRows() > 0) {
  $res = $res->fetchArray();
  $res = $db->query("SELECT * FROM pg_preset WHERE id=?", $res['template_id']);
} else {
  // Setting PG for each promotion
  $proID = $memberInfo['member_promo'];
  if ($proID > 0) {
    // Prepare PG win/loss relating to promotion
    $sql = "SELECT * FROM pg_winloss_pro WHERE pro_id=? AND member_no=? AND game_code=?";
    $res = $db->query($sql, $proID, $memberNo, $gameCode);
    if ($res->numRows() <= 0) {
      $sql = "INSERT INTO pg_winloss_pro (pro_id,member_no,game_code,issue_month,issue_date) ";
      $sql .= "VALUES (?,?,?,?,?)";
      $db->query($sql, $proID, $memberNo, $gameCode, date('m'), date('Y-m-d'));
    }

    $sql = "SELECT * FROM pg_setting_pro WHERE pro_id=? AND isActive=?";
    $res = $db->query($sql, $proID, 1);
    if ($res->numRows() > 0) {
      $res = $res->fetchArray();
      $presetId = $res['template_id'];
      $res = $db->query("SELECT * FROM pg_preset WHERE id=? AND isActive=?", $presetId, 1);
      if ($res->numRows() <= 0) {
        $res = $db->query($sqlDefault);
      }
    } else {
      // 2 ครั้งแรก กำไรกรุบกริบ
      $res = $db->query("SELECT * FROM pg_preset WHERE id=? AND isActive=?", 4, 1);
      if ($res->numRows() > 0) {
        $sql = "SELECT COUNT(*) as deposit_count FROM log_deposit WHERE member_no=? AND channel IN (1,2,3,5)";
        $res = $db->query($sql, $memberNo);
        if ($res->numRows() > 0) {
          $res = $res->fetchArray();
          if ($res['deposit_count'] < 3) {
            $res = $db->query("SELECT * FROM pg_preset WHERE id=? AND isActive=?", 4, 1);
            if ($res->numRows() <= 0) {
              $res = $db->query($sqlDefault);
            }
            $sql = "SELECT * FROM pg_winloss_2deposit WHERE member_no=? AND issue_date=?";
            $res2 = $db->query($sql, $memberNo, date('Y-m-d'));
            if ($res2->numRows() <= 0) {
              $sql = "INSERT INTO pg_winloss_2deposit (pro_id,member_no,game_code,issue_month,issue_date) ";
              $sql .= "VALUES (?,?,?,?,?)";
              $db->query($sql, $proID, $memberNo, $gameCode, date('m'), date('Y-m-d'));
            }
          } else {
            $res = $db->query($sqlDefault);
          }
        } else {
          $res = $db->query($sqlDefault);
        }
      } else {
        $res = $db->query($sqlDefault);
      }
    }
  } else {
    // 2 ครั้งแรก กำไรกรุบกริบ
    $res = $db->query("SELECT * FROM pg_preset WHERE id=? AND isActive=?", 4, 1);
    if ($res->numRows() > 0) {
      $sql = "SELECT COUNT(*) as deposit_count FROM log_deposit WHERE member_no=? AND channel IN (1,2,3,5)";
      $res = $db->query($sql, $memberNo);
      if ($res->numRows() > 0) {
        $res = $res->fetchArray();
        if ($res['deposit_count'] < 3) {
          $res = $db->query("SELECT * FROM pg_preset WHERE id=? AND isActive=?", 4, 1);
          if ($res->numRows() <= 0) {
            $res = $db->query($sqlDefault);
          }
          $sql = "SELECT * FROM pg_winloss_2deposit WHERE member_no=? AND issue_date=?";
          $res2 = $db->query($sql, $memberNo, date('Y-m-d'));
          if ($res2->numRows() <= 0) {
            $sql = "INSERT INTO pg_winloss_2deposit (pro_id,member_no,game_code,issue_month,issue_date) ";
            $sql .= "VALUES (?,?,?,?,?)";
            $db->query($sql, $proID, $memberNo, $gameCode, date('m'), date('Y-m-d'));
          }
      } else {
          $res = $db->query($sqlDefault);
        }
      } else {
        $res = $db->query($sqlDefault);
      }
    } else {
      $res = $db->query($sqlDefault);
    }
  }
}

if ($res->numRows() > 0) {
  $res = $res->fetchArray();
  // file_put_contents('pg-authen.txt', json_encode($res) . PHP_EOL . PHP_EOL, FILE_APPEND);
  $arrPGPreset['setting'][0]['percent'] = $res['normal_spin'];

  $arrPGPreset['setting'][1]['percent'] = $res['less_bet'];
  $arrPGPreset['setting'][1]['option']['from'] = $res['less_bet_from'];
  $arrPGPreset['setting'][1]['option']['to'] = $res['less_bet_to'];

  $arrPGPreset['setting'][2]['percent'] = $res['more_bet'];
  $arrPGPreset['setting'][2]['option']['from'] = $res['more_bet_from'];
  $arrPGPreset['setting'][2]['option']['to'] = $res['more_bet_to'];

  $arrPGPreset['setting'][3]['percent'] = $res['freespin_less_bet'];
  $arrPGPreset['setting'][3]['option']['from'] = $res['freespin_less_bet_from'];
  $arrPGPreset['setting'][3]['option']['to'] = $res['freespin_less_bet_to'];

  $arrPGPreset['setting'][4]['percent'] = $res['freespin_more_bet'];
  $arrPGPreset['setting'][4]['option']['from'] = $res['freespin_more_bet_from'];
  $arrPGPreset['setting'][4]['option']['to'] = $res['freespin_more_bet_to'];

  $arrPGPreset['buyFeatureSetting'][0]['percent'] = $res['buy_feature_less_bet'];
  $arrPGPreset['buyFeatureSetting'][1]['percent'] = $res['buy_feature_more_bet'];
}

// Save PG current preset
$sql = "SELECT * FROM pg_current_preset WHERE member_no=? AND game_code=?";
$res = $db->query($sql, $memberNo, $gameCode);
if ($res->numRows() > 0) {
  $sql = "UPDATE pg_current_preset SET preset=? ";
  $sql .= "WHERE member_no=? AND game_code=?";
  $db->query($sql, json_encode($arrPGPreset), $memberNo, $gameCode);
} else {
  $sql = "INSERT INTO pg_current_preset (member_no,game_code,preset) ";
  $sql .= " VALUES (?,?,?)";
  $db->query($sql, $memberNo, $gameCode, json_encode($arrPGPreset));
}

if ($memberNo == 1000001) {
  // file_put_contents('pg-test.log', json_encode($arrPGPreset) . PHP_EOL, FILE_APPEND);
}

// Player Login
$arr['body'] = json_encode([
  'username' => strtolower($memberUsername),
  'gameCode' => $gameCode,
  'sessionToken' => strtolower($memberUsername),
  'language' => 'th',
]);

$targetURL = $providerConfig['api_endpoint'] . '/seamless/api/v2/login';
$res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
$response =  $res->getBody()->getContents();
$arrRes = json_decode($response, true);
$errCode = $arrRes['status'];
if ($errCode == 'success') {
  $gameURL = $arrRes['data']['url'];
  $errCode = 0;
  $uuID = genUUID();
  $arrLaunch['url'] = $gameURL;
  $arrLaunch['dt'] = date('Y-m-d H:i:s');
  $_SESSION[$uuID] = json_encode($arrLaunch);
  $db->query("INSERT INTO launch_token (member_no,token,game_url) VALUE (?,?,?)", $memberNo, $uuID, $gameURL);
  $gameURL = './launch?token=' . $uuID;

  // Get game setting
  $arr['body'] = json_encode([
    'username' => strtolower($memberUsername),
    'gameCode' => $gameCode
  ]);
  $targetURL = $providerConfig['api_endpoint'] . '/seamless/api/v2/getSettings';
  try {
    $res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
  } catch (Exception $e) {
    // file_put_contents('pg-preset.log', date('Y-m-d H:i:s') . " [{$memberUsername}] - getSetting -  ERROR  : {$arr['body']}" . PHP_EOL, FILE_APPEND);
    // file_put_contents('pg-preset.log', date('Y-m-d H:i:s') . " [{$memberUsername}] - getSetting -  ERROR  : {$e->getMessage()}" . PHP_EOL, FILE_APPEND);
  }

  // Set game setting
  $arr['body'] = json_encode($arrPGPreset);
  $targetURL = $providerConfig['api_endpoint'] . '/seamless/api/v2/setGameSetting';
  try {
    $res = $client->request('POST', $targetURL, $arr);  //<<<<<<<<<<<<<<<
    $response =  $res->getBody()->getContents();
  } catch (Exception $e) {
    // file_put_contents('pg-preset.log', date('Y-m-d H:i:s') . " [{$memberUsername}] - setGameSetting -  ERROR  : {$arr['body']}" . PHP_EOL, FILE_APPEND);
    // file_put_contents('pg-preset.log', date('Y-m-d H:i:s') . " [{$memberUsername}] - setGameSetting -  ERROR  : {$e->getMessage()}" . PHP_EOL, FILE_APPEND);
  }

  if ($memberNo == 1000001) {
    // file_put_contents('pg-test.log', $response . PHP_EOL . PHP_EOL, FILE_APPEND);
  }
} else {
  $gameURL = 'ไม่สามารถให้บริการได้ในขณะนี้ กรุณาลองใหม่อีกครั้ง';
  $errCode = 1;
}

echo json_encode(['error' => $errCode, 'gameURL' => $gameURL]);