<?php  
$DB_Host        =DBHost;
$DB_Username    = DBUser;
$DB_Password    = DBPassword;
$DB_Name        = DBName;
$DB_Charset     = 'utf8';
$DB_port        = DBPort; 
$mysqli = new mysqli($DB_Host, $DB_Username, $DB_Password, $DB_Name,$DB_port); 
mysqli_set_charset($mysqli, $DB_Charset); 
mysqli_options($mysqli, MYSQLI_INIT_COMMAND, "SET time_zone = 'Asia/Bangkok'"); 
if ($mysqli->connect_errno) { 
    printf("Connect failed: %s\n", $mysqli->connect_error); 
    exit();
} else {
    //echo 'DB Connected!!!!<BR>';
}
