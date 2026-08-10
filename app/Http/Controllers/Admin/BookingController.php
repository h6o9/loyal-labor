<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\GlobalSetting\app\Models\Setting;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    private function bookingExpiryMinutes(): int
    {
        $value = null;
        try {
            $value = getSettings('booking_request_expiry_minutes');
        } catch (\Throwable $e) {
            $value = null;
        }

        if (is_object($value)) {
            $value = null;
        }

        $minutes = (int) ($value ?: 5);
        return $minutes < 1 ? 5 : $minutes;
    }

    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('bookings.view');

        $query = Booking::with(['customer', 'technician'])->latest();

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('reference', function ($booking) {
                    if ($booking->booking_reference) {
                        return '<span class="badge badge-warning font-weight-bold" style="font-size:14px; letter-spacing:1px;">'
                            . e($booking->booking_reference) . '</span>';
                    }

                    return '<span class="text-muted">Pending confirmation</span>';
                })
                ->addColumn('customer_name', function ($booking) {
                    return e(optional($booking->customer)->name);
                })
                ->addColumn('technician_name', function ($booking) {
                    return e(optional($booking->technician)->name);
                })
                ->addColumn('status_badge', function ($booking) {
                    $color = $booking->status === 'accepted' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'secondary');
                    return '<span class="badge badge-' . $color . '">' . ucfirst($booking->status) . '</span>';
                })
                ->addColumn('date_time', function ($booking) {
                    return trim($booking->service_date . ' ' . $booking->time_slot);
                })
                ->addColumn('action', function ($booking) {
                    if (!checkAdminHasPermission('bookings.delete')) {
                        return '';
                    }

                    return '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="'
                        . route('admin.bookings.destroy', $booking->id) . '"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['reference', 'status_badge', 'action'])
                ->make(true);
        }

        return view('admin.bookings.index');
    }

    public function settings()
    {
        checkAdminHasPermissionAndThrowException('bookings.settings.view');

        $minutes = $this->bookingExpiryMinutes();

        return view('admin.bookings.settings', compact('minutes'));
    }

    public function updateSettings(Request $request)
    {
        checkAdminHasPermissionAndThrowException('bookings.settings.edit');

        $request->validate([
            'booking_request_expiry_minutes' => 'required|integer|min:1|max:1440',
        ]);

        $minutes = (string) $request->booking_request_expiry_minutes;
        $setting = Setting::where('key', 'booking_request_expiry_minutes')->first();

        if ($setting) {
            $setting->update(['value' => $minutes]);
        } else {
            $setting = new Setting();
            $setting->key = 'booking_request_expiry_minutes';
            $setting->value = $minutes;
            $setting->save();
        }

        Cache::forget('setting');

        return redirect()
            ->route('admin.bookings.settings')
            ->with('success', 'Booking request expiry minutes updated.');
    }

    public function destroy(Booking $booking)
    {
        checkAdminHasPermissionAndThrowException('bookings.delete');

        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted successfully.');
    }
}
