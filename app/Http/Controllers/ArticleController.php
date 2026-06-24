<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\SavedArticle;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        $categories = Article::distinct()->pluck('category');
        $user = Auth::user();
        $savedArticles = $user ? $user->savedArticles->pluck('article_id')->toArray() : [];

        $articles = Article::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            })
            ->when(request('category'), function ($query, $category) {
                $query->where('category', $category);
            })
            ->latest('published_at')
            ->paginate(10);

        return view('articles.index', compact('articles', 'categories', 'savedArticles'));
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }

    public function toggleSave($id)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $saved = SavedArticle::where('user_id', $userId)
            ->where('article_id', $id)
            ->first();

        if ($saved) {
            $saved->delete();
            return back()->with('success', 'Article removed from saved');
        } else {
            SavedArticle::create([
                'user_id' => $userId,
                'article_id' => $id
            ]);
            return back()->with('success', 'Article saved!');
        }
    }
}
