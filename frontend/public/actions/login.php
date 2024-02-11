<?php
require '../bootstart.php';
require_once ROOT . '/core/detect.php';
if (isset($_SESSION['id'])) {
    exit();
}
if (isset($limiter)) {
    $limiterns = $limiter->hit('requestlimitlogin:' . getIP(), 1, 3); // เรียกได้ 1 ครั้งใน 5 วินาที
    if ($limiterns['overlimit']) {
        InSertLogSys(['member_no' => '', 'ip' => getIP(), 'log_type' => 'loginlimit', 'txt_data' => @$_POST['username'] . '-' . date('Y-m-d') . ' ' . date('H:i:s')]);
        exit(0);
    }
}
if (!empty($_GET['token'])) {
    $id = base64_decode($_GET['token']);
    $lastaccess = date('Y-m-d H:i:s');
    $uuidlastaccess = uniqid(true);
    // echo $password;
    $ip = getIP();
    $query = "SELECT id,member_login,username,telephone,lname,lname,bank_name,bank_accountnumber,status,member_promo  FROM members WHERE id=?";
    $row = $conn->query($query, [$id])->row_array();

    if (isset($row['status']) || !$row) {
        if ($row['status'] != 1) {
            echo "<script>swal.fire('เข้าสู่ระบบ','กรุณาติดต่อ admin! ','error');</script>";
            exit();
        }
    }

    $MY_HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['member_no'] = $row['id'];
    $_SESSION['ip'] = $ip;
    $_SESSION['USER_AGENT'] = $MY_HTTP_USER_AGENT;
    $_SESSION['ip_live'] = $ip;
    $_SESSION['USER_AGENT_live'] = $MY_HTTP_USER_AGENT;
    $_SESSION['member_login'] = $row['member_login'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['member_name'] = $row['lname'] . " " . $row['lname'];
    $_SESSION['bank_name'] = $row['bank_name'];
    $_SESSION['bank_name'] = $row['bank_name'];
    $_SESSION['member_promo'] = $row['member_promo'];
    $_SESSION['bank_accountnumber'] = $row['bank_accountnumber'];
    $_SESSION['uuid_lastaccess'] = $uuidlastaccess;

    $conn->query("UPDATE members SET lastaccess=?,uuid_lastaccess=? WHERE id = ?", [$lastaccess, $uuidlastaccess, $_SESSION['member_no']]);

    $data_login = $conn->query("WITH ToDelete AS (
    SELECT RANK() OVER ( PARTITION BY ip_address ORDER BY id DESC )rank,id  FROM log_login WHERE member_no=?  
    )   
    SELECT * FROM ToDelete WHERE ToDelete.rank > 4; ", [$_SESSION['member_no']])->result_array();
    $delete_list = [];
    foreach ($data_login as $k => $v) {
        $delete_list[] = $v['id'] * 1;
    }
    if (@sizeof($delete_list) > 0) {
        $conn->where_in('id', $delete_list);
        $conn->delete('log_login');
    }

    $sql = "INSERT INTO log_login (member_no,ip_address,user_agent,device_info) VALUES (?,?,?,?)";
    $conn->query($sql, [$_SESSION['member_no'], $ip, $MY_HTTP_USER_AGENT, getDeviceInfo()]);
    $key = 'MB:Auth_check:' . $_SESSION['member_no'];
    DelteCache($key);

    ?>
<script src="<?= GetFullDomain() ?>/assets/js/jquery.min.js"></script>
    <script>
        window.auth = true;
        $(document).ready(function() {
            if (window.location.hostname == 'localhost') {
                window.open(window.location.protocol + "//" + window.location.hostname + "/rpg88/frontend/public/home/", "_blank");
            } else {
                window.open(window.location.protocol + "//" + window.location.hostname + "/home/", '_blank');
            }
        });
    </script>
    <?php
    exit();
} else {

    if (empty($_POST['username'])) {
        echo "<script>swal.fire('ข้อมูลไม่ครบ','กรุณากรอก เบอร์โทรศัพท์', 'error');</script>";
        exit();
    } elseif (empty($_POST['password'])) {
        echo "<script>swal.fire('ข้อมูลไม่ครบ','กรุณากรอก รหัสผ่าน', 'error');</script>";
        exit();
    } else {
        $username = trim($_POST['username']);
        $password = hash("sha512", trim($_POST['password']) . KEY_SALT);
        $lastaccess = date('Y-m-d H:i:s');
        $uuidlastaccess = uniqid(true);
        // echo $password;
        $ip = getIP();
        $query = "SELECT id,member_login,username,telephone,lname,lname,bank_name,bank_code,bank_accountnumber,status,member_promo  FROM members WHERE telephone=? AND password =?";
        $row = $conn->query($query, [$username, $password])->row_array();
        if (!$row) {
            $query = "SELECT id,member_login,username,telephone,lname,lname,bank_name,bank_code,bank_accountnumber,status,member_promo  FROM members WHERE telephone = ? AND password =?";
            $row = $conn->query($query, [$username, $password])->row_array();

            if (!$row) {
                echo "<script>swal.fire('เข้าสู่ระบบ','เบอร์โทรศัพท์ หรือ รหัสผ่าน ไม่ถูกต้อง','error');</script>";
                exit();
            }
        }
        if (isset($row['status']) || !$row) {
            if ($row['status'] != 1) {
                echo "<script>swal.fire('เข้าสู่ระบบ','กรุณาติดต่อ admin! ','error');</script>";
                exit();
            }
        }
        $MY_HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['member_no'] = $row['id'];
        $_SESSION['ip'] = $ip;
        $_SESSION['USER_AGENT'] = $MY_HTTP_USER_AGENT;
        $_SESSION['ip_live'] = $ip;
        $_SESSION['USER_AGENT_live'] = $MY_HTTP_USER_AGENT;
        $_SESSION['member_login'] = $row['member_login'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['member_name'] = $row['lname'] . " " . $row['lname'];
        $_SESSION['bank_name'] = $row['bank_name'];
        $_SESSION['bank_code'] = $row['bank_code'];
        $_SESSION['member_promo'] = $row['member_promo'];
        $_SESSION['bank_accountnumber'] = $row['bank_accountnumber'];
        $_SESSION['uuid_lastaccess'] = $uuidlastaccess;


        $conn->query("UPDATE members SET lastaccess=?,uuid_lastaccess=? WHERE id = ?", [$lastaccess, $uuidlastaccess, $_SESSION['member_no']]);

        $data_login = $conn->query("WITH ToDelete AS (
            SELECT RANK() OVER ( PARTITION BY ip_address ORDER BY id DESC )rank,id  FROM log_login WHERE member_no=?  
            )   
            SELECT * FROM ToDelete WHERE ToDelete.rank > 4; ", [$_SESSION['member_no']])->result_array();
        $delete_list = [];
        foreach ($data_login as $k => $v) {
            $delete_list[] = $v['id'] * 1;
        }
        if (@sizeof($delete_list) > 0) {
            $conn->where_in('id', $delete_list);
            $conn->delete('log_login');
        }
        $sql = "INSERT INTO log_login (member_no,ip_address,user_agent,device_info) VALUES (?,?,?,?)";
        $conn->query($sql, [$_SESSION['member_no'], $ip, $MY_HTTP_USER_AGENT, getDeviceInfo()]);
        $key = 'MB:Auth_check:' . $_SESSION['member_no'];
        DelteCache($key);
    ?>

        <script>
            window.auth = true;
            Swal.fire({
                title: "ยินดีต้อนรับสมาชิกค่ะ",
                html: "กำลังเข้าสู่เว็บไซต์...",
                timer: 1000,
                showConfirmButton: false,
                timerProgressBar: true,
                onBeforeOpen: function() {
                    Swal.showLoading();
                    timerInterval = setInterval(function() {}, 100);
                },
                onClose: function() {
                    clearInterval(timerInterval);
                },
            }).then(function(result) {
                $.ajax({
                    type: "GET",
                    url: "/popup-msg?id=4&w=" + screen.width + "&h=" + screen.height,
                    dataType: "json",
                }).done(function(data) {
                    // if (data.title != "") {
                    //     Swal.fire({
                    //         title: data.title,
                    //         imageUrl: data.imageUrl,
                    //         imageWidth: data.imageWidth,
                    //         imageHeight: data.imageHeight,
                    //         imageAlt: "",
                    //         timer: data.timer,
                    //     }); 
                    // }

                });
                if (window.location.hostname == 'localhost') {
                    window.location.href = window.location.protocol + "//" + window.location.hostname + "/WYNNCASINO/frontend/public/home/";
                } else {
                    window.location.href = window.location.protocol + "//" + window.location.hostname + "/home/";
                }
                if (result.dismiss === Swal.DismissReason.timer) {

                }
            });
        </script>
<?php
    exit();
}
}
?>