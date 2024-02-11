<?php
function GetDatapro($pro_id){
  switch ($pro_id) {
      case 'deposit_freq':
        return [1 => ['reward'=>0,'status'=>0,'remark'=>''], 2 =>['reward'=>0,'status'=>0,'remark'=>'']
        , 3 =>['reward'=>200,'status'=>1,'remark'=>'FQ200']
        , 4 => ['reward'=>0,'status'=>0,'remark'=>''], 5 => ['reward'=>0,'status'=>0,'remark'=>'']
        , 6 => ['reward'=>0,'status'=>0,'remark'=>'']
        , 7 =>['reward'=>500,'status'=>1,'remark'=>'FQ500']
        , 8 => ['reward'=>0,'status'=>0,'remark'=>''], 9 => ['reward'=>0,'status'=>0,'remark'=>'']
        , 10 =>['reward'=>1000,'status'=>1,'remark'=>'FQ1000']
        , 11 => ['reward'=>0,'status'=>0,'remark'=>''], 12 =>['reward'=>0,'status'=>0,'remark'=>'']
        , 13 => ['reward'=>0,'status'=>0,'remark'=>''], 14 => ['reward'=>0,'status'=>0,'remark'=>'']
        , 15 => ['reward'=>2000,'status'=>1,'remark'=>'FQ2000']];
          break; 
      default: break;
  }
}
?>