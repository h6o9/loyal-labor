<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\State;

class LocationApiController extends Controller
{
    /**
     * @return mixed
     */
    public function getCountry()
    {
        $countries = Country::when(request('q'), function ($query) {
            $q = request('q');

            return $query->where('name', 'like', "%$q%");
        })
            ->when(request('id'), function ($query) {
                return $query->where('id', request('id'));
            })
            ->when(request('status'), function ($query) {
                return $query->where('status', request('status'));
            }, function ($query) {
                return $query->where('status', 'active');
            })
            ->select('id', 'name', 'code', 'slug', 'status')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $countries,
        ]);
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        $states = State::when(request('q'), function ($query) {
            $q = request('q');

            return $query->where('name', 'like', "%$q%");
        })
            ->when(request('country'), function ($query) {
                return $query->where('country_id', request('country'));
            })
            ->when(request('id'), function ($query) {
                return $query->where('id', request('id'));
            })
            ->select('id', 'name', 'country_id')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $states,
        ]);
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        $states = City::when(request('q'), function ($query) {
            $q = request('q');

            return $query->where('name', 'like', "%$q%");
        })
            ->when(request('state'), function ($query) {
                return $query->where('state_id', request('state'));
            })
            ->when(request('id'), function ($query) {
                return $query->where('id', request('id'));
            })
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $states,
        ]);
    }
}
