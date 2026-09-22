<?php

namespace App\Http\Controllers;

use App\Services\TranslationEditorService;
use Illuminate\Http\Request;

class TranslationEditorController extends Controller
{
    protected TranslationEditorService $editorService;

    public function __construct(TranslationEditorService $editorService)
    {
        $this->editorService = $editorService;
    }

    /**
     * Display Translation Studio UI
     */
    public function index()
    {
        $data = $this->editorService->getTranslations();

        return view('localization.editor', compact('data'));
    }

    /**
     * Save translation key-value edits
     */
    public function save(Request $request)
    {
        $request->validate([
            'translations' => 'required|array',
        ]);

        $this->editorService->saveTranslations($request->input('translations'));

        return redirect()
            ->route('localization.editor')
            ->with('success', __('Translations updated and saved successfully!'));
    }

    /**
     * Add new translation key
     */
    public function addKey(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'values' => 'nullable|array',
        ]);

        $this->editorService->addKey(
            $request->input('key'),
            $request->input('values', [])
        );

        return redirect()
            ->route('localization.editor')
            ->with('success', __('New translation key added successfully!'));
    }
}
