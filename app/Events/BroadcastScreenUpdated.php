<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BroadcastScreenUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Booking $booking,
        public array $payload = [],
        public array $technicianIds = []
    ) {
    }

    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('broadcast.' . (int) $this->booking->id),
            new PrivateChannel('customer.' . (int) $this->booking->customer_id),
        ];

        foreach ($this->technicianIds as $technicianId) {
            $channels[] = new PrivateChannel('technician.' . (int) $technicianId);
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'broadcast.screen.updated';
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
