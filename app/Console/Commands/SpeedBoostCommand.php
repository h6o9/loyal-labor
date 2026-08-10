<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SpeedBoostCommand extends Command
{
    protected $signature = 'app:speed-boost {--clear : Clear all caches instead of building them}';

    protected $description = 'Cache config/views/events for maximum Laravel performance (full project speed boost)';

    public function handle(): int
    {
        if ($this->option('clear')) {
            $this->call('optimize:clear');
            $this->info('All caches cleared.');

            return self::SUCCESS;
        }

        $this->info('Running full project speed boost...');

        $this->call('config:cache');

        try {
            $this->call('view:cache');
        } catch (\Throwable $e) {
            $this->warn('View cache skipped: ' . $e->getMessage());
        }

        try {
            $this->call('event:cache');
        } catch (\Throwable $e) {
            $this->warn('Event cache skipped: ' . $e->getMessage());
        }

        if (function_exists('opcache_reset')) {
            opcache_reset();
            $this->info('OPcache reset.');
        }

        $this->newLine();
        $this->info('Speed boost complete. Deploy updated files then run this on live server too.');
        $this->line('  php artisan app:speed-boost');

        return self::SUCCESS;
    }
}
