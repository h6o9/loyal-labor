<?php

namespace App\Http\Controllers;

use App\Enums\RedirectType;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    use RedirectHelperTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(checkAdminHasPermission('city.list'), 403);

        $query = City::with(['state' => function ($q) {
            return $q->with('country');
        }])->latest();

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('state_name', function ($city) {
                    return e(optional($city->state)->name);
                })
                ->addColumn('country_name', function ($city) {
                    return e(optional(optional($city->state)->country)->name);
                })
                ->addColumn('action', function ($city) {
                    $actions = '';

                    if (checkAdminHasPermission('city.edit')) {
                        $actions .= '<a class="btn btn-primary btn-sm" href="' . route('admin.city.edit', $city->id) . '"><i class="fa fa-edit" aria-hidden="true"></i></a> ';
                    }

                    if (checkAdminHasPermission('city.delete')) {
                        $actions .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.city.destroy', $city->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.locations.cities.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(checkAdminHasPermission('city.create'), 403);
        $states    = State::all();
        $countries = Country::all();

        return view('admin.locations.cities.create', compact('states', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(checkAdminHasPermission('city.store'), 403);

        $request->validate([
            'name'     => 'required',
            'state_id' => 'required',
        ], [
            'name.required'     => __('Name is Required'),
            'state_id.required' => __('State is Required'),
        ]);

        $city           = new City;
        $city->name     = trim($request->name);
        $city->state_id = $request->state_id;
        $city->save();

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.city.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(checkAdminHasPermission('city.edit'), 403);

        $city = City::find($id);

        $countries   = Country::all();
        $cityCountry = $city->state->country ?? '';

        $states = State::where('country_id', $cityCountry->id)->get();

        if (!$city) {
            $notification = __('City Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.city.index')->with($notification);
        }

        return view('admin.locations.cities.edit', compact('city', 'states', 'countries', 'cityCountry'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(checkAdminHasPermission('city.update'), 403);

        $city = City::find($id);

        if (!$city) {
            $notification = __('City Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.city.index')->with($notification);
        }

        $request->validate([
            'name'     => 'required',
            'state_id' => 'required',
        ], [
            'name.required'     => __('Name is Required'),
            'state_id.required' => __('State is Required'),
        ]);

        $city->name     = trim($request->name);
        $city->state_id = $request->state_id;
        $city->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.city.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(checkAdminHasPermission('city.delete'), 403);

        $city = City::find($id);
        if (!$city) {
            $notification = __('City Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.city.index');
        } else {
            $city->delete();
            $notification = __('Delete Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.city.index');
        }
    }

    /**
     * Get all resources By State Id from storage.
     */
    public function getAllCitiesByState(string $id)
    {
        if (str_contains($id, ',')) {
            $id     = explode(',', $id);
            $cities = City::whereIn('state_id', $id)->get();
            if ($cities->count() > 0) {
                return ['status' => 200, 'data' => $cities];
            } else {
                return ['status' => 404, 'message' => __('Cities Not Found'), 'data' => []];
            }
        }

        $cities = State::find($id)->cities;
        if ($cities->count() > 0) {
            return ['status' => 200, 'data' => $cities];
        } else {
            return ['status' => 404, 'message' => __('Cities Not Found'), 'data' => []];
        }
    }
}
