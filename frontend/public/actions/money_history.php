<?php 

require '../bootstart.php';   

require_once ROOT.'/core/security.php';

$member_no = $_SESSION['member_no'];  

$pageNumber=1;//(@$_POST['page']>1?$_POST['page']:1);

$itemsPerPage = 20;

$totalItems=0;

$member_wallet=[];

$offset = ($itemsPerPage * $pageNumber) - $itemsPerPage;  

$money_type=@$_GET['money_type'];

$member_wallet=GetLogsDW($money_type,$member_no,$itemsPerPage=20,$offset);   

$sql = "SELECT bank_name,bank_accountnumber FROM members WHERE id=?"; // AND status='1'";

$data_wallet = $conn->query($sql, [$member_no])->row_array();



 foreach ($member_wallet as $k => $v) {

  if($money_type=='deposit'){

    $member_wallet[$k]['bank_name_to']=$v['remark']; 

    $member_wallet[$k]['bank_number_to']='';

    $member_wallet[$k]['amount']=number_format($v['amount'],2,".",",");

  }else if($money_type=='withdraw'){
    $bank_name_to=BankCode2Name($data_wallet['bank_name']); ;$bank_number_to=$data_wallet['bank_accountnumber'];
    if($v['remark2']==1&&in_array(($v['status']*1),[3,4])){
      $bank_name_to=$v['remark'].'*';$bank_number_to='';
     }else if($v['remark2']==2&&$v['status']==1){
      $bank_name_to=$v['remark'].'*';$bank_number_to='';
     }
    $member_wallet[$k]['bank_name_to']=$bank_name_to; 

    $member_wallet[$k]['bank_number_to']=$bank_number_to;

    $member_wallet[$k]['amount']=number_format($v['amount_actual'],2,".",",");

  }

   

  //  $member_wallet[$k]['withdraw'] = number_format($v['withdraw'], 2, ".", ",");

  //  $member_wallet[$k]['cashback_wallet']=number_format($v['cashback_wallet'],2,".",",");

  //  $member_wallet[$k]['create_date']=thai_date_fullmonth(strtotime($v['trx_date'].' '.$v['trx_time']));

   $member_wallet[$k]['create_date']=$v['trx_date'].' '.$v['trx_time'];

     

  //  $member_wallet[$k]['updated_date']=thai_date_fullmonth(strtotime($v['updated_date']));

  //  $member_wallet[$k]['updated_date_time']=thai_time(strtotime($v['updated_date']));

  switch ($v['status']) {

    case 0:$member_wallet[$k]['status']='ล้มเหลว'; break;

    case 1:$member_wallet[$k]['status']='สำเร็จ'; break;

    case 2:$member_wallet[$k]['status']='รอดำเนินการ'; break;

    case 3:$member_wallet[$k]['status']='ถูกปฏิเสธ';break; 

    case 4:$member_wallet[$k]['status']='คืนเงิน';break; 

    default:$member_wallet[$k]['status']='ติดต่อ admin';break;

  }

 }

echo json_encode(['datalist'=>$member_wallet,'itemtotal'=>0,'money_type'=>@$_GET['money_type'],'data_wallet'=>$data_wallet]);

exit(); 

?>