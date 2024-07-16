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
        // Ambil data update dari webhook
        $update = Telegram::commandsHandler(true);

        // Ambil teks dari pesan yang masuk
        $text = $update->getMessage()->getText();
        $chatId = $update->getMessage()->getChat()->getId();

        // Tangani pesan yang masuk
        if (strtolower($text) == '/widy') {
            $responseText = "Selamat datang!";
        } else {
            $responseText = "Ketikkanmu: " . $text;
        }

        // Kirim balasan
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $responseText
        ]);

        return response()->json(['status' => 'success']);
    }
}
