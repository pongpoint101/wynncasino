<?php
class ConfigData { 
  public  $Userrootlist=['supportroot1','snsupport02','snsupport01','tommystyp','supportploy1'];
  public  $admin_manual_depositby_bank=[1,2,3];
  public  $admin_manual_depositby_bank_orther=[0,2,4,5,6,7,8];
  public  $admin_manual_depositby_pro=[10,12,13,14,15,61,62,63,64,65,66,67,68,69,70,71,72,83,87,90,95];// รายรายฝากที่เกี่ยวกับโปน
  public  $source_ref=['facebookv'=>'Facebook','tiktok'=>'Tiktok','google'=>'Google','ads'=>'ป้ายโฆษณา','friend'=>'เพื่อนแนะนำ','y2kro'=>'y2k-ro'
  ,'sms'=>'ข้อความ SMS','lineacc'=>'ข้อความ Line','youyubeacc'=>'YouTube','other'=>'อื่นๆ'];  
  private static $Dataenum = [
     13=>'Free200'
    ,14=>'Free60'
    ,4=>'SMS Delayed'
    ,1=>'Manual-Topup SCB'
    ,2=>'Manual-Topup TrueWallet'
    ,3=>'Manual-Topup KBANK' 
    ,150=>'VizPay <= Manual-Topup'
  ]; 

  public  function toOrdinal($name) {//ConfigData::toOrdinal("Read");
    return array_search($name, self::$Dataenum);
  } 
  public  function toString($ordinal) {//ConfigData::toString(1);
      return self::$Dataenum[$ordinal];
  }  
  public  function Getgroup_manual($name) { 
    switch ($name) {
      case 'bank': return $this->admin_manual_depositby_bank; break;
      case 'bank_orther': return $this->admin_manual_depositby_bank_orther; break;
      case 'pro': return $this->admin_manual_depositby_pro; break;
      default: return[];  break;
    }
  }   

  public  function toOrdinal_source($name) {//ConfigData::toOrdinal("Read");
        $d=array_search($name, $this->source_ref);
    return ($d?$d:'');
  } 
  public  function toString_source($ordinal) {//ConfigData::toString(1);
      return (isset($this->source_ref[$ordinal])?$this->source_ref[$ordinal]:'');
  }   
 }