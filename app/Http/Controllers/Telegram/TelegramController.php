<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Http\Request;

class TelegramController extends Controller
{
    public function handle(Request $request)
    {
        // Tangani pesan yang masuk
        if ($text == '/widy') {
            $responseText = "Hello! Bot telegram berhasil di buat.";
        } else {
            $responseText = "Ketikkanmu: " . $text;
        }

        return response()->json(['status' => 'success']);
    }
}
