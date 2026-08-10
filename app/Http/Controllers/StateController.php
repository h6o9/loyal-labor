<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(checkAdminHasPermission('state.list'), 403);

        $query = State::with('country')->latest();

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('country_name', function ($state) {
                    return e(optional($state->country)->name);
                })
                ->addColumn('action', function ($state) {
                    $actions = '';

                    if (checkAdminHasPermission('state.edit')) {
                        $actions .= '<a class="btn btn-primary btn-sm" href="' . route('admin.state.edit', $state->id) . '"><i class="fa fa-edit" aria-hidden="true"></i></a> ';
                    }

                    if (checkAdminHasPermission('state.delete')) {
                        $actions .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.state.destroy', $state->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.locations.states.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(checkAdminHasPermission('state.create'), 403);
        $countries = Country::all();

        return view('admin.locations.states.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(checkAdminHasPermission('state.store'), 403);

        $request->validate([
            'name'       => 'required',
            'country_id' => 'required',
        ], [
            'name.required'       => __('Name is Required'),
            'country_id.required' => __('Country is Required'),
        ]);

        $state             = new State();
        $state->name       = trim($request->name);
        $state->country_id = $request->country_id;
        $state->save();

        $notification = __('Created Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.state.index')->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_unless(checkAdminHasPermission('state.edit'), 403);

        $countries = Country::all();
        $state     = State::find($id);

        if (!$state) {
            $notification = __('State Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.state.index')->with($notification);
        }

        return view('admin.locations.states.edit', compact('state', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_unless(checkAdminHasPermission('state.update'), 403);

        $state = State::find($id);

        if (!$state) {
            $notification = __('State Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.state.index')->with($notification);
        }

        $request->validate([
            'name'       => 'required',
            'country_id' => 'required',
        ], [
            'name.required'       => __('Name is Required'),
            'country_id.required' => __('Country is Required'),
        ]);

        $state->name       = trim($request->name);
        $state->country_id = $request->country_id;
        $state->save();

        $notification = __('Updated Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->route('admin.state.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort_unless(checkAdminHasPermission('state.delete'), 403);

        $state = State::find($id);
        if (!$state) {
            $notification = __('State Not Found');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->route('admin.state.index')->with($notification);
        } else {
            $state->delete();
            $notification = __('Delete Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return redirect()->route('admin.state.index')->with($notification);
        }
    }

    /**
     * Get all resources By Country Id from storage.
     */
    public function getAllStateByCountry($id)
    {
        if (str_contains($id, ',')) {
            $id     = explode(',', $id);
            $states = State::whereIn('country_id', $id)->get();
            if ($states->count() > 0) {
                return ['status' => 200, 'data' => $states];
            } else {
                return ['status' => 404, 'message' => __('States Not Found'), 'data' => []];
            }
        }

        $states = Country::find($id)->states;
        if ($states->count() > 0) {
            return ['status' => 200, 'data' => $states];
        } else {
            return ['status' => 404, 'message' => __('States Not Found'), 'data' => []];
        }
    }
}
