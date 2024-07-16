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
Route::get('/set-webhook', function () {
    $response = Telegram::setWebhook(['url' => 'https://37e5-182-4-103-103.ngrok-free.app/webhook']);

    return $response ? 'Webhook is set' : 'Failed to set webhook';
});
