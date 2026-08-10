<?php

namespace Modules\Product\app\Services;

use Modules\Product\app\Models\UnitType;

class UnitTypeService
{
    public function getAll()
    {
        return UnitType::latest()->get();
    }

    public function getActiveAll()
    {
        return UnitType::latest()->where('status', 1)->get();
    }

    public function getParentUnits()
    {
        return UnitType::latest()->where('base_unit', null)->get();
    }

    public function save($request)
    {
        $unit_type = new UnitType;
        $unit_type->name = $request->name;
        $unit_type->ShortName = $request->ShortName;
        $unit_type->base_unit = $request->base_unit;
        $unit_type->operator = $request->operator;
        $unit_type->operator_value = $request->operator_value;
        $unit_type->status = $request->status;
        $unit_type->save();

        return $unit_type;
    }

    public function update($request, $id)
    {
        $unit_type = UnitType::findOrFail($id);
        $unit_type->name = $request->name;
        $unit_type->ShortName = $request->ShortName;
        $unit_type->base_unit = $request->base_unit;
        $unit_type->operator = $request->operator;
        $unit_type->operator_value = $request->operator_value;
        $unit_type->status = $request->status;
        $unit_type->save();

        return true;
    }

    public function findById($id)
    {
        return UnitType::with('children')->findOrFail($id);
    }

    public function delete($id)
    {
        $unit_type = UnitType::findOrFail($id);
        if (count($unit_type->products) > 0) {
            return 'not_possible';
        }
        $unit_type->delete();

        return 'possible';
    }
}
