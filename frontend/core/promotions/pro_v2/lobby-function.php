<?php 
$res = $conn->query("SELECT member_promo FROM members WHERE id=?",[$member_no])->row_array();
@$_SESSION['member_promo'] = $res['member_promo'];
$member_promo = $res['member_promo']; 

if ($member_promo!=0) { 
  $promotion_data=[ 
  ['promo_id'=> 1,'status'=> 'on', 'allowplay'=>[2]], // Free50
  ['promo_id'=> 2,'status'=> 'on', 'allowplay'=>[2]], // โปรฯ 100% สมัครใหม่ ไม่เกิน 200 ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000
  ['promo_id'=> 16,'status'=> 'on', 'allowplay'=>[2]],// Free 60
  ['promo_id'=> 3,'status'=> 'on', 'allowplay'=>[2]], // Pro 10%
  ['promo_id'=> 4,'status'=> 'on', 'allowplay'=>[2]],// Happy Time (Deposit 400, get more 100)
  ['promo_id'=> 6,'status'=> 'on', 'allowplay'=>[2]], // คอมมิชชั่น/คืนยอดเสีย,COM MONTH
  ['promo_id'=> 7,'status'=> 'on', 'allowplay'=>[2]], // AFF ,AFF MONTH
  ['promo_id'=> 9,'status'=> 'on', 'allowplay'=>[2]], // โปร Welcome Back ฝาก 100 ได้ 50 ทำยอด 1 เท่า
  ['promo_id'=> 21,'status'=> 'on', 'allowplay'=>[2]],// ขาประจำ เราจัดให้ 5,000
  ['promo_id'=> 22,'status'=> 'on', 'allowplay'=>[2]],// ขาประจำ เราจัดให้ 10,000
  ['promo_id'=> 23,'status'=> 'on', 'allowplay'=>[2]],// ขาประจำ เราจัดให้ 20,000
  ['promo_id'=> 24,'status'=> 'on', 'allowplay'=>[2]],// ขาประจำ เราจัดให้ 50,000   
  ['promo_id'=> 30,'status'=> 'on', 'allowplay'=>[2]],// Happy New Year 
  ['promo_id'=> 51,'status'=> 'on', 'allowplay'=>[2]],//รางวัลยอดเล่นสูงสุด 5 อันดับแรก
  ['promo_id'=> 52,'status'=> 'on', 'allowplay'=>[2]],// สมัครใหม่ ฝากครั้งแรก 20 ได้ 100  
  ['promo_id'=> 83,'status'=> 'on', 'allowplay'=>[2]],// ฝากประจำ 
  ['promo_id'=> 87,'status'=> 'on', 'allowplay'=>[2]],// ฝากประจำ 
  ['promo_id'=> 90,'status'=> 'on', 'allowplay'=>[2]],// ฝากประจำ 
  ['promo_id'=> 95,'status'=> 'on', 'allowplay'=>[2]],// ฝากประจำ 
  ['promo_id'=> 102,'status'=> 'on', 'allowplay'=>[2]], // โปรฯ 50% สมัครใหม่ ฝาก 200 รับเพิ่ม 100 เป็น 300 - ทำยอดบวก 1 เท่า - ถอนสูงสด 1,000

  ['promo_id'=> 33, 'status'=> 'on', 'allowplay'=>[1]],
  ['promo_id'=> 120,'status'=> 'on', 'allowplay'=>[1]],//ฝากแรกของวัน ฝาก 300 รับเพิ่มอีก 50
  ['promo_id'=> 121,'status'=> 'on', 'allowplay'=>[1]],//รับโบนัส 2% ทั้งวัน สูงสุด 1,000
 ];  
$lobby_error_msg=''; 
$allowgame=[];
$promo_status_lobby=200;

$indexpromo= array_search($member_promo, array_column($promotion_data, 'promo_id'));
if ($indexpromo===false){$promo_status_lobby=500; 
}else if ($indexpromo!==false) {  
    $datapro=$promotion_data[$indexpromo];  
    if($datapro['status']!='on'){$promo_status_lobby=501; 
    }else if (!in_array($productType, $datapro['allowplay'])) { // ไม่เจอเกมส์ที่เล่นได้
      $promo_status_lobby=502; 
      if(in_array(1, $datapro['allowplay'])){
        array_push($allowgame,'คาสิโน');
       }
       if(in_array(2, $datapro['allowplay'])){
        array_push($allowgame,'สล็อตหรือยิงปลา');
       } 
      if(in_array(3, $datapro['allowplay'])){
        array_push($allowgame,'กีฬา');
      } 
    }  
} 
 
  if (count($allowgame)>0) {
    $lobby_error_msg="โปรฯ ของท่านสามารถเข้าเล่นได้เฉพาะ".implode(',',$allowgame)."เท่านั้นค่ะ";  
  }else if($promo_status_lobby!=200){
    $lobby_error_msg="กรุณาเลือกเล่นเกมส์อื่น! โปรโมชั่นไม่ตรงกับเกมส์ที่จะเล่น ($promo_status_lobby)!";  
  }  

  if(@$is_lobby==1&&count($allowgame)>0||(@$is_lobby==1&&$promo_status_lobby!=200)){
    ?>
      <script>Swal.fire('ผิดพลาด', '<?=$lobby_error_msg?>', 'error');</script>
      <?php 
      exit();
  }else if (@$is_lobby==0&&count($allowgame)>0||(@$is_lobby==0&&$promo_status_lobby!=200)){
    ?>
    <script>alert("<?=$lobby_error_msg?>");</script>
    <?php 
    exit();
  }else if (@$is_lobby==3&&count($allowgame)>0||(@$is_lobby==3&&$promo_status_lobby!=200)){ //api
    exit();
  }  
}