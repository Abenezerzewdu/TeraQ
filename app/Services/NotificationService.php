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
        $apiKey  = config('services.textbee.api_key');
        $baseUrl = config('services.textbee.base_url', 'https://api.textbee.dev');
        $deviceId = config('services.textbee.device_id');

        if (!$apiKey || !$deviceId) {
            Log::error('TextBee: API key or device ID is missing in configuration.');
            return null;
        }

        try {
            $response = Http::withHeaders([
                'x-api-key'    => $apiKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->post("{$baseUrl}/api/v1/gateway/devices/{$deviceId}/send-sms", [
                'recipients' => [$phone],
                'message'    => $message,
            ]);

            if ($response->failed()) {
                Log::error('TextBee API Error [' . $response->status() . ']: ' . $response->body());
            } else {
                Log::info("TextBee SMS sent to {$phone}");
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
