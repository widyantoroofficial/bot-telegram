<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        // Dapatkan data update dari webhook
        $chatId = $update->getMessage()->getChat()->getId();
        $text = $update->getMessage()->getText();

        // Tangani pesan yang masuk
        if ($text == '/start') {
            $responseText = "Hello! Bot telegram berhasil di buat.";
        } else {
            $responseText = "You said: " . $text;
        }

        // Kirim balasan
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $responseText
        ]);

        return response()->json(['status' => 'success']);
    }
}
