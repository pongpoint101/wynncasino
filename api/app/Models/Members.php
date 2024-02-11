<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'members';
    protected $primaryKey = 'id';
    function hasOneWallet()
    {
        return $this->hasOne('App\Models\Wallet', 'member_no', 'id');
    }
}
