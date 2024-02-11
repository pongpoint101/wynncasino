<?php

if (session_status() == PHP_SESSION_NONE) {

    session_start();
}

session_destroy();

// header('location:/');
header('refresh:0;url=https://wynncasino888.asia');
