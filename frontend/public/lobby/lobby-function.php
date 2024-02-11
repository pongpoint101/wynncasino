<?php
function SkipSlot($member_promo)
{
  $skip_slot = 0;

  switch (+$member_promo) {
    case 1: // Free50
    case 2: // Pro 100%
    case 3: // Pro 10%
    case 4: // Happy Time (Deposit 400, get more 100)
    case 9: // Welcome back 50%
    case 10: // Merry X'mas
    // case 11: // Happy New Year
    case 2425: // Merry X'mas
    case 2023: // Happy New Year
    case 16: // Free 60
      $skip_slot = 1;
      break;

    default:
      $skip_slot = 0;
      break;
  }

  if ($skip_slot === 1) {
    return true;
  } else {
    return false;
  }
}

function SkipCasino($member_promo)
{
  $skip_casino = 0;
  switch ($member_promo) {
    case 31: // โปรแรกเข้า 60% - เฉพาะ Casino
    case 32: // โปรฝากแรกของวัน 20% - เฉพาะ Casino
    case 33: // โปรฝาก 100 บวก 100 - เฉพาะ Casino
      $skip_casino = 1;
      break;

    default:
      $skip_casino = 0;
      break;
  }

  if ($skip_casino === 1) {
    return true;
  } else {
    return false;
  }
}
