<?php

use App\Http\Controllers\WalletController;
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

Route::get('wallets/edit',  [WalletController::class, 'edit'])->name('wallets.edit');
Route::post('wallets/update',  [WalletController::class, 'update'])->name('wallets.update');
Route::get('wallets/export',  [WalletController::class, 'export'])->name('wallets.export');
