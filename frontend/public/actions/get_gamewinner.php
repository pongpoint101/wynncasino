<?php
require '../bootstart.php';
function random_username($string)
{
    $nrRand = rand(100000, 9999999);

    $string = strtolower($string);
    return preg_replace('/^(.+)\s(.{2}).+$/', '$1$2', $string, 1) . $nrRand;
}
$key = 'MB:gamewinner_list';
$Cachedata = GetCachedata($key);
if ($Cachedata) {
    $data = json_decode($Cachedata, true);
} else {
    $game_winners = [];
    $gamewin = [
        ['image' => 'THUNDERSTRUCK2.jpg', 'game_name' => 'THUNDERSTRUCK 2', 'reward_num' => rand(100, 2000)],
        ['image' => 'WILDFIREWORKS.jpg', 'game_name' => 'WILD FIREWORKS', 'reward_num' => rand(100, 2000)],
        ['image' => 'COCKTAILNIGHTS.jpg', 'game_name' => 'COCKTAIL NIGHTS', 'reward_num' => rand(100, 2000)],
        ['image' => 'MAHJONGWAYS2.jpg', 'game_name' => 'MAHJONG WAYS 2', 'reward_num' => rand(100, 2000)],
    ];
    for ($i = 0; $i < 10; $i++) {
        $usercode = strtoupper(random_username("WYNN"));
        $str_length = strlen($usercode);
        $usercode = substr($usercode, 0, 7) . str_repeat('*', $str_length - 8) . substr($usercode, $str_length - 3, $str_length - 2);
        $t = $gamewin[rand(0, 3)];
        $t['username'] = $usercode;
        $game_winners[]['gamewin'] = $t;
    }
    $data['winners'] = $game_winners;
    SetCachedata($key, json_encode($data, JSON_PRESERVE_ZERO_FRACTION), 60 * 60);
}
echo json_encode(['data' => $data]);
exit();
