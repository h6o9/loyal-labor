<?php

namespace Modules\Blog\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Modules\Blog\app\Http\Requests\CategoryRequest;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('blog.category.view');

        Paginator::useBootstrap();

        $query = BlogCategory::with('translation')->latest();

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('title', function ($category) {
                    return e($category->title);
                })
                ->addColumn('status_toggle', function ($category) {
                    if (!checkAdminHasPermission('blog.category.update')) {
                        return '';
                    }

                    $checked = $category->status ? 'checked' : '';

                    return '<input onchange="changeStatus(' . $category->id . ')" id="status_toggle_' . $category->id . '" type="checkbox" ' . $checked . ' data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger">';
                })
                ->addColumn('action', function ($category) {
                    if (!checkAdminHasPermission('blog.category.edit') && !checkAdminHasPermission('blog.category.delete')) {
                        return '';
                    }

                    $html = '<div>';

                    if (checkAdminHasPermission('blog.category.edit')) {
                        $html .= '<a href="' . route('admin.blog-category.edit', ['blog_category' => $category->id, 'code' => getSessionLanguage()]) . '" class="m-1 text-white btn btn-sm btn-warning" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('blog.category.delete')) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.blog-category.destroy', $category->id) . '"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['status_toggle', 'action'])
                ->make(true);
        }

        return view('blog::Category.index');
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('blog.category.create');

        return view('blog::Category.create');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('blog.category.store');
        $category = BlogCategory::create($request->validated());

        $languages = Language::all();

        $this->generateTranslations(
            TranslationModels::BlogCategory,
            $category,
            'blog_category_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.blog-category.edit', ['blog_category' => $category->id, 'code' => $languages->first()->code]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('blog.category.edit');
        $code = request('code') ?? getSessionLanguage();
        if (! Language::where('code', $code)->exists()) {
            abort(404);
        }
        $category = BlogCategory::findOrFail($id);
        $languages = allLanguages();

        return view('blog::Category.edit', compact('category', 'code', 'languages'));
    }

    public function update(CategoryRequest $request, BlogCategory $blog_category)
    {
        checkAdminHasPermissionAndThrowException('blog.category.update');
        $validatedData = $request->validated();

        $blog_category->update($validatedData);

        $this->updateTranslations(
            $blog_category,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.blog-category.edit', ['blog_category' => $blog_category->id, 'code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blogCategory)
    {
        checkAdminHasPermissionAndThrowException('blog.category.delete');
        if ($blogCategory->posts()->count() > 0) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => __('Cannot delete category, it has posts.')]);
        }
        $blogCategory->translations()->each(function ($translation) {
            $translation->category()->dissociate();
            $translation->delete();
        });

        $blogCategory->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.blog-category.index');
    }

    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('blog.category.update');
        $blogCategory = BlogCategory::find($id);
        $status = $blogCategory->status == 1 ? 0 : 1;
        $blogCategory->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
