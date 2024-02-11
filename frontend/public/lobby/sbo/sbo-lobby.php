<?php
header('Access-Control-Allow-Origin: *');
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
require_once ROOT . '/core/db2/db.php';

// $site = include(__DIR__ . '/config/site.php');
// require_once '/core/db2/dbmodel.php';
// require_once '../../function.php';
// require_once '../../vendor/mobiledetect/mobiledetectlib/Mobile_Detect.php';

// require_once '../../vendor/autoload.php';
require_once './api/sbo-function.php';
// require_once '../lobby-function.php';

$db = new DB();

$res = $db->query("SELECT * FROM lobby_control WHERE provider_id=123 AND provider_name='SBO' AND status=1");
if ($res->numRows() <= 0) {
  echo "Error : 1007<BR>Message : Provider under maintenance<BR>";
  exit();
}

$res = $res->fetchArray();
$productType = $res['product_type'];
require ROOT . '/core/promotions/lobby-function.php';

$sql = "SELECT * FROM m_site WHERE 1";
$siteInfo = $db->query($sql)->fetchArray();

$memberNo = $_SESSION['member_no'];
$member_promo = $_SESSION['member_promo'];

$member = getMembers($memberNo);
$sboConfig = getProviderConfig('sbo', 66, 1);
$res = getSBOAuth(1, $sboConfig, $member, $siteInfo);

if (!empty($res)) {
  if ($res['error']['id'] == 0 || $res['error']['id'] == 4103) {
    $res = getSBOAuth(2, $sboConfig, $member, $siteInfo);
    if (!empty($res)) {
      if ($res['error']['id'] == 0) {
        echo "<script>window.open('" . $res['url'] . "','_self')</script>";
      } else {
        echo "<script>alert('Status : " . $res['error']['id'] . "(" . $res['error']['msg'] . "')</script>";
      }
    }
  } else {
    echo "<script>alert('Status : " . $res['error']['id'] . "(" . $res['error']['msg'] . "')</script>";
  }
}
