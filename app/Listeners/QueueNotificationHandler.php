<?php

namespace App\Listeners;

use App\Events\UserJoinedQueue;
use App\Events\UserServingStarted;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class QueueNotificationHandler implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function handleUserJoined(UserJoinedQueue $event): void
    {
        $this->notificationService->sendWelcomeSMS($event->entry);
    }

    public function handleUserServingStarted(UserServingStarted $event): void
    {
        // 1. Notify the person whose turn just started
        $this->notificationService->sendTurnStartedSMS($event->entry);

        // 2. Notify the NEXT person in line (Reminder)
        // The one who just started serving was at position 1 (now 0/serving)
        // The person who is now at position 1 should be notified
        $nextInLine = $event->entry->queue->entries()
            ->where('status', 'waiting')
            ->where('position', 1)
            ->first();

        if ($nextInLine) {
            $this->notificationService->sendReminderSMS($nextInLine);
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events): array
    {
        return [
            UserJoinedQueue::class => 'handleUserJoined',
            UserServingStarted::class => 'handleUserServingStarted',
        ];
    }
}
