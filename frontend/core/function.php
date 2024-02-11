<?php
$dayTH = ['อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์'];
$monthTH = [null,'มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];
$monthTH_brev = [null,'ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];

function str_check($str)
{ 
    $str = str_replace("_", "", $str);

    $str = str_replace("%", "", $str);

    $str = str_replace("=", "", $str);

    $str = str_replace("<", "", $str);

    $str = str_replace(">", "", $str);

    $str = str_replace("\'", "", $str);

    $str = str_replace("-", "", $str);

    $str = str_replace(";", "", $str);

    $str = str_replace("select", "", $str);

    $str = str_replace("update", "", $str);

    $str = str_replace("delete", "", $str);

    $str = str_replace("insert", "", $str);

    $str = str_replace("union", "", $str);

    $str = str_replace("--", "", $str);

    $str = str_replace("$", "", $str);

    $str = str_replace("#", "", $str); 
    return $str;
}
function misc_parsestring($text, $allowchr = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    if (empty($allowchr)) {
        $allowchr = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    if (empty($text)) {
        return false;
    }
    $size = strlen($text);
    for ($i = 0; $i < $size; $i++) {
        $tmpchr = substr($text, $i, 1);
        if (strpos($allowchr, $tmpchr) === false) {
            return false;
        }
    }
    return true;
}
function get_bank_logo($bank_code)
{
    $return_val = array();

    if ($bank_code == "014") {
        $return_val['bank'] = "scb";
        $return_val['logo'] = "scb-logo.png";
        $return_val['css'] = "thbanks-scb";
    }
    if ($bank_code == "004") {
        $return_val['bank'] = "kbank";
        $return_val['logo'] = "kbank-logo.png";
        $return_val['css'] = "thbanks-kbank";
    }
    if ($bank_code == "025") {
        $return_val['bank'] = "bay";
        $return_val['logo'] = "bay-logo.png";
        $return_val['css'] = "thbanks-bay";
    }
    if ($bank_code == "999") {
        $return_val['bank'] = "truewallet";
        $return_val['logo'] = "truewallet.png";
        $return_val['css'] = "truewallet";
    }
    if ($bank_code == "006") {
        $return_val['bank'] = "ktb";
        $return_val['logo'] = "ktb-logo.png";
        $return_val['css'] = "thbanks-ktb";
    }
    if ($bank_code == "011") {
        $return_val['bank'] = "tmb";
        $return_val['logo'] = "tmb-logo.png";
        $return_val['css'] = "thbanks-tmb";
    }
    if ($bank_code == "017") {
        $return_val['bank'] = "citi";
        $return_val['logo'] = "citi-logo.png";
        $return_val['css'] = "thbanks-citi";
    }
    if ($bank_code == "020") {
        $return_val['bank'] = "scbt";
        $return_val['logo'] = "scbt-logo.png";
        $return_val['css'] = "thbanks-scbt";
    }
    if ($bank_code == "022") {
        $return_val['bank'] = "cimp";
        $return_val['logo'] = "cimp-logo.png";
        $return_val['css'] = "thbanks-cimp";
    }
    if ($bank_code == "024") {
        $return_val['bank'] = "uob";
        $return_val['logo'] = "uob-logo.png";
        $return_val['css'] = "thbanks-uob";
    }
    if ($bank_code == "025") {
        $return_val['bank'] = "bay";
        $return_val['logo'] = "bay-logo.png";
        $return_val['css'] = "thbanks-bay";
    }
    if ($bank_code == "030") {
        $return_val['bank'] = "gsb";
        $return_val['logo'] = "gsb-logo.png";
        $return_val['css'] = "thbanks-gsb";
    }
    if ($bank_code == "031") {
        $return_val['bank'] = "hsbc";
        $return_val['logo'] = "hsbc-logo.png";
        $return_val['css'] = "thbanks-hsbc";
    }
    if ($bank_code == "033") {
        $return_val['bank'] = "ghb";
        $return_val['logo'] = "ghb-logo.png";
        $return_val['css'] = "thbanks-ghb";
    }
    if ($bank_code == "034") {
        $return_val['bank'] = "baac";
        $return_val['logo'] = "baac-logo.png";
        $return_val['css'] = "thbanks-baac";
    }
    if ($bank_code == "065") {
        $return_val['bank'] = "tbank";
        $return_val['logo'] = "tbank-logo.png";
        $return_val['css'] = "thbanks-tbank";
    }
    if ($bank_code == "066") {
        $return_val['bank'] = "ibank";
        $return_val['logo'] = "ibank-logo.png";
        $return_val['css'] = "thbanks-ibank";
    }
    if ($bank_code == "067") {
        $return_val['bank'] = "tisco";
        $return_val['logo'] = "tisco-logo.png";
        $return_val['css'] = "thbanks-tisco";
    }
    if ($bank_code == "069") {
        $return_val['bank'] = "kk";
        $return_val['logo'] = "kk-logo.png";
        $return_val['css'] = "thbanks-kk";
    }
    if ($bank_code == "070") {
        $return_val['bank'] = "icbc";
        $return_val['logo'] = "icbc-logo.png";
        $return_val['css'] = "thbanks-icbc";
    }
    if ($bank_code == "071") {
        $return_val['bank'] = "tcrb";
        $return_val['logo'] = "tcrb-logo.png";
        $return_val['css'] = "thbanks-tcrb";
    }
    if ($bank_code == "073") {
        $return_val['bank'] = "lhb";
        $return_val['logo'] = "lhb-logo.png";
        $return_val['css'] = "thbanks-lhb";
    }

    return $return_val;
} 
function bank_type_rename($str_bnk)
{ 
    $str_bnk = str_replace("01", "ไทยพาณิชย์", $str_bnk);
    $str_bnk = str_replace("02", "กรุงเทพ", $str_bnk);
    $str_bnk = str_replace("03", "กสิกรไทย", $str_bnk);
    $str_bnk = str_replace("04", "กรุงไทย", $str_bnk);
    $str_bnk = str_replace("05", "ธกส.", $str_bnk);
    $str_bnk = str_replace("06", "ทหารไทย", $str_bnk);
    $str_bnk = str_replace("07", "ซีไอเอ็มบี ไทย", $str_bnk);
    $str_bnk = str_replace("08", "ยูโอบี", $str_bnk);
    $str_bnk = str_replace("09", "กรุงศรีอยุธยา", $str_bnk);
    $str_bnk = str_replace("10", "ออมสิน", $str_bnk);
    $str_bnk = str_replace("11", "แลนแอนด์เฮาส์", $str_bnk);
    $str_bnk = str_replace("12", "ธนชาต", $str_bnk);
    $str_bnk = str_replace("13", "ทิสโก้", $str_bnk);
    $str_bnk = str_replace("14", "เกียรตินาคิน", $str_bnk);

    return $str_bnk;
}

function BankCode2Name($str_bnk)
{  
    $str_bnk = str_replace("014", "ไทยพาณิชย์", $str_bnk);
    $str_bnk = str_replace("002", "กรุงเทพ", $str_bnk);
    $str_bnk = str_replace("004", "กสิกรไทย", $str_bnk);
    $str_bnk = str_replace("006", "กรุงไทย", $str_bnk);
    $str_bnk = str_replace("034", "ธกส.", $str_bnk);
    $str_bnk = str_replace("018", "ซูมิโตโม มิตซุย", $str_bnk);
    $str_bnk = str_replace("011", "ทหารไทย", $str_bnk);
    $str_bnk = str_replace("020", "สแตนดาร์ดชาร์เตอร์ด", $str_bnk);
    $str_bnk = str_replace("022", "ซีไอเอ็มบี ไทย", $str_bnk);
    $str_bnk = str_replace("024", "ยูโอบี", $str_bnk);
    $str_bnk = str_replace("025", "กรุงศรีอยุธยา", $str_bnk);
    $str_bnk = str_replace("030", "ออมสิน", $str_bnk);
    $str_bnk = str_replace("039", "มิซูโฮ", $str_bnk);
    $str_bnk = str_replace("031", "HSBC", $str_bnk);
    $str_bnk = str_replace("071", "ไทยเครดิต", $str_bnk);
    $str_bnk = str_replace("073", "แลนแอนด์เฮาส์", $str_bnk);
    $str_bnk = str_replace("065", "ธนชาต", $str_bnk);
    $str_bnk = str_replace("067", "ทิสโก้", $str_bnk);
    $str_bnk = str_replace("069", "เกียรตินาคิน", $str_bnk);
    $str_bnk = str_replace("066", "อิสลามแห่งประเทศไทย", $str_bnk);
    $str_bnk = str_replace("033", "อาคารสงเคราะห์", $str_bnk);
    $str_bnk = str_replace("017", "ซิตี้แบงค์", $str_bnk);
    $str_bnk = str_replace("070", "ไอซีบีซี", $str_bnk);

    return $str_bnk;
}

function BankName2Code($str_bnk)
{ 
    $str_bnk = str_replace("ไทยพาณิชย์", "014", $str_bnk);
    $str_bnk = str_replace("กรุงเทพ", "002", $str_bnk);
    $str_bnk = str_replace("กสิกรไทย", "004", $str_bnk);
    $str_bnk = str_replace("กรุงไทย", "006", $str_bnk);
    $str_bnk = str_replace("ธกส.", "034", $str_bnk);
    $str_bnk = str_replace("ซูมิโตโม มิตซุย", "018", $str_bnk);
    $str_bnk = str_replace("ทหารไทย", "011", $str_bnk);
    $str_bnk = str_replace("สแตนดาร์ดชาร์เตอร์ด", "020", $str_bnk);
    $str_bnk = str_replace("ซีไอเอ็มบี ไทย", "022", $str_bnk);
    $str_bnk = str_replace("ยูโอบี", "024", $str_bnk);
    $str_bnk = str_replace("กรุงศรีอยุธยา", "025", $str_bnk);
    $str_bnk = str_replace("ออมสิน", "030", $str_bnk);
    $str_bnk = str_replace("มิซูโฮ", "039", $str_bnk);
    $str_bnk = str_replace("HSBC", "031", $str_bnk);
    $str_bnk = str_replace("ไทยเครดิต", "071", $str_bnk);
    $str_bnk = str_replace("แลนแอนด์เฮาส์", "073", $str_bnk);
    $str_bnk = str_replace("ธนชาต", "065", $str_bnk);
    $str_bnk = str_replace("ทิสโก้", "067", $str_bnk);
    $str_bnk = str_replace("เกียรตินาคิน", "069", $str_bnk);
    $str_bnk = str_replace("อิสลามแห่งประเทศไทย", "066", $str_bnk);
    $str_bnk = str_replace("อาคารสงเคราะห์", "033", $str_bnk);
    $str_bnk = str_replace("ซิตี้แบงค์", "017", $str_bnk);
    $str_bnk = str_replace("ไอซีบีซี", "070", $str_bnk);

    return $str_bnk;
}

function BankShortName2Code($str_bnk)
{ 
    $str_bnk = str_replace("SCB", "014", $str_bnk);   // ไทยพานิชย์
    $str_bnk = str_replace("BBLA", "002", $str_bnk);  // กรุงเทพ
    $str_bnk = str_replace("BBL", "002", $str_bnk);   // กรุงเทพ
    $str_bnk = str_replace("KBANK", "004", $str_bnk); // กสิกร
    $str_bnk = str_replace("KBNK", "004", $str_bnk);  // กสิกร
    $str_bnk = str_replace("KTBA", "006", $str_bnk);  // กรุงไทย
    $str_bnk = str_replace("KTB", "006", $str_bnk);   // กรุงไทย
    $str_bnk = str_replace("BAAC", "034", $str_bnk);  // ธกส.
    $str_bnk = str_replace("SEC", "018", $str_bnk);   // 
    $str_bnk = str_replace("TMBA", "011", $str_bnk);  // ทหารไทย
    $str_bnk = str_replace("TMB", "011", $str_bnk);   // ทหารไทย
    $str_bnk = str_replace("CIMB", "022", $str_bnk);  // ซีไอเอ็มบี
    $str_bnk = str_replace("UOBT", "024", $str_bnk);  // UOB
    $str_bnk = str_replace("UOB", "024", $str_bnk);   // UOB
    $str_bnk = str_replace("BAYA", "025", $str_bnk);  // กรุงศรีฯ
    $str_bnk = str_replace("BAY", "025", $str_bnk);   // กรุงศรีฯ
    $str_bnk = str_replace("GSBA", "030", $str_bnk);  // ออมสิน
    $str_bnk = str_replace("GSB", "030", $str_bnk);   // ออมสิน
    $str_bnk = str_replace("HSBC", "031", $str_bnk);  // HSCB
    $str_bnk = str_replace("TCD", "071", $str_bnk);   //
    $str_bnk = str_replace("LHBA", "073", $str_bnk);  // Land & House
    $str_bnk = str_replace("LH", "073", $str_bnk);  // Land & House
    $str_bnk = str_replace("TBANK", "065", $str_bnk); // ธนชาติ
    $str_bnk = str_replace("TBNK", "065", $str_bnk);  // ธนชาติ
    $str_bnk = str_replace("TISCO", "067", $str_bnk); // ทิสโก้
    $str_bnk = str_replace("KKP", "069", $str_bnk);   // เกียรตินาคิน
    $str_bnk = str_replace("ISBT", "066", $str_bnk);  // ธ.อิสลาม
    $str_bnk = str_replace("GHB", "033", $str_bnk);   // ธ.อาคารสงเคราะห์
    $str_bnk = str_replace("CITI", "017", $str_bnk);  // ซิตี้แบงค์
    $str_bnk = str_replace("ICBCT", "070", $str_bnk); // ICBCT

    return $str_bnk;
}  
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

function thai_date_and_time($time){   // 19 ธันวาคม 2556 เวลา 10:10:43
    global $dayTH,$monthTH;   
    $thai_date_return = date("j",$time);   
    $thai_date_return.=" ".$monthTH[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    $thai_date_return.= " เวลา ".date("H:i:s",$time);
    return $thai_date_return;   
} 
function thai_time($time){   // 10:10:43
    $thai_date_return= date("H:i:s",$time);
    return $thai_date_return;   
} 
function thai_date_and_time_short($time){   // 19  ธ.ค. 2556 10:10:4
    global $dayTH,$monthTH_brev;   
    $thai_date_return = date("j",$time);   
    $thai_date_return.=" ".$monthTH_brev[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    $thai_date_return.= " ".date("H:i:s",$time);
    return $thai_date_return;   
} 
function thai_date_short($time){   // 19  ธ.ค. 2556a
    global $dayTH,$monthTH_brev;   
    $thai_date_return = date("j",$time);   
    $thai_date_return.=" ".$monthTH_brev[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    return $thai_date_return;   
} 
function thai_date_fullmonth($time,$without_date=false){   // 19 ธันวาคม 2556
    global $dayTH,$monthTH;   
    $thai_date_return = date("j",$time);   
    if ($without_date) {$thai_date_return =''; }
    $thai_date_return.=" ".$monthTH[date("n",$time)];   
    $thai_date_return.= " ".(date("Y",$time)+543);   
    return $thai_date_return;   
} 
function thai_date_short_number($time){   // 19-12-56
    global $dayTH,$monthTH;   
    $thai_date_return = date("d",$time);   
    $thai_date_return.="-".date("m",$time);   
    $thai_date_return.= "-".substr((date("Y",$time)+543),-2);   
    return $thai_date_return;   
}  
if ( ! function_exists('is_php')){
	/**
	 * Determines if the current version of PHP is equal to or greater than the supplied value
	 *
	 * @param	string
	 * @return	bool	TRUE if the current version is $version or higher
	 */
	function is_php($version)
	{
		static $_is_php;
		$version = (string) $version;

		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = version_compare(PHP_VERSION, $version, '>=');
		}

		return $_is_php[$version];
	}
}
function echojs($title,$msg,$style=1,$type="success"){
    if ($style==1) {
      ?>
        <script>
         Swal.fire("<?=$title?>", "<?=$msg?>","<?=$type?>"); 
        </script>
      <?php  
   }else if ($style==2) {
    ?>
    <script> 
      Swal.fire({
           title: "<?=$title?>",
           html: "<?=$msg?>",
           icon: "<?=$type?>",
       }); 
     </script>
   <?php
   }else if($style==3){
       ?>
       <script> 
       Swal.fire({
                    icon:"<?=$type?>",
                    title:"<?=$title?>",
                    html: "<?=$msg?>",
                    showConfirmButton: false,
                    timer: 3000,
                });
        </script>       
       <?php
   }   
   exit();
  }
  function getDeviceInfo(){  
  $deviceInfo['ip_address'] = Detect::ip();
  $deviceInfo['host_name'] = Detect::ipHostname();
  $deviceInfo['ip_org'] = Detect::ipOrg();
  $deviceInfo['device_type'] = Detect::deviceType();
  $deviceInfo['country'] = Detect::ipCountry();
  $deviceInfo['os'] = Detect::os();
  $deviceInfo['browser'] = Detect::browser();
  $deviceInfo['brand'] = Detect::brand();

  return json_encode($deviceInfo);
}
function getDateTime($format){
    $date_time_now = new DateTime("NOW");
    return $date_time_now->format($format);
}
function DESEncrypt($key, $str, $iv = 0)
{
    // $this->key = $key;
    if ($iv == 0) {
        $iv = $key;
    }

    $str = base64_encode(openssl_encrypt($str, 'DES-CBC', $key, OPENSSL_RAW_DATA, $iv));
    $urlemstr = urlencode($str);
    return $urlemstr;
}

function DESDecrypt($key, $str, $iv = 0)
{
    if ($iv == 0) {
        $iv = $key;
    }

    $str = openssl_decrypt(base64_decode($str), 'DES-CBC', $key, OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $iv);
    return rtrim($str, "\x01..\x1F");
    // return $str;
}

function BuildMD5($str, $md5key, $Time, $SecretKey)
{
    $PreMD5Str = $str . $md5key . $Time . $SecretKey;
    $OutMD5 = md5($PreMD5Str);
    return $OutMD5;
}

function XML2Array($xml_string)
{
    $ob = simplexml_load_string($xml_string);
    $json = json_encode($ob);
    $arr = json_decode($json, true);
    return $arr;
}

function Array2XML($data, $root)
{
    $xml_data = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?>' . $root);

    foreach ($data as $key => $value) {
        if (is_array($value)) {
            if (is_numeric($key)) {
                $key = 'item' . $key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            Array2XML($value, $subnode);
        } else {
            $xml_data->addChild("$key", htmlspecialchars("$value"));
        }
    }
    return $xml_data;
}
function isMobile(){
    $mobDetect = new Mobile_Detect;
    if ($mobDetect->isMobileDevice()) {
        return 1;
    } else {
        return 0;
    }
}
function GUID(){
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
function validateDate($date, $format = 'Y-m-d') {
  try {
    $dateTime = DateTime::createFromFormat($format, $date);
    return $dateTime && $dateTime->format($format) === $date;
  } catch (\Throwable $th) {
    //throw $th;
 }
 return false;
}
?>