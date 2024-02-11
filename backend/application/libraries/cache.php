<?php   
require_once __DIR__ . '/RateLimiter.php'; 
if(REDIS_SERVER){
try {
     $cache_server = new \Redis(); 
     $cache_server->connect(REDIS_IP, REDIS_PORT,2.5);
	//   $cache_server->setOption(Redis::OPT_PREFIX, REDIS_PREFIX);
     $cache_server->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);	  // Don't serialize data
     $cache_server->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);	  // Use built-in serialize/unserialize 
   //   $cache_server->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY); // Use igBinary serialize/unserialize �������
   //   $cache_server->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);// ������ 
	  $cache_server->select(REDIS_DB);  
 }  
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
 } 
}
function is_pagelimit($page,$request_num=5,$request_time=10){
   global $cache_server;
   if (isset($cache_server) && ratelimiteip) { // ติดต่อ redis ได้
    $limiter = new RateLimiter($cache_server, 'M_', true);
    $limiterns = $limiter->hit($page.':' . $_SERVER['REMOTE_ADDR'], $request_num, $request_time); // เรียกได้ 5 ครั้งใน 10 วินาที
     if ($limiterns['overlimit']) {return false;}
   } 
  return true;
}
function SetCachedata($key,$data,$ttl=0)
{ 
    global $cache_server;
    if (isset($cache_server) && REDIS_SERVER) { // ติดต่อ redis ได้
       if($ttl>0){ 
            return $cache_server->setEx($key, $ttl,$data);
        }else{
            return $cache_server->set($key,$data);
       } 
    }
    return false;
}
function DelteCache($key)
{ 
    global $cache_server;
    if (isset($cache_server)) { // ติดต่อ redis ได้
        return $cache_server->del($key);
    }
    return false;
}
function GetCachedata($key)
{
    global $cache_server;
    if (isset($cache_server) && REDIS_SERVER) { // ติดต่อ redis ได้ 
       return $cache_server->get($key);
    }
    return false;
}
function SetMA($provider){
    return SetCachedata('ALL:MA'.$provider,'ma');
}
function GetMA($provider){
    return GetCachedata('ALL:MA'.$provider);
}

?>