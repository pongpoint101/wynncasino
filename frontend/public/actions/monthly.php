<?php
require '../bootstart.php';
require_once ROOT . '/core/online.php';
$site = GetWebSites();
//  if(@$_GET['api_key']!='@699999999'){exit();}
//  $month =isset($_GET['month'])?$_GET['month']:date('m'); 
//  $year =isset($_GET['year'])?$_GET['year']:date('Y'); 
$data = file_get_contents('php://input');
if (strlen($data) > 0) {
    $data = json_decode($data, true);
    $month = $data['month'];
    $year =  $data['year'];
} else {
    $month = date('m');
    $year = date('Y');
}
$trx_date = $month . $year;
$withdraw_today = [];

$sql = "SELECT sum(amount) as deposit_today_amount, count(*) as deposit_today_count from log_deposit";
$sql .= " WHERE amount>0 AND status=1 AND YEAR(trx_date)=? AND MONTH(trx_date)=?";
$sql .= " AND remark NOT IN ('ฟรี50','Free50','Free60','WB50','HNY50')";
$sql .= " AND (channel IN (1,2,3,5))";

$datakey = 'report_deposit:' . $trx_date . ':' . $site['brand_name'];
$deposit_today = GetDataSqlWhereOne($datakey, $sql, [$month, $year], 5 * 60);

$sql = "SELECT * From log_withdraw";
$sql .= " WHERE amount>0 AND status=1 AND YEAR(trx_date)=? AND MONTH(trx_date)=?";

$withdraw_today['withdraw_today_amount'] = 0;
$datakey = 'report_withdraw:' . $trx_date . ':' . $site['brand_name'];
$res = GetDataSqlWhereAll($datakey, $sql, [$month, $year], 5 * 60);
foreach ($res as $key => $value) {
    switch ($value['remark']) {
        case 'Free50':
            $withdraw_today['withdraw_today_amount'] += 100;
            break;
        case 'Free60':
            $withdraw_today['withdraw_today_amount'] += 120;
            break;
        case 'P102':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        case 'Pro100%':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        case 'CASINO100':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        case 'P118':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        case 'P119':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        case 'P120':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        case 'P121':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        case 'P122':
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
        default:
            $withdraw_today['withdraw_today_amount'] += $value['amount_actual'];
            break;
    }
    $withdraw_today['withdraw_today_count']++;
}

// New register member
$sql = "SELECT count(1) as registered_today FROM members WHERE  YEAR(create_at)=? AND MONTH(create_at)=?";
$datakey = 'report_regis_today:' . $trx_date . ':' . $site['brand_name'];
$register_member = GetDataSqlWhereOne($datakey, $sql, [$month, $year], 5 * 60);
// Member counted - Active
$sql = "SELECT count(1) as member_count FROM members WHERE status=1"; // WHERE status=5 AND  DATE(update_date)=?";
$datakey = 'report_member_active_count:' . $trx_date . ':' . $site['brand_name'];
$memberCountActive = GetDataSqlWhereOne($datakey, $sql, [], 5 * 60);
// Member counted - Inactive
$sql = "SELECT count(1) as member_count FROM members WHERE status<>1"; // WHERE status=5 AND  DATE(update_date)=?"; 
$datakey = 'report_member_inactive_count:' . $trx_date . ':' . $site['brand_name'];
$memberCountInactive = GetDataSqlWhereOne($datakey, $sql, [], 5 * 60);
$member_count = $memberCountActive['member_count'] + $memberCountInactive['member_count'];

echo json_encode([
    'brand_name' => $site['brand_name'],
    'deposit' => $deposit_today,
    'withdraw' => $withdraw_today,
    'new_regis' => $register_member['registered_today'],
    'member_count' => $member_count,
    'member_active' => $memberCountActive['member_count'],
    'member_inactive' => $memberCountInactive['member_count'],
    'online_user' => online::who(),
]);
