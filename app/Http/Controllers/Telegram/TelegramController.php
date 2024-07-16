<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => 'https://bot.latihanserver.my.id/webhook']);

        return $response ? 'Webhook is set' : 'Failed to set webhook';
    }

    public function handle(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        $chatId = $update->getMessage()->getChat()->getId();

        // Kirim pesan dengan Instant View
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => "https://winnicode.com/",
            'parse_mode' => 'HTML',
        ]);

        return response()->json(['status' => 'success']);
    }
}
