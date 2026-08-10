<?php

namespace Modules\Product\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Product\app\Models\Attribute;
use Modules\Product\app\Models\AttributeValue;
use Yajra\DataTables\Facades\DataTables;

class AttributeValueController extends Controller
{
    use GenerateTranslationTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->attribute_id) {
            $notification = [
                'message'    => __('Please select attribute first'),
                'alert-type' => 'error',
            ];

            return to_route('admin.attribute.index')->with($notification);
        }

        $attribute = Attribute::find($request->attribute_id);

        if ($request->ajax()) {
            $query = AttributeValue::where('attribute_id', $request->attribute_id);

            return DataTables::of($query)
                ->addColumn('name', function ($value) {
                    return e($value->name);
                })
                ->addColumn('action', function ($value) {
                    $html = '<div class="btn-group">';

                    if (checkAdminHasPermission('product.attribute.edit')) {
                        $html .= '<a class="btn btn-primary btn-sm me-2" data-toggle="tooltip" href="' . route('admin.attributes.values.edit', $value->id) . '?code=' . getSessionLanguage() . '" title="' . __('Edit') . '"><i class="fas fa-edit"></i></a>';
                    }

                    if (checkAdminHasPermission('product.attribute.delete')) {
                        $html .= '<a data-bs-toggle="modal" data-bs-target="#deleteModal" href="javascript:;" class="btn btn-danger btn-sm" onclick="deleteData(' . $value->id . ')"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }

                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['name', 'action'])
                ->make(true);
        }

        return view('product::products.attributes.values.index', compact('attribute'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request = Validator::make($request->all(), [
            'name'         => 'required',
            'attribute_id' => 'required',
            'attribute'    => 'required',
        ]);

        if ($request->fails()) {
            return redirect()->back()
                ->withErrors($request)
                ->withInput();
        }

        $data = $request->validated();

        $value = AttributeValue::create($data);

        $this->generateTranslations(
            TranslationModels::AttributeValue,
            $value,
            'attribute_value_id',
            $request,
        );

        $notification = [
            'message'    => __('Created successfully'),
            'alert-type' => 'success',
        ];

        return back()->with($notification);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attrValue = AttributeValue::find($id);

        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        return view('product::products.attributes.values.edit', compact('attrValue', 'code'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        $request = Validator::make($request->all(), [
            'name'         => 'required',
        ]);

        if ($request->fails()) {
            return redirect()->back()
                ->withErrors($request)
                ->withInput();
        }

        $data = $request->validated();

        $attrValue = AttributeValue::findOrFail($id);

        $attrValue->update($data);

        $attrValue->translations()->where('lang_code', $code)?->update([
            'name' => $data['name'],
        ]);

        return back()->with([
            'message'    => __('Updated successfully'),
            'alert-type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attrValue = AttributeValue::find($id);

        $attrValue->translations()->delete();

        $attrValue->delete();

        return back()->with([
            'message'    => __('Deleted successfully'),
            'alert-type' => 'success',
        ]);
    }
}
