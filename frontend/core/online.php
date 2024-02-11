<?php
defined('MAX_IDLE_TIME') or define('MAX_IDLE_TIME', 3);
class online
{
  public static function who()
  {
    $path = session_save_path();
    if (trim($path) == "") {
      return FALSE;
    }
    $d = dir($path);
    $i = 0;
    while (false !== ($entry = $d->read())) {
      if ($entry != "." and $entry != "..") {
        if (time() - filemtime($path . "/$entry") < MAX_IDLE_TIME * 60) {
          $i++;
        }
      }
    }
    $d->close();
    return $i;
  }
}
