<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceCategory;
use App\Models\TechnicianAvailability;
use App\Models\TechnicianReview;
use App\Models\TechnicianWorkGallery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TechnicianController extends Controller
{
    //
	public function submitVerification(Request $request)
{
    $user = $request->user();

    // Only technicians can submit verification
    if ($user->user_type != 'technician') {
        return response()->json([
            'success' => false,
            'message' => 'Not a technician.'
        ], 403);
    }

    // Check required documents uploaded
    if (!$user->cnic_front || !$user->cnic_back || !$user->photo) {
        return response()->json([
            'success' => false,
            'message' => 'Please upload CNIC front, CNIC back and photo first.'
        ], 400);
    }

    // Submit for review only if still pending
    if ($user->status === 'pending') {
        $user->update([
            'status' => 'review'
        ]);
    }

    // Check account approval
    $isApproved = $user->is_verified &&
                  $user->cnic_front_verified &&
                  $user->cnic_back_verified &&
                  $user->photo_verified;

    // Certificates are required only for technicians
    if ($user->user_type === 'technician') {
        $isApproved = $isApproved && $user->certificates_verified;
    }

    return response()->json([
        'success' => 200,
        'message' => $isApproved
            ? 'Account verification approved.'
            : 'Account approval pending.',
        'verification_status' => $isApproved ? 'approved' : 'pending',
        'status' => $user->status,
        'data' => [
            'email_verified' => (bool) $user->is_verified,
            'cnic_front_verified' => (bool) $user->cnic_front_verified,
            'cnic_back_verified' => (bool) $user->cnic_back_verified,
            'photo_verified' => (bool) $user->photo_verified,
            'certificates_verified' => (bool) $user->certificates_verified,
        ]
    ]);
}

    public function activateSubscription(Request $request)
    {
        return $this->assignSubscriptionPlan($request);
    }

    public function changeSubscriptionPlan(Request $request)
    {
        return $this->assignSubscriptionPlan($request);
    }

    private function assignSubscriptionPlan(Request $request)
    {
        $user = $request->user();

        if ($user->user_type != 'technician') {
            return response()->json(['success' => false, 'message' => 'Only technicians can select a subscription plan.'], 403);
        }

        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_method' => 'nullable|string|max:100',
        ]);

        $subscription = \App\Models\Subscription::where('id', $request->subscription_id)
            ->where('is_active', true)
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Selected subscription plan is not active.',
            ], 404);
        }

        $data = [
            'subscription_id' => $subscription->id,
            'subscription' => 'active',
            'payment_status' => 'verified',
            'subscription_end' => $subscription->endsAtFrom(now())->toDateString(),
            'status' => 'active',
        ];

        // #region agent log
        @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'runId' => 'sub-duration', 'hypothesisId' => 'H-DUR3', 'location' => 'TechnicianController::activateSubscription', 'message' => 'subscription end calculated', 'data' => ['subscription_id' => $subscription->id, 'duration' => $subscription->duration_value, 'duration_unit' => $subscription->duration_unit, 'subscription_end' => $data['subscription_end']], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        if ($request->filled('payment_method') && Schema::hasColumn('users', 'payment_method')) {
            $data['payment_method'] = $request->payment_method;
        }

        if ($request->hasFile('payment_screenshot')) {
            $data['payment_screenshot'] = $request->file('payment_screenshot')->store('payments', 'public');
        }

        $user->update($data);
        $user->refresh()->load('subscriptionPlan');

        return response()->json([
            'success' => true,
            'message' => 'Subscription plan assigned successfully.',
            'payment_method' => $user->payment_method,
            'subscription' => $user->subscriptionPlan ? $user->subscriptionPlan->toApiArray() : null,
            'subscription_end' => $user->subscription_end,
            'status' => $user->status,
        ]);
    }

    // Get technician status
    public function status(Request $request)
    {
        $user = $request->user()->load('subscriptionPlan');
        
        return response()->json([
            'documents' => [
                'cnic_front' => !is_null($user->cnic_front),
                'cnic_back' => !is_null($user->cnic_back),
                'photo' => !is_null($user->photo),
                'certificates' => !empty($user->certificates),
            ],
            'verification' => [
                'cnic_front_verified' => (bool) $user->cnic_front_verified,
                'cnic_back_verified' => (bool) $user->cnic_back_verified,
                'photo_verified' => (bool) $user->photo_verified,
                'certificates_verified' => (bool) $user->certificates_verified,
            ],
            'account_status' => $user->status,
            'payment_status' => $user->payment_status ?? 'none',
            'subscription_active' => $user->hasActiveSubscription(),
            'subscription_end' => $user->subscription_end,
            'is_featured' => $user->isFeaturedTechnician(),
            'has_verified_badge' => $user->hasVerifiedPlanBadge(),
            'feature_keys' => $user->activeFeatureKeys(),
            'subscription_plan' => $user->subscriptionPlan ? $user->subscriptionPlan->toApiArray() : null,
            'availability' => $user->availabilities,
            'currently_available' => Schema::hasColumn('users', 'currently_available')
                ? (bool) ($user->currently_available ?? true)
                : true,
        ]);
    }

    public function updateAvailability(Request $request)
    {
        $user = $request->user();

        if ($user->user_type != 'technician') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'availability' => 'required|array',
            'availability.*.day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'availability.*.start' => 'nullable|date_format:H:i',
            'availability.*.end' => 'nullable|date_format:H:i',
            'availability.*.is_available' => 'boolean',
        ]);

        foreach ($request->availability as $schedule) {
            TechnicianAvailability::updateOrCreate(
                [
                    'technician_id' => $user->id,
                    'day' => $schedule['day'],
                    'specific_date' => $schedule['specific_date'] ?? null,
                ],
                [
                    'start_time' => $schedule['start'] ?? null,
                    'end_time' => $schedule['end'] ?? null,
                    'is_available' => $schedule['is_available'] ?? true,
                ]
            );
        }

        return response()->json([
            'message' => 'Availability updated successfully',
            'availability' => TechnicianAvailability::where('technician_id', $user->id)->get(),
        ]);
    }

    public function toggleDayAvailability(Request $request)
    {
        $user = $request->user();

        if ($user->user_type != 'technician') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'is_available' => 'required|boolean',
        ]);

        $availability = TechnicianAvailability::where([
            'technician_id' => $user->id,
            'day' => $request->day,
        ])->first();

        if ($availability) {
            $availability->update(['is_available' => $request->is_available]);
        }

        return response()->json([
            'message' => $request->is_available
                ? "Now available on {$request->day}"
                : "Now off on {$request->day}",
            'availability' => $availability,
        ]);
    }

    public function toggleCurrentlyAvailable(Request $request)
    {
        $user = $request->user();

        if ($user->user_type !== 'technician') {
            return response()->json(['success' => false, 'message' => 'Only technicians can update availability.'], 403);
        }

        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        if (!Schema::hasColumn('users', 'currently_available')) {
            return response()->json([
                'success' => false,
                'message' => 'currently_available column missing. Run migration: 2026_07_22_120000_add_currently_available_to_users_table.php',
            ], 500);
        }

        $isAvailable = $request->boolean('is_available');

        $user->update([
            'currently_available' => $isAvailable,
        ]);

        $user->refresh();

        return response()->json([
            'success' => true,
            'message' => $isAvailable
                ? 'You are now available for bookings.'
                : 'You are now marked as busy.',
            'currently_available' => $isAvailable,
            'availability_status' => $isAvailable ? 'Available' : 'Busy',
        ]);
    }

    public function getTechnicians(Request $request)
    {
        $request->validate([
            'filter' => 'nullable|in:all,top_rated,nearest,featured',
            'sort_by' => 'nullable|in:rating,distance,name,featured',
            'service_category_id' => 'nullable|integer|exists:service_categories,id',
            'category' => 'nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius_km' => 'nullable|numeric|min:1|max:200',
        ]);

        $filter = $request->input('filter', 'all');
        $customerLat = $request->filled('latitude') ? (float) $request->latitude : null;
        $customerLng = $request->filled('longitude') ? (float) $request->longitude : null;
        $radiusKm = (float) ($request->input('radius_km', 50));

        if ($filter === 'nearest' && ($customerLat === null || $customerLng === null)) {
            return response()->json([
                'success' => false,
                'message' => 'latitude and longitude are required for nearest filter (Google Maps structure ready).',
                'google_maps' => [
                    'required_fields' => ['latitude', 'longitude'],
                    'optional_fields' => ['radius_km'],
                    'integration_status' => 'pending',
                ],
            ], 422);
        }

        $query = User::query()
            ->where('users.user_type', 'technician')
            ->where('users.status', 'active');

        if ($request->filled('service_category_id')) {
            $categoryId = (int) $request->service_category_id;

            if (!ServiceCategory::whereKey($categoryId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service category not found.',
                ], 404);
            }

            $query->whereIn('users.id', function ($subQuery) use ($categoryId) {
                $subQuery->select('user_id')
                    ->from('service_category_user')
                    ->where('service_category_id', $categoryId);
            });
        } elseif ($request->filled('category')) {
            $categoryIds = is_array($request->category)
                ? $request->category
                : array_filter(array_map('trim', explode(',', (string) $request->category)));

            $query->whereHas('serviceCategories', function ($categoryQuery) use ($categoryIds) {
                $categoryQuery->whereIn('service_categories.id', $categoryIds);
            });
        }

        if (Schema::hasTable('technician_reviews')) {
            $query->withAvg('approvedTechnicianReviews as avg_rating', 'rating')
                ->withCount('approvedTechnicianReviews as review_count');
        }

        if ($filter === 'top_rated' && Schema::hasTable('technician_reviews')) {
            $query->having('review_count', '>', 0)
                ->orderByDesc('avg_rating')
                ->orderByDesc('review_count');
        }

        if ($request->filled('district_id')) {
            $query->where('users.district_id', $request->district_id);
        }

        $technicians = $query->with(['serviceCategories', 'availabilities', 'subscriptionPlan'])->get();

        $ratingBreakdowns = [];
        if (Schema::hasTable('technician_reviews') && $technicians->isNotEmpty()) {
            $breakdownRows = TechnicianReview::query()
                ->where('is_approved', true)
                ->whereIn('technician_id', $technicians->pluck('id'))
                ->select('technician_id', 'rating', DB::raw('COUNT(*) as total'))
                ->groupBy('technician_id', 'rating')
                ->get();

            foreach ($breakdownRows as $row) {
                $ratingBreakdowns[$row->technician_id][(int) $row->rating] = (int) $row->total;
            }
        }

        $mapped = $technicians->map(function ($tech) use ($customerLat, $customerLng, $request, $ratingBreakdowns) {
            $avgRating = round((float) ($tech->avg_rating ?? 0), 1);
            $reviewCount = (int) ($tech->review_count ?? 0);

            $filteredCategoryId = $request->filled('service_category_id')
                ? (int) $request->service_category_id
                : null;

            $primaryCategory = $filteredCategoryId
                ? ($tech->serviceCategories->firstWhere('id', $filteredCategoryId)
                    ?? $tech->serviceCategories->first())
                : $tech->serviceCategories->first();

            $distanceKm = null;
            if ($customerLat !== null && $customerLng !== null && $tech->latitude && $tech->longitude) {
                $distanceKm = $this->distanceKm(
                    $customerLat,
                    $customerLng,
                    (float) $tech->latitude,
                    (float) $tech->longitude
                );
            }

            $currentlyAvailable = Schema::hasColumn('users', 'currently_available')
                ? (bool) ($tech->currently_available ?? true)
                : true;

            $featureKeys = $tech->activeFeatureKeys();
            $isFeatured = $tech->isFeaturedTechnician();
            $hasVerifiedBadge = $tech->hasVerifiedPlanBadge();
            $plan = $tech->subscriptionPlan;

            return [
                'id' => $tech->id,
                'name' => $tech->name,
                'photo' => $tech->photo ? asset($tech->photo) : null,
                'rating' => $avgRating,
                'review_count' => $reviewCount,
                'rating_label' => $this->ratingLabel($avgRating),
                'service_category' => $primaryCategory ? [
                    'id' => $primaryCategory->id,
                    'name' => $primaryCategory->name,
                ] : null,
                'service_categories' => $tech->serviceCategories->map(fn ($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ])->values(),
                'experience' => $tech->experience,
                'distance_km' => $distanceKm,
                'distance_text' => $distanceKm !== null ? $distanceKm . ' km away' : null,
                'currently_available' => $currentlyAvailable,
                'availability_status' => $this->resolveAvailabilityStatus($currentlyAvailable),
                'is_featured' => $isFeatured,
                'featured_badge' => $isFeatured ? 'FEATURED' : null,
                'has_verified_badge' => $hasVerifiedBadge,
                'subscription' => [
                    'plan_type' => $plan->plan_type ?? null,
                    'plan_type_label' => $plan?->plan_type_label,
                    'is_active' => $tech->hasActiveSubscription(),
                    'feature_keys' => $featureKeys,
                ],
            ];
        });

        // #region agent log
        @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'runId' => 'featured-broadcast', 'hypothesisId' => 'H-FEAT', 'location' => 'TechnicianController::getTechnicians', 'message' => 'featured flags computed', 'data' => ['total' => $mapped->count(), 'featured_count' => $mapped->where('is_featured', true)->count(), 'filter' => $filter, 'sample' => $mapped->take(3)->map(fn ($t) => ['id' => $t['id'], 'is_featured' => $t['is_featured'], 'plan' => $t['subscription']['plan_type'] ?? null])->values()], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        if ($filter === 'featured') {
            $mapped = $mapped->filter(fn ($t) => $t['is_featured'])->values();
        }

        if ($filter === 'top_rated') {
            $mapped = $mapped->filter(fn ($t) => $t['review_count'] > 0)
                ->sort(function ($a, $b) use ($ratingBreakdowns) {
                    $score = function ($tech) use ($ratingBreakdowns) {
                        $breakdown = $ratingBreakdowns[$tech['id']] ?? [];

                        return [
                            (int) ($breakdown[5] ?? 0),
                            (int) ($breakdown[4] ?? 0),
                            $tech['rating'],
                            $tech['review_count'],
                        ];
                    };

                    $left = $score($a);
                    $right = $score($b);

                    foreach ($left as $index => $value) {
                        if ($value === $right[$index]) {
                            continue;
                        }

                        return $right[$index] <=> $value;
                    }

                    return 0;
                })
                ->values();
        }

        if ($filter === 'nearest') {
            $mapped = $mapped->filter(fn ($t) => $t['distance_km'] !== null && $t['distance_km'] <= $radiusKm)
                ->sortBy(fn ($t) => $t['distance_km'])
                ->values();
        }

        $sortBy = $request->input('sort_by');
        if ($sortBy === 'rating') {
            $mapped = $mapped->sort(function ($a, $b) use ($ratingBreakdowns) {
                $score = function ($tech) use ($ratingBreakdowns) {
                    $breakdown = $ratingBreakdowns[$tech['id']] ?? [];

                    return [
                        (int) ($breakdown[5] ?? 0),
                        (int) ($breakdown[4] ?? 0),
                        $tech['rating'],
                        $tech['review_count'],
                    ];
                };

                $left = $score($a);
                $right = $score($b);

                foreach ($left as $index => $value) {
                    if ($value === $right[$index]) {
                        continue;
                    }

                    return $right[$index] <=> $value;
                }

                return 0;
            })->values();
        } elseif ($sortBy === 'distance' && $customerLat !== null) {
            $mapped = $mapped->sortBy(fn ($t) => $t['distance_km'] ?? 999999)->values();
        } elseif ($sortBy === 'name') {
            $mapped = $mapped->sortBy('name')->values();
        } elseif ($sortBy === 'featured' || (!$sortBy && in_array($filter, ['all', 'featured'], true))) {
            // Featured / priority search plans float to the top for home + search lists.
            $mapped = $mapped->sort(function ($a, $b) {
                $aPriority = ($a['is_featured'] ? 2 : 0)
                    + (in_array('priority_search_placement', $a['subscription']['feature_keys'] ?? [], true) ? 1 : 0);
                $bPriority = ($b['is_featured'] ? 2 : 0)
                    + (in_array('priority_search_placement', $b['subscription']['feature_keys'] ?? [], true) ? 1 : 0);

                if ($aPriority === $bPriority) {
                    return $b['rating'] <=> $a['rating'];
                }

                return $bPriority <=> $aPriority;
            })->values();
        }

        $categoryFilter = $request->input('service_category_id') ?? $request->input('category', 'all');
        $featuredList = $mapped->filter(fn ($t) => $t['is_featured'])->values();

        return response()->json([
            'success' => 200,
            'filter' => $filter,
            'sort_by' => $sortBy ?? 'featured',
            'service_category_id' => $categoryFilter,
            'google_maps' => [
                'customer_latitude' => $customerLat,
                'customer_longitude' => $customerLng,
                'radius_km' => $radiusKm,
                'integration_status' => 'structure_ready',
                'distance_note' => 'distance_km is null until customer lat/lng and technician coordinates are set',
            ],
            'featured_total' => $featuredList->count(),
            'featured' => $featuredList,
            'total' => $mapped->count(),
            'data' => $mapped,
        ]);
    }

    private function distanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return round($earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a)), 1);
    }

    private function resolveAvailabilityStatus(bool $currentlyAvailable): string
    {
        return $currentlyAvailable ? 'Available' : 'Busy';
    }

    private function ratingLabel(float $rating): string
    {
        return match (true) {
            $rating >= 4.5 => 'Excellent',
            $rating >= 4.0 => 'Good',
            $rating >= 3.0 => 'Average',
            $rating > 0 => 'Fair',
            default => 'New',
        };
    }

    public function getSubscriptions()
    {
        $subscriptions = \App\Models\Subscription::query()
            ->where('is_active', true)
            ->orderBy('price_pkr')
            ->get()
            ->map(fn ($sub) => $sub->toApiArray());

        // #region agent log
        @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'runId' => 'sub-impl', 'hypothesisId' => 'H-SUB4', 'location' => 'TechnicianController::getSubscriptions', 'message' => 'subscriptions api response', 'data' => ['total' => $subscriptions->count(), 'sample' => $subscriptions->first()], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        return response()->json([
            'success' => true,
            'total' => $subscriptions->count(),
            'data' => $subscriptions,
        ]);
    }

	public function getSkills(Request $request)
    {
        try {
            // Query binding with optional filters
            $query = DB::table('skills')
                ->select('id', 'name', 'created_at', 'updated_at');

            // ✅ Search by name (optional)
            if ($request->has('search') && !empty($request->search)) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            }

            // ✅ Order by (optional)
            $orderBy = $request->order_by ?? 'id';
            $orderDir = $request->order_dir ?? 'asc';
            $query->orderBy($orderBy, $orderDir);

            // ✅ Pagination (optional)
            $perPage = $request->per_page ?? 10;
            $skills = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Skills fetched successfully',
                'data' => $skills->items(),
                'pagination' => [
                    'current_page' => $skills->currentPage(),
                    'per_page' => $skills->perPage(),
                    'total' => $skills->total(),
                    'last_page' => $skills->lastPage(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch skills: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getTechnicianProfile($technicianId)
    {
        $technician = User::query()
            ->where('id', $technicianId)
            ->where('user_type', 'technician')
            ->where('status', 'active')
            ->with(['serviceCategories', 'availabilities', 'workGalleries'])
            ->first();

        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician not found.',
            ], 404);
        }

        $skills = $technician->skills;
        if (is_string($skills)) {
            $skills = json_decode($skills, true) ?? [];
        }

        $jobsCompleted = Booking::where('technician_id', $technician->id)
            ->where('status', 'completed')
            ->count();

        $ratingStats = null;
        $highestRating = 0;
        $avgRating = 0;
        $reviewCount = 0;

        if (Schema::hasTable('technician_reviews')) {
            $ratingStats = TechnicianReview::where('technician_id', $technician->id)
                ->where('is_approved', true)
                ->selectRaw('AVG(rating) as avg_rating, MAX(rating) as highest_rating, COUNT(*) as review_count')
                ->first();

            $avgRating = round((float) ($ratingStats->avg_rating ?? 0), 1);
            $highestRating = (int) ($ratingStats->highest_rating ?? 0);
            $reviewCount = (int) ($ratingStats->review_count ?? 0);
        }

        $primaryCategory = $technician->serviceCategories->first();
        $currentlyAvailable = Schema::hasColumn('users', 'currently_available')
            ? (bool) ($technician->currently_available ?? true)
            : true;

        $galleryItems = $technician->workGalleries->map(fn ($item) => [
            'id' => $item->id,
            'image' => asset($item->image),
            'caption' => $item->caption,
        ])->values();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $technician->id,
                'name' => $technician->name,
                'photo' => $technician->photo ? asset($technician->photo) : null,
                'bio' => $technician->bio,
                'experience' => $technician->experience,
                'service_category' => $primaryCategory ? [
                    'id' => $primaryCategory->id,
                    'name' => $primaryCategory->name,
                ] : null,
                'service_categories' => $technician->serviceCategories->map(fn ($cat) => [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ])->values(),
                'rating' => $avgRating,
                'highest_rating' => $highestRating,
                'review_count' => $reviewCount,
                'rating_label' => $this->ratingLabel($avgRating),
                'jobs_completed' => $jobsCompleted,
                'skills' => is_array($skills) ? $skills : [],
                'currently_available' => $currentlyAvailable,
                'availability_status' => $this->resolveAvailabilityStatus($currentlyAvailable),
                'availability' => $technician->availabilities->map(fn ($slot) => [
                    'day' => $slot->day,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'is_available' => (bool) $slot->is_available,
                ])->values(),
                'work_gallery' => [
                    'total' => $galleryItems->count(),
                    'items' => $galleryItems,
                ],
            ],
        ]);
    }

    public function uploadWorkGallery(Request $request)
    {
        $user = $request->user();

        if ($user->user_type !== 'technician') {
            return response()->json(['success' => false, 'message' => 'Only technicians can upload work gallery images.'], 403);
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'caption' => 'nullable|string|max:255',
        ]);

        $file = $request->file('image');
        $directory = public_path('backend/img/gallery');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = 'work_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);
        $imagePath = 'backend/img/gallery/' . $filename;

        $gallery = TechnicianWorkGallery::create([
            'technician_id' => $user->id,
            'image' => $imagePath,
            'caption' => $request->caption,
            'sort_order' => (int) TechnicianWorkGallery::where('technician_id', $user->id)->max('sort_order') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Work image uploaded successfully.',
            'data' => [
                'id' => $gallery->id,
                'image' => asset($gallery->image),
                'caption' => $gallery->caption,
            ],
        ], 201);
    }

    public function myWorkGallery(Request $request)
    {
        $user = $request->user();

        if ($user->user_type !== 'technician') {
            return response()->json(['success' => false, 'message' => 'Only technicians can view work gallery.'], 403);
        }

        $items = TechnicianWorkGallery::where('technician_id', $user->id)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($item) => [
                'id' => $item->id,
                'image' => asset($item->image),
                'caption' => $item->caption,
                'created_at' => $item->created_at,
            ]);

        return response()->json([
            'success' => true,
            'total' => $items->count(),
            'data' => $items,
        ]);
    }

    public function deleteWorkGallery(Request $request, $galleryId)
    {
        $user = $request->user();

        if ($user->user_type !== 'technician') {
            return response()->json(['success' => false, 'message' => 'Only technicians can delete work gallery images.'], 403);
        }

        $gallery = TechnicianWorkGallery::where('technician_id', $user->id)->findOrFail($galleryId);

        $fullPath = public_path($gallery->image);
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }

        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Work gallery image deleted.',
        ]);
    }



public function getServiceCategory()
{
    try {
        $data = ServiceCategory::select('id', 'name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Service categories retrieved successfully.',
            'data' => $data,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
