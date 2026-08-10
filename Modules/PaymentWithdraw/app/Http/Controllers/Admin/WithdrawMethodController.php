<?php

namespace Modules\PaymentWithdraw\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GlobalMailTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\app\Http\Enums\PaymentStatus;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;
use Modules\Wallet\app\Models\WalletHistory;
use Yajra\DataTables\Facades\DataTables;

class WithdrawMethodController extends Controller
{
    use GlobalMailTrait;

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $query = WithdrawMethod::query();

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('description', 'like', '%' . $request->keyword . '%')
                ->orWhere('min_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('max_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%');
        });
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('user'), function ($q) use ($request) {
            $q->where('user_id', $request->user);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('min_amount_col', function ($method) {
                    return currency($method->min_amount);
                })
                ->addColumn('max_amount_col', function ($method) {
                    return currency($method->max_amount);
                })
                ->addColumn('charge_col', function ($method) {
                    return $method->withdraw_charge . '%';
                })
                ->addColumn('status_toggle', function ($method) {
                    return '<input id="status_toggle_' . $method->id . '" data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="changeStatus(' . $method->id . ')"' . ($method->status == 'active' ? ' checked' : '') . '>';
                })
                ->addColumn('action', function ($method) {
                    return '<a class="m-1 text-white btn btn-sm btn-warning" href="' . route('admin.withdraw-method.edit', $method->id) . '" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a> '
                        . '<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm" onclick="deleteData(' . $method->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                })
                ->rawColumns(['status_toggle', 'action'])
                ->make(true);
        }

        return view('paymentwithdraw::admin.method.index');
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        return view('paymentwithdraw::admin.method.create');
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $rules = [
            'name'            => 'required',
            'minimum_amount'  => 'required|numeric',
            'maximum_amount'  => 'required|numeric',
            'withdraw_charge' => 'required|numeric',
            'description'     => 'required',
        ];
        $customMessages = [
            'name.required'            => __('Name is required'),
            'minimum_amount.required'  => __('Min amount is required'),
            'maximum_amount.required'  => __('Max amount is required'),
            'withdraw_charge.required' => __('Charge is required'),
            'description.required'     => __('Description is required'),
        ];
        $request->validate($rules, $customMessages);

        $method                  = new WithdrawMethod;
        $method->name            = $request->name;
        $method->min_amount      = $request->minimum_amount;
        $method->max_amount      = $request->maximum_amount;
        $method->withdraw_charge = $request->withdraw_charge;
        $method->description     = $request->description;
        $method->status          = $request->status;
        $method->save();

        $notification = __('Create Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');
        $method = WithdrawMethod::find($id);

        return view('paymentwithdraw::admin.method.edit', compact('method'));
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function update(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $rules = [
            'name'            => 'required',
            'minimum_amount'  => 'required|numeric',
            'maximum_amount'  => 'required|numeric',
            'withdraw_charge' => 'required|numeric',
            'description'     => 'required',
        ];
        $customMessages = [
            'name.required'            => __('Name is required'),
            'minimum_amount.required'  => __('Min amount is required'),
            'maximum_amount.required'  => __('Max amount is required'),
            'withdraw_charge.required' => __('Charge is required'),
            'description.required'     => __('Description is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $method                  = WithdrawMethod::find($id);
        $method->name            = $request->name;
        $method->min_amount      = $request->minimum_amount;
        $method->max_amount      = $request->maximum_amount;
        $method->withdraw_charge = $request->withdraw_charge;
        $method->description     = $request->description;
        $method->status          = $request->status;
        $method->save();

        $notification = __('Update Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    /**
     * @param $id
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $method = WithdrawMethod::find($id);
        $method->delete();

        $notification = __('Delete Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-method.index')->with($notification);
    }

    /**
     * @param Request $request
     */
    public function withdraw_list(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $query = WithdrawRequest::query();

        $query->with('user');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('method', 'like', '%' . $request->keyword . '%')
                ->orWhere('total_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%')
                ->orWhere('account_info', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $query->when($request->filled('user'), function ($q) use ($request) {
            $q->where('user_id', $request->user);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->ajax()) {
            return $this->withdrawsDatatable($query);
        }

        $title = __('Withdraw request');

        $users = User::select('name', 'id')->whereHas('seller')->get();

        return view('paymentwithdraw::admin.index', compact('title', 'users'));
    }

    /**
     * @param Request $request
     */
    public function pending_withdraw_list(Request $request)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $query = WithdrawRequest::query();

        $query->with('user')->where('status', 'pending');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $q->where('method', 'like', '%' . $request->keyword . '%')
                ->orWhere('total_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_amount', 'like', '%' . $request->keyword . '%')
                ->orWhere('withdraw_charge', 'like', '%' . $request->keyword . '%')
                ->orWhere('account_info', 'like', '%' . $request->keyword . '%');
        });

        $query->when($request->filled('user'), function ($q) use ($request) {
            $q->where('user_id', $request->user);
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('id', $request->order_by == 1 ? 'asc' : 'desc');
        });

        if ($request->ajax()) {
            return $this->withdrawsDatatable($query);
        }

        $title = __('Pending withdraw');
        $users = User::select('name', 'id')->whereHas('seller')->get();

        return view('paymentwithdraw::admin.index', compact('title', 'users'));
    }

    /**
     * Build the shared Yajra DataTables JSON response used by both withdraw_list()
     * and pending_withdraw_list(), since they render the same paymentwithdraw::admin.index view.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    private function withdrawsDatatable($query)
    {
        return DataTables::of($query)
            ->addColumn('user_name', function ($withdraw) {
                return '<a href="' . route('admin.customer-show', $withdraw->user_id) . '">' . e(optional($withdraw->user)->name) . '</a>';
            })
            ->addColumn('charge_col', function ($withdraw) {
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
                }
                if ($withdraw->status == 'rejected') {
                    return '<span class="badge bg-danger">' . __('Rejected') . '</span>';
                }
                return '<span class="badge bg-danger">' . __('Pending') . '</span>';
            })
            ->addColumn('action', function ($withdraw) {
                return '<a class="btn btn-primary btn-sm" href="' . route('admin.show-withdraw', $withdraw->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a> '
                    . '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" onclick="deleteData(' . $withdraw->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
            })
            ->rawColumns(['user_name', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * @param $id
     */
    public function show_withdraw($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw = WithdrawRequest::find($id);

        return view('paymentwithdraw::admin.show', compact('withdraw'));
    }

    /**
     * @param $id
     */
    public function destroy_withdraw($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw = WithdrawRequest::findOrFail($id);

        if ($withdraw->status == 'approved') {
            $notification = __('You can not delete approved withdraw request');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.withdraw-list')->with($notification);
        }

        $withdraw->delete();

        $notification = __('Delete Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-list')->with($notification);
    }

    /**
     * @param $id
     */
    public function approved_withdraw($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw = WithdrawRequest::find($id);
        $user     = User::findOrFail($withdraw->user_id);

        try {
            DB::beginTransaction();

            $withdraw->status        = 'approved';
            $withdraw->approved_date = now();
            $withdraw->save();

            if ($withdraw->status == 'approved') {
                // Cast to string for bcsub to maintain precision
                $userWalletBalance   = number_format((float) $user->wallet_balance, 2, '.', '');
                $walletHistoryAmount = number_format((float) $withdraw->total_amount, 2, '.', '');

                // Subtract using bcsub for accurate decimal arithmetic
                $userWalletNewBalance = bcsub($userWalletBalance, $walletHistoryAmount, 2);

                // Update the wallet balance
                $user->wallet_balance = $userWalletNewBalance;
                $user->save();

                $wallet                      = new WalletHistory();
                $wallet->user_id             = $withdraw->user_id;
                $wallet->vendor_id           = $withdraw->vendor_id;
                $wallet->withdraw_request_id = $withdraw->id;
                $wallet->amount              = $withdraw->total_amount;
                $wallet->transaction_id      = uniqid('withdraw_');
                $wallet->payment_gateway     = $withdraw->method;
                $wallet->payment_status      = PaymentStatus::COMPLETED->value;
                $wallet->transaction_type    = 'debit';
                $wallet->save();

                notifyAdmin(
                    'Withdraw request approval',
                    "A withdraw request has been approved for {$user->name} ({$user->email}). Please check the details.",
                    'success',
                    link: route('admin.show-withdraw', ['id' => $withdraw->id])
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logError("Unable to approve withdraw request", $e);

            return redirect()->route('admin.withdraw-list')->with([
                'message'    => __('Failed to approve withdraw request. Please try again later.'),
                'alert-type' => 'error',
            ]);
        }

        $amount = defaultCurrency($withdraw->withdraw_amount);

        [$subject, $message] = $this->fetchEmailTemplate('approved_withdraw', ['user_name' => $user->name, 'amount' => $amount, 'method' => $withdraw->method]);

        $this->sendMail($user->email, $subject, $message, [
            'Withdraw Details' => route('seller.my-withdraw.show', ['my_withdraw' => $withdraw->id]),
        ]);

        $notification = __('Withdraw request approval successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.withdraw-list')->with($notification);

    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('payment.withdraw.management');

        $withdraw_method = WithdrawMethod::find($id);
        $status          = $withdraw_method->status == 'active' ? 'inactive' : 'active';
        $withdraw_method->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
