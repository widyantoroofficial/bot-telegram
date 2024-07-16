<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
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
        switch (strtolower($text)) {
            case '/users':
                $users = User::all();

                if ($users->isEmpty()) {
                    $responseText = "Tidak ada pengguna yang tersedia.";
                } else {
                    $responseText = "Daftar Pengguna:\n";
                    foreach ($users as $user) {
                        $responseText .= "- {$user->name}\n"; // Sesuaikan dengan kolom yang ingin ditampilkan
                    }
                }
                break;
            case '/start':
                $responseText = "Bot telah dimulai.";
                break;
            default:
                $responseText = "Maaf, perintah tidak dikenali.";
                break;
        }

        // Kirim balasan
        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $responseText
        ]);

        return response()->json(['status' => 'success']);
    }
}
