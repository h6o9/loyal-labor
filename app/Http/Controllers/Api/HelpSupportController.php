<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HelpSupport;
use Illuminate\Http\Request;

class HelpSupportController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'booking_reference' => 'nullable|string',
            'issue_category' => 'required|string',
            'description' => 'required|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'priority' => 'required|in:low,medium,high',
        ]);

        try {
            $bookingId = null;

            if ($request->filled('booking_reference')) {
                // #region agent log
                @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'runId' => 'help-support-fix', 'hypothesisId' => 'H-HS1', 'location' => 'HelpSupportController::submit', 'message' => 'looking up booking by reference', 'data' => ['has_reference' => true], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
                // #endregion

                $booking = Booking::where(
                    'booking_reference',
                    $request->booking_reference
                )->first();

                if (!$booking) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking not found.',
                    ], 404);
                }

                $bookingId = $booking->id;
            }

            $path = null;

            if ($request->hasFile('screenshot')) {
                $path = $request->file('screenshot')->store('support', 'public');
            }

            $helpSupport = HelpSupport::create([
                'user_id' => $request->user()->id,
                'booking_id' => $bookingId,
                'issue_category' => $request->issue_category,
                'description' => $request->description,
                'screenshot' => $path,
                'priority' => $request->priority,
            ]);

            // #region agent log
            @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'runId' => 'help-support-fix', 'hypothesisId' => 'H-HS1', 'location' => 'HelpSupportController::submit', 'message' => 'complaint submitted', 'data' => ['help_support_id' => $helpSupport->id, 'booking_id' => $bookingId], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
            // #endregion

            return response()->json([
                'success' => true,
                'message' => 'Your complaint has been submitted successfully. We will get back to you soon.',
                'data' => $helpSupport,
            ]);
        } catch (\Throwable $e) {
            \Log::error('Help support submit failed: ' . $e->getMessage());

            // #region agent log
            @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'runId' => 'help-support-fix', 'hypothesisId' => 'H-HS1', 'location' => 'HelpSupportController::submit', 'message' => 'submit failed', 'data' => ['error' => $e->getMessage()], 'timestamp' => round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
            // #endregion

            return response()->json([
                'success' => false,
                'message' => 'Unable to submit complaint. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
