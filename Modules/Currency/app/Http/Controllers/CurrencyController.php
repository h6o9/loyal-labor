<?php

namespace Modules\Currency\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Currency\app\Enums\AllCurrencyEnum;
use Modules\Currency\app\Models\MultiCurrency;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    /**
     * @var mixed
     */
    protected $all_currency_code;

    public function __construct()
    {
        $this->all_currency_code = AllCurrencyEnum::getAll();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('currency.view');

        $query = MultiCurrency::query();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('default_badge', function ($currency) {
                    if ($currency->is_default == 'yes') {
                        return '<span class="badge bg-success">' . __('Default') . '</span>';
                    }

                    return '<span class="badge bg-danger">' . __('No') . '</span>';
                })
                ->addColumn('status_badge', function ($currency) {
                    if ($currency->status == 'active') {
                        return '<span class="badge bg-success">' . __('Active') . '</span>';
                    }

                    return '<span class="badge bg-danger">' . __('Inactive') . '</span>';
                })
                ->addColumn('action', function ($currency) {
                    $html = '';

                    if (checkAdminHasPermission('currency.edit')) {
                        $html .= '<a class="m-1 text-white btn btn-sm btn-warning" href="' . route('admin.currency.edit', $currency->id) . '" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('currency.delete') && $currency->id != 1) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.currency.destroy', $currency->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['default_badge', 'status_badge', 'action'])
                ->make(true);
        }

        return view('currency::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        checkAdminHasPermissionAndThrowException('currency.create');
        $all_currency = $this->all_currency_code;

        return view('currency::create', compact('all_currency'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        checkAdminHasPermissionAndThrowException('currency.store');
        $rules = [
            'currency_name' => 'required|unique:multi_currencies',
            'country_code' => 'required|unique:multi_currencies',
            'currency_code' => [
                'required',
                'unique:multi_currencies',
                Rule::in(array_keys($this->all_currency_code)),
            ],
            'currency_icon' => 'required',
            'currency_rate' => 'required|numeric|gt:0',
        ];
        $customMessages = [
            'currency_name.required' => __('Currency name is required'),
            'currency_name.unique' => __('Currency name already exist'),
            'country_code.required' => __('Country code is required'),
            'country_code.unique' => __('Country code already exist'),
            'currency_code.required' => __('Currency code is required'),
            'currency_code.unique' => __('Currency code already exist'),
            'currency_code.in' => __('Currency code is not valid'),
            'currency_icon.required' => __('Currency icon is required'),
            'currency_rate.required' => __('Currency rate is required'),
            'currency_rate.numeric' => __('Currency rate must be number'),
        ];

        $request->validate($rules, $customMessages);

        $currency = new MultiCurrency;

        if ($request->is_default == 'yes') {
            MultiCurrency::where(['is_default' => 'yes'])->update(['is_default' => 'no']);
        }

        $currency->currency_name = $request->currency_name;
        $currency->country_code = $request->country_code;
        $currency->currency_code = $request->currency_code;
        $currency->currency_icon = $request->currency_icon;
        $currency->currency_rate = $request->currency_rate;
        $currency->is_default = $request->is_default;
        $currency->currency_position = $request->currency_position;
        $currency->status = $request->status;
        $currency->save();

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.currency.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('currency.edit');
        $currency = MultiCurrency::findOrFail($id);
        $all_currency = $this->all_currency_code;

        return view('currency::edit', compact('currency', 'all_currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('currency.update');
        $rules = [
            'currency_name' => 'required|unique:multi_currencies,currency_name,'.$id,
            'country_code' => 'required|unique:multi_currencies,country_code,'.$id,
            'currency_code' => [
                'required',
                'unique:multi_currencies,currency_code,'.$id,
                Rule::in(array_keys($this->all_currency_code)),
            ],
            'currency_icon' => 'required',
            'currency_rate' => 'required|numeric',
        ];
        $customMessages = [
            'currency_name.required' => __('Currency name is required'),
            'currency_name.unique' => __('Currency name already exist'),
            'country_code.required' => __('Country code is required'),
            'country_code.unique' => __('Country code already exist'),
            'currency_code.required' => __('Currency code is required'),
            'currency_code.unique' => __('Currency code already exist'),
            'currency_code.in' => __('Currency code is not valid'),
            'currency_icon.required' => __('Currency icon is required'),
            'currency_rate.required' => __('Currency rate is required'),
            'currency_rate.numeric' => __('Currency rate must be number'),
        ];

        $request->validate($rules, $customMessages);

        $currency = MultiCurrency::findOrFail($id);

        if ($request->is_default == 'yes' && $currency->is_default != 'yes') {
            MultiCurrency::where('is_default', 'yes')->update(['is_default' => 'no']);
        } elseif ($request->is_default == 'no' && $currency->is_default == 'yes') {
            MultiCurrency::where('id', 1)->update(['is_default' => 'yes']);
        }

        $currency->currency_name = $request->currency_name;
        $currency->country_code = $request->country_code;
        $currency->currency_code = $request->currency_code;
        $currency->currency_icon = $request->currency_icon;
        $currency->currency_rate = $request->currency_rate;
        $currency->is_default = $request->is_default;
        $currency->currency_position = $request->currency_position;
        $currency->status = $request->status;
        $currency->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.currency.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('currency.delete');

        $currency = MultiCurrency::find($id);
        if ($currency->is_default == 'yes') {
            return redirect()->route('admin.currency.index')->with(
                [
                    'alert-type' => 'error',
                    'message' => __('Default currency can not be deleted'),
                ]
            );
        }

        $currency->delete();

        $notification = __('Delete Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.currency.index')->with($notification);
    }
}
