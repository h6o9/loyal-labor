<?php

namespace Modules\Product\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Modules\Product\app\Models\UnitType;
use Modules\Product\app\Services\UnitTypeService;
use Yajra\DataTables\Facades\DataTables;

class UnitTypeController extends Controller
{
    use RedirectHelperTrait;

    /**
     * @var mixed
     */
    protected $unitTypeService;

    /**
     * @param UnitTypeService $unitTypeService
     */
    public function __construct(UnitTypeService $unitTypeService)
    {
        $this->unitTypeService = $unitTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('product.unit.view');

        $query = UnitType::with('parent')->latest();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('parent_name', function ($unit) {
                    return e($unit->parent?->name ?? '');
                })
                ->addColumn('status_badge', function ($unit) {
                    if ($unit->status == 1) {
                        return '<span class="badge badge-success">' . __('Active') . '</span>';
                    }

                    return '<span class="badge badge-danger">' . __('Inactive') . '</span>';
                })
                ->addColumn('action', function ($unit) {
                    if (!checkAdminHasPermission('product.unit.edit') && !checkAdminHasPermission('product.unit.delete')) {
                        return '';
                    }

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button class="btn bg-label-primary dropdown-toggle" id="btnGroupDrop' . $unit->id . '" data-bs-toggle="dropdown" type="button" aria-haspopup="true" aria-expanded="false">' . __('Action') . '</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop' . $unit->id . '">';

                    if (checkAdminHasPermission('product.unit.edit')) {
                        $html .= '<a class="dropdown-item edit-btn" href="' . route('admin.unit.edit', $unit->id) . '">' . __('Edit') . '</a>';
                    }

                    if (checkAdminHasPermission('product.unit.delete')) {
                        $html .= '<a class="dropdown-item" href="javascript:;" onclick="deleteData(' . $unit->id . ')">' . __('Delete') . '</a>';
                    }

                    $html .= '</div></div>';

                    return $html;
                })
                ->rawColumns(['parent_name', 'status_badge', 'action'])
                ->make(true);
        }

        $parentUnits = $this->unitTypeService->getParentUnits();

        return view('product::unit-types.index', compact('parentUnits'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        checkAdminHasPermissionAndThrowException('product.unit.create');
        $request->validate([
            'name'      => 'required|unique:unit_types,name',
            'ShortName' => 'required',
            'status'    => 'required',
        ]);
        try {
            $unit = $this->unitTypeService->save($request);

            if ($request->ajax()) {
                return response()->json(['message' => 'Created successfully', 'unit' => $unit, 'status' => 200], 200);
            }

            return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.unit.index');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.unit.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        checkAdminHasPermissionAndThrowException('product.unit.edit');
        $unit = $this->unitTypeService->findById($id);

        return $unit;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        checkAdminHasPermissionAndThrowException('product.unit.edit');
        $request->validate([
            'name'      => 'required|unique:unit_types,name,' . $id,
            'ShortName' => 'required',
            'status'    => 'required',
            'base_unit' => [
                'nullable',
                'exists:unit_types,id',
                ...($id ? [Rule::notIn([$id])] : []),
            ],
        ]);
        try {
            $this->unitTypeService->update($request, $id);

            return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.unit.index');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.unit.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        checkAdminHasPermissionAndThrowException('product.unit.delete');
        try {

            $unit = $this->unitTypeService->findById($id);

            if ($unit && $unit?->products?->count() > 0) {
                return $this->redirectWithMessage(RedirectType::DELETE->value, notification: [
                    'message'    => __('Unit delete failed, Associted with products!'),
                    'alert-type' => 'error',
                ]);
            }

            $result = $this->unitTypeService->delete($id);
            if ($result == 'not_possible') {
                return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.unit.index', notification: ['message' => 'Unit Has Products. Unit cannot be deleted', 'alert-type' => 'error']);
            }

            return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.unit.index');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.unit.index');
        }
    }

    /**
     * @param $id
     */
    public function unitByParent($id)
    {
        $unit = $this->unitTypeService->findById($id);

        return response()->json($unit, 200);
    }
}
