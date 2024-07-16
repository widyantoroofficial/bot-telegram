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

        // Tangani perintah yang masuk
        switch (strtolower($text)) {
            case '/start':
                // Tautan dengan web page preview
                $linkText = "Kunjungi WinniCode";
                $linkUrl = "https://winnicode.com/";

                // Kirim pesan dengan tautan yang memiliki web page preview
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "<a href='{$linkUrl}'>{$linkText}</a>",
                    'parse_mode' => 'HTML'
                ]);
                break;
            default:
                $responseText = "Maaf, perintah tidak dikenali.";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText
                ]);
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
