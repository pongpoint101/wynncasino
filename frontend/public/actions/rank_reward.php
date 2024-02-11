<?php
require '../bootstart.php';  
require_once ROOT.'/core/security.php';
 
$arr_return = array();

$type = $_GET['type'];
$selected_type = $_GET['selected_type'];
$member_no = $_SESSION['member_no'];
$currMonth = $_GET['current_month'];
$currYear = $_GET['current_year'];
$mdateselect = (validateDate($_GET['mdateselect']))?$_GET['mdateselect']:date('Y-m-d', strtotime(' -1 day'));
$cache_mdateselect=true;
if($mdateselect<date('Y-m-d',strtotime("yesterday"))){$cache_mdateselect=false;}
$arr_return['error_code'] = 200; 
$arr_return['selected_type'] = $selected_type; 
$arr_return['datalist'] = []; 
$startreward='2023-11-28';
$reward_enable=false;
$temp=[];
if ($currMonth == 0) {
  exit();
} 
$currMonth = date('n'); 
$currYear = date('Y'); 
if ($currMonth<=1) {
  $currMonth = 12; 
  $currYear = date('Y')-1; 
}else{
  $currMonth = date('n')-1;  
}
$previous_day=date('Y-m-d', strtotime(' -1 day'));
if($type=='daily'){
switch ($selected_type) {
  case 'affmonthssssssssssss': // Get AFF reward info  
    $sql = "SELECT * FROM aff_monthly WHERE aff_month=? AND aff_year=?  ORDER BY amount DESC LIMIT 30";  
    $datakey='rewardlist_aff:'.$member_no; 
    $res=GetDataSqlWhereAll($datakey,$sql,[$currMonth, $currYear],60*60);  

     $sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,a.pro_symbol 
    ,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
    $sql.=" FROM pro_promotion_detail a 
    INNER JOIN pro_month_aff b ON a.pro_group_id=b.pro_id
    WHERE a.pro_id=23 AND pro_status=1 ORDER BY rating;";
    $datakey='data_maff_reward:list:'; 
    $data_reward=GetDataSqlWhereAll($datakey,$sql,[],5*60);
       
    if (@sizeof($res)> 0) {  
      $count_no = 1;
      foreach ($res as $k => $v) {
        if (!$reward_enable||@$data_reward[$k]['status']!=1) { continue; }
        $rewardAmount =(isset($data_reward[$k]['amount'])?$data_reward[$k]['amount']:0);    
        $temp[$k]['count_no']=$count_no++; 
        $temp[$k]['member_no']=WEBSITE.$v['member_no']; 
        $usercode=$temp[$k]['member_no'];
        $str_length = strlen($usercode); 
        // $temp[$k]['member_no']=substr($usercode, 0,7).str_repeat('*', $str_length-10).substr($usercode, $str_length - 3, $str_length - 2);
        $temp[$k]['amount']=number_format($v['amount'], 2); 
        $temp[$k]['rewardAmount']=number_format($rewardAmount, 2);   
        $arr_return['datalist']= $temp;
      }
    }  
    break; 
   case 'comttttttttttttttttttt': // Get commission reward info  
    $sql = "SELECT * FROM comm_monthly WHERE comm_month=? AND comm_year=?  ORDER BY amount DESC LIMIT 30";
    $datakey='rewardlist_com:'.$member_no; 
    $res=GetDataSqlWhereAll($datakey,$sql,[$currMonth, $currYear],60*60);   

    $sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,a.pro_symbol 
    ,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
    $sql.=" FROM pro_promotion_detail a 
    INNER JOIN pro_reward b ON a.pro_group_id=b.pro_id
    WHERE a.pro_id=24 AND pro_status=1 ORDER BY rating;";
    $datakey='data_mcomm_reward:list:'; 
    $data_reward=GetDataSqlWhereAll($datakey,$sql,[],5*60);

    if (@sizeof($res)> 0) { 
      $count_no = 1;
      foreach ($res as $k => $v) { 
        if (!$reward_enable||@$data_reward[$k]['status']!=1) { continue; }
        $rewardAmount =(isset($data_reward[$k]['amount'])?$data_reward[$k]['amount']:0);  
        
        $temp[$k]['count_no']=$count_no++; 
        $temp[$k]['member_no']=WEBSITE.$v['member_no']; 
        $usercode=$temp[$k]['member_no'];
        $str_length = strlen($usercode); 
        // $temp[$k]['member_no']=substr($usercode, 0,7).str_repeat('*', $str_length-10).substr($usercode, $str_length - 3, $str_length - 2);
        $temp[$k]['amount']=number_format($v['amount'], 2); 
        $temp[$k]['rewardAmount']=number_format($rewardAmount, 2);  
        $arr_return['datalist']= $temp;  
      }
    }  
    break; 
    case 'maxplayer': // Get maxplayer reward info   
      $sql = "SELECT member_no,turnover_total,amount,status FROM rewardmaxplayer_daily WHERE trx_date =? ORDER BY turnover_total DESC LIMIT 5;";
      $datakey='rewardlist_maxplayer_daily:'.str_replace('_', '-', $mdateselect); 
      $res=GetDataSqlWhereAll($datakey,$sql,[$mdateselect],60*60,$cache_mdateselect);   

      $sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,a.pro_symbol 
      ,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
      $sql.=" FROM pro_promotion_detail a 
      INNER JOIN pro_reward b ON a.pro_group_id=b.pro_id
      WHERE a.pro_id=19 AND pro_status=1 ORDER BY rating;";
      $datakey='data_daily_maxplayer:list:'; 
      $data_reward=GetDataSqlWhereAll($datakey,$sql,[],5*60);
      $reward_enable=false;
      if(!isset($data_reward[0]['amount'])){$reward_enable=true;}
      if (@sizeof($res)> 0) { 
        $count_no = 1;
        foreach ($res as $k => $v) { 
          if ($reward_enable) { continue; } 
          if ($v['turnover_total']<=1000) { continue; }// ยอดเล่น <1000 ไม่ให้
          $rewardAmount =(isset($data_reward[$k]['amount'])?$data_reward[$k]['amount']:0);  

          $temp[$k]['count_no']=$count_no++;  
          $temp[$k]['member_allow']=0;
          $temp[$k]['member_no']=WEBSITE.$v['member_no']; 
          $usercode=$temp[$k]['member_no'];
          $str_length = strlen($usercode); 
          if ($v['member_no']==$_SESSION['member_no']) { 
            $temp[$k]['member_allow']=$v['member_no']; 
          }else{
            $temp[$k]['member_no']=substr($usercode, 0,7).str_repeat('*', $str_length-10).substr($usercode, $str_length - 3, $str_length - 2);
          }
          $temp[$k]['amount']=number_format($v['turnover_total'], 2); 
          $temp[$k]['rewardAmount']=number_format($v['amount'], 2);  
          $temp[$k]['status']=$v['status']; 
          if($mdateselect<=$startreward&&$v['status']==1){
              $temp[$k]['status']=3;
           }
          $arr_return['datalist']= $temp;  
        }
      }  
      break;  
      case 'aff': // Get AFF reward info  
        $sql = "SELECT * FROM rewardaff_winner_daily WHERE trx_date=?  ORDER BY amount DESC LIMIT 30";  
        $datakey='reward_reward_dailyaff:'.str_replace('_', '-', $mdateselect); 
        $res=GetDataSqlWhereAll($datakey,$sql,[$mdateselect],60*60,$cache_mdateselect);  
    
         $sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,a.pro_symbol 
         ,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
         $sql.=" FROM pro_promotion_detail a 
         INNER JOIN pro_reward b ON a.pro_group_id=b.pro_id
         WHERE a.pro_id=179 AND pro_status=1 ORDER BY rating;";
        $datakey='reward_pro_dailyaff:list:'; 
        $data_reward=GetDataSqlWhereAll($datakey,$sql,[],5*60); 
        
        if (@sizeof($res)> 0) {  
          $count_no = 1;
          $reward_enable=false;
          if(!isset($data_reward[0]['amount'])){$reward_enable=true;}
          foreach ($res as $k => $v) {
            if ($reward_enable) { continue; } 

            $temp[$k]['count_no']=$count_no++;  
            $temp[$k]['member_allow']=0;
            $temp[$k]['member_no']=WEBSITE.$v['member_no']; 
            $usercode=$temp[$k]['member_no'];
            $str_length = strlen($usercode); 
            if ($v['member_no']==$_SESSION['member_no']) { 
              $temp[$k]['member_allow']=$v['member_no']; 
            }else{
              $temp[$k]['member_no']=substr($usercode, 0,7).str_repeat('*', $str_length-10).substr($usercode, $str_length - 3, $str_length - 2);
            }
            $temp[$k]['amount']=number_format($v['turnover_total'], 2); 
            $temp[$k]['rewardAmount']=number_format($v['amount'], 2);  
            $temp[$k]['status']=$v['status'];
            if($mdateselect<=$startreward&&$v['status']==1){
              $temp[$k]['status']=3;
            }
            $arr_return['datalist']= $temp;
          }
        }  
        break;      
        case 'cashbackplayer': // Get cashbackplayer reward info  
          $sql = "SELECT * FROM rewardcashback_daily WHERE trx_date=?  ORDER BY amount DESC LIMIT 30";  
          $datakey='reward_daily_cashbackplayer:'.str_replace('_', '-', $mdateselect); 
          $res=GetDataSqlWhereAll($datakey,$sql,[$mdateselect],60*60,$cache_mdateselect);  
      
           $sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,a.pro_symbol 
           ,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
           $sql.=" FROM pro_promotion_detail a 
           INNER JOIN pro_reward b ON a.pro_group_id=b.pro_id
           WHERE a.pro_id=180 AND pro_status=1 ORDER BY rating;";
          $datakey='reward_pro_dailyaff:list:'; 
          $data_reward=GetDataSqlWhereAll($datakey,$sql,[],5*60);
          $reward_enable=false;
          if(!isset($data_reward[0]['amount'])){$reward_enable=true;}
          if (@sizeof($res)> 0) {  
            $count_no = 1;
            foreach ($res as $k => $v) {
              if ($reward_enable) { continue; } 

              $temp[$k]['count_no']=$count_no++;  
              $temp[$k]['member_allow']=0;
              $temp[$k]['member_no']=WEBSITE.$v['member_no']; 
              $usercode=$temp[$k]['member_no'];
              $str_length = strlen($usercode); 
              if ($v['member_no']==$_SESSION['member_no']) { 
                $temp[$k]['member_allow']=$v['member_no']; 
              }else{
                $temp[$k]['member_no']=substr($usercode, 0,7).str_repeat('*', $str_length-10).substr($usercode, $str_length - 3, $str_length - 2);
              }
              $temp[$k]['amount']=number_format($v['turnover_total'], 2); 
              $temp[$k]['rewardAmount']=number_format($v['amount'], 2);  
              $temp[$k]['status']=$v['status']; 
              if($mdateselect<=$startreward&&$v['status']==1){
                $temp[$k]['status']=3;
             }
              $arr_return['datalist']= $temp;
            }
          }  
          break;
          case 'maxwinnerplayer': // Get maxwinnerplayer reward info   
            $sql = "SELECT member_no,turnover_total,amount,status FROM rewardmaxwinner_player_daily WHERE trx_date = ? ORDER BY turnover_total DESC LIMIT 5;";
            $datakey='reward_maxwinnerplayer_daily:'.str_replace('_', '-', $mdateselect); 
            $res=GetDataSqlWhereAll($datakey,$sql,[$mdateselect],60*60,$cache_mdateselect);   
      
            $sql = "SELECT amount,rating,status,a.pro_id,pro_group_id,pro_group_name,pro_is_group,a.pro_symbol 
            ,pro_name,pro_name_short,pro_description,pro_allow_playgame,pro_min_playgame";  
            $sql.=" FROM pro_promotion_detail a 
            INNER JOIN pro_reward b ON a.pro_group_id=b.pro_id
            WHERE a.pro_id=181 AND pro_status=1 ORDER BY rating;";
            $datakey='data_daily_maxplayer:list:'; 
            $data_reward=GetDataSqlWhereAll($datakey,$sql,[],5*60);
            $reward_enable=false;
            if(!isset($data_reward[0]['amount'])){$reward_enable=true;}
            if (@sizeof($res)> 0) { 
              $count_no = 1;
              foreach ($res as $k => $v) { 
                if ($reward_enable) { continue; } 
                if ($v['turnover_total']<=1000) { continue; }// ยอดเล่น <1000 ไม่ให้
                $rewardAmount =(isset($data_reward[$k]['amount'])?$data_reward[$k]['amount']:0);  
      
                $temp[$k]['count_no']=$count_no++;  
                $temp[$k]['member_allow']=0;
                $temp[$k]['member_no']=WEBSITE.$v['member_no']; 
                $usercode=$temp[$k]['member_no'];
                $str_length = strlen($usercode); 
                if ($v['member_no']==$_SESSION['member_no']) { 
                  $temp[$k]['member_allow']=$v['member_no']; 
                }else{
                  $temp[$k]['member_no']=substr($usercode, 0,7).str_repeat('*', $str_length-10).substr($usercode, $str_length - 3, $str_length - 2);
                }
                $temp[$k]['amount']=number_format($v['turnover_total'], 2); 
                $temp[$k]['rewardAmount']=number_format($v['amount'], 2);  
                $temp[$k]['status']=$v['status']; 
                if($mdateselect<=$startreward&&$v['status']==1){
                  $temp[$k]['status']=3;
                } 
               $arr_return['datalist']= $temp;  
              }
            }  
            break;
  default: break;
 } 
}
echo json_encode($arr_return);
exit();