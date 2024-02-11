<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\EmployController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/createuser', [AuthController::class, 'createUser']);
Route::post('/login', [AuthController::class, 'loginApi']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/players/player_withdraw',[WithdrawController::class,'playerWithdraw']);
    Route::get('/players/player_deposit',[DepositController::class,'playerDeposit']);
    Route::get('/players/player_deposit/rollback',[DepositController::class,'playerDepositRollback']);
    Route::get('/players/pro_deposit',[DepositController::class,'proDeposit']);
    Route::get('/players/list',[PlayerController::class,'list']);
    Route::get('/players/history_bet',[PlayerController::class,'history_bet']);
    Route::get('/players/partner_list',[PlayerController::class,'partner_list']);

    Route::get('/players/summary',[SummaryController::class,'summary']);
    Route::get('/employee/list',[EmployController::class,'list']);
    Route::get('/employee',[EmployController::class,'checkToken']);
    Route::post('/employee',[EmployController::class,'postCheckToken']);
    Route::post('/players/update_partner',[PlayerController::class,'updatePartner']);
    Route::get('/players/log/list',[PlayerController::class,'playerLog']);
    Route::get('/players/games',[PlayerController::class,'games']);
    Route::get('/players/platform',[PlayerController::class,'provider']);

});


