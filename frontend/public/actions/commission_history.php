<?php 
require '../bootstart.php';   
require_once ROOT.'/core/security.php';
$member_no = $_SESSION['member_no'];  
$pageNumber=1;//(@$_POST['page']>1?$_POST['page']:1);
$itemsPerPage = 20;
$totalItems=0;
$data_result =[];
$offset = ($itemsPerPage * $pageNumber) - $itemsPerPage;
$com_type=@$_GET['com_type'];
if($com_type=='commission'){
$dataCommiss = $conn->query("SELECT id,member_no,amount,status,remark,date_com,create_date FROM member_a_commission WHERE member_no=? ORDER BY date_com DESC LIMIT ? OFFSET ?",[$member_no,$itemsPerPage,$offset])->result_array();
 foreach ($dataCommiss as $k => $v) {
  $data_result[$k]['id']=$v['id'];
  $data_result[$k]['amount']=number_format($v['amount'],2,".",",");
  $data_result[$k]['date_receive']=$v['date_com'];
  $data_result[$k]['status']=$v['status'];
  $data_result[$k]['remark']=$v['remark'];
  switch ($v['status']) {
    case 0:
      $data_result[$k]['statustxt']='<button type="button" value="โอนเข้ากระเป๋าหลัก" class="btn btn-round btn-warning btn-block">โอนเข้ากระเป๋าหลัก</button>';
      $datacheck=$conn->query("SELECT status FROM member_a_return_loss WHERE member_no=? AND date_return = CURDATE();",[$member_no])->row();
      if (@$datacheck->status!=0){
        $data_result[$k]['statustxt']='โอนเข้ากระเป๋าหลักแล้ว'; 
        $data_result[$k]['status']=1;
       } 
    break;
    case 1:$data_result[$k]['statustxt']='โอนเข้ากระเป๋าหลักแล้ว'; break;
    case 2:$data_result[$k]['statustxt']='หมดอายุ'; break;
    default:$data_result[$k]['statustxt']='-';break;
  } 
 }
} else if($com_type=='return_loss'){
  $dataCommiss = $conn->query("SELECT id,member_no,amount,status,remark,date_return,create_date FROM member_a_return_loss WHERE member_no=? ORDER BY date_return DESC LIMIT ? OFFSET ?",[$member_no,$itemsPerPage,$offset])->result_array();
 foreach ($dataCommiss as $k => $v) {
  $data_result[$k]['id']=$v['id'];
  $data_result[$k]['amount']=number_format($v['amount'],2,".",",");
  $data_result[$k]['date_receive']=$v['date_return'];
  $data_result[$k]['status']=$v['status'];
  $data_result[$k]['remark']=$v['remark'];
  switch ($v['status']) {
    case 0:$data_result[$k]['statustxt']='<button type="button" value="โอนเข้ากระเป๋าหลัก" class="btn btn-round btn-warning btn-block">โอนเข้ากระเป๋าหลัก</button>'; 
      $datacheck=$conn->query("SELECT status FROM member_a_commission WHERE member_no=? AND date_com = CURDATE();",[$member_no])->row();
     if (@$datacheck->status!=0){
        $data_result[$k]['statustxt']='โอนเข้ากระเป๋าหลักแล้ว'; 
        $data_result[$k]['status']=1;
     }
    break;
    case 1:$data_result[$k]['statustxt']='โอนเข้ากระเป๋าหลักแล้ว'; break;
    case 2:$data_result[$k]['statustxt']='หมดอายุ'; break;
    default:$data_result[$k]['statustxt']='-';break;
  } 
 }
}

echo json_encode(['datalist'=>$data_result,'itemtotal'=>0,]);
exit(); 

?>