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

        // #region agent log
        @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'hypothesisId' => 'H3', 'location' => 'ServiceCategoryController::index', 'message' => 'admin index categories', 'data' => ['total' => (clone $query)->count(), 'with_icon' => (clone $query)->whereNotNull('icon')->where('icon', '!=', '')->count(), 'active' => (clone $query)->where('is_active', 1)->count()], 'timestamp' => (int) round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        if ($request->ajax() || $request->has('draw') || $request->has('start')) {
            return DataTables::of($query)
                ->addColumn('icon_html', function ($category) {
                    $url = $category->iconUrl();
                    if ($url) {
                        return '<img src="' . e($url) . '" alt="' . e($category->name) . '" style="width:48px;height:48px;object-fit:contain;border-radius:10px;background:#f8f9fc;padding:4px;">';
                    }

                    if ($category->icon && str_starts_with((string) $category->icon, 'fa-')) {
                        return '<i class="fa ' . e($category->icon) . ' fa-2x"></i>';
                    }

                    return '<span class="text-muted">—</span>';
                })
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
                ->rawColumns(['icon_html', 'status_toggle', 'action'])
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
            'icon_file' => 'required|file|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'required|in:0,1',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);
        if (ServiceCategory::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $iconPath = $this->storeCategoryIcon($request, $slug);

        // #region agent log
        @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'hypothesisId' => 'H1', 'location' => 'ServiceCategoryController::store', 'message' => 'create category icon upload', 'data' => ['has_file' => $request->hasFile('icon_file'), 'icon' => $iconPath, 'file_exists' => is_file(public_path($iconPath)), 'is_active' => (int) $request->is_active, 'slug' => $slug], 'timestamp' => (int) round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        ServiceCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $iconPath,
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
            'icon_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'required|in:0,1',
        ]);

        $slug = $request->slug ?: Str::slug($request->name);
        if (ServiceCategory::where('slug', $slug)->where('id', '!=', $serviceCategory->id)->exists()) {
            $slug .= '-' . time();
        }

        $iconPath = $serviceCategory->icon;
        if ($request->hasFile('icon_file')) {
            $iconPath = $this->storeCategoryIcon($request, $slug);
        }

        $serviceCategory->update([
            'name' => $request->name,
            'slug' => $slug,
            'icon' => $iconPath,
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

        // #region agent log
        @file_put_contents(base_path('debug-545283.log'), json_encode(['sessionId' => '545283', 'hypothesisId' => 'H4', 'location' => 'ServiceCategoryController::changeStatus', 'message' => 'category status toggled', 'data' => ['id' => $category->id, 'is_active' => (bool) $category->is_active], 'timestamp' => (int) round(microtime(true) * 1000)]) . "\n", FILE_APPEND);
        // #endregion

        return response()->json([
            'success' => true,
            'message' => $category->is_active ? 'Category activated.' : 'Category deactivated.',
            'is_active' => (bool) $category->is_active,
        ]);
    }

    private function storeCategoryIcon(Request $request, string $slug): string
    {
        $file = $request->file('icon_file');
        $extension = strtolower($file->getClientOriginalExtension() ?: 'png');
        $filename = $slug . '-' . time() . '.' . $extension;
        $directory = public_path('backend/img/service-categories');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $file->move($directory, $filename);

        return 'backend/img/service-categories/' . $filename;
    }
}
