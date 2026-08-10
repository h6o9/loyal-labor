<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Order\app\Http\Enums\OrderStatus;
use Modules\Order\app\Models\Order;
use Modules\Order\app\Models\OrderStatusChangeHistory;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Product\app\Models\Product;
use Modules\Product\app\Models\ProductReview;

class SellerDashboardController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $user = Auth::guard('web')->user();

        $seller = $user->seller;

        $todayOrders = Order::with('user')->where('vendor_id', vendorId())->orderBy('id', 'desc')->whereDay('created_at', now()->day)->get();

        $totalOrders = Order::with('user')->where('vendor_id', vendorId())->orderBy('id', 'desc')->get();

        $monthlyOrders = Order::with('user')->whereHas('items', function ($query) use ($user) {
            $query->where('vendor_id', $user->seller->id);
        })->orderBy('id', 'desc')->whereMonth('created_at', now()->month)->get();

        $yearlyOrders = Order::with('user')->whereHas('items', function ($query) use ($user) {
            $query->where('vendor_id', $user->seller->id);
        })->orderBy('id', 'desc')->whereYear('created_at', now()->year)->get();

        $products = Product::where('vendor_id', $seller->id)->get();

        $reviews = ProductReview::where('order_id', $seller->id)->get();
        $reports = [];

        $totalWithdraw = WithdrawRequest::where('vendor_id', $seller->id)
            ->where('status', 'approved')
            ->sum('total_amount');

        $totalPendingWithdraw = WithdrawRequest::where('vendor_id', $seller->id)
            ->where('status', 'pending')
            ->sum('total_amount');

        $availableYears = Order::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray() ?? [];

        $cardData = [];

        $cardData['availableYears'] = $availableYears ?? [];

        $month = request()->get('month', now()->month);
        $year  = request()->get('year', now()->year);

        $baseQuery = Order::whereHas('items', function ($query) use ($user) {
            $query->where('vendor_id', $user->seller->id);
        })->whereMonth('created_at', $month)
            ->whereYear('created_at', $year);

        $cardData['totalMonthlyOrders'] = (clone $baseQuery)->count();

        $cardData['totalMonthlyPendingOrders'] = (clone $baseQuery)
            ->where('order_status', OrderStatus::PENDING->value)
            ->count();

        $cardData['totalMonthlyShippedOrders'] = (clone $baseQuery)
            ->where('order_status', OrderStatus::SHIPPED->value)
            ->count();

        $cardData['totalMonthlyDeliveredOrders'] = (clone $baseQuery)
            ->where('order_status', OrderStatus::DELIVERED->value)
            ->count();

        [$cardData['sellChartLabels'], $cardData['sellChartValues'], $cardData['sellChartAmount']]   = $this->getBalanceData();
        [$cardData['salesChartLabels'], $cardData['salesChartValues'], $cardData['salesChartCount']] = $this->getSalesCountData();

        return view('seller.dashboard', compact('todayOrders', 'totalOrders', 'monthlyOrders', 'yearlyOrders', 'products', 'reviews', 'reports', 'seller', 'totalWithdraw', 'totalPendingWithdraw', 'cardData', ));
    }

    /**
     * @param $vendorId
     */
    public function getBalanceData($vendorId = null)
    {
        $month    = request()->get('month', now()->month);
        $year     = request()->get('year', now()->year);
        $vendorId = is_null($vendorId) ? vendorId() : $vendorId;

        // Get first and last day of selected month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        $orderData = Order::whereHas('items', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })
            ->join('order_payment_details', 'orders.order_payment_details_id', '=', 'order_payment_details.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_payment_details.payable_amount_without_rate) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray() ?? [];

        // Fill missing dates with zero
        $labels = [];
        $values = [];
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->addDay());

        foreach ($period as $date) {
            $formatted = $date->format('d-m-Y');
            $labels[]  = $formatted;
            $values[]  = isset($orderData[$date->format('Y-m-d')]) ? (float) $orderData[$date->format('Y-m-d')] : 0;
        }

        // return total amount
        $totalAmount = array_sum($values);

        return [
            $labels,
            $values,
            $totalAmount,
        ];
    }

    /**
     * @return mixed
     */
    public function getSalesCountData($vendorId = null)
    {
        $month    = request()->get('month', now()->month);
        $year     = request()->get('year', now()->year);
        $vendorId = is_null($vendorId) ? vendorId() : $vendorId;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate   = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        // Sum of qty from order_details per day
        $orderData = Order::whereHas('items', function ($q) use ($vendorId) {
            $q->where('vendor_id', $vendorId);
        })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with(['items' => function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            }])
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('Y-m-d');
            })
            ->map(function ($orders) {
                return $orders->sum(function ($order) {
                    return $order->items->sum('qty');
                });
            })
            ->toArray();

        $labels = [];
        $values = [];
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->copy()->addDay());

        foreach ($period as $date) {
            $formatted = $date->format('d-m-Y');
            $labels[]  = $formatted;
            $values[]  = isset($orderData[$date->format('Y-m-d')]) ? (int) $orderData[$date->format('Y-m-d')] : 0;
        }

        $totalQtySold = array_sum($values);

        return [
            $labels,
            $values,
            $totalQtySold,
        ];
    }

    /**
     * @return mixed
     */
    public function allNotifications(Request $request)
    {
        $vendorId = vendorId();

        $allNotifications = OrderStatusChangeHistory::whereHas('order', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })
            ->with([
                'order',
                'changedByUser',
                'changedByAdmin',
            ])
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $query->whereHas('order', function ($query) use ($request) {
                    $query->whereAny(['order_id'], 'like', '%' . $request->keyword . '%')
                        ->orWhereHas('billingAddress', function ($q) use ($request) {
                            $q->whereAny(['name', 'email', 'phone', 'address', 'city', 'state', 'country', 'zip_code'], 'like', '%' . $request->keyword . '%');
                        })
                        ->orWhereHas('paymentDetails', function ($q) use ($request) {
                            $q->whereAny(['transaction_id', 'payment_method'], 'like', '%' . $request->keyword . '%');
                        })
                        ->orWhereHas('shippingAddress', function ($q) use ($request) {
                            $q->whereAny(['name', 'email', 'phone', 'address', 'city', 'state', 'country', 'zip_code'], 'like', '%' . $request->keyword . '%');
                        })
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->whereAny(['name', 'email', 'phone', 'address'], 'like', '%' . $request->keyword . '%');
                        });
                })->orWhereAny(['type', 'to_status'], 'like', '%' . $request->keyword . '%');
            })
            ->latest()
            ->paginate(30);

        return view('seller.all-notifications', compact('allNotifications'));
    }
}
