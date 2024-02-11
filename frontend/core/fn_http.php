<?php
function getIP($ipstring=false)
{
    $clientIP = '0.0.0.0';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $clientIP = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        # when behind cloudflare
        $clientIP = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $clientIP = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $clientIP = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
        $clientIP = $_SERVER['HTTP_FORWARDED'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $clientIP = $_SERVER['REMOTE_ADDR'];
    }
    // Strip any secondary IP etc from the IP address
    $listIP = explode(',', $clientIP);
    if (isset($listIP[1])) {
        $clientIP = $listIP[0];
    }
    // if (strpos($clientIP, ',') > 0) {
    //     $clientIP = substr($clientIP, 0, strpos($clientIP, ','));
    // }
    if($ipstring){return str_replace(":", "_", $clientIP);}
    return $clientIP;   
}

function get_domain($url = null)
{
    $pieces = '';
    if (isset($url)) {
        $pieces = parse_url((isset($url) ? $url : $_SERVER['HTTP_HOST']));
    } else {
        $sevssl = 'http://';
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
            $sevssl = 'https://';
        }
        $pieces = parse_url((isset($url) ? $url : $sevssl . $_SERVER['HTTP_HOST']));
    }
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return FALSE;
}
function GetFullDomain()
{
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        return "http://" . $_SERVER['HTTP_HOST'] . '/WYNNCASINO/frontend/public/';
    }
    $protocol = "https://"; //(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'];
    return $protocol . $domainName;
}
