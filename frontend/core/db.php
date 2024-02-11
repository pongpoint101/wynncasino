<?php
defined('ROOT') or exit('No access allowed');

define('BASEPATH', ROOT . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'codeigniter' . DIRECTORY_SEPARATOR . 'framework' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR); // relative 
// $Common = BASEPATH.'/core/Common.php';
// require_once($Common);
// define('BASEPATH', realpath('/private/vendor/codeigniter/framework/system').DIRECTORY_SEPARATOR); // absolute
// use CodeigniterDatabase\CodeigniterDatabase as CodeigniterDatabase; 
//Database configuration 
$db_data = [
    'dsn'    => '',
    'hostname' => DBHost,
    'username' => DBUser,
    'password' => DBPassword,
    'database' => DBName,
    'dbdriver' => 'mysqli',
    'port' => DBPort,
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => TRUE,
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
];
$ci3db = &\anovsiradj\CI3DataBase::init();
$ci3db->set_db_config('db-server-0', $db_data);
$conn = &$ci3db->db(); // or
// $conn = new CodeigniterDatabase($db_data); 
// $res = $conn->query('select * from m_site')->result_array(); 
// print_r($res);exit();  
function getMembers($member_no)
{
    global $conn;
    $sql = "SELECT id,commission_money_all,cashback_money_all,member_login
    ,turnover_all,deposit_all,withdraw_all FROM members WHERE id=? AND status='1'";
    $result = $conn->query($sql, [$member_no])->row_array();

    return $result;
}
function getMemberTurnover($member_no)
{
    global $conn;
    // $sql = "SELECT * FROM member_turnover WHERE member_no=?";
    $sql = "SELECT * FROM member_turnover_product WHERE member_no=?";
    $result = $conn->query($sql, [$member_no])->result_array();
    return $result;
}
function getCommClaimed($member_no)
{
    global $conn;
    $sql = "SELECT sum(amount) as sum_amount FROM log_claim_commission WHERE member_no=?";
    $sum_total = 0;
    $result = $conn->query($sql, [$member_no])->result_array();

    foreach ($result as $row) {
        $sum_total += $row['sum_amount'];
    }
    return $sum_total;
}

function getMemberNoAffUpline($p_aff_upline)
{
    global $conn;
    $result = $conn->query("SELECT id,username,group_af_l1,group_af_l2 FROM members WHERE af_code =?", [$p_aff_upline]);
    $data = null;
    if ($result->num_rows() > 0) {
        $result1 = $result->row_array();
        if ($result1['group_af_l1'] > 0) {
            $sql = "SELECT id,username,group_af_l1,group_af_l2  FROM members WHERE id=?";
            $row = $conn->query($sql, [$result1['group_af_l1']])->row_array();
            $data['group_af_l1'] = $result1;
            $data['group_af_l2'] = $row;
        } else {
            $data['group_af_l1'] = $result1;
            $data['group_af_l2'] = null;
        }
    }
    return $data;
}
function check_bank_num($bank_number, $bank_code)
{
    global $conn;
    $rows = $conn->query("SELECT bank_accountnumber FROM `members` WHERE bank_code=?AND bank_accountnumber = ?", [$bank_code, $bank_number]);
    if ($rows->num_rows() > 0) {
        return 1;
    } else {
        return 0;
    }
}
function check_line($lineid)
{
    global $conn;
    $rows = $conn->query("SELECT line_id FROM `members` WHERE line_id = ?", [$lineid]);
    if ($rows->num_rows() > 0) {
        return 1;
    } else {
        return 0;
    }
}
function check_phone($phone)
{
    global $conn;
    $rows = $conn->query("SELECT telephone FROM `members` WHERE telephone = ?", [$phone]);
    if ($rows->num_rows() > 0) {
        return 1;
    } else {
        return 0;
    }
}

function getLatestDepositToday($member_no)
{
    global $conn;
    $return_val = 0;
    $sql = "SELECT * FROM log_deposit WHERE member_no=? AND DATE(trx_date) = CURRENT_DATE() AND status=1 ORDER BY trx_date,trx_time DESC"; // AND status='1'";
    $result = $conn->query($sql, [$member_no]); //->fetch_assoc();

    if ($result->num_rows() > 0) {
        $result = $result->row_array();
        $return_val = $result['amount'];
    }
    return $return_val;
}

function GetPromo_List()
{
    global $conn;
    $promo_list = $conn->query("SELECT promo_id,short_desc,full_desc,min,max,turnover_multiplier,calculate_value FROM m_promo WHERE promo_status='1' ORDER BY promo_id");

    return $promo_list->result_array();
}
function getMemberWallet($member_no)
{
    global $conn;
    $sql = "SELECT id,main_wallet,aff_wallet_l1,aff_wallet_l2,commission_wallet,cashback_wallet
    ,turnover,deposit,withdraw FROM member_wallet WHERE member_no=?";
    $result = $conn->query($sql, [$member_no])->row_array();

    return $result;
}
function getMemberBank($member_no)
{
    global $conn;
    $sql = "SELECT * FROM member_bank WHERE member_no=?"; // AND status='1'";
    $result = $conn->query($sql, [$member_no])->row_array();

    return $result;
}
function checkPromoActive($promo_id)
{
    global $conn;
    $return_val = true;
    $sql = "SELECT * FROM m_promo WHERE promo_id=?";
    $result = $conn->query($sql, [$promo_id])->row_array();

    if ($result['promo_status'] == 0) {
        $return_val = false;
    }
    return $return_val;
}


function getProviderConfig($provider_code, $site_id, $operation_mode)
{
    $key = 'ALL:DATA_PROVIDER:' . $provider_code;
    $Cachedata = GetCachedata($key);
    if ($Cachedata) { // ติดต่อ redis ได้ และ มีคีย์
        $data = json_decode($Cachedata, true);
    } else {
        global $conn;
        $sql = "SELECT config_string FROM m_providers WHERE provider_code=? AND site_id=? AND operation_mode=?";
        $result = $conn->query($sql, [$provider_code, $site_id, $operation_mode])->row_array();
        $data = json_decode($result['config_string'], true);

        SetCachedata($key, $result['config_string'], 60);
    }
    return $data;
}
function GetWebSites()
{ // 
    $key = 'All:WebSites';
    $Cachedata = GetCachedata($key);
    if ($Cachedata) {
        $data = json_decode($Cachedata, true);
    } else {
        global $conn;
        $sql = "SELECT * FROM m_site WHERE site_id='" . WEBSITE . "'";
        $data = $conn->query($sql)->row_array();
        SetCachedata($key, json_encode($data, JSON_PRESERVE_ZERO_FRACTION), 60);
    }
    return $data;
}
function GetLogsDW($money_type, $member_no, $itemsPerPage = 20, $offset = '')
{
    $key = 'MB:DW_' . $money_type . ':' . $member_no;
    $Cachedata = GetCachedata($key);
    if ($Cachedata) {
        $data = json_decode($Cachedata, true);
    } else {
        global $conn;
        if ($money_type == 'withdraw') {
            $data = $conn->query("with cte_widthdraw AS (
                SELECT amount,amount_actual,remark,1 AS remark2,trx_date,trx_time,status FROM log_withdraw WHERE member_no=? 
                UNION ALL
                SELECT amount,amount AS amount_actual,IF(remark IS NULL OR remark = '','ถอนเครดิต',remark) AS remark,2 AS remark2,CAST(update_date AS DATE) AS trx_date, CAST(update_date AS time) AS trx_time,1 AS status
                 FROM log_del_credit WHERE member_no=? 
               ) 
             SELECT * from cte_widthdraw ORDER BY trx_date DESC,trx_time DESC
             LIMIT ? OFFSET ?", [$member_no,$member_no, $itemsPerPage, $offset])->result_array();
        } else if ($money_type == 'deposit') {
            $data = $conn->query("SELECT * FROM log_deposit WHERE member_no=? ORDER BY trx_date DESC,trx_time DESC LIMIT ? OFFSET ?", [$member_no, $itemsPerPage, $offset])->result_array();
        }
        SetCachedata($key, json_encode($data, JSON_PRESERVE_ZERO_FRACTION), 60);
    }
    return $data;
}
function GetDataSqlWhereAll($datakey, $sql = '', $param_sql = [], $ttl = 60, $deletecahe = false)
{
    $key = 'MB:' . $datakey;
    if ($deletecahe) {
        DelteCache($key);
    }
    $Cachedata = GetCachedata($key);
    if ($Cachedata) {
        $data = json_decode($Cachedata, true);
    } else {
        global $conn;
        if (@sizeof($param_sql) > 0) {
            $data = $conn->query($sql, $param_sql)->result_array();
        } else {
            $data = $conn->query($sql)->result_array();
        }
        SetCachedata($key, json_encode($data, JSON_PRESERVE_ZERO_FRACTION), $ttl);
    }
    return $data;
}
function GetDataSqlWhereOne($key, $sql, $where = [], $ttl = 60)
{
    $key = 'MB:' . $key;
    $Cachedata = GetCachedata($key);
    if ($Cachedata) {
        $data = json_decode($Cachedata, true);
    } else {
        global $conn;
        if (@sizeof($where) > 0) {
            $data = $conn->query($sql, $where)->row_array();
        } else {
            $data = $conn->query($sql)->row_array();
        }
        SetCachedata($key, json_encode($data, JSON_PRESERVE_ZERO_FRACTION), $ttl);
    }
    return $data;
}
function GetDataMem($where)
{
    global $conn;
    $sql = "SELECT id,af_code,turnover_all,commission_wallet,commission_money_all,aff_type FROM members WHERE id=?";
    if (@sizeof($where) > 0) {
        $data = $conn->query($sql, $where)->row_array();
    } else {
        $data = $conn->query($sql)->row_array();
    }
    return $data;
}
function GetWinlossLevel1($member_no, $date_before)
{
    global $conn;
    $data['data'] = null;
    $data['total'] = null;
    if ($member_no) {
        $sql1 = "SELECT members.id
                FROM view_list_af_l1
                LEFT JOIN members
                ON view_list_af_l1.username=members.username
                WHERE view_list_af_l1.group_af_l1 = ?
                ORDER BY view_list_af_l1.username";
        $query1 = $conn->query($sql1, [$member_no])->result();
        $arr_member_no = array();
        foreach ($query1 as $row) {
            // $data[]= GetAffWinloss($row->id);
            array_push($arr_member_no, $row->id);
        }
        $implode_member_no = implode(',', $arr_member_no);
        if ($implode_member_no) {
            if ($date_before == 1) {

                $date_start = date("Y-m-d", strtotime("first day of previous month"));
                $date_end = date("Y-m-d", strtotime("last day of previous month"));
            } else {
                $date_start = date('Y-m-01');
                $date_end = date('Y-m-t');
                // $date_start = '2022-04-01';
                // $date_end = '2022-04-04';
            }
            $sql = "SELECT  member_no,SUM(deposit) as deposit,SUM(withdraw) as withdraw,SUM(promotion) as promotion, created_date
                    FROM aff_win_loss
                    WHERE member_no  IN ($implode_member_no)
                    AND created_date >= ? AND created_date<= ?
                    GROUP BY created_date
                    ORDER BY created_date
                    ";
            $query_win_loss = $conn->query($sql, [$date_start, $date_end])->result();
            $deposit_tot = 0;
            $withdraw_tot = 0;
            $winloss_tot = 0;
            $promotion_tot = 0;
            $total_tot = 0;
            $data['data'] = null;
            $data['total'] = null;

            foreach ($query_win_loss as $row) {
                $deposit = !empty($row->deposit) ? $row->deposit : (float)0.00;
                $withdraw = !empty($row->withdraw) ? $row->withdraw : (float)0.00;
                $promotion = !empty($row->promotion) ? $row->promotion : (float)0.00;
                $winloss = $deposit - $withdraw;
                $total = $winloss - $promotion;

                $deposit_tot = $deposit;
                $withdraw_tot = $withdraw;
                $winloss_tot = $winloss;
                $promotion_tot = $promotion;
                $total_tot = $total;
                $data['data'][] = array(
                    "member_no" => $row->member_no,
                    "date" => thai_date_short(strtotime($row->created_date)),
                    "deposit" => $deposit_tot,
                    "withdraw" => $withdraw_tot,
                    "winloss" => $winloss_tot,
                    "promotion" => $promotion_tot,
                    "total" => $total_tot,
                );
            }

            if ($data['data']) {
                $deposit_t = 0;
                $withdraw_t = 0;
                $winloss_t = 0;
                $promotion_t = 0;
                $total_t = 0;

                foreach ($data['data'] as $row) {
                    $deposit_t += !empty($row['deposit']) ? $row['deposit'] : (float)0.00;
                    $withdraw_t += !empty($row['withdraw']) ? $row['withdraw'] : (float)0.00;
                    $winloss_t += !empty($row['winloss']) ? $row['winloss'] : (float)0.00;
                    $promotion_t += !empty($row['promotion']) ? $row['promotion'] : (float)0.00;
                    $total_t += !empty($row['total']) ? $row['total'] : (float)0.00;
                    $data['total'] = array(
                        "deposit" => $deposit_t,
                        "withdraw" => $withdraw_t,
                        "winloss" => $winloss_t,
                        "promotion" => $promotion_t,
                        "total" => $winloss_t - $promotion_t,
                    );
                }
            }
        }
    } else {
        $data['data'] = null;
        $data['total'] = null;
    }
    return $data;
}
function GetWinlossLevel2($member_no, $date_before)
{
    global $conn;
    $data['data'] = null;
    $data['total'] = null;
    if ($member_no) {
        $sql1 = "SELECT members.id
                FROM view_list_af_l2
                LEFT JOIN members
                ON view_list_af_l2.username=members.username
                WHERE view_list_af_l2.group_af_l2 = ?
                ORDER BY view_list_af_l2.username";
        $query1 = $conn->query($sql1, [$member_no])->result();
        $arr_member_no = array();
        foreach ($query1 as $row) {
            // $data[]= GetAffWinloss($row->id);
            array_push($arr_member_no, $row->id);
        }
        $implode_member_no = implode(',', $arr_member_no);
        if ($implode_member_no) {
            if ($date_before == 1) {
                $date_start = date("Y-m-d", strtotime("first day of previous month"));
                $date_end = date("Y-m-d", strtotime("last day of previous month"));
            } else {
                $date_start = date('Y-m-01');
                $date_end = date('Y-m-t');
                // $date_start = '2022-04-01';
                // $date_end = '2022-04-04';
            }
            $sql = "SELECT  member_no,SUM(deposit) as deposit,SUM(withdraw) as withdraw,SUM(promotion) as promotion, created_date
                    FROM aff_win_loss
                    WHERE member_no  IN ($implode_member_no)
                    AND created_date >= ? AND created_date<= ?
                    GROUP BY created_date
                    ORDER BY created_date
                    ";
            $query_win_loss = $conn->query($sql, [$date_start, $date_end])->result();
            $deposit_tot = 0;
            $withdraw_tot = 0;
            $winloss_tot = 0;
            $promotion_tot = 0;
            $total_tot = 0;
            $data['data'] = null;
            $data['total'] = null;
            foreach ($query_win_loss as $row) {
                $deposit = !empty($row->deposit) ? $row->deposit : (float)0.00;
                $withdraw = !empty($row->withdraw) ? $row->withdraw : (float)0.00;
                $promotion = !empty($row->promotion) ? $row->promotion : (float)0.00;
                $winloss = $deposit - $withdraw;
                $total = $winloss - $promotion;

                $deposit_tot = $deposit;
                $withdraw_tot = $withdraw;
                $winloss_tot = $winloss;
                $promotion_tot = $promotion;
                $total_tot = $total;
                $data['data'][] = array(
                    "member_no" => $row->member_no,
                    "date" => thai_date_short(strtotime($row->created_date)),
                    "deposit" => $deposit_tot,
                    "withdraw" => $withdraw_tot,
                    "winloss" => $winloss_tot,
                    "promotion" => $promotion_tot,
                    "total" => $total_tot,
                );
            }
            if ($data['data']) {
                $deposit_t = 0;
                $withdraw_t = 0;
                $winloss_t = 0;
                $promotion_t = 0;
                $total_t = 0;
                foreach ($data['data'] as $row) {
                    $deposit_t += !empty($row['deposit']) ? $row['deposit'] : (float)0.00;
                    $withdraw_t += !empty($row['withdraw']) ? $row['withdraw'] : (float)0.00;
                    $winloss_t += !empty($row['winloss']) ? $row['winloss'] : (float)0.00;
                    $promotion_t += !empty($row['promotion']) ? $row['promotion'] : (float)0.00;
                    $total_t += !empty($row['total']) ? $row['total'] : (float)0.00;
                    $data['total'] = array(
                        "deposit" => $deposit_t,
                        "withdraw" => $withdraw_t,
                        "winloss" => $winloss_t,
                        "promotion" => $promotion_t,
                        "total" => $total_t,
                    );
                }
            }
        }
    } else {
        $data['data'] = null;
        $data['total'] = null;
    }
    return $data;
}
function GetPromotionCate($member_no)
{
    global $conn;
    if ($member_no) {
        $query_win_loss = null;
        $arr_member_no = array();
        // find member_no level1
        $sql1 = "SELECT members.id
                    FROM view_list_af_l1
                    LEFT JOIN members
                    ON view_list_af_l1.username=members.username
                    WHERE view_list_af_l1.group_af_l1 = ?
                    ORDER BY view_list_af_l1.username";
        $query1 = $conn->query($sql1, [$member_no])->result();
        foreach ($query1 as $row) {
            array_push($arr_member_no, $row->id);
        }

        //find member_no level2
        $sql2 = "SELECT members.id
                    FROM view_list_af_l2
                    LEFT JOIN members
                    ON view_list_af_l2.username=members.username
                    WHERE view_list_af_l2.group_af_l2 = ?
                    ORDER BY view_list_af_l2.username";
        $query2 = $conn->query($sql2, [$member_no])->result();
        foreach ($query2 as $row) {
            array_push($arr_member_no, $row->id);
        }
        $implode_member_no = implode(',', $arr_member_no);
        $pro = array('ฟรี50', 'Free50', 'Free60', 'WB50', 'HNY50');
        $date_start = date('Y-m-01');
        $date_end = date('Y-m-t');
        // $date_start = '2022-04-01';
        // $date_end = '2022-04-04';
        if ($implode_member_no) {
            $sql = "SELECT SUM(amount) as amount, remark
                    FROM log_deposit
                    WHERE member_no  IN ($implode_member_no)
                    AND trx_date >= ? AND trx_date<= ?
                    AND status = 1
                    AND channel NOT IN (1,2,3,5)
                    GROUP BY remark
                    ORDER BY remark
                    ";
            //  $sql = "SELECT  member_no,SUM(promotion) as promotion, created_date
            //  FROM aff_win_loss
            //  WHERE member_no  IN ($implode_member_no)
            //  AND created_date >= ? AND created_date<= ?
            //  GROUP BY created_date
            //  "; 
            $query_win_loss = $conn->query($sql, [$date_start, $date_end])->result();
        }
        return $query_win_loss;
    }
}
function GetWinlossTable($member_no)
{
    global $conn;
    if ($member_no) {
        $arr_member_no = array();
        // find member_no level1
        $sql1 = "SELECT members.id
                    FROM view_list_af_l1
                    LEFT JOIN members
                    ON view_list_af_l1.username=members.username
                    WHERE view_list_af_l1.group_af_l1 = ?
                    ORDER BY view_list_af_l1.username";
        $query1 = $conn->query($sql1, [$member_no])->result();
        foreach ($query1 as $row) {
            array_push($arr_member_no, $row->id);
        }
        $data['data'] = null;
        //find member_no level2
        // $sql2 = "SELECT members.id
        //             FROM view_list_af_l2
        //             LEFT JOIN members
        //             ON view_list_af_l2.username=members.username
        //             WHERE view_list_af_l2.group_af_l2 = ?
        //             ORDER BY view_list_af_l2.username"; 
        // $query2 = $conn->query($sql2, [$member_no])->result(); 
        // foreach($query2 as $row){
        //     array_push($arr_member_no,$row->id);
        // }
        $implode_member_no = implode(',', $arr_member_no);
        $date_start = date('Y-m-01');
        $date_end = date('Y-m-t');
        // $date_start = '2022-04-01';
        // $date_end = '2022-04-04';
        if ($implode_member_no) {
            $sql = "SELECT  member_no,SUM(deposit) as deposit,SUM(withdraw) as withdraw,SUM(promotion) as promotion, created_date
                    FROM aff_win_loss
                    WHERE member_no  IN ($implode_member_no)
                    AND created_date >= ? AND created_date<= ?
                    GROUP BY created_date
                    ORDER BY created_date
                    ";
            $query_win_loss = $conn->query($sql, [$date_start, $date_end])->result();
            // return $query_win_loss;
            $deposit_tot = 0;
            $withdraw_tot = 0;
            $winloss_tot = 0;
            $promotion_tot = 0;
            $total_tot = 0;

            foreach ($query_win_loss as $row) {
                $deposit = !empty($row->deposit) ? $row->deposit : (float)0.00;
                $withdraw = !empty($row->withdraw) ? $row->withdraw : (float)0.00;
                $promotion = !empty($row->promotion) ? $row->promotion : (float)0.00;
                $winloss = $deposit - $withdraw;
                $total = $winloss - $promotion;

                $deposit_tot = $deposit;
                $withdraw_tot = $withdraw;
                $winloss_tot = $winloss;
                $promotion_tot = $promotion;
                $total_tot = $total;
                $data['data'][] = array(
                    "member_no" => $row->member_no,
                    "date" => thai_date_short(strtotime($row->created_date)),
                    "deposit" => $deposit_tot,
                    "withdraw" => $withdraw_tot,
                    "winloss" => $winloss_tot,
                    "promotion" => $promotion_tot,
                    "total" => $total_tot,
                );
            }
            if ($data['data']) {
                $deposit_t = 0;
                $withdraw_t = 0;
                $winloss_t = 0;
                $promotion_t = 0;
                $total_t = 0;
                foreach ($data['data'] as $row) {
                    $deposit_t += !empty($row['deposit']) ? $row['deposit'] : (float)0.00;
                    $withdraw_t += !empty($row['withdraw']) ? $row['withdraw'] : (float)0.00;
                    $winloss_t += !empty($row['winloss']) ? $row['winloss'] : (float)0.00;
                    $promotion_t += !empty($row['promotion']) ? $row['promotion'] : (float)0.00;
                    $total_t += !empty($row['total']) ? $row['total'] : (float)0.00;
                    $data['total'] = array(
                        "deposit" => $deposit_t,
                        "withdraw" => $withdraw_t,
                        "winloss" => $winloss_t,
                        "promotion" => $promotion_t,
                        "total" => $total_t,
                    );
                }
            }
        }
        return $data;
    }
}
function GetLogWinloss($member_no)
{
    global $conn;
    $data = 0;
    $sql = "SELECT * FROM log_aff_win_loss WHERE member_no=? ORDER BY id DESC LIMIT 1";
    $query = $conn->query($sql, [$member_no])->result();
    if ($query) {
        if ($query[0]->cal < 0) {
            $data = $query[0]->cal;
        } else {
            $data = 0;
        }
    }
    return $data;
}
function getMemberPro($member_no)
{
    global $conn;
    return $conn->query("SELECT * FROM member_promo WHERE status=1 AND member_no=? ORDER BY id DESC LIMIT 1", [$member_no])->row_array();
}
function getTotalTurnover($member_no)
{
    global $conn;
    //     $sql = "SELECT sac_turnover,aec_turnover,pgs_turnover,wmc_turnover,sps_turnover,jks_turnover,kmc_turnover,rts_turnover,evp_turnover
    //   ,kas_turnover,sbo_turnover,ambpg_turnover,ambpk_turnover,jls_turnover,isb_turnover,fgs_turnover
    //    FROM member_turnover WHERE member_no=?";
    //     $res = $conn->query($sql, [$member_no])->row_array();
    //     $return_val = $res['sac_turnover'];
    //     $return_val += $res['aec_turnover'];
    //     $return_val += $res['pgs_turnover'];
    //     $return_val += $res['wmc_turnover'];
    //     $return_val += $res['sps_turnover'];
    //     $return_val += $res['jks_turnover'];
    //     $return_val += $res['kmc_turnover'];
    //     $return_val += $res['rts_turnover'];
    //     $return_val += $res['evp_turnover'];
    //     $return_val += $res['kas_turnover'];
    //     $return_val += $res['sbo_turnover'];
    //     $return_val += $res['ambpg_turnover'];
    //     $return_val += $res['ambpk_turnover'];
    //     $return_val += $res['isb_turnover'];
    //     $return_val += $res['jls_turnover'];
    //     $return_val += $res['fgs_turnover'];
    //     return $return_val;

    $sql = "SELECT SUM(current_turnover) as sum_total_turnover FROM member_turnover_product WHERE member_no=?";
    $res = $conn->query($sql, [$member_no])->row_array();
    return $res['sum_total_turnover'];
}
function getTotalSlotTurnover($member_no)
{
    global $conn; 
    $PlatformList = 'platform_code IN (SELECT provider_code FROM provider_category WHERE category_id=3)';
    //     $sql = "SELECT sac_turnover,aec_turnover,pgs_turnover,wmc_turnover,sps_turnover,jks_turnover,kmc_turnover,rts_turnover,evp_turnover
    //   ,kas_turnover,sbo_turnover,ambpg_turnover,ambpk_turnover,jls_turnover,isb_turnover,fgs_turnover
    //    FROM member_turnover WHERE member_no=?";

    $sql = 'SELECT SUM(current_turnover) AS sum_current_turnover FROM member_turnover_product WHERE member_no=? AND ' . $PlatformList;
    $res = $conn->query($sql, [$member_no])->row_array();
    // $return_val = $res['pgs_turnover'];
    // $return_val += $res['wmc_turnover'];
    // $return_val += $res['sps_turnover'];
    // $return_val += $res['jks_turnover'];
    // $return_val += $res['kmc_turnover'];
    // $return_val += $res['rts_turnover'];
    // $return_val += $res['evp_turnover'];
    // $return_val += $res['kas_turnover'];
    // $return_val += $res['ambpg_turnover'];
    // $return_val += $res['ambpk_turnover'];
    // $return_val += $res['isb_turnover'];
    // $return_val += $res['jls_turnover'];
    // $return_val += $res['fgs_turnover'];
    // return $return_val;
    return $res['sum_current_turnover'];
}
function getTotalFishingTurnover($member_no)
{
    global $conn; 
    $PlatformList = 'platform_code IN (SELECT provider_code FROM provider_category WHERE category_id=4)';

    $sql = 'SELECT SUM(current_turnover) AS sum_current_turnover FROM member_turnover_product WHERE member_no=? AND ' . $PlatformList;
    $res = $conn->query($sql, [$member_no])->row_array();
    return $res['sum_current_turnover'];
}

function getTotalCasinoTurnover($member_no)
{
    global $conn; 
    $PlatformList = 'platform_code IN (SELECT provider_code FROM provider_category WHERE category_id=2)';

    $sql = 'SELECT SUM(current_turnover) AS sum_current_turnover FROM member_turnover_product WHERE member_no=? AND ' . $PlatformList;
    $res = $conn->query($sql, [$member_no])->row_array();
    return $res['sum_current_turnover'];
}
function getTotalSportbookTurnover($member_no)
{
    global $conn; 
    $PlatformList = 'platform_code IN (SELECT provider_code FROM provider_category WHERE category_id=1)';

    $sql = 'SELECT SUM(current_turnover) AS sum_current_turnover FROM member_turnover_product WHERE member_no=? AND ' . $PlatformList;
    $res = $conn->query($sql, [$member_no])->row_array();
    return $res['sum_current_turnover'];
}
function clearTurnover($member_no)
{
    global $conn;
    // $sql = "UPDATE member_turnover SET outstanding_turnover=0,sac_turnover=0,aec_turnover=0,pgs_turnover=0,wmc_turnover=0,";
    // $sql .= "sps_turnover=0,jks_turnover=0,kmc_turnover=0,rts_turnover=0,evp_turnover=0,kas_turnover=0,sbo_turnover=0,";
    // $sql .= "ambpg_turnover=0,ambpk_turnover=0,jls_turnover=0,fgs_turnover=0,isb_turnover=0";
    // $sql .= " WHERE member_no=?";
    $sql = "UPDATE member_turnover_product SET current_turnover=0 WHERE member_no=?";
    $conn->query($sql, [$member_no]);
}
function deposite_PaddingUpdate($member_no, $currnt_balance)
{
    global $conn;
    $sql = "SELECT * FROM log_deposit WHERE member_no=? AND status=2 ORDER BY trx_date ASC,trx_time ASC";
    $res_padding = $conn->query($sql, [$member_no]);

    if ($res_padding->num_rows() > 0) {
        $res = $res_padding->row_array();

        // if (strpos($res[0]['remark'], 'มียอดถอนค้าง')) {
        //   $res[0]['remark'] = str_replace('มียอดถอนค้าง(', '', $res[0]['remark']);
        //   $res[0]['remark'] = str_replace(')', '', $res[0]['remark']);
        // }

        $sql_padding_w = "SELECT * FROM log_withdraw WHERE member_no=? AND status=2";
        $res_padding_w = $conn->query($sql_padding_w, [$member_no])->num_rows();


        if ($res_padding_w == 0) { // ยอดถอนค้างจะไม่เติมเงินออโต้ให้
            $depositAmount = (int)$res['amount'];
            $sql = "UPDATE log_deposit SET status=1 WHERE member_no=? AND trx_id=?";
            $conn->query($sql, [$member_no, $res['trx_id']]);
            adjustMemberWallet($member_no, $depositAmount, 1);
            adjustWalletHistory('member', $member_no, $depositAmount, $currnt_balance);
            $conn->query("UPDATE promofree50 SET status=2 WHERE member_no=?", [$member_no]);
            $conn->query("UPDATE promofree60 SET status=2 WHERE member_no=?", [$member_no]);
            $conn->query("UPDATE members SET member_promo=0,member_last_deposit=? WHERE id=?", [$res['channel'], $member_no]);
            //Record and set promo condition
            $conn->query("DELETE FROM member_promo WHERE member_no=?", [$member_no]);
            $sql = "INSERT INTO member_promo (member_no,promo_id,status,promo_accepted_date,turnover_expect,remark) VALUE (?,?,?,?,?,?)";
            $conn->query($sql, [$member_no, 0, 1, date('Y-m-d H:i:s'), $depositAmount, 'Auto1']);
            clearTurnover($member_no);
        }
    }
}
function adjustMemberWallet($member_no, $update_amount, $update_type)   //$update_type =>   1=บวก  2=ลบ
{
    global $conn;
    $result = getMemberWallet($member_no);
    $sql = "UPDATE member_wallet SET ";
    if ($update_amount != 0) {
        if (is_numeric($result['main_wallet'])) {
            if ($result['main_wallet'] < 0) {
                $sql .= "main_wallet=" . $update_amount;
            } else {
                if ($update_type == 1) { // บวก
                    $sql .= "main_wallet=main_wallet+" . $update_amount;
                } else { // ลบ
                    $sql .= "main_wallet=main_wallet-" . $update_amount;
                }
            }
        } else {
            $sql .= " main_wallet=" . $update_amount;
        }
        $sql .= " WHERE member_no=" . $member_no;
        $conn->query($sql);
    }
    $result = getMemberWallet($member_no);
    return $result['main_wallet'];
}
function adjustWalletHistory($adjust_from, $adjust_to, $adjust_amount, $amount_b4_adjust = 0, $trx_id = '', $remark = '')
{
    global $conn;
    $sql = "INSERT INTO adjust_wallet_history (adjust_from,adjust_to,adjust_amount,b4_adjust_amount,trx_id,remark) VALUES ";
    $sql .= "('" . $adjust_from . "'," . $adjust_to . "," . $adjust_amount . "," . $amount_b4_adjust . ",'" . $trx_id . "','" . $remark . "')";
    $conn->query($sql);
}
function getMBankSetting($p_bank_code)
{
    global $conn;
    $sql = "SELECT * FROM m_bank_setting WHERE status=1 AND bank_code = ? ";
    return $conn->query($sql, [$p_bank_code])->row_array();
}
function getMemberInfo($p_member)
{
    global $conn;
    $sql = "SELECT * FROM members WHERE ";
    if (strlen($p_member) > 6) {
        $sql .= "username=?";
    } else {
        $sql .= "id=?";
    }
    $result = $conn->query($sql, [$p_member]);
    if ($result->num_rows() == 0) {
        $arr_return = array('error_code' => 404, 'msg' => 'Data not found : ' . __FUNCTION__);
    } else {
        $arr_return = $result->row_array();
        $arr_return['error_code'] = 0;
    }
    return $arr_return;
}
function selectedPromo($member_no, $promo_id, $expect_turnover, $promo_cal_result, $trx_id = '', $remark = "")
{
    global $conn;
    switch ($promo_id) {
        case 3:
            $remark = "Pro10%";
            $expect_turnover = 0;
            $type = 1;
            switch ($promo_cal_result) {
                case 30:
                    $type = 1;
                    break;
                case 50:
                    $type = 2;
                    break;
                case 100:
                    $type = 3;
                    break;
                case 300:
                    $type = 4;
                    break;
                case 500:
                    $type = 5;
                    break;
                default:
                    break;
            }
            $data = [
                'member_no' => $member_no,
                'trx_id' => $trx_id,
                'status' => 1,
                'type' => $type,
                'update_by' => 'SYSTEM',
                'arrival_date' => date('Y-m-d H:s:i'),
                'win_expect' => ($promo_cal_result * 22)
            ];
            $conn->insert('promop10p', $data);
            break;
        case 4:
            $remark = "HappyTime";
            $expect_turnover = 0;
            break;
        default:
            # code...
            break;
    }
    $sql = "UPDATE member_promo SET turnover_expect=?,promo_accepted_date=?, status=1,promo_id=?";
    $sql .= ",remark=? WHERE member_no=? AND promo_id=0";
    $conn->query($sql, [$expect_turnover, date('Y-m-d H:i:s'), $promo_id, $remark, $member_no]);
    if ($conn->affected_rows() <= 0) {
        $data = [
            'member_no' => $member_no,
            'promo_id' => $promo_id,
            'status' => 1,
            'promo_accepted_date' => date('Y-m-d H:i:s'),
            'turnover_expect' => $expect_turnover,
            'remark' => $remark
        ];
        $conn->insert('member_promo', $data);
    }
    $sql = "UPDATE members SET member_promo=? WHERE id=?";
    $conn->query($sql, [$promo_id, $member_no]);
}
function Auth_check($member_no)
{
    $arr_return = null;
    $key = 'MB:Auth_check:' . $member_no;
    $Cachedata = GetCachedata($key);
    if ($Cachedata) { // ติดต่อ redis ได้ และ มีคีย์
        $arr_return = json_decode($Cachedata, true);
    } else {
        global $conn;
        $sql = 'SELECT ip_address,uuid_lastaccess,user_agent,members.status FROM members
    LEFT JOIN log_login  ON log_login.member_no=members.id
    WHERE members.id =? ORDER BY log_login.id DESC LIMIT 1';
        $result = $conn->query($sql, [$member_no]);
        if ($result->num_rows() > 0) {
            $arr_return = $result->row_array();
            SetCachedata($key, json_encode($arr_return), 3 * 60);
        }
    }
    return $arr_return;
}
function InSertLogSys($data=[],$member_no='')
{
      global $conn;
       if($member_no!=''){// update
        $conn->where('id', $member_no);
        $conn->update('log_systeme', $data);
      }else{// insert  
        $conn->insert('log_systeme', $data);
    }
    if(isset($data['member_no'])){$member_no=$data['member_no'];}
    if($member_no!=''){CheckLockerUser($member_no);} 
}
function CheckLockerUser($member_no=''){
    global $conn; 
    $log_list = $conn->query("SELECT create_at FROM log_systeme WHERE member_no=? AND create_at>DATE_SUB(NOW(), INTERVAL 1 HOUR) AND (log_code IS NULL OR log_code = '') ORDER BY create_at",[$member_no]); 
    $data=$log_list->result_array();
    $old_create_at='';$dupicat_all=0; $dupicat=0; 
    foreach ($data as $v) { 
        if($old_create_at==''){$old_create_at=$v['create_at']; continue;}
        $timeFirst  = strtotime($old_create_at);
        $timeSecond = strtotime($v['create_at']); 
        if(($timeSecond-$timeFirst)<=0){$dupicat++;}
        $old_create_at=$v['create_at'];
        $dupicat_all++;
      }
       if($dupicat>0||$dupicat_all>=10){
        $conn->set('status',2);
        $conn->where('id', $member_no);
        $conn->update('members');  
        @session_destroy();
       }  
}
function check_truewallet($tw)
{
    global $conn;
    $rows = $conn->query("SELECT truewallet_phone FROM `members` WHERE truewallet_phone = ?", [$tw]);
    if ($rows->num_rows() > 0) {
        return 1;
    } else {
        return 0;
    }
}
function GetTrueWallet()
{ 
    $key = 'All:truewallet';
    $Cachedata = GetCachedata($key);
    if ($Cachedata) {
        $data = json_decode($Cachedata, true);
    } else {
        global $conn;
        $sql = "SELECT * FROM true_wallet WHERE isActive=1";
        $data = $conn->query($sql)->row_array();
        SetCachedata($key, json_encode($data, JSON_PRESERVE_ZERO_FRACTION), 60);
    }
    return $data;
}
function FindByproMember($member_no=null){ 
    global $conn;
    $row='';$result_array='';      
    if ($member_no!=''&&$member_no!=null) { 
        $conn->where('member_no', $member_no); 
    }      
    $conn->order_by('id', 'DESC'); 
    $sql = $conn->get('pro_logs_promotion'); 

    $num_rows=$sql->num_rows();
    if ($num_rows>0) {
        $result_array = $sql->result_array(); 
        $row = $sql->row();
    }  
    $result['num_rows'] = $num_rows;
    $result['result_array'] = $result_array;
    $result['row'] = $row;  
   return $result;
}
 function Getdetail_More($pro_id=null,$pro_id_more=null){
    global $conn;
    $result_array = "";
    $row = "";  
    $conn->where('pro_id =', $pro_id); 
    if($pro_id_more!=null){
        $conn->where('id =', $pro_id_more);
    }
    $sql = $conn->get('pro_promotion_detail_more');
    $num_rows=$sql->num_rows(); 
    if($num_rows<=0){
        $conn->where('pro_id =', $pro_id); 
        $conn->order_by('deposit_start_amount', 'asc');
        $sql = $conn->get('pro_promotion_detail_more'); 
        $num_rows=$sql->num_rows(); 	 
    } 
    if($num_rows>0){
        $result_array = $sql->result_array();
        $row = $sql->row();
    }
    $result['num_rows'] = $num_rows;
    $result['result_array'] = $result_array;
    $result['row'] = $row;
    return $result;
}

function genUUID($data = null)
{
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function RandomString($strLength)
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $input_length = strlen($permitted_chars);
    $random_string = '';
    for ($i = 0; $i < $strLength; $i++) {
        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }

    return $random_string;
}
