<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameList extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $table = 'autosystem_game_list';
    protected $primaryKey = 'id';
}
