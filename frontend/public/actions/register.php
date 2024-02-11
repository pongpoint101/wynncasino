<?php
require '../bootstart.php';
$Website = GetWebSites(); 
$errorMSG = "";
if ($Website['maintenance']!=1) {
    echo "<script>swal.fire('ขออภัย','ระบบยังไม่เปิดรับสามัครสมาชิกใหม่!','error');</script>";
    require 'unregister_x.php';
    exit();
}
if ($Website['truewallet_is_register']==1&&$_POST['choose_bank']==2) {
    echo "<script>swal.fire('ขออภัย','รับสมัครสมาชิกใหม่ทางทรูมันนี่ วอลเล็ท!','error');</script>";
    exit();
}

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

if (empty($_POST['phone'])) {
    $errorMSG = "กรุณากรอก เบอร์มือถือ";
} elseif (empty($_POST['password'])) {
    $errorMSG = "กรุณากรอก รหัสผ่าน";
} elseif (empty($_POST['password_confirmation'])) {
    $errorMSG = "กรุณากรอก ยืนยันรหัสผ่าน";
} elseif (trim($_POST['password']) != trim($_POST['password_confirmation'])) {
    $errorMSG = "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน";
} elseif (empty($_POST['bank_account'])&&@$_POST['choose_bank']==1) {
    $errorMSG = "กรุณากรอก เลขบัญชีธนาคาร";
} elseif (strlen(trim($_POST['bank_account'])) < 10&&@$_POST['choose_bank']==1) {
    $errorMSG = "หมายเลขบัญชีธนาคารไม่ถูกต้อง";
} elseif (empty($_POST['bank_code'])&&@$_POST['choose_bank']==1) {
    $errorMSG = "กรุณาเลือก ธนาคาร";
} elseif (empty($_POST['first_name'])) {
    $errorMSG = "กรุณากรอก ชื่อในสมุดบัญชีธนาคาร";
} elseif (empty($_POST['last_name'])) {
    $errorMSG = "กรุณากรอก นามสกุลในสมุดบัญชีธนาคาร";
} elseif (empty($_POST['line_id'])) {
    $errorMSG = "กรุณากรอก ไลน์ไอดี";
} elseif (empty($_POST['source'])) {
    $errorMSG = "กรุณาเลือก รู้จักเว็บเราจากที่ไหน?";
}else if(empty($_POST['choose_bank'])){
    $errorMSG = "กรุณาเลือก ช่องทางฝาก-ถอน";
}else if(empty($_POST['truewallet'])&&@$_POST['choose_bank']==2){
    $errorMSG = "กรุณากรอก เบอร์ทรูมันนี่ วอลเล็ท";
}


if (!empty($errorMSG)) {
    echo "<script>swal.fire('กรุณาตรวจสอบ','$errorMSG','error');</script>";
    exit();
}

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
$partner =isset($_GET['partner'])? $_GET['partner'] :null;
//var_dump($aff_upline_id1, $aff_upline_id2);exit(); 

// Check duplicated IP

$ip = getIP();
$sql = "SELECT ip FROM members WHERE ip =? group by ip HAVING COUNT(id) > 3";
$sql_result = $conn->query($sql, [$ip]);
if ($sql_result->num_rows() > 0) {
    $errorCode = 400;
    $errorMSG = "หมายเลข IP " . $ip . " ของคุณเคยสมัครไปแล้ว";
}

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
$row_bank =0;
$row_line = check_line($line_id);
$row_phone = check_phone($phone);
$know_us_from = str_check(trim($_POST['source']));
$choose_bank = str_check(trim($_POST['choose_bank']));
if($choose_bank==1){
    $row_bank = check_bank_num($bank_account, $bank_code);  
}
$truewallet_phone=str_check(trim($_POST['truewallet']));
$truewallet_account=str_check(trim($_POST['truewallet_account']));
$row_tw=0;
if($choose_bank==2){$row_tw = check_truewallet($truewallet_phone);}

$scb_scb = substr($bank_account, -4);
$scb_other = substr($bank_account, -6);
$kbank_kbank = substr($bank_account, -7, 6);

$re = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/i';

if (misc_parsestring($phone, '0123456789') == false || strlen($phone) != 10) {
    $errorMSG = "หมายเลขโทรศัพท์ ต้องเป็นตัวเลข 10 หลักเท่านั้น";
    $errorCode = 400;
} elseif (!preg_match('/^[ก-๙-เa-zA-Z.\s]+$/', $firstname)) {
    $errorMSG = "ชื่อจริงต้องเป็นภาษาไทยเท่านั้น ห้ามมีเว้นวรรค";
    $errorCode = 400;
} elseif (!preg_match('~[ก-๙-เa-zA-Z-_./]+~', $lastname)) {
    $errorMSG = "นามสกุลต้องเป็นภาษาไทยเท่านั้น ห้ามมีเว้นวรรค $lastname";
    $errorCode = 400;
} elseif (mb_strlen($passwordText, 'UTF8') < 4) {
    $errorMSG = "รหัสผ่านต้องมีไม่ต่ำกว่า 8 ตัว";
    $errorCode = 400;
} elseif (!preg_match($re, $passwordText)) {
    if (misc_parsestring($passwordText) == false) {
        $errorMSG = "รหัสผ่านต้องมีอักษรภาษาอังกฤษกับตัวเลขผสมเท่านั้น";
        $errorCode = 400;
    } else {
        $errorMSG = "รหัสผ่านรวมไม่ต่ำกว่า 8 ตัวและต้องมีหนึ่งตัวอักษรและหนึ่งตัวเลขอย่างน้อย 1 ตัว";
        $errorCode = 400;
    }
} elseif (misc_parsestring($bank_account, '0123456789') == false&&@$choose_bank==1) {
    $errorMSG = "หมายเลขบัญชี ต้องเป็นตัวเลข 10 หลักเท่านั้น";
    $errorCode = 400;
} elseif ($row_bank > 0&&@$choose_bank==1) {
    $errorMSG = "หมายเลขบัญชี $bank_account นี้ถูกใช้งานแล้ว";
    $errorCode = 400;
} elseif ($row_phone > 0) {
    $errorMSG = "หมายเลขโทรศัพท์: $phone นี้ถูกใช้งานแล้ว";
    $errorCode = 400;
} elseif ($row_line > 0) {
    $errorMSG = "ไลน์ไอดี: $line_id นี้ถูกใช้งานแล้ว";
    $errorCode = 400;
}elseif ($row_tw > 0) {
    $errorMSG = "ทรูมันนี่ วอลเล็ท: $truewallet_phone นี้ถูกใช้งานแล้ว";
    $errorCode = 400;
}

if (!empty($errorMSG)) {
    echo "<script>swal.fire('กรุณาตรวจสอบ','$errorMSG','error');</script>";
    exit();
}

$sql = "SELECT id FROM members WHERE fname =? AND lname=?";
$sql_result = $conn->query($sql, [$firstname, $lastname])->row_array();
if (isset($sql_result['id'])) {
    $errorMSG = "คุณเคยสมัครไปแล้ว";
    $errorCode = 400;
}

if ($errorCode == 200) {  
    try {
        $uuid = uniqid();
        $uuidlastaccess=uniqid(true);
        $conn->trans_start();
        $sql = "INSERT INTO members (member_login,password,passwordText,full_name,fname,lname,telephone, bank_code,bank_name, bank_accountnumber, line_id,source_ref,af_code,scb_scb,scb_other,kbank_kbank,group_af_l1,group_af_username_l1,group_af_l2,group_af_username_l2,ip,create_at,aff_type,choose_bank,truewallet_phone,truewallet_account,uuid_lastaccess,partner)";
        $sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $param = [uniqid(), $password, $passwordText, $fullname,$firstname, $lastname, $phone, $bank_code, $bank_name, $bank_account, $line_id, $know_us_from, $uuid, $scb_scb, $scb_other, $kbank_kbank, $aff_upline_id1, $aff_upline_username1, $aff_upline_id2, $aff_upline_username2, $ip, date('Y-m-d H:i:s'), DEFAULT_AFF_TYPE_MEMBER,$choose_bank,$truewallet_phone,$truewallet_account,$uuidlastaccess,$partner];
        $conn->query($sql, $param);
        $id = $conn->insert_id();
        $username = strtoupper(WEBSITE) . $id;
        $conn->set('username', $username);
        $conn->where('id', $id);
        $conn->update('members');

        $conn->query("INSERT INTO member_wallet (member_no) VALUES (?)", [$id]);
        // $conn->query("INSERT INTO member_turnover (member_no) VALUES (?)",[$id]);
        $conn->query("INSERT INTO aff_member_branch (member_no,group_af_l1,group_af_l2,create_at) VALUES (?,?,?,?)", [$id, $aff_upline_id1, $aff_upline_id2, date('Y-m-d H:i:s')]);
        $conn->trans_complete();
        $errorMSG = 'ลงทะเบียนเรียบร้อย!';
        unset($_COOKIE['aff']);
        setcookie('aff', null, -1, '/');
    } catch (Exception $e) {
        // handle Error 
        $errorCode = 500;
        $errorMSG = 'INSERT DATA ERROR!';
        echo $e->getMessage();
        $conn->trans_rollback();
    }

    $row = [];
    if ($errorCode == 200 && !isset($_SESSION['member_no'])) {
        $row = $conn->query("SELECT id,username,telephone,full_name,fname,lname,bank_code,bank_name,bank_accountnumber,status,ip,member_login,member_promo,create_at,partner FROM members WHERE telephone =?", [$phone])->row_array();
    }
    if ($errorCode == 200 && @sizeof($row) > 0 && !isset($_SESSION['member_no'])) {
        if ($row['partner']) {
            if(URL_PARTNER !=''){
                try {
                    $targetURL = URL_PARTNER;
                    $client = new GuzzleHttp\Client([
                      'exceptions'       => true,
                      'verify'           => false,
                      'timeout' => 10, // Response timeout
                      'connect_timeout' => 10, // Connection timeout
                      'headers'          => [
                        'Content-Type'   => 'application/json'
                      ]
                    ]);
                    $req['player_id'] = $row['id'];
                    $req['player_telephone'] = $row['telephone'];
                    $req['player_name'] = $row['fname'].' '.$row['lname'];
                    $req['player_balance'] = '0';
                    $req['player_register_date'] = $row['create_at'];
                    $req['player_aff'] = '0';
                    $req['player_partner'] = $row['partner'];
                    $req['player_summary_win'] = '0';
                    $req['player_summary_loss'] = '0';
                    $arr['body'] = json_encode($req);
                    $res = $client->request('POST', $targetURL, $arr); 
                    $res = json_decode($res->getBody()->getContents(),true);
                    if (@$res['error']!=0) {
                      throw new Exception($res['code']*1);
                    }  
                
                } catch (Exception $e) {
                    $msg_error=$e->getMessage(); 
                    
                }
            }
        }
        $_SESSION['member_no'] = $row['id'];
        $_SESSION['ip'] = $ip;
        $_SESSION['USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['ip_live'] = $ip;
        $_SESSION['USER_AGENT_live'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['member_login'] = $row['member_login'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['member_name'] = $row['fname'] . " " . $row['lname'];
        $_SESSION['bank_name'] = $row['bank_name'];
        $_SESSION['bank_code'] = $row['bank_code'];
        $_SESSION['member_promo'] = $row['member_promo'];
        $_SESSION['bank_accountnumber'] = $row['bank_accountnumber'];
        $_SESSION['uuid_lastaccess']=$uuidlastaccess;
        try {
            $targetURL = $Website['vz_addcustomer']; 
            $client = new GuzzleHttp\Client([
              'exceptions'       => true,
              'verify'           => false,
              'timeout' => 10, // Response timeout
              'connect_timeout' => 10, // Connection timeout
              'headers'          => [
                'Content-Type'   => 'application/json'
              ]
            ]);
            $member_no=$_SESSION['member_no'];
            $req['site_id'] = (string)$Website['site_id'];
            $req['cust_fname'] = (string)$firstname;
            $req['cust_lname'] = (string)$lastname;
            $req['member_no'] = (string)$member_no;
            $req['cust_account_no'] = (string)$bank_account;
            $req['cust_bank_code'] = (string)$bank_code;
            $req['cust_bank_name'] = (string)$bank_name;
            $arr['body'] = json_encode($req);
            $res = $client->request('POST', $targetURL, $arr); 
            $res = json_decode($res->getBody()->getContents(),true);
            if (@$res['error']!=0) {
              throw new Exception($res['code']*1);
            }  
           } catch (Exception $e) {
             $msg_error=$e->getMessage(); 
             if(gettype($msg_error)=='integer'){
                $data = [
                  'error' =>$res['error'],
                  'code'=>$res['code'],
                  'message' => $res['message'],
                  'result' => $res['result'],
                  'site_id' => $res['site_id'],
                  'cust_account_no' => $res['cust_account_no'] 
                ]; 
               }else{
                $data = [
                  'message' =>$msg_error
                  ,'site_id' => $Website['site_id']
                  ,'cust_account_no' => $member_no 
                ]; 
             } 
            $conn->insert('vz_customer', $data);
          }

        echo '<script>window.auth=true;swal.fire("' . $errorMSG . '");
       document.cookie="aff=;expires=Thu, 01 Jan 1970"
       </script>';
    } else {
        echo '<script>swal.fire("' . $errorMSG . '");</script>';
        if ($errorCode == 200) {
            echo '<script> document.cookie="aff=;expires=Thu, 01 Jan 1970" </script>';
        }
    }
} else {
    echo "<script>swal.fire('$errorMSG');</script>";
}
exit();
