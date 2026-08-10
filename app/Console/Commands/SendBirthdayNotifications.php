<?php

namespace App\Console\Commands;

use App\Events\BirthdayEvent;
use App\Models\User;
use Illuminate\Console\Command;

class SendBirthdayNotifications extends Command
{
    /**
     * @var string
     */
    protected $signature = 'notify:birthday';
    /**
     * @var string
     */
    protected $description = 'Send birthday notifications to users';

    public function handle()
    {
        $today = now()->format('m-d'); // '05-15'

        User::whereRaw("DATE_FORMAT(birthday, '%m-%d') = ?", [$today])
            ->get()
            ->each(function ($user) {
                event(new BirthdayEvent($user));
            });

        $this->info('Birthday notifications sent!');
    }
}
