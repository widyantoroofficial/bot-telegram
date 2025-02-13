<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BotPukesmasPrembunController extends Controller
{
    public function handle(Request $request)
    {
        $update = Telegram::getWebhookUpdate();
        $message = $update->getMessage();
        $text = $message->getText();
        $chatId = $message->getChat()->getId();

        switch (strtolower($text)) {
            case '/start':
                $responseText = "Selamat datang di bot!";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                $responseText = "Silahkan Masukan Password Untuk Export Database Pukesmas Prembun";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                break;

            case '/exportdbpukesmas':
                $responseText = "oke.. pw lu bener,lanjut";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
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
                $responseText = "Ulangi Password lu salah.";
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
        $gambarPath = public_path('gambar/mega2.jpg'); // Sesuaikan dengan path gambar Anda

        if (file_exists($gambarPath)) {
            Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($gambarPath, 'mega2.jpg')
            ]);
        } else {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => 'invalid',
            ]);
        }
    }
}
