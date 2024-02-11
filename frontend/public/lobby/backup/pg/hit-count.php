<?php
require '../../bootstart.php';  
require_once ROOT.'/core/security.php';    

$GameID = $_POST['game_id']; 
$conn->set('hit_count', 'hit_count=hit_count+1', FALSE);
$conn->where('id', $GameID);
$conn->update('pg_list_games');