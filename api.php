<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AndroidPaymentController;
use App\Http\Controllers\Api\BankAlfalahPaymentController;
use App\Http\Controllers\Api\FeatureAdsPaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('feature')->group(function () {
    Route::get('/test', [FeatureAdsPaymentController::class, 'testApi']);
    Route::post('/create-hosted-checkout', [FeatureAdsPaymentController::class, 'createHostedCheckout']);
    Route::post('/check-payment-status', [FeatureAdsPaymentController::class, 'checkPaymentStatus']);
    Route::get('/payment/success', [FeatureAdsPaymentController::class, 'paymentCallback'])
        ->name('feature.payment.success');

    Route::get('/payment/thankyou/{order_id}', function ($order_id) {
        return view('feature_thankyou', [
            'orderId' => $order_id,
            'errors' => session('errors')
        ]);
    })->name('feature.payment.thankyou');

    Route::get('/payment/cancel', function () {
        return response()->json(['success' => false, 'message' => 'Payment cancelled by user']);
    })->name('feature.payment.cancel');

    Route::get('/pay/{orderId}', function ($orderId, Request $request) {
        $sessionId = $request->query('session_id');
        $order = \App\Models\FeatureAdsPayment::where('order_id', $orderId)->first();

        if (!$order) {
            return "❌ Invalid or expired order.";
        }

        return view('feature_checkout', [
            'sessionId' => $sessionId,
            'amount' => $order->amount,
            'orderId' => $orderId
        ]);
    });
});

Route::prefix('gateway')->group(function () {
    Route::get('/test', [AndroidPaymentController::class, 'testApi']);
    Route::post('/create-hosted-checkout', [AndroidPaymentController::class, 'createHostedCheckout']);
    Route::post('/check-payment-status', [AndroidPaymentController::class, 'checkPaymentStatus']);
    Route::post('/payment-callback', [AndroidPaymentController::class, 'paymentCallback']);

    Route::get('/payment/thankyou/{order_id}', function ($order_id) {
        return view('feature_thankyou', [
            'orderId' => $order_id,
            'errors' => session('errors')
        ]);
    })->name('gateway.payment.thankyou');

    Route::get('/pay/{orderId}', function ($orderId, Request $request) {
        $sessionId = $request->query('session_id');
        $order = \App\Models\PaymentTemp::where('order_id', $orderId)->first();

        if (!$order) {
            return "❌ Invalid or expired order.";
        }

        return view('mobile_checkout', [
            'sessionId' => $sessionId,
            'amount' => $order->amount,
            'orderId' => $orderId
        ]);
    });
});

Route::post('/scan-qr', [TicketController::class, 'scanQr']);

Route::group(['namespace' => 'Api'], function () {

    Route::post('register', 'AuthController@register');
    Route::post('user-login', 'AuthController@login');
    Route::post('social-login', 'AuthController@userSocialLogin');
    Route::post('forget-password', 'AuthController@forgetPassword');
    Route::post('confirm-token', 'AuthController@confirmToken');
    Route::post('submit-reset-password', 'AuthController@submitResetPassword');

     Route::get('/intro-video', 'HomeController@introvideo');

    Route::post('/create-session', [BankAlfalahPaymentController::class, 'createCheckoutSession']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('user-location', 'AuthController@userLocation');
        Route::get('edit-profile', 'AuthController@editProfile');
        Route::post('update-profile', 'AuthController@updateProfile');
        Route::post('update-password', 'AuthController@updatePassword');
        Route::post('location', 'AuthController@location');
        Route::get('is_verify', 'AuthController@is_verify');

        Route::post('create-event', 'EventController@createEvent');
        Route::get('events', 'EventController@getEvents');
        Route::get('event/{id}', 'EventController@event');
	 	Route::get('entertainer/{entertainer_id}/approved-events', 'EventController@getEntertainerApprovedEvents');
		Route::get('venue/{venue_id}/approved-events', 'EventController@getVenueProviderApprovedEvents');

        Route::get('entertainer-talent', 'EventController@entertainer_tallents');
        Route::get('user-events', 'EventController@userEvents');
        Route::post('event', 'EventController@getEvent');
        Route::get('event_entertainer/{id}', 'EventController@getEventEntertainers');
        Route::post('update-event/{id}', 'EventController@updateEvent');
        Route::get('event-feature-packages', 'EventController@getEventFeaturePackages');
        Route::post('event-select-package', 'EventController@EventSelectPackage');
        Route::post('join-event', 'EventController@joinEvent');
        Route::post('delete-event/{id}', 'EventController@delete_event');
        // Route::get('event-feature-packages', 'EventController@getEventFeaturePackages');
        Route::get('my-booking-event', 'EventController@myBookingEvent');
        Route::get('my-booking', 'EventController@myBooking');
        Route::get('my-ticket', 'EventController@myTicket');
        Route::get('history-ticket', 'EventController@myHistory');
        Route::get('upcoming-event', 'EventController@upComingEvent');
        // Route::post('scan-qr', 'EventController@scanQr');
        Route::post('check-ticket', 'EventController@checkTicket');
        Route::get('notification-watched/{id}', 'EventController@notification');

        Route::post('create-entertainer', 'EntertainerController@createEntertainer');
        Route::get('entertainers', 'EntertainerController@getEntertainer');
        Route::get('entertainer/{id}', 'EntertainerController@getSingleEntertainer');
        Route::post('update-entertainer/{id}', 'EntertainerController@updateEntertainer');
        Route::get('entertainer-price-package/{id}', 'EntertainerController@getEntertainerPricePackage');
        Route::get('entertainer-feature-packages', 'EntertainerController@getEntertainerFeaturePackages');
        Route::post('entertainer-select-package', 'EntertainerController@EntertainerSelectPackage');
        Route::get('talent-categories', 'EntertainerController@talentCategory');
        Route::post('delete-talent/{id}', 'EntertainerController@delete_talent');
        Route::get('talent-reviews/{id}', 'EntertainerController@entertainer_reviews');
        Route::get('single-talent/{id}', 'EntertainerController@getSingleTalent');
        Route::post('approved-request-for-entertainer', 'EntertainerController@approvedRequestForEvent');
        Route::get('talent-package/{id}', 'EntertainerController@talentPackage');

        Route::get('admin-id', 'ChatController@getAdmin');

        Route::get('venues', 'VenueController@getVenues');
        Route::get('venue/{id}', 'VenueController@getSingleVenue');
        Route::get('my-venues', 'VenueController@myVenues');
        Route::post('venue-location', 'VenueController@venue_location');
        Route::get('venue-pricing/{id}', 'VenueController@venuePricing');
        Route::post('create-venue', 'VenueController@createVenue');
        Route::post('delete-venue/{id}', 'VenueController@destroy');
        Route::get('edit-venue/{id}', 'VenueController@editVenue');
        Route::post('update-venue/{id}', 'VenueController@updateVenue');
        Route::get('venue-feature-packages', 'VenueController@getVenueFeaturePackages');
        Route::post('venue-select-package', 'VenueController@VenueSelectPackage');
        Route::get('venue-category', 'VenueController@venue_category');
        Route::post('book-venue', 'VenueController@book_venue');
        Route::get('venue-reviews/{id}', 'VenueController@venue_reviews');
        Route::get('single-venue/{id}', 'VenueController@singleVenue');
        Route::post('approved-request-for-venue', 'VenueController@approvedRequestForVenue');

        Route::get('venues-reviews', 'ReviewController@getVenuesReviews');
        Route::get('venue-review/{id}', 'ReviewController@getSingleVenueReview');
        Route::get('events-reviews', 'ReviewController@getEventsReviews');
        Route::get('event-review/{id}', 'ReviewController@getSingleEventReview');
        Route::get('entertainers-reviews', 'ReviewController@getEntertainersReviews');
        Route::get('entertainer-review/{id}', 'ReviewController@getSingleEntertainerReview');
        Route::post('create-review', 'ReviewController@createReviews');

        Route::get('feature-events', 'HomeController@featureEvents');

        Route::get('home-page', 'HomeController@HomePage');
        Route::get('top-rated-events', 'HomeController@topRatedEvents');
        Route::get('top-rated-entertainers', 'HomeController@topRatedEntertainers');
        Route::get('top-rated-venues', 'HomeController@topRatedVenues');
        Route::get('term-of-use', 'HomeController@terms');

        Route::get('get-admin', 'ChatController@index');
        Route::post('store-message', 'ChatController@store');
        Route::get('single-message-delete', 'ChatController@messageDeleted');
        Route::get('messages', 'ChatController@get_ChatMessages');
        Route::post('all-message-delete', 'ChatController@allMessageDeleted');

        Route::get('user-notifications', 'NotificationController@getUserNotification');
        Route::post('entertainer-request', 'NotificationController@requestEventEntertainer');
        Route::post('venue-request', 'NotificationController@requestEventVenue');

        Route::post('search-data', 'SearchController@searchData');
        Route::post('search-filter', 'SearchController@searchFilter');
        Route::post('my-ads-filter', 'SearchController@myAdsFilter');

        Route::post('payment', 'PaymentController@payment');

        Route::post('request-for-delete-account', 'AuthController@deleteAccountRequest');
        Route::post('request-for-event-delete-account/{id}', 'AuthController@eventDeleteAccountRequest');
        Route::get('get-event-delete-account', 'AuthController@getEventDeleteAccount');

        Route::post('/bankalfalah/create-checkout-session', [BankAlfalahPaymentController::class, 'createCheckoutSession']);
        Route::post('/payment/make-payment', [BankAlfalahPaymentController::class, 'makePayment']);
        Route::get('/bankalfalah/retrieve-order/{orderId}', [BankAlfalahPaymentController::class, 'retrieveOrder']);

        
    });
});
