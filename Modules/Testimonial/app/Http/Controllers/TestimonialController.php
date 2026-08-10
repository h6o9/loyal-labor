<?php

namespace Modules\Testimonial\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Testimonial\app\Http\Requests\TestimonialRequest;
use Modules\Testimonial\app\Models\Testimonial;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('testimonial.view');
        Paginator::useBootstrap();

        $query = Testimonial::with('translation');

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('name', function ($testimonial) {
                    return e($testimonial->name);
                })
                ->addColumn('designation', function ($testimonial) {
                    return e($testimonial->designation);
                })
                ->editColumn('image', function ($testimonial) {
                    $image = $testimonial?->image && file_exists(public_path($testimonial?->image))
                        ? asset($testimonial?->image)
                        : asset(getSettings('default_avatar'));

                    return '<img src="' . e(asset($image)) . '" alt="' . e($testimonial?->name) . '" class="rounded-circle my-2">';
                })
                ->addColumn('status_badge', function ($testimonial) {
                    if (!checkAdminHasPermission('testimonial.update')) {
                        return '';
                    }

                    return '<input onchange="changeStatus(' . $testimonial->id . ')" id="status_toggle" type="checkbox" ' . ($testimonial->status ? 'checked' : '') . ' data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger">';
                })
                ->addColumn('action', function ($testimonial) {
                    $html = '';

                    if (checkAdminHasPermission('testimonial.edit')) {
                        $html .= '<a href="' . route('admin.testimonial.edit', ['testimonial' => $testimonial->id, 'code' => getSessionLanguage()]) . '" class="m-1 text-white btn btn-sm btn-warning" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('testimonial.delete')) {
                        $html .= '<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm" onclick="deleteData(' . $testimonial->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['image', 'status_badge', 'action'])
                ->make(true);
        }

        $testimonials = $query->paginate(15);

        return view('testimonial::index', compact('testimonials'));
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('testimonial.create');

        return view('testimonial::create');
    }

    public function store(TestimonialRequest $request)
    {
        checkAdminHasPermissionAndThrowException('testimonial.store');

        $testimonial = Testimonial::create($request->validated());

        if ($testimonial && $request->hasFile('image')) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $testimonial->image);
            $testimonial->image = $file_name;
            $testimonial->save();
        }

        $languages = allLanguages();

        $this->generateTranslations(
            TranslationModels::Testimonial,
            $testimonial,
            'testimonial_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.testimonial.edit', ['testimonial' => $testimonial->id, 'code' => $languages->first()->code]);
    }

    public function edit($id)
    {
        checkAdminHasPermissionAndThrowException('testimonial.edit');
        $code = request('code') ?? getSessionLanguage();
        abort_unless(Language::where('code', $code)->exists(), 404);

        $testimonial = Testimonial::findOrFail($id);
        $languages = allLanguages();

        return view('testimonial::edit', compact('testimonial', 'code', 'languages'));
    }

    public function update(TestimonialRequest $request, $id)
    {
        checkAdminHasPermissionAndThrowException('testimonial.update');

        $testimonial = Testimonial::findOrFail($id);

        $validatedData = $request->validated();

        $testimonial->update($validatedData);

        if ($testimonial && $request->hasFile('image')) {
            $file_name = file_upload($request->image, 'uploads/custom-images/', $testimonial->image);
            $testimonial->image = $file_name;
            $testimonial->save();
        }

        $this->updateTranslations(
            $testimonial,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.testimonial.edit', ['testimonial' => $testimonial->id, 'code' => $request->code]);
    }

    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('testimonial.delete');

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->translations()->each(function ($translation) {
            $translation->testimonial()->dissociate();
            $translation->delete();
        });

        if ($testimonial->image) {
            if (File::exists(public_path($testimonial->image))) {
                @unlink(public_path($testimonial->image));
            }
        }
        $testimonial->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.testimonial.index');
    }

    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('testimonial.update');
        $testimonial = Testimonial::find($id);
        $status = $testimonial->status == 1 ? 0 : 1;
        $testimonial->update(['status' => $status]);

        $notification = __('Updated Successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
