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
    public function sendSMS($phone, $message)
    {
        $apiKey = env('TEXTBEE_API_KEY');
        $baseUrl = env('TEXTBEE_BASE_URL', 'https://api.textbee.dev');
        $deviceId = env('TEXTBEE_DEVICE_ID', '69e8bf82b5cd3ce4c72b8d46');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($baseUrl . '/messages/send', [
                'to' => $phone,
                'message' => $message,
                'deviceId' => $deviceId,
            ]);

            // We avoid calling any methods on $response here to prevent "undefined method" errors.
            // Laravel will log errors if the request itself fails.
            
            return $response;
        } catch (\Exception $e) {
            Log::error('TextBee Exception: ' . $e->getMessage());
            return null;
        }
    }

    public function sendWelcomeSMS($entry)
    {
        if (!$entry->phone) return;

        $message = "Hi {$entry->user_name}, you've joined the queue for {$entry->queue->name}! You're currently at position #{$entry->position}. We'll notify you when it's your turn.";
        return $this->sendSMS($entry->phone, $message);
    }

    public function sendTurnStartedSMS($entry)
    {
        if (!$entry->phone) return;

        $message = "It's your turn! Please head to the counter for {$entry->queue->name}. TeraQ team.";
        return $this->sendSMS($entry->phone, $message);
    }

    public function sendReminderSMS($entry)
    {
        if (!$entry->phone) return;

        $message = "Get ready! You're next in line for {$entry->queue->name}. It should be your turn in a few minutes.";
        return $this->sendSMS($entry->phone, $message);
    }
}
