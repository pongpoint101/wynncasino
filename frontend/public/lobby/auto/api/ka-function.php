<?php
function getSignature($system_id, $version, array $args, $system_key)
{
  $md5 = array();
  $md5[] = $system_id;
  $md5[] = $version;
  foreach ($args as $required_arg) {
    $arg = $required_arg;
    if (is_array($arg)) {
      if (count($arg)) {
        $recursive_arg = '';
        array_walk_recursive($arg, function ($item) use (&$recursive_arg) {
          if (!is_array($item)) {
            $recursive_arg .= ($item . ':');
          }
        });
        $md5[] = substr($recursive_arg, 0, strlen($recursive_arg) - 1); // get rid of last colon-sign
      } else {
        $md5[] = '';
      }
    } else {
      $md5[] = $arg;
    }
  }
  $md5[] = $system_key;
  $md5_str = implode('*', $md5);
  $md5 = md5($md5_str);
  return $md5;
}

function hitsStatistic($gameID, $gameName)
{
  global $db;

  $sql = "UPDATE ka_hits_statistic SET hits_count=hits_count+1 WHERE game_id=?";
  $res = $db->query($sql, $gameID);
  if ($res->affectedRows() <= 0) {
    $sql = "INSERT INTO ka_hits_statistic (game_id,game_name,hits_count) VALUES (?,?,?)";
    $db->query($sql, $gameID, $gameName, 1);
  }
}
