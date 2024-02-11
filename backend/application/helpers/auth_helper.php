<?php
/**
 * Auth Helper
 *
 * @author       Firoz Ahmad Likhon <likh.deshi@gmail.com>
 * @purpose      Auth Helper
 */

if(! function_exists("check")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function check()
    {
        $auth = new Auth();
        return $auth->loginStatus();
    }
}

if(! function_exists("can")) {

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @return bool
     */
    function can($permissions='')
    {
        $auth = new Auth();
        return $auth->can($permissions);
    }
}
if(! function_exists("canroute")) {

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @return bool
     */
    function canroute($permissions='')
    {
        $auth = new Auth();
        return $auth->canroute($permissions);
    }
}
if(! function_exists("hasRole")) {

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @return bool
     */
    function hasRole($roles)
    {
        $auth = new Auth();
        return $auth->hasRole($roles);
    }
}
if(! function_exists("BankCode2ShortName")) {
    function BankCode2ShortName($str_bnk)
    { 
        $str_bnk = str_replace("014", "SCB", $str_bnk);   // ไทยพานิชย์ 
        $str_bnk = str_replace("002", "BBLA", $str_bnk);  // กรุงเทพ
        $str_bnk = str_replace("002", "BBL", $str_bnk);   // กรุงเทพ BBL
        $str_bnk = str_replace("004", "KBANK", $str_bnk); // กสิกร KBANK
        $str_bnk = str_replace("004", "KBNK", $str_bnk);  // กสิกร KBNK
        $str_bnk = str_replace("006", "KTBA", $str_bnk);  // กรุงไทย KTBA
        $str_bnk = str_replace("006", "KTB", $str_bnk);   // กรุงไทย KTB
        $str_bnk = str_replace("034", "BAAC", $str_bnk);  // ธกส. SEC
        $str_bnk = str_replace("018", "SEC", $str_bnk);   // 
        $str_bnk = str_replace("011", "TMBA", $str_bnk);  // ทหารไทย
        $str_bnk = str_replace("011", "TMB", $str_bnk);   // ทหารไทย
        $str_bnk = str_replace("022", "CIMB", $str_bnk);  // ซีไอเอ็มบี
        $str_bnk = str_replace("024", "UOBT", $str_bnk);  // UOB
        $str_bnk = str_replace("024", "UOB", $str_bnk);   // UOB
        $str_bnk = str_replace("025", "BAYA", $str_bnk);  // กรุงศรีฯ
        $str_bnk = str_replace("025", "BAY", $str_bnk);   // กรุงศรีฯ
        $str_bnk = str_replace("030", "GSBA", $str_bnk);  // ออมสิน
        $str_bnk = str_replace("030", "GSB", $str_bnk);   // ออมสิน
        $str_bnk = str_replace("031", "HSBC", $str_bnk);  // HSCB
        $str_bnk = str_replace("071", "TCD", $str_bnk);   //
        $str_bnk = str_replace("073", "LHBA", $str_bnk);  // Land & House
        $str_bnk = str_replace("073", "LH", $str_bnk);  // Land & House
        $str_bnk = str_replace("065", "TBANK", $str_bnk); // ธนชาติ
        $str_bnk = str_replace("065", "TBNK", $str_bnk);  // ธนชาติ
        $str_bnk = str_replace("067", "TISCO", $str_bnk); // ทิสโก้
        $str_bnk = str_replace("069", "KKP", $str_bnk);   // เกียรตินาคิน
        $str_bnk = str_replace("066", "ISBT", $str_bnk);  // ธ.อิสลาม
        $str_bnk = str_replace("033", "GHB", $str_bnk);   // ธ.อาคารสงเคราะห์
        $str_bnk = str_replace("017", "CITI", $str_bnk);  // ซิตี้แบงค์
        $str_bnk = str_replace("070", "ICBCT", $str_bnk); // ICBCT 
        return $str_bnk;
    } 
}
if(! function_exists("is_valid_domain_name")) {
    function is_valid_domain_name($domain_name)
    {
              $domain_len = strlen($domain_name);
            if ($domain_len < 3 OR $domain_len > 253)
                return FALSE;
    
            //getting rid of HTTP/S just in case was passed.
            if(stripos($domain_name, 'http://') === 0)
                $domain_name = substr($domain_name, 7); 
            elseif(stripos($domain_name, 'https://') === 0)
                $domain_name = substr($domain_name, 8);
            
            //we dont need the www either                 
            if(stripos($domain_name, 'www.') === 0)
                $domain_name = substr($domain_name, 4); 
    
            //Checking for a '.' at least, not in the beginning nor end, since http://.abcd. is reported valid
            if(strpos($domain_name, '.') === FALSE OR $domain_name[strlen($domain_name)-1]=='.' OR $domain_name[0]=='.')
                return FALSE;
                     
            //now we use the FILTER_VALIDATE_URL, concatenating http so we can use it, and return BOOL
            return (filter_var ('http://' . $domain_name, FILTER_VALIDATE_URL)===FALSE)? FALSE:TRUE;
    
    } 
}