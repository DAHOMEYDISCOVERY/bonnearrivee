<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PageAdminController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('path')->paginate(20);

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'content_override' => 'nullable|string',
            'use_override' => 'boolean',
        ]);

        $page->update($validated);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page mise à jour avec succès');
    }
}
