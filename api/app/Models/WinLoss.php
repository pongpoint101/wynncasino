<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WinLoss extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'member_provider_wl_monthly';
    protected $primaryKey = 'id';
}
