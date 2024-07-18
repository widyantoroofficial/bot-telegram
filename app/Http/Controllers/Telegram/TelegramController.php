<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Telegram\Bot\FileUpload\InputFile;
use Carbon\Carbon;

class TelegramController extends Controller
{
    public function setWebhook()
    {
        $response = Telegram::setWebhook(['url' => 'https://bot.latihanserver.my.id/webhook']);

        return $response ? 'Webhook is set' : 'Failed to set webhook';
    }

    public function handle(Request $request)
    {
        $update = Telegram::getWebhookUpdate();

        if ($update->isType('callback_query')) {
            $callbackQuery = $update->getCallbackQuery();
            $callbackData = $callbackQuery->getData();
            $chatId = $callbackQuery->getMessage()->getChat()->getId();

            if ($callbackData === 'exportdb') {
                $this->exportsemuadatabase($chatId);
            }

            return response()->json(['status' => 'success']);
        }

        $message = $update->getMessage();
        $text = $message->getText();
        $chatId = $message->getChat()->getId();

        switch (strtolower($text)) {
            case '/start':
                $responseText = "Selamat datang di bot! Klik tombol di bawah ini untuk mengekspor database.";

                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [
                            [
                                ['text' => 'Export Database', 'callback_data' => 'exportdb']
                            ]
                        ]
                    ])
                ]);
                break;

            case '/exportdb':
                $responseText = "ini db nya tong!!!!";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                $this->exportsemuadatabase($chatId);
                $responseText = "makasih..dong lah";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                $this->sendGambar($chatId);
                break;

            default:
                $responseText = "Perintah tidak dikenal.";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                break;
        }

        return response()->json(['status' => 'success']);
    }

    public function exportsemuadatabase($chatId)
    {
        // Nama file untuk ekspor
        $filename = 'backup_pukesmas_prembun_' . date('Y-m-d_H-i-s') . '.sql';
        // Lokasi penyimpanan file
        $filePath = storage_path('app/db' . $filename);

        // Nama database, username dan password dari konfigurasi
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        // Perintah mysqldump untuk mengekspor database
        $command = "mysqldump --user={$username} --password={$password} {$database} > {$filePath}";

        // Jalankan perintah
        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            // Jika berhasil, kirim file ke pengguna dan hapus setelah dikirim
            Telegram::sendDocument([
                'chat_id' => $chatId,
                'document' => InputFile::create($filePath, $filename)
            ]);
            unlink($filePath); // Hapus file setelah dikirim
        } else {
            // Jika gagal, kembalikan pesan error
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'Gagal mengekspor database.',
            ]);
        }
    }
    public function sendGambar($chatId)
    {
        $gambarPath = storage_path('app/public/mega.jpeg'); // Sesuaikan dengan path gambar Anda

        if (file_exists($gambarPath)) {
            Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($gambarPath, 'mega.jpeg')
            ]);
        } else {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'invalid',
            ]);
        }
    }
}
