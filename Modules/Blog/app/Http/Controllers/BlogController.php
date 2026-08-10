<?php

namespace Modules\Blog\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Modules\Blog\app\Http\Requests\PostRequest;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('blog.view');

        $query = Blog::query()->with('category');

        $query->when($request->filled('keyword'), function ($qa) use ($request) {
            $qa->whereHas('translations', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%');
                $q->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        });

        $query->when($request->filled('is_popular'), function ($q) use ($request) {
            $q->where('is_popular', $request->is_popular);
        });

        $query->when($request->filled('show_homepage'), function ($q) use ($request) {
            $q->where('show_homepage', $request->show_homepage);
        });

        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        $orderBy = $request->filled('order_by') && $request->order_by == 1 ? 'asc' : 'desc';
        $query->orderBy('id', $orderBy);

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('title', function ($blog) {
                    return e($blog->title);
                })
                ->addColumn('category_name', function ($blog) {
                    return e(optional($blog->category)->title);
                })
                ->addColumn('show_homepage_badge', function ($blog) {
                    return $blog->show_homepage == 1
                        ? '<span class="badge bg-success">' . __('Yes') . '</span>'
                        : '<span class="badge bg-danger">' . __('No') . '</span>';
                })
                ->addColumn('is_popular_badge', function ($blog) {
                    return $blog->is_popular == 1
                        ? '<span class="badge bg-success">' . __('Yes') . '</span>'
                        : '<span class="badge bg-danger">' . __('No') . '</span>';
                })
                ->addColumn('status_toggle', function ($blog) {
                    if (!checkAdminHasPermission('blog.update')) {
                        return '';
                    }

                    $checked = $blog->status ? 'checked' : '';

                    return '<input id="status_toggle_' . $blog->id . '" data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger" type="checkbox" onchange="changeStatus(' . $blog->id . ')" ' . $checked . '>';
                })
                ->addColumn('action', function ($blog) {
                    if (!checkAdminHasPermission('blog.edit') && !checkAdminHasPermission('blog.delete')) {
                        return '';
                    }

                    $html = '';

                    if (checkAdminHasPermission('blog.edit')) {
                        $html .= '<a class="m-1 text-white btn btn-sm btn-warning" title="' . __('Edit') . '" href="' . route('admin.blogs.edit', ['blog' => $blog->id, 'code' => getSessionLanguage()]) . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('blog.delete')) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.blogs.destroy', $blog->id) . '"><i class="fa fa-trash"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['show_homepage_badge', 'is_popular_badge', 'status_toggle', 'action'])
                ->make(true);
        }

        return view('blog::Post.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        checkAdminHasPermissionAndThrowException('blog.create');
        $categories = BlogCategory::active()->get();

        return view('blog::Post.create', ['categories' => $categories]);
    }

    /**
     * @param  PostRequest $request
     * @return mixed
     */
    public function store(PostRequest $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('blog.store');

        try {
            DB::beginTransaction();

            $blog = Blog::create(array_merge(['admin_id' => Auth::guard('admin')->user()->id], $request->validated()));

            if ($blog && $request->hasFile('image')) {
                $file_name   = file_upload($request->image);
                $blog->image = $file_name;
                $blog->save();
            }

            $this->generateTranslations(
                TranslationModels::Blog,
                $blog,
                'blog_id',
                $request,
            );
            DB::commit();

            return $this->redirectWithMessage(
                RedirectType::CREATE->value,
                'admin.blogs.edit',
                [
                    'blog' => $blog->id,
                    'code' => allLanguages()->first()->code,
                ]
            );
        } catch (Exception $e) {
            logError('Error while creating blog post: ', $e);

            DB::rollBack();

            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => __('An error occurred while creating the blog post. Please try again.'),
            ]);
        }

    }

    /**
     * @param $id
     */
    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('blog.edit');

        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        $blog       = Blog::with('translation')->findOrFail($id);
        $categories = BlogCategory::all();
        $languages  = allLanguages();

        return view('blog::Post.edit', compact('blog', 'code', 'categories', 'languages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('blog.update');

        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $blog = Blog::findOrFail($id);

            if ($blog && !empty($request->image)) {
                $file_name   = file_upload($request->image, 'uploads/custom-images/', $blog->image);
                $blog->image = $file_name;
                $blog->save();
            }
            $blog->update($validatedData);

            $this->updateTranslations(
                $blog,
                $request,
                $validatedData,
            );

            DB::commit();
            return $this->redirectWithMessage(
                RedirectType::UPDATE->value,
                'admin.blogs.edit',
                ['blog' => $blog->id, 'code' => $request->code]
            );
        } catch (Exception $e) {
            logError('Error while creating blog post: ', $e);

            DB::rollBack();

            return redirect()->back()->with([
                'alert-type' => 'error',
                'message'    => __('An error occurred while creating the blog post. Please try again.'),
            ]);
        }
    }

    /**
     * @param  $id
     * @return mixed
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('blog.delete');

        $blog = Blog::findOrFail($id);

        if ($blog->comments()->count() > 0) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => __('Cannot delete post, it has comments.')]);
        }

        $blog->translations()->each(function ($translation) {
            $translation->post()->dissociate();
            $translation->delete();
        });

        if ($blog->image) {
            if (File::exists(public_path($blog->image))) {
                unlink(public_path($blog->image));
            }
        }
        $blog->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.blogs.index');
    }

    /**
     * @param $id
     */
    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('blog.update');

        $blog   = Blog::find($id);
        $status = $blog->status == 1 ? 0 : 1;
        $blog->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
