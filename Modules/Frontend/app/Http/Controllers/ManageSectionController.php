<?php

namespace Modules\Frontend\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Frontend\app\Models\Section;
use Modules\Language\app\Models\Language;

class ManageSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($section)
    {
        checkAdminHasPermissionAndThrowException('frontend.view');

        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        $section = Section::whereNull('home_id')->where('name', $section)->with(['translations' => function ($query) use ($code) {
            $query->where('lang_code', $code);
        }])->firstOrFail();

        $languages = allLanguages();

        return view('frontend::section.index', compact('section', 'languages', 'code'));
    }

    /**
     * @param Request    $request
     * @param $section
     */
    public function update(Request $request, $section)
    {
        checkAdminHasPermissionAndThrowException('frontend.update');

        $section = Section::whereName($section)->firstOrFail();

        if ($request->has('status')) {
            $section->status = $request->status;
            $section->save();
        }

        $fields = array_diff($request->keys(), ['_token', 'section_id', 'status']);

        $images = [];

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                if (!$file->isValid() || !in_array($file->extension(), ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
                    return back()
                        ->withErrors([$key => 'The ' . str($key)->replace('_', ' ')->title()->toString() . ' must be an image.'])
                        ->withInput();
                }

                if ($file->getSize() > 1024 * 1024) {
                    return back()
                        ->withErrors([$key => 'The ' . str($key)->replace('_', ' ')->title()->toString() . ' must not be greater than 1 MB.'])
                        ->withInput();
                }
                $images[] = $key;
            }
        }

        $global_content = (new FrontendController)->updateSectionContent($section?->global_content, $request, $fields, $images);

        $section->update(['global_content' => $global_content]);

        $translationsInput = $request->input('translations', []);

        foreach ($translationsInput as $langCode => $fields) {
            $section->translations()->where('lang_code', $langCode)->update([
                'content' => $fields,
            ]);
        }

        return back()->with([
            'alert-type' => 'success',
            'message'    => __(':name Page updated successfully', ['name' => str($section->name)->replace('_', ' ')->title()->toString()]),
        ]);
    }
}
