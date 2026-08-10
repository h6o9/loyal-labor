<?php

namespace App\Listeners;

use App\Events\BirthdayEvent;
use App\Notifications\BirthdayNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBirthdayNotification implements ShouldQueue
{
    /**
     * @param BirthdayEvent $event
     */
    public function handle(BirthdayEvent $event)
    {
        $event->user->notify(new BirthdayNotification());
    }
}
