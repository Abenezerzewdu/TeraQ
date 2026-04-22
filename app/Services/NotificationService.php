<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function sendSMS($phone,$message){
    
    try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TEXTBEE_API_KEY'),
                'Accept' => 'application/json',
            ])->post(env('TEXTBEE_BASE_URL') . '/messages/send', [
                'to' => $phone,
                'message' => $message,
            ]);

           if ($response && method_exists($response, 'failed') && $response->failed()) {
    Log::error('TextBee failed', [
        'body' => $response->body()
    ]);
}

        } catch (\Exception $e) {
            Log::error('SMS Exception: ' . $e->getMessage());
        }
    }

    
}
