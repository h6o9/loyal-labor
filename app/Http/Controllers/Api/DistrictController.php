<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\DistrictCity;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'status']);

        return response()->json([
            'success' => true,
            'status' => 'success',
            'data' => $districts,
        ]);
    }

    public function cities(Request $request, $districtId = null)
    {
        $districtId = $districtId
            ?? $request->input('district_id')
            ?? $request->query('district_id');

        if (!$districtId) {
            return response()->json([
                'success' => false,
                'message' => 'district_id is required.',
            ], 422);
        }

        $district = District::where('id', $districtId)
            ->where('status', 'active')
            ->first(['id', 'name', 'status']);

        if (!$district) {
            return response()->json([
                'success' => false,
                'message' => 'District not found.',
            ], 404);
        }

        $cities = DistrictCity::where('district_id', $district->id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'district_id', 'name', 'status']);

        return response()->json([
            'success' => true,
            'message' => $cities->isEmpty()
                ? 'No cities found for this district.'
                : 'Cities retrieved successfully.',
            'district' => $district,
            'data' => $cities,
        ]);
    }
}
