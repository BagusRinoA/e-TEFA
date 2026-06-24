<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    public function index()
    {
        $articles = Article::latest('published_at')->paginate(12);

        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'author' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'read_time' => 'nullable|string|max:50',
            'published_at' => 'nullable|date',
        ]);

        $data['published_at'] = $data['published_at'] ?? now();

        if ($request->hasFile('image')) {
            try {
                $data['image'] = $this->imageUploadService->upload(
                    $request->file('image'),
                    'articles'
                );
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload gambar: ' . $e->getMessage());
            }
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // Hapus gambar dari storage jika ada
        if ($article->image) {
            $this->imageUploadService->delete($article->image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus');
    }
}
