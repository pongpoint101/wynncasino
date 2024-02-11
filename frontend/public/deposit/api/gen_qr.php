<?php
require '../../bootstart.php';
require_once ROOT . '/core/security.php';
$WebSites = GetWebSites();

$sitetitle = 'QR Code';
$assets_head = '<link rel="stylesheet" href="' . GetFullDomain() . '/assets/css/member_page3.css?v=3">';
include_once ROOT_APP . '/componentx/header.php';

include_once '../config/Database.php';
include_once '../models/Qr_Deposite_Log.php';
include_once '../models/Vizpay.php';

$config = [
    'api_key' => 'ec89798f-c7243696-05f85d7a-73de8896',
    'secret_key' => '7d60485d-bf027e71-ca764d67-73b776f0',
    'version' => 'v1',
    'api_url' => 'https://prod.vizpay.io/'
];

if (isset($_POST['amount']) && isset($_SESSION['member_no'])) {
    $amount = $_POST['amount'];
    $site_id = strtoupper(WEBSITE);
    $member_no = $site_id . "_" . $_SESSION['member_no'];
    $ref_account_no = $_SESSION['bank_accountnumber'];
    // Dont Forget to get bank_code in the SESSION!!!
    $ref_bank_code = $_SESSION['bank_code'];
    $ref_name = $_SESSION['member_name'];
    $trx_id = uniqid();
    $vizpay = new Vizpay($config);

    try {
        // Instantiate DB & connect
        $database = new Database();
        $db = $database->connect();
        $qr_deposite_log = new Qr_Deposite_Log($db);
        // TODO: Change callback url to payment hub url
        $callback_url = "https://bigdealtech.co.th/payment_hub/index.php";
        $post_data = [
            "order_id" => $trx_id,
            "user_id" =>  $member_no,
            "amount" => $amount,
            "ref_name" => $ref_name,
            "ref_account_no" => $ref_account_no,
            "ref_bank_code" => $ref_bank_code,
            "callback_url" => $callback_url
        ];

        $post_data['signature'] = $vizpay->gen_signature($post_data);
        $result = $vizpay->call_url('/deposit/qrcode', 'POST', $post_data);
        // print_r($post_data); 


        if ($result['error'] !== 0) {
            $qr_url = $result['result']['image'];
            // print_r($result);
        } else {
            echo 'Can not generate qr code.';
            print_r($result);
            die();
        }
    } catch (Exception $error) {
        echo json_encode(["success" => false, "message" => $error]);
    }
}

?>
<style>
    body {
        /* background-color: #122033; */
        background-color: lightgrey;
    }

    .qr-section {
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .qr-section p {
        font-size: larger;
    }

    .qr-img {
        max-width: 50%;
        height: auto;
    }
</style>
<section class="qr-section">
    <img class="qr-img" src="<?php echo $qr_url ?>" alt="QR Code Image">
    <p class="mt-3">ต้องโอนเงินจากบัญชี ต่อไปนี้เท่านั้น</p>
    <p><?php echo $_SESSION['bank_name'] ?>: <?php echo $_SESSION['bank_accountnumber'] ?> </p>
</section>
<?php
$assets_footer = '<script src="' . GetFullDomain() . '/assets/js/navbar_control.js"></script>';

include_once ROOT_APP . '/componentx/footer.php';
?>