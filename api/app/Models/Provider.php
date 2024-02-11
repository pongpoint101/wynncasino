<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'lobby_control';
    protected $primaryKey = 'id';
}
