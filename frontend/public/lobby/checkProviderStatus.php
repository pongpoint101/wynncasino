<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
$provider = $_REQUEST['provider'] ?? $_POST['provider'] ?? $_GET['provider'] ?? NULL;
$status = 0;
$providerName = '';
$res = $conn->query("SELECT * FROM lobby_control WHERE provider_code=?", [$provider]);
if ($res->num_rows() > 0) {
  $res = $res->row_array();
  $status = $res['status'];
  $providerName = $res['provider_name'];
  $lobbyType = $res['lobby_type'];
  $lobbyURL = $res['lobby_url'];
  $productType = $res['product_type'];

  $res = $conn->query("SELECT * FROM members WHERE id=? AND member_promo>0", [$_SESSION['member_no']]);
  if (($res->num_rows() > 0) && ($productType == 4)) {
    echo json_encode(['status' => -1, 'providerName' => $providerName]);
  } else {
    echo json_encode(['status' => $status, 'providerName' => $providerName, 'lobbyURL' => $lobbyURL, 'lobbyType' => $lobbyType]);
  }
} else {
  echo json_encode(['status' => $status, 'providerName' => $providerName]);
}
