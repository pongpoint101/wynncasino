<?php  
$errorMSG = ""; 
$errorCode = 200;
$aff_upline_id1 = null;
$aff_upline_username1 = null;
$aff_upline_id2 = null;
$aff_upline_username2 = null;

$verifybank = @json_decode($_SESSION['verifybank']);
$_POST['first_name'] = ($verifybank->firstName) ? $verifybank->firstName : '';
$_POST['last_name'] = ($verifybank->lastName) ? $verifybank->lastName : '';
$_POST['bank_account'] = @$_SESSION['bank_account'];
$_POST['bank_code'] = @$_SESSION['bank_code'];
  
if (isset($_GET['aff']) && strlen($_GET['aff'] > 0)) {
    $data_aff_upline = getMemberNoAffUpline($_GET['aff']);
} else if (isset($_COOKIE['aff']) && strlen($_COOKIE['aff'] > 0)) {
    $data_aff_upline = getMemberNoAffUpline($_COOKIE['aff']);
}

if (isset($data_aff_upline['group_af_l1'])) {
    $group = $data_aff_upline['group_af_l1'];
    $aff_upline_id1 = $group['id'];
    $aff_upline_username1 = $group['username'];
}

if (isset($data_aff_upline['group_af_l2'])) {
    $group = $data_aff_upline['group_af_l2'];
    $aff_upline_id2 = $group['id'];
    $aff_upline_username2 = $group['username'];
} 

$ip = getIP(); 

$phone = str_check(trim($_POST['phone']));
$password = hash("sha512", trim($_POST['password']) . KEY_SALT);
$passwordText = trim($_POST['password']);
$fullname =  ($verifybank->fullName) ? $verifybank->fullName : '';
$firstname = str_check(trim($_POST['first_name']));
$lastname = trim($_POST['last_name']);
if($lastname!='-'){$lastname =str_check(trim($_POST['last_name']));}
$bank_code = str_check(trim($_POST['bank_code']));
$bank_name = BankCode2ShortName($bank_code);
$bank_account = str_check(trim($_POST['bank_account']));
$line_id = str_check(trim($_POST['line_id']));
$source = trim($_POST['source']);
$row_bank = check_bank_num($bank_account, $bank_code);
$row_line = check_line($line_id);
$row_phone = check_phone($phone);
$know_us_from = str_check(trim($_POST['source']));
$choose_bank = str_check(trim($_POST['choose_bank']));
$truewallet_phone=str_check(trim($_POST['truewallet']));
$truewallet_account=str_check(trim($_POST['truewallet_account']));
$row_tw=0;
if($choose_bank==2){$row_tw = check_truewallet($truewallet_phone);}

$scb_scb = substr($bank_account, -4);
$scb_other = substr($bank_account, -6);
$kbank_kbank = substr($bank_account, -7, 6);

try {
    $uuid = uniqid();
    $uuidlastaccess=uniqid(true);
    $conn->trans_start();
    $sql = "INSERT INTO member_unregister (member_login,password,passwordText,full_name,fname,lname,telephone, bank_code,bank_name, bank_accountnumber, line_id,source_ref,af_code,scb_scb,scb_other,kbank_kbank,group_af_l1,group_af_username_l1,group_af_l2,group_af_username_l2,ip,create_at,aff_type,choose_bank,truewallet_phone,truewallet_account,uuid_lastaccess)";
    $sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $param = [uniqid(), $password, $passwordText, $fullname,$firstname, $lastname, $phone, $bank_code, $bank_name, $bank_account, $line_id, $know_us_from, $uuid, $scb_scb, $scb_other, $kbank_kbank, $aff_upline_id1, $aff_upline_username1, $aff_upline_id2, $aff_upline_username2, $ip, date('Y-m-d H:i:s'), DEFAULT_AFF_TYPE_MEMBER,$choose_bank,$truewallet_phone,$truewallet_account,$uuidlastaccess];
    $conn->query($sql, $param);
    $id = $conn->insert_id();
    $username = strtoupper(WEBSITE) . $id;
    $conn->set('username', $username);
    $conn->where('id', $id);
    $conn->update('member_unregister');

    $conn->trans_complete();
    $errorMSG = 'ลงทะเบียนเรียบร้อย!'; 
 } catch (Exception $e) {
    // handle Error 
    $errorCode = $e->getCode();
    $errorMSG = 'INSERT DATA ERROR!';
    echo $e->getMessage();
    $conn->trans_rollback();
}
exit();
