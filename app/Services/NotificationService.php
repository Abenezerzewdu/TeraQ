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
        $apiKey = config('services.textbee.api_key');
        $baseUrl = config('services.textbee.base_url', 'https://api.textbee.dev');
        $deviceId = config('services.textbee.device_id');

        if (!$apiKey || !$deviceId) {
            Log::error('TextBee Configuration missing.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ])->post($baseUrl . '/messages/send', [
                'to' => $phone,
                'message' => $message,
                'deviceId' => $deviceId,
            ]);

            if ($response->failed()) {
                Log::error('TextBee API Error: ' . $response->body());
            } else {
                Log::info("SMS sent to {$phone}: {$message}");
            }
            
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
