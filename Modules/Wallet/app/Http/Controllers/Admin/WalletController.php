<?php

namespace Modules\Wallet\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Traits\GlobalMailTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\Wallet\app\Http\Controllers\WalletController as UserWalletController;
use Modules\Wallet\app\Models\WalletHistory;
use Yajra\DataTables\Facades\DataTables;

class WalletController extends Controller
{
    use GlobalMailTrait;

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');

        $query = WalletHistory::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    })
                        ->orWhereHas('vendor', function ($q) use ($keyword) {
                            $q->where('shop_name', 'like', '%' . $keyword . '%')
                                ->orWhere('email', 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('order', function ($q) use ($keyword) {
                            $q->whereAny(['order_id', 'transaction_id'], 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('orderDetails', function ($q) use ($keyword) {
                            $q->whereAny(['product_name', 'product_sku'], 'like', '%' . $keyword . '%');
                        })->orWhere('transaction_id', 'like', '%' . $keyword . '%');
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('payment_status', $request->status);
            })
            ->when($request->filled('vendor_id'), function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })
            ->when($request->filled('order_by'), function ($q) use ($request) {
                $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
            }, function ($q) {
                $q->latest();
            });

        if ($request->ajax()) {
            return $this->walletHistoriesDataTable($query);
        }

        $data['totalCreditAmount']        = WalletHistory::where('transaction_type', 'credit')->sum('amount');
        $data['totalDebitAmount']         = WalletHistory::where('transaction_type', 'debit')->sum('amount');
        $data['totalPendingCreditAmount'] = WalletHistory::where('transaction_type', 'credit')->where('payment_status', 'pending')->sum('amount');

        $data['sellers'] = Vendor::with('user')->get();

        $data['title'] = __('Wallet History');

        return view('wallet::admin.index', $data);
    }

    /**
     * @param Request $request
     */
    public function pending_wallet_payment(Request $request)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');

        $query = WalletHistory::where('payment_status', 'pending')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    })
                        ->orWhereHas('vendor', function ($q) use ($keyword) {
                            $q->where('shop_name', 'like', '%' . $keyword . '%')
                                ->orWhere('email', 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('order', function ($q) use ($keyword) {
                            $q->whereAny(['order_id', 'transaction_id'], 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('orderDetails', function ($q) use ($keyword) {
                            $q->whereAny(['product_name', 'product_sku'], 'like', '%' . $keyword . '%');
                        })->orWhere('transaction_id', 'like', '%' . $keyword . '%');
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('payment_status', $request->status);
            })
            ->when($request->filled('vendor_id'), function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })
            ->when($request->filled('order_by'), function ($q) use ($request) {
                $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
            }, function ($q) {
                $q->latest();
            });

        if ($request->ajax()) {
            return $this->walletHistoriesDataTable($query);
        }

        $data['totalCreditAmount']        = WalletHistory::where('transaction_type', 'credit')->sum('amount');
        $data['totalDebitAmount']         = WalletHistory::where('transaction_type', 'debit')->sum('amount');
        $data['totalPendingCreditAmount'] = WalletHistory::where('transaction_type', 'credit')
            ->where('payment_status', 'pending')
            ->sum('amount');

        $data['sellers'] = Vendor::with('user')->get();
        $data['title']   = __('Pending Wallet Payment');

        return view('wallet::admin.index', $data);
    }

    /**
     * @param Request $request
     */
    public function rejected_wallet_payment(Request $request)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');

        $query = WalletHistory::where('payment_status', 'rejected')
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    })
                        ->orWhereHas('vendor', function ($q) use ($keyword) {
                            $q->where('shop_name', 'like', '%' . $keyword . '%')
                                ->orWhere('email', 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('order', function ($q) use ($keyword) {
                            $q->whereAny(['order_id', 'transaction_id'], 'like', '%' . $keyword . '%');
                        })
                        ->orWhereHas('orderDetails', function ($q) use ($keyword) {
                            $q->whereAny(['product_name', 'product_sku'], 'like', '%' . $keyword . '%');
                        })->orWhere('transaction_id', 'like', '%' . $keyword . '%');
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('payment_status', $request->status);
            })
            ->when($request->filled('vendor_id'), function ($q) use ($request) {
                $q->where('vendor_id', $request->vendor_id);
            })
            ->when($request->filled('order_by'), function ($q) use ($request) {
                $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
            }, function ($q) {
                $q->latest();
            });

        if ($request->ajax()) {
            return $this->walletHistoriesDataTable($query);
        }

        $data['totalCreditAmount']        = WalletHistory::where('transaction_type', 'credit')->sum('amount');
        $data['totalDebitAmount']         = WalletHistory::where('transaction_type', 'debit')->sum('amount');
        $data['totalPendingCreditAmount'] = WalletHistory::where('transaction_type', 'credit')
            ->where('payment_status', 'pending')
            ->sum('amount');

        $data['sellers'] = Vendor::with('user')->get();
        $data['title']   = __('Pending Wallet Payment');

        return view('wallet::admin.index', $data);
    }

    /**
     * Build the shared Yajra DataTables JSON response for the wallet-history
     * list view (admin.wallet-history / admin.pending-wallet-payment /
     * admin.rejected-wallet-payment all render the same columns).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    protected function walletHistoriesDataTable($query)
    {
        return DataTables::of($query)
            ->addColumn('user_name', function ($walletHistory) {
                return '<a href="' . route('admin.customer-show', $walletHistory->user_id) . '">' . e(optional($walletHistory->user)->name) . '</a>';
            })
            ->addColumn('order_info', function ($walletHistory) {
                if ($walletHistory->transaction_type == 'credit' && $walletHistory->order) {
                    return '<a href="' . route('admin.order', $walletHistory->order->order_id) . '" target="_blank">#' . e($walletHistory->order->order_id) . '</a>';
                }

                return __('N/A');
            })
            ->addColumn('for_info', function ($walletHistory) {
                if ($walletHistory->transaction_type == 'credit' && $walletHistory->orderDetails) {
                    return '<a href="' . route('admin.product.show', ['product' => $walletHistory->orderDetails->product_id]) . '" target="_blank" rel="noopener noreferrer">' . e($walletHistory->orderDetails->product_sku) . '</a>';
                }

                if ($walletHistory->transaction_type == 'debit' && $walletHistory->withdrawRequest) {
                    return '<a href="' . route('admin.show-withdraw', $walletHistory->withdrawRequest->id) . '" target="_blank" rel="noopener noreferrer">' . __('Show Withdraw') . '</a>';
                }

                return __('N/A');
            })
            ->editColumn('payment_gateway', function ($walletHistory) {
                return __($walletHistory->payment_gateway);
            })
            ->editColumn('amount', function ($walletHistory) {
                return currency($walletHistory->amount);
            })
            ->addColumn('transaction_type_badge', function ($walletHistory) {
                if ($walletHistory->transaction_type == 'credit') {
                    return '<span class="badge bg-success"><i class="fas fa-plus"></i> ' . __('Credit') . '</span>';
                }

                return '<span class="badge bg-danger"><i class="fas fa-minus"></i> ' . __('Debit') . '</span>';
            })
            ->addColumn('payment_status_badge', function ($walletHistory) {
                if ($walletHistory->payment_status == 'completed') {
                    return '<div class="badge bg-success">' . __('Completed') . '</div>';
                } elseif ($walletHistory->payment_status == 'rejected') {
                    return '<div class="badge bg-danger">' . __('Rejected') . '</div>';
                }

                return '<div class="badge bg-warning">' . __('Pending') . '</div>';
            })
            ->editColumn('created_at', function ($walletHistory) {
                return formattedDateTime($walletHistory->created_at);
            })
            ->addColumn('action', function ($walletHistory) {
                $html = '<a class="btn btn-primary btn-sm" href="' . route('admin.show-wallet-history', $walletHistory->id) . '"><i class="fa fa-eye"></i></a> ';
                $html .= '<a class="btn btn-danger btn-sm delete" data-url="' . route('admin.delete-wallet-history', $walletHistory->id) . '" href=""><i class="fa fa-trash"></i></a>';

                return $html;
            })
            ->rawColumns(['user_name', 'order_info', 'for_info', 'transaction_type_badge', 'payment_status_badge', 'action'])
            ->make(true);
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');

        $wallet_history = WalletHistory::findOrFail($id);

        return view('wallet::admin.show', ['wallet_history' => $wallet_history]);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');
        $wallet_history = WalletHistory::findOrFail($id);
        $wallet_history->delete();

        $notification = __('Payment delete successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.wallet-history')->with($notification);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function rejected_wallet_request(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');

        $request->validate([
            'subject'     => 'required',
            'description' => 'required',
        ], [
            'subject.required'     => __('Subject is required'),
            'description.required' => __('Description is required'),
        ]);

        $wallet_history                 = WalletHistory::findOrFail($id);
        $wallet_history->payment_status = PaymentStatus::REJECTED->value;
        $wallet_history->save();

        try {
            $user = User::findOrFail($wallet_history->user_id);

            //mail send
            $message = $request->description;
            $message = str_replace('[[name]]', $user->name, $message);
            $this->sendMail($user->email, $request->subject, $message);
        } catch (Exception $e) {
            logError("Error in sending wallet request rejection email: ", $e);
        }

        $notification = __('Wallet request rejected successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return back()->with($notification);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function approved_wallet_request($id)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');

        try {
            DB::beginTransaction();
            $wallet_history                 = WalletHistory::findOrFail($id);
            $wallet_history->payment_status = PaymentStatus::COMPLETED->value;
            $wallet_history->save();

            UserWalletController::updateWalletBalance($wallet_history->id, true);

            $notification = __('Wallet payment approval successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logError("Error in approving wallet request: ", $e);

            notifyAdmin(
                'Wallet Approval Error',
                'An error occurred while approving wallet request ID: ' . $id . '. Error: ' . $e->getMessage(),
                'danger',
                link: route('admin.show-wallet-history', $id)
            );

            $notification = __('Something went wrong, please try again');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
        }

        return back()->with($notification);

    }

    /**
     * @param $field
     */
    public function autoApproveStatus($field)
    {
        checkAdminHasPermissionAndThrowException('wallet.management');

        $currentValue = getSettings('wallet_amount_auto_approve') == 1 ? 0 : 1;

        Setting::where('key', 'wallet_amount_auto_approve')->update(['value' => $currentValue]);

        Cache::forget('setting');

        return response()->json([
            'status'  => true,
            'message' => __('Updated successfully'),
        ]);
    }
}
