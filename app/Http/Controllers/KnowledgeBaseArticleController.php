<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBaseArticle;
use Illuminate\Http\Request;

class KnowledgeBaseArticleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = KnowledgeBaseArticle::query()->where('is_published', true);

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
        }

        $articles = $query->latest()->paginate(10);
        return view('knowledge-base.index', compact('articles', 'search'));
    }

    public function manage()
    {
        // Admin or Support only
        if (!auth()->user()->isItStaff()) {
            abort(403);
        }
        $articles = KnowledgeBaseArticle::latest()->paginate(20);
        return view('knowledge-base.manage', compact('articles'));
    }

    public function create()
    {
        if (!auth()->user()->isItStaff()) {
            abort(403);
        }
        return view('knowledge-base.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isItStaff()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_published' => 'boolean',
        ]);

        KnowledgeBaseArticle::create($validated);

        return redirect()->route('knowledge-base.manage')
            ->with('success', 'Article created successfully.');
    }

    public function show(KnowledgeBaseArticle $knowledgeBaseArticle)
    {
        if (!$knowledgeBaseArticle->is_published && !auth()->user()->isItStaff()) {
            abort(404);
        }

        $knowledgeBaseArticle->increment('views');

        return view('knowledge-base.show', compact('knowledgeBaseArticle'));
    }

    public function edit(KnowledgeBaseArticle $knowledgeBaseArticle)
    {
        if (!auth()->user()->isItStaff()) {
            abort(403);
        }
        return view('knowledge-base.edit', compact('knowledgeBaseArticle'));
    }

    public function update(Request $request, KnowledgeBaseArticle $knowledgeBaseArticle)
    {
        if (!auth()->user()->isItStaff()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
            'is_published' => 'boolean',
        ]);

        $knowledgeBaseArticle->update($validated);

        return redirect()->route('knowledge-base.manage')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(KnowledgeBaseArticle $knowledgeBaseArticle)
    {
        if (!auth()->user()->isItStaff()) {
            abort(403);
        }

        $knowledgeBaseArticle->delete();

        return redirect()->route('knowledge-base.manage')
            ->with('success', 'Article deleted successfully.');
    }
}
