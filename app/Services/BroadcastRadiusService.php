<?php

namespace App\Services;

use App\Events\BroadcastScreenUpdated;
use App\Http\Events\BroadcastServiceRequestCreated;
use App\Models\Booking;
use App\Models\BookingBroadcastNotified;
use App\Models\BookingIndividualOffer;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class BroadcastRadiusService
{
    public function ensureTables(): void
    {
        if (!Schema::hasTable('booking_broadcast_notified')) {
            Schema::create('booking_broadcast_notified', function ($table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('technician_id');
                $table->unsignedInteger('radius_km')->nullable();
                $table->decimal('distance_km', 8, 2)->nullable();
                $table->timestamps();
                $table->unique(['booking_id', 'technician_id']);
            });
        }

        if (!Schema::hasTable('booking_individual_offers')) {
            Schema::create('booking_individual_offers', function ($table) {
                $table->id();
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('technician_id');
                $table->string('status', 40)->default('pending');
                $table->timestamps();
            });
        }

        if (Schema::hasTable('bookings')) {
            $adds = [
                'latitude' => function ($table) {
                    $table->decimal('latitude', 10, 8)->nullable();
                },
                'longitude' => function ($table) {
                    $table->decimal('longitude', 11, 8)->nullable();
                },
                'current_radius_km' => function ($table) {
                    $table->unsignedInteger('current_radius_km')->nullable();
                },
                'last_expand_prompt_at' => function ($table) {
                    $table->timestamp('last_expand_prompt_at')->nullable();
                },
            ];

            foreach ($adds as $column => $callback) {
                if (!Schema::hasColumn('bookings', $column)) {
                    try {
                        Schema::table('bookings', $callback);
                    } catch (\Throwable $e) {
                    }
                }
            }
        }
    }

    public function nextRadius(?int $current): int
    {
        $current = (int) $current;
        if ($current < 20) {
            return 20;
        }

        return $current + 20;
    }

    public function resolveCoordinates(Booking $booking, ?User $customer = null, ?float $latitude = null, ?float $longitude = null, ?string $address = null): array
    {
        $lat = $latitude ?? ($booking->latitude ? (float) $booking->latitude : null);
        $lng = $longitude ?? ($booking->longitude ? (float) $booking->longitude : null);

        if ($lat && $lng) {
            return [$lat, $lng];
        }

        if ($customer && $customer->latitude && $customer->longitude) {
            return [(float) $customer->latitude, (float) $customer->longitude];
        }

        $geo = $this->geocodeAddress($address ?: trim(($booking->address ?? '') . ', ' . ($booking->city ?? '')));
        if ($geo) {
            return $geo;
        }

        return [null, null];
    }

    public function findTechniciansInRadius(int $serviceCategoryId, float $latitude, float $longitude, int $radiusKm, array $excludeTechnicianIds = []): Collection
    {
        $haversine = '(6371 * acos(LEAST(1, cos(radians(?)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians(?)) + sin(radians(?)) * sin(radians(users.latitude)))))';

        $query = User::query()
            ->where('user_type', 'technician')
            ->where('status', 'active')
            ->where('is_verified', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereHas('serviceCategories', function ($q) use ($serviceCategoryId) {
                $q->where('service_categories.id', $serviceCategoryId);
            })
            ->select('users.*')
            ->selectRaw($haversine . ' as distance_km', [$latitude, $longitude, $latitude])
            ->whereRaw($haversine . ' <= ?', [$latitude, $longitude, $latitude, $radiusKm])
            ->orderBy('distance_km');

        if (Schema::hasColumn('users', 'currently_available')) {
            $query->where('currently_available', true);
        }

        if (!empty($excludeTechnicianIds)) {
            $query->whereNotIn('users.id', $excludeTechnicianIds);
        }

        return $query->get();
    }

    public function notifyTechnicians(Booking $booking, Collection $technicians, int $radiusKm, string $title, string $body): Collection
    {
        $this->ensureTables();
        $fcm = app(FcmPushService::class);

        foreach ($technicians as $technician) {
            BookingBroadcastNotified::updateOrCreate(
                [
                    'booking_id' => $booking->id,
                    'technician_id' => $technician->id,
                ],
                [
                    'radius_km' => $radiusKm,
                    'distance_km' => isset($technician->distance_km) ? round((float) $technician->distance_km, 2) : null,
                ]
            );

            $notification = UserNotification::create([
                'user_id' => $technician->id,
                'title' => $title,
                'body' => $body,
                'type' => 'broadcast_request',
                'data' => [
                    'booking_id' => $booking->id,
                    'request_id' => $booking->booking_reference,
                    'emergency_level' => $booking->emergency_level,
                    'open' => 'pending_requests',
                ],
                'push_status' => 'pending',
            ]);

            $sent = $fcm->sendToToken(
                $technician->fcmtoken ?? null,
                $title,
                $body,
                $notification->data ?? []
            );
            $notification->update(['push_status' => $sent ? 'sent' : 'skipped']);
        }

        $ids = $technicians->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
        try {
            event(new BroadcastServiceRequestCreated($booking->fresh(['serviceCategory', 'district', 'customer']), $ids));
        } catch (\Throwable $e) {
            Log::warning('Pusher technician dispatch failed: ' . $e->getMessage());
        }

        return $technicians;
    }

    public function remainingTechnicians(Booking $booking): Collection
    {
        $this->ensureTables();
        $rejectedIds = [];
        if (Schema::hasTable('booking_rejections')) {
            $rejectedIds = DB::table('booking_rejections')->where('booking_id', $booking->id)->pluck('technician_id')->map(fn ($id) => (int) $id)->all();
        }
        $notifiedIds = BookingBroadcastNotified::where('booking_id', $booking->id)->pluck('technician_id')->map(fn ($id) => (int) $id)->all();
        $ids = array_values(array_diff($notifiedIds, $rejectedIds));

        if (empty($ids)) {
            return collect();
        }

        [$lat, $lng] = $this->resolveCoordinates($booking);
        $query = User::query()
            ->whereIn('id', $ids)
            ->where('status', 'active');

        if (Schema::hasColumn('users', 'currently_available')) {
            $query->where('currently_available', true);
        }

        if ($lat && $lng) {
            $haversine = '(6371 * acos(LEAST(1, cos(radians(?)) * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians(?)) + sin(radians(?)) * sin(radians(users.latitude)))))';
            $query->select('users.*')
                ->selectRaw($haversine . ' as distance_km', [$lat, $lng, $lat]);
        }

        return $query->get();
    }

    public function technicianCards(Collection $technicians, ?int $bookingId = null): array
    {
        return $technicians->map(function ($tech) use ($bookingId) {
            $name = (string) $tech->name;
            $initials = '';
            foreach (preg_split('/\s+/', trim($name)) as $part) {
                if ($part !== '') {
                    $initials .= strtoupper(substr($part, 0, 1));
                }
                if (strlen($initials) >= 2) {
                    break;
                }
            }

            $photo = $this->technicianPhotoUrl($tech->photo ?? null);

            return [
                'id' => $tech->id,
                'name' => $name,
                'initials' => $initials !== '' ? $initials : 'NA',
                'photo' => $photo,
                'profile_image' => $photo,
                'image' => $photo,
                'currently_available' => (bool) ($tech->currently_available ?? true),
                'distance_km' => isset($tech->distance_km) ? round((float) $tech->distance_km, 1) : null,
                'profile_endpoint' => '/api/technicians/' . $tech->id . '/profile',
                'select_endpoint' => $bookingId ? '/api/bookings/' . $bookingId . '/select-technician' : '/api/bookings/{bookingId}/select-technician',
            ];
        })->values()->all();
    }

    public function screenPayload(Booking $booking, string $event = 'updated'): array
    {
        $technicians = $this->remainingTechnicians($booking);
        $cards = $this->technicianCards($technicians, (int) $booking->id);
        $preview = array_slice($cards, 0, 4);
        $total = count($cards);
        $currentRadius = (int) ($booking->current_radius_km ?: 20);
        $nextRadius = $this->nextRadius($currentRadius);
        $promptAt = $booking->last_expand_prompt_at ?: $booking->created_at;
        $secondsSincePrompt = $promptAt ? now()->diffInSeconds($promptAt) : 120;
        $pendingOffer = BookingIndividualOffer::where('booking_id', $booking->id)
            ->where('status', 'technician_accepted')
            ->latest()
            ->first();
        $waiting = $booking->status === 'pending' && !$booking->technician_id;
        $expandPrompt = $waiting
            && !$pendingOffer
            && $secondsSincePrompt >= 120;

        // #region agent log
        @file_put_contents(base_path('debug-1d5da7.log'), json_encode(['sessionId' => '1d5da7', 'runId' => 'pre-fix', 'hypothesisId' => 'A,B,C', 'location' => 'BroadcastRadiusService::screenPayload', 'message' => 'broadcast screen payload', 'data' => ['booking_id' => $booking->id, 'status' => $booking->status, 'current_radius_km' => $currentRadius, 'technician_count' => $total, 'photos_present' => collect($cards)->filter(fn ($c) => !empty($c['profile_image']))->count(), 'seconds_since_prompt' => $secondsSincePrompt, 'expand_prompt' => $expandPrompt, 'has_pending_approval' => (bool) $pendingOffer, 'event' => $event], 'timestamp' => (int) round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        $approval = null;
        if ($pendingOffer) {
            $tech = User::find($pendingOffer->technician_id);
            $approval = [
                'technician_id' => $pendingOffer->technician_id,
                'technician_name' => $tech?->name,
                'message' => ($tech?->name ?? 'Technician') . ' is accepting your request. Do you approve?',
                'approve_endpoint' => '/api/bookings/' . $booking->id . '/approve-technician',
                'decline_endpoint' => '/api/bookings/' . $booking->id . '/decline-technician',
            ];
        }

        return [
            'event' => $event,
            'success' => true,
            'booking_id' => $booking->id,
            'request_id' => $booking->booking_reference,
            'status' => $booking->status,
            'emergency_level' => $booking->emergency_level,
            'service_category' => $booking->serviceCategory?->name,
            'address' => $booking->address,
            'city' => $booking->city,
            'created_ago' => 'Just now',
            'current_radius_km' => $currentRadius,
            'next_radius_km' => $nextRadius,
            'technicians_notified' => $total,
            'technicians' => $cards,
            'technicians_preview' => $preview,
            'more_count' => max(0, $total - 4),
            'waiting_for_first_acceptance' => $waiting,
            'job_locks_on_accept' => true,
            'expand_prompt' => $expandPrompt,
            'seconds_until_expand_prompt' => $waiting && !$pendingOffer ? max(0, 120 - $secondsSincePrompt) : null,
            'poll_every_seconds' => 120,
            'expand_message' => $expandPrompt
                ? 'No one accepted your request within ' . $currentRadius . ' km. Would you like to expand to ' . $nextRadius . ' km or cancel the request?'
                : null,
            'expand_endpoint' => '/api/bookings/' . $booking->id . '/expand-radius',
            'cancel_endpoint' => '/api/bookings/' . $booking->id . '/cancel',
            'actions' => [
                [
                    'key' => 'expand',
                    'label' => 'Increase to ' . $nextRadius . ' km',
                    'endpoint' => '/api/bookings/' . $booking->id . '/expand-radius',
                ],
                [
                    'key' => 'cancel',
                    'label' => 'Cancel request',
                    'endpoint' => '/api/bookings/' . $booking->id . '/cancel',
                ],
            ],
            'pending_approval' => $approval,
            'pusher' => [
                'key' => config('services.pusher.key'),
                'cluster' => config('services.pusher.cluster'),
                'channel' => 'private-broadcast.' . $booking->id,
                'event' => 'broadcast.screen.updated',
            ],
        ];
    }

    public function pushScreen(Booking $booking, string $event = 'updated'): array
    {
        $payload = $this->screenPayload($booking->fresh(['serviceCategory', 'district', 'customer']), $event);
        $technicianIds = [];
        if (Schema::hasTable('booking_broadcast_notified')) {
            $technicianIds = BookingBroadcastNotified::where('booking_id', $booking->id)
                ->pluck('technician_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }
        try {
            event(new BroadcastScreenUpdated($booking, $payload, $technicianIds));
        } catch (\Throwable $e) {
            Log::warning('Broadcast screen pusher failed: ' . $e->getMessage());
        }

        return $payload;
    }

    public function notifyUser(User $user, string $title, string $body, array $data = [], string $type = 'broadcast_request'): void
    {
        $notification = UserNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => $data,
            'push_status' => 'pending',
        ]);

        $sent = app(FcmPushService::class)->sendToToken($user->fcmtoken ?? null, $title, $body, $data);
        $notification->update(['push_status' => $sent ? 'sent' : 'skipped']);
        // #region agent log
        @file_put_contents(base_path('debug-1d5da7.log'), json_encode(['sessionId' => '1d5da7', 'runId' => 'pre-fix', 'hypothesisId' => 'D,E', 'location' => 'BroadcastRadiusService::notifyUser', 'message' => 'notification dispatched', 'data' => ['user_id' => $user->id, 'user_type' => $user->user_type, 'type' => $type, 'title' => $title, 'push_status' => $sent ? 'sent' : 'skipped', 'has_fcm' => !empty($user->fcmtoken)], 'timestamp' => (int) round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion
    }

    private function technicianPhotoUrl(?string $photo): ?string
    {
        if (!$photo) {
            return null;
        }
        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }
        if (str_starts_with($photo, 'profiles/') || str_starts_with($photo, 'storage/')) {
            return asset('storage/' . ltrim(str_replace('storage/', '', $photo), '/'));
        }

        return asset($photo);
    }

    private function geocodeAddress(string $address): ?array
    {
        $address = trim($address);
        $key = config('services.google_maps.key');
        if ($address === '' || !$key) {
            return null;
        }

        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'key' => $key,
            ]);
            $location = $response->json('results.0.geometry.location');
            if (!empty($location['lat']) && !empty($location['lng'])) {
                return [(float) $location['lat'], (float) $location['lng']];
            }
        } catch (\Throwable $e) {
            Log::warning('Geocode failed: ' . $e->getMessage());
        }

        return null;
    }
}
