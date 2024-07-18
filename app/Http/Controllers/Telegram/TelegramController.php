<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Models\User;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $message = $update->getMessage();
        $text = $message->getText();
        $chatId = $message->getChat()->getId();

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
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                break;

            case '/exportdb':
                $responseText = $this->exportsemuadatabase();
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
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
    public function exportsemuadatabase()
    {
        // Nama file untuk ekspor
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        // Lokasi penyimpanan file
        $filePath = storage_path('app/' . $filename);

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
            // Jika berhasil, kembalikan path ke file SQL
            return response()->download($filePath)->deleteFileAfterSend(true);
        } else {
            // Jika gagal, kembalikan pesan error
            return response()->json(['status' => 'error', 'message' => 'Failed to export database'], 500);
        }
    }
}
