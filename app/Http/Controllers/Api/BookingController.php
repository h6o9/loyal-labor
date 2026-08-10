<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\District;         // ✅ Yeh bhi add karein (agar use kar rahe hain)
use App\Models\ServiceCategory;  // ✅ Yeh import add karein
use App\Support\BookingReference;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    use GlobalMailTrait;

    private function bookingExpiryMinutes(): int
    {
        $value = null;
        try {
            $value = getSettings('booking_request_expiry_minutes');
        } catch (\Throwable $e) {
            $value = null;
        }

        // getSettings($key) returns stdClass when key doesn't exist; guard it
        if (is_object($value)) {
            $value = null;
        }

        $minutes = (int) ($value ?: 5);
        return $minutes < 1 ? 5 : $minutes;
    }

    public function getExpirySettings()
    {
        $minutes = $this->bookingExpiryMinutes();

        // #region agent log
        @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'hypothesisId' => 'H1', 'location' => 'BookingController::getExpirySettings', 'message' => 'expiry settings read', 'data' => ['expiry_minutes' => $minutes], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        return response()->json([
            'success' => true,
            'expiry_minutes' => $minutes,
            'expires_in_seconds' => $minutes * 60,
            'message' => "Booking requests expire after {$minutes} minute(s) if not accepted.",
        ]);
    }

    private function formatBookingStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pending',
            'accepted' => 'Upcoming',
            'on_the_way' => 'On the Way',
            'work_started' => 'Work Started',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'rejected' => 'Rejected',
            'expired' => 'Expired',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    private function formatTimeSlot12h(?string $timeSlot): ?string
    {
        if (!$timeSlot) {
            return null;
        }

        try {
            return Carbon::parse($timeSlot)->format('g:i A');
        } catch (\Throwable $e) {
            return $timeSlot;
        }
    }

    private function formatServiceDateParts($serviceDate): array
    {
        if (!$serviceDate) {
            return ['day' => null, 'date' => null, 'date_formatted' => null];
        }

        $date = Carbon::parse($serviceDate);

        return [
            'day' => $date->format('D'),
            'date' => $date->format('Y-m-d'),
            'date_formatted' => $date->format('D, M j'),
        ];
    }

    private function getPersonInitials(?string $name): string
    {
        if (!$name) {
            return '';
        }

        $parts = preg_split('/\s+/', trim($name));
        $initials = '';

        foreach ($parts as $part) {
            if ($part !== '') {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }

        return substr($initials, 0, 2);
    }

    private function resolveBookingServiceCategory(Booking $booking): ?array
    {
        if ($booking->serviceCategory) {
            return [
                'id' => $booking->serviceCategory->id,
                'name' => $booking->serviceCategory->name,
                'slug' => $booking->serviceCategory->slug,
                'icon' => $booking->serviceCategory->icon,
            ];
        }

        $technician = $booking->technician;
        if (!$technician) {
            return null;
        }

        if ($technician->relationLoaded('serviceCategories') && $technician->serviceCategories->isNotEmpty()) {
            $category = $technician->serviceCategories->first();

            return [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'icon' => $category->icon,
            ];
        }

        if ($technician->services) {
            $services = is_string($technician->services)
                ? json_decode($technician->services, true)
                : $technician->services;

            if (is_array($services) && count($services) > 0) {
                $slug = (string) $services[0];

                return [
                    'id' => null,
                    'name' => ucfirst(str_replace('_', ' ', $slug)),
                    'slug' => $slug,
                    'icon' => null,
                ];
            }
        }

        return null;
    }

    private function formatTechnicianSummary(?User $technician): ?array
    {
        if (!$technician) {
            return null;
        }

        return [
            'id' => $technician->id,
            'name' => $technician->name,
            'initials' => $this->getPersonInitials($technician->name),
            'photo' => $technician->photo ? asset($technician->photo) : null,
            'is_available' => (bool) ($technician->currently_available ?? false),
        ];
    }

    private function formatBookingCardForCustomer(Booking $booking): array
    {
        $dateParts = $this->formatServiceDateParts($booking->service_date);
        $timeFormatted = $this->formatTimeSlot12h($booking->time_slot);
        $location = trim(($booking->address ?? '') . ($booking->city ? ', ' . $booking->city : ''), ', ');

        return [
            'id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'booking_type' => $booking->booking_type,
            'status' => $booking->status,
            'status_label' => $this->formatBookingStatusLabel($booking->status),
            'technician' => $this->formatTechnicianSummary($booking->technician),
            'service_category' => $this->resolveBookingServiceCategory($booking),
            'service_details' => $booking->service_details,
            'service_date' => $dateParts['date'],
            'service_day' => $dateParts['day'],
            'service_date_formatted' => $dateParts['date_formatted'],
            'time_slot' => $booking->time_slot,
            'time_formatted' => $timeFormatted,
            'date_time_formatted' => ($dateParts['date_formatted'] && $timeFormatted)
                ? $dateParts['date_formatted'] . ' • ' . $timeFormatted
                : null,
            'address' => $booking->address,
            'city' => $booking->city,
            'location' => $location ?: null,
            'payment_status' => $booking->payment_status,
            'created_at' => $booking->created_at?->toIso8601String(),
        ];
    }

    private function formatBookingConfirmationForCustomer(Booking $booking): array
    {
        $card = $this->formatBookingCardForCustomer($booking);

        return [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'status' => $booking->status,
            'status_label' => $card['status_label'],
            'technician_name' => $booking->technician?->name,
            'technician' => $card['technician'],
            'service_details' => $booking->service_details,
            'service_category' => $card['service_category'],
            'service_date' => $card['service_date'],
            'service_day' => $card['service_day'],
            'service_date_formatted' => $card['service_date_formatted'],
            'time_slot' => $card['time_slot'],
            'time_formatted' => $card['time_formatted'],
            'date_time_formatted' => $card['date_time_formatted'],
            'address' => $booking->address,
            'city' => $booking->city,
            'location' => $card['location'],
            'job_progress' => [
                'accepted_at' => $booking->accepted_at ? Carbon::parse($booking->accepted_at)->format('g:i A') : null,
                'on_the_way_at' => $booking->on_the_way_at ? Carbon::parse($booking->on_the_way_at)->format('g:i A') : null,
                'work_started_at' => $booking->work_started_at ? Carbon::parse($booking->work_started_at)->format('g:i A') : null,
                'completed_at' => $booking->completed_at ? Carbon::parse($booking->completed_at)->format('g:i A') : null,
            ],
        ];
    }

    public function getBookingConfirmation(Request $request, $bookingId)
    {
        $customer = $request->user();

        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['accepted', 'on_the_way', 'work_started', 'completed'])
            ->with(['technician.serviceCategories', 'serviceCategory', 'district'])
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Confirmed booking not found. It may still be pending or expired.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed!',
            'confirmation' => $this->formatBookingConfirmationForCustomer($booking),
        ]);
    }

    private function expirePendingBookingsQuery($query)
    {
        return $query->where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', Carbon::now())
            ->update(['status' => 'expired']);
    }

    private function matchingTechniciansQuery(int $serviceCategoryId, ?int $districtId = null)
    {
        $query = User::query()
            ->where('user_type', 'technician')
            ->where('status', 'active')
            ->where('is_verified', true)
            ->whereHas('serviceCategories', function ($q) use ($serviceCategoryId) {
                $q->where('service_categories.id', $serviceCategoryId);
            });

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        return $query;
    }

    private function technicianCanViewBroadcast(User $technician, Booking $booking): bool
    {
        if (!$booking->isBroadcast() || $booking->status !== 'pending' || $booking->technician_id) {
            return false;
        }

        if ($booking->expires_at && Carbon::now()->greaterThan($booking->expires_at)) {
            return false;
        }

        if (!$booking->service_category_id) {
            return false;
        }

        $hasCategory = $technician->serviceCategories()
            ->where('service_categories.id', $booking->service_category_id)
            ->exists();

        if (!$hasCategory) {
            return false;
        }

        if ($booking->district_id && (int) $technician->district_id !== (int) $booking->district_id) {
            return false;
        }

        return true;
    }

    // Customer: Broadcast request to all technicians in selected category
    public function broadcastRequest(Request $request)
{
    $request->validate([
        'service_category_id' => 'required|exists:service_categories,id',
        'district_id' => 'nullable|exists:districts,id',
        'address' => 'required|string',
        'city' => 'required|string',
        'service_details' => 'required|string',
        'emergency_level' => 'required|in:medium,high,emergency',
        'additional_notes' => 'nullable|string',
    ]);

    $customer = $request->user();
    $expiryMinutes = $this->bookingExpiryMinutes();
    $now = Carbon::now();

    // Fetch service category and district names
    $serviceCategory = ServiceCategory::find($request->service_category_id);
    $district = $request->district_id ? District::find($request->district_id) : null;

    $booking = Booking::create([
        'customer_id' => $customer->id,
        'technician_id' => null,
        'booking_type' => 'broadcast',
        'service_category_id' => $request->service_category_id,
        'district_id' => $request->district_id,
        'emergency_level' => $request->emergency_level,
        'status' => 'pending',
        'service_date' => $now->toDateString(),
        'time_slot' => $now->format('H:i:s'),
        'service_details' => $request->service_details,
        'address' => $request->address,
        'city' => $request->city,
        'phone' => $request->phone ?? $customer->phone,
        'additional_notes' => $request->additional_notes,
        'booking_reference' => null,
        'expires_at' => $now->copy()->addMinutes($expiryMinutes),
    ]);

    $techniciansNotified = $this->matchingTechniciansQuery(
        (int) $request->service_category_id,
        $request->district_id ? (int) $request->district_id : null
    )->count();

    // Load relationships
    $booking->load(['serviceCategory', 'district']);

    return response()->json([
        'success' => true,
        'message' => 'Your request has been broadcast to available technicians.',
        'booking' => [
            'id' => $booking->id,
            'customer_id' => $booking->customer_id,
            'technician_id' => $booking->technician_id,
            'booking_type' => $booking->booking_type,
            'service_category' => $booking->serviceCategory ? $booking->serviceCategory->name : null, // ✅ Name instead of ID
            'service_category_id' => $booking->service_category_id, // ✅ Keeping ID as well (optional)
            'district' => $booking->district ? $booking->district->name : null, // ✅ Name instead of ID
            'district_id' => $booking->district_id, // ✅ Keeping ID as well (optional)
            'emergency_level' => $booking->emergency_level,
            'status' => $booking->status,
            'service_date' => $booking->service_date,
            'time_slot' => $booking->time_slot,
            'service_details' => $booking->service_details,
            'address' => $booking->address,
            'city' => $booking->city,
            'phone' => $booking->phone,
            'additional_notes' => $booking->additional_notes,
            'booking_reference' => $booking->booking_reference,
            'expires_at' => $booking->expires_at,
            'created_at' => $booking->created_at,
            'updated_at' => $booking->updated_at,
        ],
        'technicians_notified' => $techniciansNotified,
        'expires_at' => $booking->expires_at,
        'expires_in_minutes' => $expiryMinutes,
    ], 201);
}

    // Customer: Live broadcast status while waiting for first acceptance
    public function getBroadcastStatus(Request $request, $bookingId)
    {
        $customer = $request->user();

        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', $customer->id)
            ->where('booking_type', 'broadcast')
            ->with(['serviceCategory', 'district', 'technician'])
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Broadcast request not found'], 404);
        }

        if ($booking->status === 'pending' && $booking->expires_at && Carbon::now()->greaterThan($booking->expires_at)) {
            $booking->update(['status' => 'expired']);
            $booking->refresh();
        }

        $techniciansNotified = $this->matchingTechniciansQuery(
            (int) $booking->service_category_id,
            $booking->district_id ? (int) $booking->district_id : null
        )->count();

        $response = [
            'success' => true,
            'booking_id' => $booking->id,
            'status' => $booking->status,
            'expires_at' => $booking->expires_at,
            'technicians_notified' => $techniciansNotified,
            'service_category' => $booking->serviceCategory,
            'district' => $booking->district,
            'emergency_level' => $booking->emergency_level,
            'address' => $booking->address,
            'city' => $booking->city,
            'service_details' => $booking->service_details,
        ];

        if ($booking->status === 'expired') {
            $response['message'] = 'Your request is expired. Please broadcast a new request.';
        } elseif ($booking->status === 'pending') {
            $response['message'] = 'Broadcasting your request. Waiting for first technician acceptance.';
            $response['remaining_seconds'] = max(0, Carbon::now()->diffInSeconds($booking->expires_at, false));
        } elseif ($booking->status === 'accepted') {
            $response['message'] = 'A technician accepted your request.';
            $response['technician'] = $booking->technician;
            $response['booking_reference'] = $booking->booking_reference;
        } elseif ($booking->status === 'cancelled') {
            $response['message'] = 'You cancelled this request.';
        }

        return response()->json($response);
    }

    // Customer: Book a technician
    public function bookTechnician(Request $request)
    {
        

        $customer = $request->user();
        $technician = User::where('id', $request->technician_id)
                         ->where('user_type', 'technician')
                         ->where('status', 'active')
                         ->first();

        if (!$technician) {
            return response()->json(['error' => 'Technician not found or not active'], 404);
        }

        // Check if technician is available on that date/time
        if (!$this->isTechnicianAvailable($technician->id, $request->service_date, $request->time_slot)) {
            return response()->json(['error' => 'Technician not available at this time'], 400);
        }

        // Check for existing booking conflict
        $existingBooking = Booking::where('technician_id', $technician->id)
            ->where('service_date', $request->service_date)
            ->where('time_slot', $request->time_slot)
            ->whereNotIn('status', ['cancelled', 'completed', 'rejected'])
            ->exists();

        if ($existingBooking) {
            return response()->json(['error' => 'This time slot is already booked'], 400);
        }

        $expiryMinutes = $this->bookingExpiryMinutes();

        $booking = Booking::create([
            'customer_id' => $customer->id,
            'technician_id' => $technician->id,
            'booking_type' => 'direct',
            'emergency_level' => $request->emergency_level,
            'status' => 'pending',
            'service_date' => $request->service_date,
            'time_slot' => $request->time_slot,
            'service_details' => $request->service_details,
            'address' => $request->address,
            'city' => $request->city,
            'phone' => $request->phone ?? $customer->phone,
            'additional_notes' => $request->additional_notes,
            'booking_reference' => null,
            'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
        ]);

        // Send notification to technician (via push, sms, or email)
        $this->notifyTechnician($technician, $booking);

        return response()->json([
            'success' => true,
            'message' => 'Booking request sent successfully',
            'booking' => $booking->load('customer', 'technician'),
            'expires_at' => $booking->expires_at,
            'note' => "Request will expire in {$expiryMinutes} minutes if not accepted. Reference code will be generated when technician confirms.",
        ], 201);
    }

    // Technician: Get all booking requests
    public function getBookingRequests(Request $request)
    {
    $technician = Auth::user();

        if ($technician->user_type !== 'technician') {
        return response()->json([
            'success' => false,
            'message' => 'Only technicians can view service requests.'
        ], 403);
        }

        $status = $request->get('status', 'pending');
        
    /*
    |--------------------------------------------------------------------------
    | Delete Expired Broadcast Requests
    |--------------------------------------------------------------------------
    */
    Booking::where('booking_type', 'broadcast')
            ->where('status', 'pending')
            ->whereNotNull('expires_at')
        ->where('expires_at', '<', now())
        ->delete();

    $categoryIds = $technician->serviceCategories()->pluck('service_categories.id');

    $bookings = Booking::query()
        ->where(function ($query) use ($technician, $categoryIds) {

            // Assigned bookings
            $query->where(function ($assigned) use ($technician) {
                $assigned->where('technician_id', $technician->id)
                    ->where(function ($expiry) {
                        $expiry->whereNull('expires_at')
                            ->orWhere('expires_at', '>=', now());
                    });
            })

            // Broadcast bookings
            ->orWhere(function ($broadcast) use ($technician, $categoryIds) {
                $broadcast->where('booking_type', 'broadcast')
                    ->whereNull('technician_id')
                    ->where('status', 'pending')
                    ->where(function ($expiry) {
                        $expiry->whereNull('expires_at')
                            ->orWhere('expires_at', '>=', now());
                    })
                    ->when(
                        $categoryIds->isNotEmpty(),
                        function ($q) use ($categoryIds) {
                            $q->whereIn('service_category_id', $categoryIds);
                        },
                        function ($q) {
                            $q->whereRaw('1 = 0');
                        }
                    )
                    ->where(function ($district) use ($technician) {
                        $district->whereNull('district_id')
                            ->orWhere('district_id', $technician->district_id);
                    });
            });
        })
        ->when($status && $status !== 'all', function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->with([
            'customer' => function($query) {
                $query->select('id', 'name', 'is_verified', 'latitude', 'longitude');
            },
            'serviceCategory:id,name',
            'district:id,name'
        ])
        ->latest()
            ->paginate(20);

    if ($bookings->total() == 0) {
        return response()->json([
            'success' => true,
            'message' => 'No service requests are currently available.',
            'data' => []
        ], 200);
    }

    // Transform data to match the required response format
    $transformedData = $this->transformBookingRequests($bookings, $technician);

    return response()->json([
        'success' => true,
        'message' => 'Service requests retrieved successfully.',
        'data' => $transformedData
    ], 200);
}

/**
 * Transform booking requests to match the required response format
 */
private function transformBookingRequests($bookings, $technician)
{
    $result = [];
    $result['current_page'] = $bookings->currentPage();
    $result['per_page'] = $bookings->perPage();
    $result['total'] = $bookings->total();
    $result['last_page'] = $bookings->lastPage();
    $result['requests'] = [];

    foreach ($bookings as $booking) {
        // Get customer initials from name
        $customerName = $booking->customer->name ?? 'Unknown Customer';
        $nameParts = explode(' ', $customerName);
        $initials = '';
        foreach ($nameParts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        $initials = substr($initials, 0, 2);

        // Calculate distance using Haversine formula
        $distance = $this->calculateDistance(
            $technician->latitude,
            $technician->longitude,
            $booking->customer->latitude ?? null,
            $booking->customer->longitude ?? null
        );

        // Format date from service_date
        $date = $booking->service_date ?? $booking->created_at;
        if ($date) {
            $dateObj = \Carbon\Carbon::parse($date);
            if ($dateObj->isToday()) {
                $formattedDate = 'Today';
            } else {
                $formattedDate = $dateObj->format('M d, Y');
            }
        } else {
            $formattedDate = null;
        }

        // Format time from time_slot
        $time = $booking->time_slot ?? null;
        if ($time) {
            $time = \Carbon\Carbon::parse($time)->format('g:i A');
        }

        // Get expiry time from expires_at
        $expiryTime = $this->getExpiryTime($booking);

        // Get priority from emergency_level
        $priority = ucfirst($booking->emergency_level ?? 'low');

        // Build full address
        $fullAddress = '';
        if ($booking->address) {
            $fullAddress = $booking->address;
        }
        if ($booking->city) {
            $fullAddress .= $fullAddress ? ', ' . $booking->city : $booking->city;
        }
        if ($booking->district && $booking->district->name) {
            $fullAddress .= $fullAddress ? ', ' . $booking->district->name : $booking->district->name;
        }

        $result['requests'][] = [
            'id' => $booking->id,
            'customer_initials' => $initials,
            'customer_name' => $customerName,
            'is_verified' => (bool)($booking->customer->is_verified ?? false),
            'service_category' => $booking->serviceCategory->name ?? 'N/A',
            'description' => $booking->service_details ?? $booking->additional_notes ?? null,
            'address' => $fullAddress ?: null,
            'city' => $booking->city ?? null,
            'district' => $booking->district->name ?? null,
            'phone' => $booking->phone ?? null,
            'distance' => $distance ? $distance . ' km' : null,
            'date' => $formattedDate,
            'time' => $time,
            'priority' => $priority,
            'expires_at' => $expiryTime,
            'status' => $booking->status ?? 'pending',
            'created_at' => $booking->created_at ? \Carbon\Carbon::parse($booking->created_at)->format('g:i A') : null,
        ];
    }

    return $result;
}

/**
 * Calculate distance between two coordinates using Haversine formula
 */
private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    if (empty($lat1) || empty($lon1) || empty($lat2) || empty($lon2)) {
        return null;
    }

    $earthRadius = 6371; // kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) + 
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * 
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return round($distance, 2);
}

/**
 * Get expiry time from expires_at
 */
private function getExpiryTime($booking)
{
    if (empty($booking->expires_at)) {
        return null;
    }

    $expires = \Carbon\Carbon::parse($booking->expires_at);
    
    // Check if already expired
    if (now()->gt($expires)) {
        return 'Expired';
    }

    // Return the expiry time in 12-hour format
    return $expires->format('g:i A');
}
    // Technician: Accept a booking request
    public function acceptBooking(Request $request, $bookingId)
    {
        $technician = $request->user();

        if ($technician->user_type !== 'technician') {
            return response()->json(['error' => 'Only technicians can accept bookings'], 403);
        }

    $booking = DB::transaction(function () use ($bookingId, $technician) {
        $booking = Booking::where('id', $bookingId)->lockForUpdate()->first();

        if (!$booking) {
            return null;
        }

        if ($booking->isBroadcast()) {
            if (!$this->technicianCanViewBroadcast($technician, $booking)) {
                return 'forbidden';
            }
        } elseif ((int) $booking->technician_id !== (int) $technician->id) {
            return null;
        }

        if ($booking->status === 'expired') {
            return 'expired';
        }

        if ($booking->status !== 'pending') {
            return 'processed';
        }

        if ($booking->expires_at && Carbon::now()->greaterThan($booking->expires_at)) {
            $booking->update(['status' => 'expired']);
            return 'expired';
        }

        if ($booking->isBroadcast() && $booking->technician_id) {
            return 'taken';
        }

        $existingAccepted = Booking::where('technician_id', $technician->id)
            ->where('service_date', $booking->service_date)
            ->where('time_slot', $booking->time_slot)
            ->whereIn('status', ['accepted', 'on_the_way', 'work_started'])
            ->where('id', '!=', $booking->id)
            ->exists();

        if ($existingAccepted) {
            return 'slot_unavailable';
        }

        $referenceCode = $booking->booking_reference ?: BookingReference::generate();

        $booking->update([
            'technician_id' => $technician->id,
            'status' => 'accepted',
            'accepted_at' => now(),
            'booking_reference' => $referenceCode,
            'expires_at' => null,
        ]);

        return $booking->fresh();
    });

    if ($booking === null) {
        return response()->json(['error' => 'Booking not found for this technician'], 404);
    }

    if ($booking === 'forbidden') {
        return response()->json(['error' => 'This broadcast request is not available for you'], 403);
    }

    if ($booking === 'expired') {
        return response()->json([
            'success' => false,
            'message' => 'This request has expired. Customer must create a new request.',
        ], 410);
    }

    if ($booking === 'processed') {
        return response()->json([
            'error' => 'Booking already processed',
        ], 409);
    }

    if ($booking === 'taken') {
        return response()->json([
            'success' => false,
            'message' => 'Another technician already accepted this request.',
        ], 409);
    }

    if ($booking === 'slot_unavailable') {
        return response()->json(['error' => 'This time slot is no longer available'], 400);
    }

        // Send notification to customer
        $this->notifyCustomer($booking->customer, $booking, 'accepted');

    $booking->load(['customer', 'technician', 'serviceCategory', 'district']);

    return response()->json([
        'success' => true,
        'message' => 'Booking confirmed successfully',
        'booking' => $this->transformAcceptedBooking($booking),
        'booking_reference' => $booking->booking_reference,
    ]);
}

/**
 * Transform accepted booking response to match the image format
 */
private function transformAcceptedBooking($booking)
{
    // Get customer initials
    $customerName = $booking->customer->name ?? 'Unknown Customer';
    $nameParts = explode(' ', $customerName);
    $initials = '';
    foreach ($nameParts as $part) {
        if (!empty($part)) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    }
    $initials = substr($initials, 0, 2);

    // Get service category name
    $serviceCategory = $booking->serviceCategory->name ?? 'N/A';

    // Build full address
    $fullAddress = '';
    if ($booking->address) {
        $fullAddress = $booking->address;
    }
    if ($booking->city) {
        $fullAddress .= $fullAddress ? ', ' . $booking->city : $booking->city;
    }
    if ($booking->district && $booking->district->name) {
        $fullAddress .= $fullAddress ? ', ' . $booking->district->name : $booking->district->name;
    }

    return [
        'id' => $booking->id,
        'booking_reference' => $booking->booking_reference,
        'customer' => [
            'id' => $booking->customer->id,
            'name' => $customerName,
            'initials' => $initials,
            'is_verified' => (bool)($booking->customer->is_verified ?? false),
            'phone' => $booking->phone ?? $booking->customer->phone ?? null,
        ],
        'service' => [
            'category' => $serviceCategory,
            'description' => $booking->service_details ?? $booking->additional_notes ?? null,
        ],
        'address' => $fullAddress ?: null,
        'job_progress' => [
            'status' => $booking->status,
            'accepted_at' => $booking->accepted_at ? Carbon::parse($booking->accepted_at)->format('g:i A') : null,
            'on_the_way_at' => $booking->on_the_way_at ? Carbon::parse($booking->on_the_way_at)->format('g:i A') : null,
            'work_started_at' => $booking->work_started_at ? Carbon::parse($booking->work_started_at)->format('g:i A') : null,
            'completed_at' => $booking->completed_at ? Carbon::parse($booking->completed_at)->format('g:i A') : null,
        ],
        'booking_details' => [
            'service_date' => $booking->service_date ? Carbon::parse($booking->service_date)->format('M d, Y') : null,
            'time_slot' => $booking->time_slot ? Carbon::parse($booking->time_slot)->format('g:i A') : null,
            'emergency_level' => ucfirst($booking->emergency_level ?? 'low'),
            'created_at' => $booking->created_at ? Carbon::parse($booking->created_at)->format('g:i A') : null,
        ],
        'status' => $booking->status,
    ];
}

    // Technician: Reject a booking request (direct bookings only)
    public function rejectBooking(Request $request, $bookingId)
    {
        $technician = $request->user();

        if ($technician->user_type !== 'technician') {
            return response()->json(['error' => 'Only technicians can reject bookings'], 403);
        }

        $request->validate([
            'reason' => 'nullable|string',
        ]);

        $booking = Booking::where('id', $bookingId)
            ->where('technician_id', $technician->id)
            ->where('booking_type', 'direct')
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found for this technician'], 404);
        }

        if ($booking->status === 'expired') {
            return response()->json([
                'success' => false,
                'message' => 'This request has already expired.',
            ], 410);
        }

        if ($booking->status !== 'pending') {
            return response()->json([
                'error' => 'Booking already processed',
                'status' => $booking->status,
            ], 409);
        }

        $booking->update([
            'status' => 'rejected',
            'cancellation_reason' => $request->reason ?? 'Rejected by technician',
            'expires_at' => null,
        ]);

        $this->notifyCustomer($booking->customer, $booking, 'rejected');

        return response()->json([
            'success' => true,
            'message' => 'Booking rejected',
        ]);
    }

    // Customer: Get my bookings
    public function myBookings(Request $request)
    {
        $customer = $request->user();

        $this->expirePendingBookingsQuery(
        Booking::where('customer_id', $customer->id)
        );

        $status = $request->get('status', 'all');
        
        $bookings = Booking::where('customer_id', $customer->id)
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->with(['technician.serviceCategories', 'serviceCategory', 'district'])
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($booking) {
                $card = $this->formatBookingCardForCustomer($booking);

                if ($booking->status === 'expired' && $booking->isBroadcast()) {
                    $card['status_message'] = 'Your request is expired. Please broadcast a new request.';
                }

                // #region agent log
                @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'hypothesisId' => 'H2', 'location' => 'BookingController::myBookings', 'message' => 'booking card formatted', 'data' => ['booking_id' => $booking->id, 'status' => $booking->status, 'status_label' => $card['status_label'], 'has_technician_summary' => $card['technician'] !== null], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
                // #endregion

                return $card;
            });

        return response()->json([
            'success' => true,
            'message' => $status === 'expired'
                ? 'Your request is expired. Please broadcast a new request.'
                : null,
            'response_format' => 'booking_cards_v1',
            'total_bookings' => $bookings->total(),
            'data' => $bookings,
        ]);
    }

    // Customer: Cancel booking
    public function cancelBooking(Request $request, $bookingId)
    {
        $customer = $request->user();

        $request->validate([
            'reason' => 'nullable|string'
        ]);

        $booking = Booking::where('id', $bookingId)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        if (!in_array($booking->status, ['pending', 'accepted'], true)) {
            return response()->json([
                'error' => 'Booking cannot be cancelled',
                'status' => $booking->status,
            ], 409);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->reason ?? 'Cancelled by customer',
            'expires_at' => null,
        ]);

        if ($booking->technician) {
        $this->notifyTechnician($booking->technician, $booking, 'cancelled');
        }

        return response()->json([
            'success' => true,
            'message' => $booking->isBroadcast()
                ? 'Broadcast request cancelled successfully'
                : 'Booking cancelled successfully',
        ]);
    }

    // Technician: Request completion OTP (customer must verify before job completes)
    public function completeBooking(Request $request, $bookingId)
    {
        $technician = $request->user();

        if ($technician->user_type !== 'technician') {
            return response()->json(['error' => 'Only technicians can complete bookings'], 403);
        }

        $booking = Booking::with('customer')
            ->where('id', $bookingId)
            ->where('technician_id', $technician->id)
            ->whereIn('status', ['accepted', 'on_the_way', 'work_started'])
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found or not in completable status'], 404);
        }

        $result = $this->requestCompletionOtp($booking, $technician);

        return response()->json($result, ($result['success'] ?? false) ? 200 : 422);
    }

    // Technician: Verify customer OTP and complete booking
    public function verifyCompletionOtp(Request $request, $bookingId)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $technician = $request->user();

        if ($technician->user_type !== 'technician') {
            return response()->json(['error' => 'Only technicians can verify completion OTP'], 403);
        }

        $booking = Booking::with('customer')
            ->where('id', $bookingId)
            ->where('technician_id', $technician->id)
            ->whereIn('status', ['accepted', 'on_the_way', 'work_started'])
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found or cannot be completed'], 404);
        }

        if (!$booking->completion_otp) {
            return response()->json([
                'error' => 'No completion OTP was requested. Tap Complete Job first.',
            ], 422);
        }

        if ($booking->completion_otp_expires_at && now()->greaterThan($booking->completion_otp_expires_at)) {
            return response()->json([
                'error' => 'Completion OTP has expired. Please request a new one.',
            ], 422);
        }

        if ($booking->completion_otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        $booking->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_otp' => null,
            'completion_otp_expires_at' => null,
        ]);

        $this->notifyCustomer($booking->customer, $booking, 'completed');

        return response()->json([
            'success' => true,
            'message' => 'Booking completed successfully.',
        ]);
    }

    // Technician: Update intermediate status
    public function updateStatus(Request $request, $bookingId)
    {
        $technician = $request->user();

        if ($technician->user_type !== 'technician') {
            return response()->json(['error' => 'Only technicians can update status'], 403);
        }

        $request->validate([
        'status' => 'required|in:on_the_way,work_started,completed'
        ]);

    $booking = Booking::with('customer')
        ->where('id', $bookingId)
            ->where('technician_id', $technician->id)
        ->whereIn('status', ['accepted', 'on_the_way', 'work_started'])
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found or cannot be updated'], 404);
        }

    // Prevent going back to previous status
    $statusFlow = ['pending', 'accepted', 'on_the_way', 'work_started', 'completed'];
    $currentIndex = array_search($booking->status, $statusFlow);
    $newIndex = array_search($request->status, $statusFlow);
    
    if ($newIndex <= $currentIndex) {
        return response()->json([
            'error' => 'Cannot move to previous status. Current status: ' . $booking->status
        ], 422);
    }

        $updateData = ['status' => $request->status];
    
    // Set timestamps based on status
        if ($request->status == 'on_the_way') {
            $updateData['on_the_way_at'] = now();
        } elseif ($request->status == 'work_started') {
            $updateData['work_started_at'] = now();
    } elseif ($request->status == 'completed') {
        $result = $this->requestCompletionOtp($booking, $technician);

        return response()->json($result, ($result['success'] ?? false) ? 200 : 422);
        }

        $booking->update($updateData);

        // Notify customer
        $this->notifyCustomer($booking->customer, $booking, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated to ' . str_replace('_', ' ', $request->status)
        ]);
    }
    // Customer: Get single booking details
    public function getBookingDetails(Request $request, $bookingId)
    {
        $user = $request->user();

        $booking = Booking::where('id', $bookingId)
            ->where(function ($query) use ($user) {
                $query->where('customer_id', $user->id)
                    ->orWhere('technician_id', $user->id)
                    ->orWhere(function ($broadcastQuery) use ($user) {
                        if ($user->user_type === 'technician') {
                            $broadcastQuery->where('booking_type', 'broadcast')
                                ->whereNull('technician_id')
                                ->where('status', 'pending');
                        }
                    });
            })
            ->with(['customer', 'technician', 'serviceCategory', 'district'])
            ->first();

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        if ($user->user_type === 'technician'
            && $booking->isBroadcast()
            && !$booking->technician_id
            && !$this->technicianCanViewBroadcast($user, $booking)) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        if ($booking->status === 'pending' && $booking->expires_at && Carbon::now()->greaterThan($booking->expires_at)) {
            $booking->update(['status' => 'expired']);
            $booking->refresh();
        }

        // Generate service progress timeline
        $timeline = [
            [
                'title' => 'Request Sent',
                'status' => 'Done',
                'time' => $booking->created_at ? $booking->created_at->format('h:i A') : null,
                'description' => null,
                'is_active' => in_array($booking->status, ['pending', 'accepted', 'on_the_way', 'work_started', 'completed']),
                'is_current' => $booking->status == 'pending'
            ],
            [
                'title' => 'Technician Accepted',
                'status' => $booking->accepted_at ? 'Done' : 'Pending',
                'time' => $booking->accepted_at ? \Carbon\Carbon::parse($booking->accepted_at)->format('h:i A') : null,
                'description' => $booking->accepted_at ? $booking->technician->name . ' accepted your request' : null,
                'is_active' => in_array($booking->status, ['accepted', 'on_the_way', 'work_started', 'completed']),
                'is_current' => $booking->status == 'accepted'
            ],
            [
                'title' => 'On the Way',
                'status' => $booking->on_the_way_at ? 'Done' : ($booking->status == 'accepted' ? 'In Progress' : 'Pending'),
                'time' => $booking->on_the_way_at ? \Carbon\Carbon::parse($booking->on_the_way_at)->format('h:i A') : null,
                'description' => null,
                'is_active' => in_array($booking->status, ['on_the_way', 'work_started', 'completed']),
                'is_current' => $booking->status == 'on_the_way'
            ],
            [
                'title' => 'Work Started',
                'status' => $booking->work_started_at ? 'Done' : ($booking->status == 'on_the_way' ? 'In Progress' : 'Pending'),
                'time' => $booking->work_started_at ? \Carbon\Carbon::parse($booking->work_started_at)->format('h:i A') : null,
                'description' => $booking->work_started_at ? null : 'Pending technician arrival',
                'is_active' => in_array($booking->status, ['work_started', 'completed']),
                'is_current' => $booking->status == 'work_started'
            ],
            [
                'title' => 'Completed',
                'status' => $booking->completed_at ? 'Done' : ($booking->status == 'work_started' ? 'In Progress' : 'Pending'),
                'time' => $booking->completed_at ? \Carbon\Carbon::parse($booking->completed_at)->format('h:i A') : null,
                'description' => $booking->completed_at ? null : 'Service completion pending',
                'is_active' => $booking->status == 'completed',
                'is_current' => $booking->status == 'completed'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $booking,
            'service_progress' => $timeline,
            'broadcast' => $booking->isBroadcast() && $booking->service_category_id ? [
                'technicians_notified' => $this->matchingTechniciansQuery(
                    (int) $booking->service_category_id,
                    $booking->district_id ? (int) $booking->district_id : null
                )->count(),
                'status_message' => $booking->status === 'expired'
                    ? 'Your request is expired. Please broadcast a new request.'
                    : null,
            ] : null,
        ]);
    }

    // Helper: Check technician availability
    private function isTechnicianAvailable($technicianId, $date, $timeSlot)
    {
        // Check if technician has any accepted booking at that time
        $conflictingBooking = Booking::where('technician_id', $technicianId)
            ->where('service_date', $date)
            ->where('time_slot', $timeSlot)
            ->whereIn('status', ['accepted', 'pending'])
            ->exists();

        if ($conflictingBooking) {
            return false;
        }

        // Check if day is within technician's working hours
        $day = strtolower(date('l', strtotime($date)));
        
        $availability = DB::table('technician_availability')
            ->where('technician_id', $technicianId)
            ->where('day', $day)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return false;
        }

        // Check if time slot is within working hours
        $requestTime = date('H:i:s', strtotime($timeSlot));
        
        return $requestTime >= $availability->start_time && $requestTime <= $availability->end_time;
    }

    // Helper: Notify technician (simplified - you can add real notifications)
    private function notifyTechnician($technician, $booking, $type = 'new')
    {
        // TODO: Implement real notifications (Firebase, OneSignal, SMS, Email)
        // For now, just log or send email
        \Log::info("Notification to {$technician->email}: New booking request #{$booking->booking_reference}");
    }

    // Helper: Notify customer
    private function notifyCustomer($customer, $booking, $status)
    {
        \Log::info("Notification to {$customer->email}: Booking #{$booking->booking_reference} is {$status}");
    }

    private function requestCompletionOtp(Booking $booking, User $technician): array
    {
        $customer = $booking->customer;

        if (!$customer || !$customer->email) {
            return [
                'success' => false,
                'message' => 'Customer email not found.',
                'email_sent' => false,
                'otp_sent' => false,
            ];
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $booking->update([
            'completion_otp' => $otp,
            'completion_otp_expires_at' => now()->addMinutes(30),
        ]);

        $emailError = null;

        try {
            $this->sendCompletionOtpMail(
                $customer->email,
                $customer->name,
                $otp,
                $booking,
                $technician->name
            );
        } catch (\Exception $e) {
            $emailError = $e->getMessage();
            \Log::error('Completion OTP email failed: ' . $emailError);
        }

        if ($emailError) {
            return [
                'success' => false,
                'message' => 'OTP generated but email failed to send.',
                'email_sent' => false,
                'email_error' => $emailError,
                'otp_sent' => true,
            ];
        }

        return [
            'success' => true,
            'message' => 'Completion OTP sent to customer email.',
            'email_sent' => true,
            'otp_sent' => true,
        ];
    }

    private function sendCompletionOtpMail(
        string $email,
        string $customerName,
        string $otp,
        Booking $booking,
        string $technicianName
    ): void {
        $appName = loyalBrandName();

        try {
            [$subject, $message] = $this->fetchEmailTemplate('booking_completion_otp', [
                'user_name' => $customerName,
                'otp' => $otp,
                'app_name' => $appName,
                'booking_reference' => $booking->booking_reference ?? (string) $booking->id,
                'technician_name' => $technicianName,
            ]);
        } catch (\Throwable $e) {
            $subject = $appName . ' - Job Completion Verification';
            $message = '<p style="margin:0 0 12px;">Dear ' . e($customerName) . ',</p>'
                . '<p style="margin:0 0 8px;">Your technician has finished the job. Share this code with them to confirm completion:</p>'
                . '<p style="margin:0;"><strong style="color:#FE7701;font-size:24px;letter-spacing:6px;">' . e($otp) . '</strong></p>'
                . '<p style="font-size:13px;color:#999;">This code expires in 30 minutes.</p>';
        }

        $this->sendMail($email, $subject, $message);
    }
}