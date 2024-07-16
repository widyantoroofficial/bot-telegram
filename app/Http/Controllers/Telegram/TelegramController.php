<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\InlineQueryResultArticle;
use Telegram\Bot\Objects\InputTextMessageContent;
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

        // Tangani inline query
        $inlineQuery = $update->getInlineQuery();

        if ($inlineQuery) {
            // Mendapatkan query dari pengguna
            $query = $inlineQuery->getQuery();

            // Buat URL yang ingin ditampilkan di Telegram
            $url = 'https://winnicode.com/';

            // Buat hasil inline query
            $results = [
                [
                    'type' => 'article',
                    'id' => '1',
                    'title' => 'Kunjungi WinniCode',
                    'input_message_content' => [
                        'message_text' => "<a href='{$url}'>Kunjungi WinniCode</a>",
                        'parse_mode' => 'HTML'
                    ],
                    'description' => 'Buka halaman WinniCode di Telegram',
                ]
            ];

            // Kirim hasil inline query ke Telegram
            Telegram::answerInlineQuery([
                'inline_query_id' => $inlineQuery->getId(),
                'results' => json_encode($results),
                'cache_time' => 0
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
