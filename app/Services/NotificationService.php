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
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('TEXTBEE_API_KEY'),
                'Accept' => 'application/json',
            ])->post(env('TEXTBEE_BASE_URL') . '/messages/send', [
                'to' => $phone,
                'message' => $message,
                'deviceId' => env('TEXTBEE_DEVICE_ID', '67b727f71f654b9d03099039'), // Use env or fallback
            ]);

            if ($response && method_exists($response, 'failed') && $response->failed()) {
                Log::error('TextBee failed', [
                    'body' => $response->body(),
                    'status' => $response->status()
                ]);
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('SMS Exception: ' . $e->getMessage());
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
