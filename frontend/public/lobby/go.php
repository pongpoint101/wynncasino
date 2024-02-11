<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
$provider = $_REQUEST['provider'] ?? $_POST['provider'] ?? $_GET['provider'] ?? NULL;
$member_no = $_SESSION['member_no'];
$status = 0;
$providerName = '';
$res = $conn->query("SELECT * FROM lobby_control WHERE provider_code=?", [$provider]);
if ($res->num_rows() > 0) {
  $res = $res->row_array();
  $status = $res['status'];
  $provider_id = $res['provider_id'];
  $productType = $res['product_type'];
  $providerName = $res['provider_name'];
  $lobbyType = $res['lobby_type'];
  $lobbyURL = $res['lobby_url'];

  $member_wallet = getMemberWallet($member_no);
  $money_log = $member_wallet['main_wallet'];
  $data = array(
    'member_no' => $member_no,
    'provider_id' => $provider_id,
    'product_type' => $productType,
    'money_log' => $money_log
  );
  $conn->insert('log_game_access', $data);

  header('Location:' . $lobbyURL);
}
