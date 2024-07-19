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
    public function example(Request $request)
    {
        switch (strtolower($text)) {
            case '/hallo':
                $responseText = "ada yang bisa di bantu";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                break;

            default:
                $responseText = "printah tidak dikenal.";
                Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => $responseText,
                ]);
                break;
        }
    }
}
