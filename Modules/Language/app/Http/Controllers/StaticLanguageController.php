<?php

namespace Modules\Language\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\LanguageTrait;

class StaticLanguageController extends Controller
{
    use LanguageTrait, RedirectHelperTrait;

    /**
     * @param Request $request
     * @param $code
     */
    public function editStaticLanguages(Request $request, $code)
    {
        checkAdminHasPermissionAndThrowException('language.edit');
        $filePath = base_path('lang/' . $code . '.json');
        if (!File::exists($filePath)) {
            return redirect()->route('admin.languages.index')->with([
                'alert-type' => 'warning',
                'message'    => __('Not Found!'),
            ]);
        }

        $language  = Language::where('code', $code)->firstOrFail();
        $languages = Language::all();
        $data      = json_decode(File::get($filePath), true);

        if ($request->filled('search')) {
            $search = preg_quote($request->search, '/');
            $search = str_replace(' ', '\s*', $search);

            $data = collect($data)->filter(function ($value, $key) use ($search) {
                return preg_match("/{$search}/i", $key) || preg_match("/{$search}/i", $value);
            })->all();
        }

        $data = $this->collectionPagination($data);

        return view('language::edit-static-language', compact('data', 'language', 'languages'));
    }

    //Paginate language file array data
    /**
     * @return mixed
     */
    private function collectionPagination($data)
    {
        $CustomPagination = customPagination();
        $perPage          = optional($CustomPagination)->language_list ?? CustomPagination::where('section_name', 'Language List')->value('item_qty');

        $currentPage      = LengthAwarePaginator::resolveCurrentPage();
        $dataCollection   = collect($data);
        $currentPageItems = $dataCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginationData   = new LengthAwarePaginator($currentPageItems, $dataCollection->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        return $paginationData;
    }

    /**
     * @return mixed
     */
    public function updateStaticLanguages(Request $request, $code)
    {
        checkAdminHasPermissionAndThrowException('language.update');

        $this->updateLanguageFile($request, $code);

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    //update paginate file data
    /**
     * @param $request
     * @param $code
     */
    private function updateLanguageFile($request, $code)
    {
        $filePath = base_path('lang/' . $code . '.json');
        if (!File::exists($filePath)) {
            return redirect()->route('admin.languages.index')->with([
                'alert-type' => 'warning',
                'message'    => __('Not Found!'),
            ]);
        }

        $existingData = json_decode(File::get($filePath), true);
        foreach ($request->values as $index => $value) {
            $existingData[$index] = $value;
        }

        File::put($filePath, json_encode($existingData, JSON_PRETTY_PRINT));
    }
}
