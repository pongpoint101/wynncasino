<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
$provider = $_REQUEST['provider'] ?? $_POST['provider'] ?? $_GET['provider'] ?? NULL;

$member_no = $_SESSION['member_no'];
$skip_casino = 0;
$selectedType = 0;   // 0=All, 1=Casino/Table game only, 2=Slot/Fishing only
$productType = 2;  // 1=Casino, 2=Slot/Fishing
$provider_id = 0;
$providerName = '';
$provider_code = '';
$lobbyURL = '';
$lobbyType = '_blank';
$game_status_ma = false;
$is_lobby = 1;

$res = $conn->query("SELECT * FROM lobby_control WHERE provider_code=?", [$provider]);
if ($res->num_rows() > 0) {
  $res = $res->row_array();
  $provider_id = $res['provider_id'];
  $lobbyType = $res['lobby_type'];
  $lobbyURL = $res['lobby_url'];
  $productType = $res['product_type'];
  $providerName = $res['provider_name'];
  $provider_code = $res['provider_code'];
  if ($res['status'] != 1) {
    $game_status_ma = true;
  }
} else {
  $game_status_ma = true;
}

if ((GetMA($provider) == 'ma') || $game_status_ma) {
?>
  <script>
    Swal.fire('ปิดปรับปรุง', 'ค่าย <?= @$res['provider_name'] ?> ปิดปรับปรุง ขออภัยในความไม่สะดวกค่ะ', 'info');
  </script>
<?php
  exit();
}
// $resx = $conn->query("SELECT * FROM members WHERE id=? AND member_promo>0", [$_SESSION['member_no']]);
// if (($resx->num_rows() > 0) && ($productType == 4 || $productType == 7)) {
?>

<?php
  // exit();
// }

require ROOT . '/core/promotions/lobby-function.php';

$member_wallet = getMemberWallet($member_no);
$money_log = $member_wallet['main_wallet'];
$data = array(
  'member_no' => $member_no,
  'provider_id' => $provider_id,
  'product_type' => $productType,
  'money_log' => $money_log
);
$conn->insert('log_game_access', $data);
SetCachedata('MB:gamecurrent:' . $member_no, $_GET['provider'], 48 * 3600);
?>
<script>
  try {
    if (checkBrowser()) {
      console.log('Safari browser detected!!!!');
      var aTag = document.getElementById('<?= $provider ?>');
      aTag.click();
    } else {
      console.log('Other browser detected!!!!');
      window.open("<?= $lobbyURL ?>", "<?= ($lobbyType == '_self' ? $lobbyType : "_blank") ?>");
    }

  } catch (error) {
    // Swal.fire({
    //   position: 'center',
    //   customClass: 'popupheight',
    //   html: '<img  src="<?= GetFullDomain() ?>/assets/images/popup/pop_block.jpg">',
    //   timer: 2000000
    // });
    //alert("❝กรุณากดที่❞ แสดงทุกครั้ง หรือปิดระบบป้องกันการเปิด window ก่อน!");   
  }
</script>