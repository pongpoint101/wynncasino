<?php
require '../bootstart.php';
require_once ROOT . '/core/security.php';
header('Content-Type: application/json');
$WebSites = GetWebSites();
$member_no = $_SESSION['member_no'];
$sqlMember = "SELECT id,username,fname,lname,bank_name,bank_accountnumber,setting_bank_id,member_promo,bank_code FROM members WHERE id=?"; // AND status='1'";
$rowMember = $conn->query($sqlMember, [$member_no])->row_array();
$assign = 1; // ID 2 - สำหรับ อื่นๆ (ยกเว้น KBank) -> SCB
if ($rowMember['bank_code'] == '004') { // ID 3 - สำหรับ KBank -> KBank => 004
    $assign = 3;
}
// $assign = 3;

// if ($rowMember['bank_code'] == '014') { //ID 1 - สำหรับ SCB -> SCB => 014
//     $assign = 1;
// } 
/*
$sql = "SELECT ms.bank_code,ms.bank_account,ms.account_name , mb.bank_short
        FROM m_bank_setting AS ms INNER JOIN
             m_bank AS mb ON(ms.bank_code = mb.bank_code)
        WHERE type=1 AND status=1 AND ms.assign = " . $assign; // AND status='1'";

$arr_bank = $conn->query($sql)->result_array();
$dataArr = array();
if (COUNT($arr_bank) > 0) {
    $numROwsI = 0;
    foreach ($arr_bank as $item) {

        $LogoBank = "";
        $ClassBottonCopy = "butboxkasikorn";
        $ClassBottonCopyMobile = "namekasikorn";
        if ($item['bank_code'] == '004') {
            $LogoBank = "../assets/images/member_page3/Kbank2.png";
            $ClassBottonCopy = "butboxkasikorn";
            $ClassBottonCopyMobile = "namekasikorn";
        } else {
            $LogoBank = "../assets/images/member_page3/scb2.png";
            $ClassBottonCopy = "butboxscb";
            $ClassBottonCopyMobile = "namescb";
        }

        $ClassActiveFirst = "";
        if ($numROwsI == 0) {
            $ClassActiveFirst = "active";
        }
        $jsonArr = array(
            'bank_code' => $item["bank_code"],
            'bank_account' => $item["bank_account"],
            'account_name' => $item["account_name"],
            'bank_short' => $item["bank_short"],
            'LogoBank' => $LogoBank,
            'ClassActiveFirst' => $ClassActiveFirst,
            'ClassBottonCopy' => $ClassBottonCopy,
            'ClassBottonCopyMobile' => $ClassBottonCopyMobile,
            'TextCondition_01' => '**ใช้บัญชีที่สมัคร ฝากมาเท่านั้น**',
            'TextCondition_02' => ''
        );
        array_push($dataArr, $jsonArr);

        $numROwsI++;
    }
}

$sqlTrue = "SELECT * FROM m_truewallet_setting WHERE wallet_status=1 ORDER BY wallet_no"; // ORDER BY wallet_no";

$resTrue = $conn->query($sqlTrue);
if ($resTrue->num_rows() > 0) {
    $resTrue = $resTrue->row_array();
    $ClassActiveFirst = "";
    if (COUNT($arr_bank) == 0) {
        $ClassActiveFirst = "active";
    }

    $jsonArr = array(
        'bank_code' => "000",
        'bank_account' => $resTrue['wallet_phone'],
        'account_name' => $resTrue['wallet_name'],
        'bank_short' => 'TrueWallet',
        'LogoBank' => '../assets/images/member_page3/True-money-wallet.png',
        'ClassActiveFirst' => $ClassActiveFirst,
        'ClassBottonCopy' => 'butboxwallet',
        'ClassBottonCopyMobile' => 'namewallet',
        'TextCondition_01' => '**ใช้ ชื่อและเบอร์ ที่สมัคร ฝากมาเท่านั้น**',
        'TextCondition_02' => '**ฝากผ่านทรูวอเลตไม่สามารถรับโบนัสได้**'
    );
    array_push($dataArr, $jsonArr);
}
*/

$targetURL = $WebSites['vz_account_transfer']. $WebSites['site_id'] . '&type=deposit'; 
$client = new GuzzleHttp\Client([
    'exceptions'       => false,
    'verify'           => false,
    'headers'          => [
        'Content-Type'   => 'application/json'
    ]
]);

$bankName = '';
$bankCode = '';
$accountName = '';
$accountNumber = ''; 
$key='MB:bank_list';
$bank_list_data=[];
$findcache=true;
if (isset($cache)) { // ติดต่อ redis ได้
    $bank_list_data=GetCachedata($key);
    if($bank_list_data){
        $findcache=false;
        $bank_list_data=json_decode($bank_list_data,true);
    }
}
//$findcache=true;
if($findcache){
    $res = $client->request('GET', $targetURL);  //<<<<<<<<<<<<<<<
    $result =  json_decode($res->getBody()->getContents(), true);
    SetCachedata($key,json_encode($result),10);
    $bank_list_data=$result; 
}
$bankName = $bank_list_data['bank_name'];
$bankCode = $bank_list_data['bank_code'];
$bank_account_no = $bank_list_data['bank_account_no'];
$bank_account_name = $bank_list_data['bank_account_name'];
$bank_code = $bank_list_data['bank_code'];
$bank_short = $bank_list_data['bank_name'];
$dataArr = array();

if(strlen($bank_account_no)>8){
    $res_bank=get_bank_logo($bank_code);
    $LogoBank='';$ClassBottonCopy='';
    if(sizeof($res_bank)>0){
        $LogoBank=GetFullDomain().'/assets/images/member_page3/'.$res_bank['logo'];
        $ClassBottonCopy='css';
    }  
    $ClassBottonCopyMobile=$ClassBottonCopy;
    $ClassActiveFirst='active';
    $jsonArr = array(
        'bank_code' => $bankCode,
        'bank_account' => $bank_account_no,
        'account_name' => $bank_account_name,
        'bank_short' => $bank_short,
        'LogoBank' => $LogoBank,
        'ClassActiveFirst' => $ClassActiveFirst,
        'ClassBottonCopy' => $ClassBottonCopy,
        'ClassBottonCopyMobile' => $ClassBottonCopyMobile,
        'TextCondition_01' => '**ใช้บัญชีที่สมัคร ฝากมาเท่านั้น**',
        'TextCondition_02' => '<span style="font-weight: bold;text-decoration: underline;">วิธีฝากเงิน , ฝากเครดิต</span><br>
        <span style="color:#FFF;">&nbsp;เนื่องจากช่วงนี้ธนาคารมีการปิดปรับปรุงระบบรายวัน ( Settlement Period ) ทางบริษัทจึงขอแจ้งปิดการทำธุรกรรมในช่วงเวลาดังต่อไปนี้ของทุกวัน
        ฝาก และ Topup ปิด 23.00-02.00 น</span><br>
        <span style="color: rgb(255 252 24);">* ถ้าระบบธนาคารกลับมาเป็นตามปกติ ทางบริษัทจะรีบแจ้งให้ท่านทราบโดยเร็วที่สุด ขออภัยในความไม่สะดวก</span>'
        // 'TextCondition_02' => 'วิธีฝากเงิน , ฝากเครดิต<br>
        // 1. ถ้าท่านฝากเงินผ่าน QR code ของบริษัท เครดิตของท่านจะเข้าอัตโนมัติภายใน ไม่เกิน 1นาที<br>
        // 2. ถ้าท่านฝากเงินแบบโอนผ่านเลขบัญชีของบริษัท ท่านจะต้องตรวจสอบเลขบัญชีทุกครั้งก่อนโอนเงิน <br>
        // <span style="color: rgb(255 252 24);">เพราะทางบริษัทมีการสลับเปลี่ยนบัญชีทุกวัน</span><br>
        // กราบขออภัยในความไม่สะดวก เราต้องการสร้างระบบนี้มาเพื่อไม่ให้ข้อมูลบัญชีของท่านรั่วไหลไปถึงมิจฉาชีพ'
    );
    array_push($dataArr, $jsonArr);
}  
// $dataArr = array();

echo json_encode(['bk_datalist' => $dataArr]);
exit();
