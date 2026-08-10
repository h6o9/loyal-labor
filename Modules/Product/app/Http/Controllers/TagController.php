<?php

namespace Modules\Product\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Product\app\Models\Tag;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Tag::query()
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $search = $request->get('keyword');
                $query->whereHas('translation', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->when($request->filled('order_by'), function ($query) use ($request) {
                $orderBy = $request->get('order_by');
                $query->orderBy('id', $orderBy);
            }, function ($query) {
                $query->latest();
            });

        if ($request->ajax()) {
            return DataTables::of($query)
                ->addColumn('name', function ($tag) {
                    return e($tag->name);
                })
                ->addColumn('action', function ($tag) {
                    $html = '';

                    if (checkAdminHasPermission('product.brand.edit')) {
                        $html .= '<a href="' . route('admin.product.tags.edit', ['tag' => $tag->id, 'code' => getSessionLanguage()]) . '" class="m-1 text-white btn btn-sm btn-warning" title="' . __('Edit') . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('product.brand.delete')) {
                        $html .= '<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm" onclick="deleteData(' . $tag->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }

        return view('product::products.tags.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product::products.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tag_translations')->where('lang_code', getSessionLanguage()),
            ],
            'slug' => 'required|unique:tags',
        ]);

        if ($request->fails()) {
            return redirect()->back()
                ->withErrors($request)
                ->withInput();
        }

        $data = $request->validated();

        $tag = Tag::create($data);

        $this->generateTranslations(
            TranslationModels::Tag,
            $tag,
            'tag_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.product.tags.edit', ['tag' => $tag->id, 'code' => getSessionLanguage()], [
            'message'    => 'Created successfully',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tag = Tag::find($id);

        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        return view('product::products.tags.edit', compact('tag', 'code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);

        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tag_translations')
                    ->where('lang_code', $code)
                    ->ignore($tag->id, 'tag_id'),
            ],
            'slug' => [
                'sometimes',
                'required',
                Rule::unique('tags')->ignore($tag->id),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        $tag->update($data);

        $this->updateTranslations(
            $tag,
            $request,
            $data,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.product.tags.edit', ['tag' => $tag->id, 'code' => $code], [
            'message'    => 'Updated successfully',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);

        if ($tag && $tag?->products?->count() > 0) {
            return $this->redirectWithMessage(RedirectType::DELETE->value, notification: [
                'message'    => __('Tag delete failed, Associted with products!'),
                'alert-type' => 'error',
            ]);
        }

        $tag->translations()->delete();

        $tag->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.product.tags.index', [], [
            'message'    => 'Deleted successfully',
            'alert-type' => 'success',
        ]);
    }
}
