<?php  
require_once __DIR__ . '/RateLimiter.php'; 
if(REDIS_SERVER){
try {
     $redis = new \Redis(); 
     $redis->connect(REDIS_IP, REDIS_PORT,2.5);
	//   $redis->setOption(Redis::OPT_PREFIX, REDIS_PREFIX);
     $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);	  // Don't serialize data
     $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);	  // Use built-in serialize/unserialize 
   //   $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY); // Use igBinary serialize/unserialize �������
   //   $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);// ������ 
	  $redis->select(REDIS_DB);
	  $cache =$redis;   
 }  
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
 } 
}
function is_pagelimit($page,$request_num=5,$request_time=10){
   global $cache;
   if (isset($cache) && ratelimiteip) { // ติดต่อ redis ได้
    $limiter = new RateLimiter($cache, 'M_', true);
    $limiterns = $limiter->hit($page.':' . $_SERVER['REMOTE_ADDR'], $request_num, $request_time); // เรียกได้ 5 ครั้งใน 10 วินาที
     if ($limiterns['overlimit']) {return false;}
   } 
  return true;
}
function SetCachedata($key,$data,$ttl=0)
{ 
    global $cache;
    if (isset($cache) && REDIS_SERVER) { // ติดต่อ redis ได้
       if($ttl>0){ 
            return $cache->setEx($key, $ttl,$data);
        }else{
            return $cache->set($key,$data);
       } 
    }
    return false;
}
function DelteCache($key)
{ 
    global $cache;
    if (isset($cache)) { // ติดต่อ redis ได้
        return $cache->del($key);
    }
    return false;
}
function GetCachedata($key)
{
    global $cache;
    if (isset($cache) && REDIS_SERVER) { // ติดต่อ redis ได้ 
       return $cache->get($key);
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