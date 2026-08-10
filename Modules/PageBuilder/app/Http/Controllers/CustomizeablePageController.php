<?php

namespace Modules\PageBuilder\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\PageBuilder\app\Http\Requests\PageRequest;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Yajra\DataTables\Facades\DataTables;

class CustomizeablePageController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    private const PROTECTED_SLUGS = ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller'];

    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('page.view');

        $query = CustomizeablePage::query();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('title_link', function ($page) {
                    return '<a href="" target="_blank">' . e($page->title) . '</a>';
                })
                ->addColumn('status_toggle', function ($page) {
                    if (!checkAdminHasPermission('page.update')) {
                        return '';
                    }

                    $checked = $page->status ? 'checked' : '';
                    $disabled = in_array($page->slug, self::PROTECTED_SLUGS) ? 'disabled' : '';

                    return '<input id="status_toggle_' . $page->id . '" data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="changeStatus(' . $page->id . ')" ' . $checked . ' ' . $disabled . '>';
                })
                ->addColumn('action', function ($page) {
                    $html = '';

                    if (checkAdminHasPermission('page.edit')) {
                        $html .= '<a class="m-1 text-white btn btn-sm btn-warning" href="' . route('admin.custom-pages.edit', ['page' => $page->id, 'code' => getSessionLanguage()]) . '" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('page.delete') && !in_array($page->slug, self::PROTECTED_SLUGS)) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.custom-pages.destroy', $page->id) . '"><i class="fa fa-trash"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['title_link', 'status_toggle', 'action'])
                ->make(true);
        }

        return view('pagebuilder::pages.index');
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('page.create');

        return view('pagebuilder::pages.create');
    }

    /**
     * @param  PageRequest $request
     * @return mixed
     */
    public function store(PageRequest $request)
    {
        checkAdminHasPermissionAndThrowException('page.store');

        $page = CustomizeablePage::create($request->validated());

        $this->generateTranslations(
            TranslationModels::CustomizablePage,
            $page,
            'customizeable_page_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.custom-pages.edit', ['page' => $page->id, 'code' => allLanguages()->first()->code]);
    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('page.edit');
        $code = request('code') ?? getSessionLanguage();
        abort_unless(Language::where('code', $code)->exists(), 404);
        $languages = allLanguages();
        $page      = CustomizeablePage::findOrFail($id);

        return view('pagebuilder::pages.edit', compact('page', 'code', 'languages'));
    }

    /**
     * @param  PageRequest $request
     * @param  $id
     * @return mixed
     */
    public function update(PageRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('page.update');

        $code = request('code') ?? getSessionLanguage();

        abort_unless(Language::where('code', $code)->exists(), 404);

        $page = CustomizeablePage::findOrFail($id);

        $page->fill($request->validated());

        $validatedData = $request->validated();

        $this->updateTranslations(
            $page,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('page.delete');

        $page = CustomizeablePage::whereNotIn('slug', ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller'])->find($id);
        if ($page) {
            $page->translations()->each(function ($translation) {
                $translation->customizeablePage()->dissociate();
                $translation->delete();
            });
            $page->delete();

            return $this->redirectWithMessage(RedirectType::DELETE->value);
        }

        return $this->redirectWithMessage(RedirectType::ERROR->value);
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        if (checkAdminHasPermission('page.update')) {
            $pageItem = CustomizeablePage::whereNotIn('slug', ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller'])->find($id);

            if (!$pageItem) {
                return response()->json([
                    'success' => false,
                ], 403);
            }
            $status = $pageItem->status == 1 ? 0 : 1;
            $pageItem->update(['status' => $status]);

            $notification = __('Updated successfully');

            return response()->json([
                'success' => true,
                'message' => $notification,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 403);
    }
}
