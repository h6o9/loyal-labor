<?php

namespace Modules\Tax\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Tax\app\Http\Requests\TaxRequest;
use Modules\Tax\app\Models\Tax;
use Yajra\DataTables\Facades\DataTables;

class TaxController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('tax.view');

        Paginator::useBootstrap();

        $query = Tax::with('translation');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('title', function ($tax) {
                    return e($tax->title);
                })
                ->addColumn('status_badge', function ($tax) {
                    if (!checkAdminHasPermission('tax.update')) {
                        return '';
                    }

                    return '<input onchange="changeStatus(' . $tax->id . ')" id="status_toggle" type="checkbox" ' . ($tax->status ? 'checked' : '') . ' data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger">';
                })
                ->addColumn('action', function ($tax) {
                    $html = '';

                    if (checkAdminHasPermission('tax.edit')) {
                        $html .= '<a href="' . route('admin.tax.edit', ['tax' => $tax->id, 'code' => getSessionLanguage()]) . '" class="m-1 text-white btn btn-sm btn-warning" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('tax.delete')) {
                        $html .= '<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm" onclick="deleteData(' . $tax->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        $taxes = $query->paginate(15);

        return view('tax::index', ['taxes' => $taxes]);
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('tax.create');

        return view('tax::create');
    }

    /**
     * @param  TaxRequest $request
     * @return mixed
     */
    public function store(TaxRequest $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('tax.store');
        $tax = Tax::create($request->validated());

        $languages = Language::all();

        $this->generateTranslations(
            TranslationModels::Tax,
            $tax,
            'tax_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.tax.edit', ['tax' => $tax->id, 'code' => $languages->first()->code]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('tax.edit');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $tax       = Tax::findOrFail($id);
        $languages = allLanguages();

        return view('tax::edit', compact('tax', 'code', 'languages'));
    }

    /**
     * @param  TaxRequest $request
     * @param  Tax        $tax
     * @return mixed
     */
    public function update(TaxRequest $request, Tax $tax)
    {
        checkAdminHasPermissionAndThrowException('tax.update');
        $validatedData = $request->validated();

        $tax->update($validatedData);

        $this->updateTranslations(
            $tax,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.tax.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        checkAdminHasPermissionAndThrowException('tax.delete');

        if ($tax && $tax?->products?->count() > 0) {
            return $this->redirectWithMessage(RedirectType::DELETE->value, notification: [
                'message'    => __('Tax delete failed, Associted with products!'),
                'alert-type' => 'error',
            ]);
        }

        $tax->translations()->each(function ($translation) {
            $translation->tax()->dissociate();
            $translation->delete();
        });

        $tax->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.tax.index');
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('tax.update');
        $tax    = Tax::find($id);
        $status = $tax->status == 1 ? 0 : 1;
        $tax->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
