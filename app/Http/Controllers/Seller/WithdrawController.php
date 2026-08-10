<?php

namespace App\Http\Controllers\Seller;

use App\Facades\MailSender;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Wallet\app\Models\WalletHistory;
use Yajra\DataTables\Facades\DataTables;

class WithdrawController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();

        $seller = $user->seller;

        $query = WithdrawRequest::where([
            'user_id'   => $user->id,
            'vendor_id' => vendorId(),
        ]);

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('amount', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('charge_amount', function ($withdraw) {
                    return defaultCurrency($withdraw->total_amount - $withdraw->withdraw_amount);
                })
                ->addColumn('total_amount_col', function ($withdraw) {
                    return defaultCurrency($withdraw->total_amount);
                })
                ->addColumn('withdraw_amount_col', function ($withdraw) {
                    return defaultCurrency($withdraw->withdraw_amount);
                })
                ->addColumn('status_badge', function ($withdraw) {
                    if ($withdraw->status == 'approved') {
                        return '<span class="badge bg-success">' . __('Approved') . '</span>';
                    } elseif ($withdraw->status == 'rejected') {
                        return '<span class="badge bg-danger">' . __('Rejected') . '</span>';
                    }

                    return '<span class="badge bg-warning">' . __('Pending') . '</span>';
                })
                ->addColumn('action', function ($withdraw) {
                    return '<a class="btn btn-primary btn-sm" href="' . route('seller.my-withdraw.show', $withdraw->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        $currentWalletAmount = $user->wallet_balance;

        $totalWalletCreditAmount = WalletHistory::where([
            'user_id'          => $user->id,
            'vendor_id'        => vendorId(),
            'transaction_type' => 'credit',
        ])->sum('amount');

        $totalWalletDebitAmount = WalletHistory::where([
            'user_id'          => $user->id,
            'vendor_id'        => vendorId(),
            'transaction_type' => 'debit',
        ])->sum('amount');

        $totalRejectedRequest = WithdrawRequest::where([
            'user_id'   => $user->id,
            'vendor_id' => vendorId(),
            'status'    => 'rejected',
        ])->count();

        return view('vendor::withdraw.index', compact('currentWalletAmount', 'totalWalletCreditAmount', 'totalWalletDebitAmount', 'totalRejectedRequest'));
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        $withdraw = WithdrawRequest::find($id);

        return view('vendor::withdraw.show_withdraw', compact('withdraw'));
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $methods = WithdrawMethod::whereStatus('active')->get();

        $user = Auth::guard('web')->user();

        $currentWalletAmount = $user->wallet_balance;

        $seller = $user->seller;

        $totalPendingRequest = WithdrawRequest::where([
            'user_id'   => $user->id,
            'vendor_id' => vendorId(),
            'status'    => 'pending',
        ])->count();

        return view('vendor::withdraw.create_withdraw', compact('methods', 'currentWalletAmount', 'totalPendingRequest'));
    }

    /**
     * @param $id
     */
    public function getWithDrawAccountInfo($id)
    {
        $method = WithdrawMethod::whereId($id)->first();

        return view('vendor::withdraw.withdraw_account_info', compact('method'));
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $rules = [
            'method_id'       => 'required|exists:withdraw_methods,id',
            'withdraw_amount' => 'required|numeric|max:' . Auth::guard('web')->user()->wallet_balance,
            'account_info'    => 'required',
        ];

        $customMessages = [
            'method_id.required'       => __('Payment Method filed is required'),
            'method_id.exists'         => __('Please select valid payment method'),
            'withdraw_amount.required' => __('Withdraw amount filed is required'),
            'withdraw_amount.numeric'  => __('Please provide valid numeric number'),
            'withdraw_amount.max'      => __('Your wallet balance is not enough to withdraw'),
            'account_info.required'    => __('Account filed is required'),
        ];

        $request->validate($rules, $customMessages);

        $user = Auth::guard('web')->user();

        if (WithdrawRequest::where(['user_id' => $user->id, 'vendor_id' => vendorId(), 'status' => 'pending'])->count() >= 1) {
            $notification = __('You already have a pending withdraw request');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $totalBalanceAmount = $user->wallet_balance;

        $method = WithdrawMethod::whereId($request->method_id)->firstOrFail();

        if ($totalBalanceAmount >= $request->withdraw_amount && $request->withdraw_amount >= $method->min_amount && $request->withdraw_amount <= $method->max_amount) {
            $withdraw = new WithdrawRequest();

            $withdraw->user_id   = $user->id;
            $withdraw->vendor_id = vendorId();
            $withdraw->method    = $method->name;

            $requestedAmount       = floatval($request->withdraw_amount);
            $withdrawChargePercent = floatval($method->withdraw_charge);

            // Make sure it's calculated with high precision
            $withdrawFee = round(($withdrawChargePercent / 100) * $requestedAmount, 2);
            $finalAmount = round($requestedAmount - $withdrawFee, 2);

            $withdraw->total_amount    = $requestedAmount;
            $withdraw->withdraw_amount = $finalAmount;
            $withdraw->withdraw_charge = $withdrawChargePercent;
            $withdraw->account_info    = $request->account_info;

            $withdraw->save();

            $this->sendRequestReceivedMail($user, $withdraw);

            $message = "A new withdraw request has been received from {$user->name} ({$user->email}). Please check the details.";

            notifyAdmin("Withdraw Request Received", $message, 'info', link: route('admin.show-withdraw', ['id' => $withdraw->id]));

            $notification = __('Withdraw request send successfully, please wait for admin approval');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('seller.my-withdraw.index')->with($notification);

        } else {
            $notification = __('Your amount range is not available');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

    }

    /**
     * @param $vendorUser
     * @param $order
     * @param $walletHistory
     */
    private static function sendRequestReceivedMail($vendorUser, $withdraw)
    {
        try {
            $email = $vendorUser->email;

            [$subject, $message] = MailSender::fetchEmailTemplate('requested_withdraw', [
                'user_name' => $vendorUser->name ?? "Name Missing!",
            ]);

            $link = [
                "Withdraw Details" => route('seller.my-withdraw.show', ['my_withdraw' => $withdraw->id]),
            ];

            MailSender::sendMail($email, $subject, $message, $link);

        } catch (Exception $e) {
            logError("Unable to send withdraw request mail", $e);
        }
    }
}
