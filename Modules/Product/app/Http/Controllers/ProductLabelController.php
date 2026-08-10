<?php

namespace Modules\Product\app\Http\Controllers;

use App\Enums\RedirectMessage;
use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Product\app\Http\Requests\ProductLabelRequest;
use Modules\Product\app\Models\ProductLabel;
use Modules\Product\app\Services\ProductLabelService;
use Yajra\DataTables\Facades\DataTables;

class ProductLabelController extends Controller
{
    use RedirectHelperTrait;

    /**
     * @param ProductLabelService $productLabelService
     */
    public function __construct(
        private ProductLabelService $productLabelService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('product.label.view');

        $query = $this->buildLabelIndexQuery($request);

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('name', function ($productLabel) {
                    return e($productLabel->name);
                })
                ->addColumn('status_badge', function ($productLabel) {
                    if (!checkAdminHasPermission('product.label.update')) {
                        return '';
                    }

                    return '<input id="status_toggle" data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="changeStatus(' . $productLabel->id . ')" ' . ($productLabel->status ? 'checked' : '') . '>';
                })
                ->addColumn('action', function ($productLabel) {
                    $html = '';

                    if (checkAdminHasPermission('product.label.edit')) {
                        $html .= '<a href="' . route('admin.label.edit', ['label' => $productLabel->id, 'code' => getSessionLanguage()]) . '" class="m-1 text-white btn btn-sm btn-warning" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('product.label.delete')) {
                        $html .= '<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm" onclick="deleteData(' . $productLabel->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['name', 'status_badge', 'action'])
                ->make(true);
        }

        return view('product::products.label.index');
    }

    /**
     * Build the filtered product label query used by both the AJAX
     * DataTables listing and (indirectly) by the non-ajax index view.
     */
    private function buildLabelIndexQuery(Request $request)
    {
        $query = ProductLabel::with('translation');

        $query->when($request->filled('keyword'), function ($q) use ($request) {
            $keyword = $request->get('keyword');
            $q->where(function ($sub) use ($keyword) {
                $sub->whereHas('translation', function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'like', '%' . $keyword . '%');
                })->orWhere('slug', 'like', '%' . $keyword . '%');
            });
        });

        $query->when($request->filled('order_by'), function ($q) use ($request) {
            $q->orderBy('slug', $request->get('order_by'));
        }, function ($q) {
            $q->orderBy('slug', 'asc');
        });

        return $query;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductLabelRequest $request)
    {
        checkAdminHasPermissionAndThrowException('product.label.create');

        $this->productLabelService->store($request);

        return $this->redirectWithMessage(RedirectMessage::CREATE->value, notification: [
            'message'    => __('Created successfully'),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('product.label.edit');

        $productLabel = $this->productLabelService->find($id);

        return view('product::products.label.edit', [
            'label' => $productLabel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductLabelRequest $request, $id): RedirectResponse
    {
        $this->productLabelService->update($request, $id);

        return $this->redirectWithMessage(RedirectMessage::UPDATE->value, notification: [
            'message'    => __('Updated successfully'),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('product.label.delete');

        $cat = $this->productLabelService->find($id);

        if ($cat && $cat?->products?->count() > 0) {
            return $this->redirectWithMessage(RedirectType::DELETE->value, notification: [
                'message'    => __('Label delete failed, Associted with products!'),
                'alert-type' => 'error',
            ]);
        }

        $this->productLabelService->delete($id);

        return $this->redirectWithMessage(RedirectMessage::DELETE->value, notification: [
            'message'    => __('Deleted successfully'),
            'alert-type' => 'success',
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     */
    public function updateStatus(Request $request, $id)
    {
        checkAdminHasPermissionAndThrowException('product.label.update');

        try {
            $this->productLabelService->updateStatus($id);

            return response()->json(['success' => true, 'message' => 'Updated successfully'], 200);
        } catch (\Exception $ex) {
            logError('Unable to update status', $ex);

            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }
}
