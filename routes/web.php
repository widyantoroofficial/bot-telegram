<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/webhook', [App\Http\Controllers\Telegram\TelegramController::class, 'handle']);
Route::post('/example', [App\Http\Controllers\Telegram\TelegramController::class, 'example']);
//webhook
Route::get('/set-webhook', [App\Http\Controllers\Telegram\WebhookController::class, 'setWebhook'])->middleware('auth');
Route::get('/set-example', [App\Http\Controllers\Telegram\WebhookController::class, 'example'])->middleware('auth');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
