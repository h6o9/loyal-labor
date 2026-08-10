<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResolvesSanctumApiUser;
use App\Models\Booking;
use App\Models\TechnicianReview;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ResolvesSanctumApiUser;

    public function rateTechnician(Request $request, $technicianId)
    {
        $request->merge(['technician_id' => (int) $technicianId]);

        return $this->storeTechnicianReview($request);
    }

    public function storeTechnicianReview(Request $request)
    {
        $customer = $this->resolveSanctumUser($request);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'UnAuthenticated. Send Authorization: Bearer {customer_token}',
            ], 401);
        }

        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'technician_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_anonymous' => 'nullable|boolean',
        ]);

        if ($customer->user_type !== 'customer') {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can rate technicians.',
            ], 403);
        }

        $technician = User::where('id', $request->technician_id)
            ->where('user_type', 'technician')
            ->first();

        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician not found.',
            ], 404);
        }

        $booking = Booking::where('id', $request->booking_id)
            ->where('customer_id', $customer->id)
            ->where('technician_id', $request->technician_id)
            ->where('status', 'completed')
            ->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Completed booking not found for this technician.',
            ], 404);
        }

        if (TechnicianReview::where('booking_id', $booking->id)->where('customer_id', $customer->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You already reviewed this booking.',
            ], 409);
        }

        $review = TechnicianReview::create([
            'booking_id' => $booking->id,
            'customer_id' => $customer->id,
            'technician_id' => $request->technician_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'tags' => $request->tags,
            'is_anonymous' => (bool) $request->boolean('is_anonymous'),
            'is_approved' => true,
        ]);

        $ratingStats = $this->technicianRatingStats((int) $request->technician_id);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'data' => $review->load('technician:id,name,photo'),
            'technician_rating' => $ratingStats,
        ], 200);
    }

    public function technicianReviews($technicianId)
    {
        $technician = User::where('id', $technicianId)
            ->where('user_type', 'technician')
            ->first();

        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician not found.',
            ], 404);
        }

        $reviews = TechnicianReview::where('technician_id', $technicianId)
            ->where('is_approved', true)
            ->with(['customer:id,name,photo'])
            ->latest()
            ->paginate(20);

        $reviews->getCollection()->transform(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'tags' => $review->tags ?? [],
                'is_anonymous' => $review->is_anonymous,
                'customer_name' => $review->is_anonymous ? 'Anonymous' : ($review->customer->name ?? 'Customer'),
                'created_at' => $review->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'technician' => [
                'id' => $technician->id,
                'name' => $technician->name,
            ],
            'technician_rating' => $this->technicianRatingStats((int) $technicianId),
            'data' => $reviews,
        ]);
    }

    private function technicianRatingStats(int $technicianId): array
    {
        $stats = TechnicianReview::where('technician_id', $technicianId)
            ->where('is_approved', true)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as review_count')
            ->first();

        $avgRating = round((float) ($stats->avg_rating ?? 0), 1);

        return [
            'rating' => $avgRating,
            'review_count' => (int) ($stats->review_count ?? 0),
            'rating_label' => match (true) {
                $avgRating >= 4.5 => 'Excellent',
                $avgRating >= 4.0 => 'Good',
                $avgRating >= 3.0 => 'Average',
                $avgRating > 0 => 'Fair',
                default => 'New',
            },
        ];
    }
}
