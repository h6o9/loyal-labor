<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        checkAdminHasPermissionAndThrowException('service.categories.view');

        $query = ServiceCategory::query()->orderBy('sort_order')->orderBy('id');

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('status_toggle', function ($category) {
                    if (!checkAdminHasPermission('service.categories.edit')) {
                        return $category->is_active
                            ? '<span class="badge badge-success">' . __('Active') . '</span>'
                            : '<span class="badge badge-danger">' . __('Inactive') . '</span>';
                    }

                    $checked = $category->is_active ? 'checked' : '';

                    return '<input onchange="changeServiceCategoryStatus(' . $category->id . ')" id="status_toggle_' . $category->id . '" type="checkbox" ' . $checked . ' data-toggle="toggle" data-onlabel="' . __('Active') . '" data-offlabel="' . __('Inactive') . '" data-onstyle="success" data-offstyle="danger">';
                })
                ->addColumn('action', function ($category) {
                    $html = '';

                    if (checkAdminHasPermission('service.categories.edit')) {
                        $html .= '<a class="btn btn-primary btn-sm" href="' . route('admin.service-categories.edit', $category->id) . '"><i class="fa fa-edit"></i></a> ';
                    }

                    if (checkAdminHasPermission('service.categories.delete')) {
                        $html .= '<a class="btn btn-danger btn-sm deleteForm" href="javascript:;" data-url="' . route('admin.service-categories.destroy', $category->id) . '"><i class="fa fa-trash"></i></a>';
                    }

                    return $html;
                })
                ->rawColumns(['status_toggle', 'action'])
                ->make(true);
        }

        return view('admin.service-categories.index');
    }

    public function create()
    {
        checkAdminHasPermissionAndThrowException('service.categories.create');

        return view('admin.service-categories.create');
    }

    public function store(Request $request)
    {
        checkAdminHasPermissionAndThrowException('service.categories.create');

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:service_categories,slug',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'required|in:0,1',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);
        if (ServiceCategory::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        ServiceCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $request->icon,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => (int) $request->is_active,
        ]);

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category created successfully.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        checkAdminHasPermissionAndThrowException('service.categories.edit');

        return view('admin.service-categories.edit', compact('serviceCategory'));
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        checkAdminHasPermissionAndThrowException('service.categories.edit');

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:service_categories,slug,' . $serviceCategory->id,
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'required|in:0,1',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);
        if (ServiceCategory::where('slug', $slug)->where('id', '!=', $serviceCategory->id)->exists()) {
            $slug .= '-' . time();
        }

        $serviceCategory->update([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $request->icon,
            'sort_order' => (int) ($request->sort_order ?? 0),
            'is_active' => (int) $request->is_active,
        ]);

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        checkAdminHasPermissionAndThrowException('service.categories.delete');

        $serviceCategory->delete();

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }

    public function changeStatus($id)
    {
        checkAdminHasPermissionAndThrowException('service.categories.edit');

        $category = ServiceCategory::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => $category->is_active ? 'Category activated.' : 'Category deactivated.',
            'is_active' => (bool) $category->is_active,
        ]);
    }
}
