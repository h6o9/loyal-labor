<?php

namespace Modules\Shipping\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Shipping\app\Models\ShippingRule;
use Modules\Shipping\app\Models\ShippingSetting;
use Yajra\DataTables\Facades\DataTables;

class ShippingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('shipping.management');

        $query = ShippingRule::query();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('charge', function ($shipping) {
                    return currency($shipping->price);
                })
                ->addColumn('action', function ($shipping) {
                    return '<a class="m-1 text-white btn btn-sm btn-warning" href="'
                        . route('admin.shipping.edit', $shipping->id) . '?code=' . getSessionLanguage()
                        . '" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a> '
                        . '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="'
                        . route('admin.shipping.destroy', $shipping->id) . '"><i class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $shippingSetting = ShippingSetting::first();

        return view('shipping::index', compact('shippingSetting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        checkAdminHasPermissionAndThrowException('shipping.management');

        $countries = Country::where('status', 'active')->get();

        return view('shipping::create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('shipping.management');

        $data = $request->validate([
            'name'       => 'required',
            'status'     => 'required',
            'price'      => 'required',
            'type'       => 'nullable|in:based_on_price,based_on_weight',
            'from'       => 'nullable|numeric|min:0',
            'to'         => 'nullable|numeric|min:0',
            'country_id' => 'nullable',
            'state_id'   => 'nullable',
            'city_id'    => 'nullable',
        ]);

        $shipping = ShippingRule::create($data);

        if ($request->location) {
            $shipping->items()->create([
                'shipping_rule_id' => $shipping->id,
                'country_id'       => $request->country_id,
                'state_id'         => $request->state_id,
                'city_id'          => $request->city_id,
            ]);
        }

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.shipping.edit', ['shipping' => $shipping->id])->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('shipping.management');

        $rule      = ShippingRule::with('items')->find($id);
        $countries = Country::where('status', 'active')->get();

        if ($rule->items) {
            $states = State::whereIn('country_id', $rule->items?->country_id ?? [])->get(['name', 'id', 'country_id']);
            $cities = City::whereIn('state_id', $rule->items?->state_id ?? [])->get(['name', 'id', 'state_id']);
        } else {
            $states = [];
            $cities = [];
        }

        return view('shipping::edit', compact('rule', 'countries', 'states', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('shipping.management');

        $data = $request->validate([
            'name'       => 'required',
            'status'     => 'required',
            'price'      => 'required',
            'type'       => 'nullable|in:based_on_price,based_on_weight',
            'from'       => 'nullable|numeric|min:0',
            'to'         => 'nullable|numeric|min:0',
            'country_id' => 'nullable',
            'state_id'   => 'nullable',
            'city_id'    => 'nullable',
        ]);

        $shipping = ShippingRule::find($id);
        $shipping->update($data);

        if ($request->location) {
            if ($shipping->items) {
                $shipping->items()->update([
                    'shipping_rule_id' => $shipping->id,
                    'country_id'       => $request->country_id,
                    'state_id'         => $request->state_id,
                    'city_id'          => $request->city_id,
                ]);
            } else {
                $shipping->items()->create([
                    'shipping_rule_id' => $shipping->id,
                    'country_id'       => $request->country_id,
                    'state_id'         => $request->state_id,
                    'city_id'          => $request->city_id,
                ]);
            }
        } else {
            $shipping->items()->delete();
        }

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.shipping.edit', ['shipping' => $shipping->id])->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('shipping.management');

        $shipping = ShippingRule::find($id);

        // delete shipping rule items
        if ($shipping->items) {
            $shipping->items()->delete();
        }

        $shipping->delete();
        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.shipping.index')->with($notification);
    }

    /**
     * @param Request $request
     */
    public function updateSetting(Request $request)
    {
        $validateData = $request->validate([
            'sort_shipping_direction' => 'required|in:asc,desc',
            'hide_other_shipping'     => 'nullable',
            'hide_shipping_option'    => 'nullable',
        ]);

        ShippingSetting::updateOrCreate(
            ['id' => 1],
            $validateData
        );

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
