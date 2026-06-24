<?php

namespace App\Http\Controllers;

use App\Models\ForumQuestion;
use App\Models\ForumReply;
use App\Models\ForumUpvote;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ForumControllerExample extends Controller
{
    use AuthorizesRequests;

    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    public function index()
    {
        $allTags = ForumQuestion::pluck('tags')->flatten()->unique();

        $questions = ForumQuestion::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->when(request('tag'), function ($query, $tag) {
                $query->whereJsonContains('tags', $tag);
            })
            ->latest()
            ->paginate(10);

        return view('forum.index', compact('questions', 'allTags'));
    }

    public function create()
    {
        return view('forum.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:10',
            'category' => 'required',
            'description' => 'required|min:20',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048'
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['description'],
            'tags' => $tags,
            'author' => Auth::user()->full_name ?? Auth::user()->username ?? Auth::user()->email,
            'user_id' => Auth::id(),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                $data['image'] = $this->imageUploadService->upload(
                    $request->file('image'),
                    'forum'
                );
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload gambar: ' . $e->getMessage());
            }
        }

        ForumQuestion::create($data);
        return redirect()->route('forum.index')->with('success', 'Pertanyaan berhasil diposting!');
    }

    public function show($id)
    {
        $question = ForumQuestion::with('replies.user')->findOrFail($id);
        return view('forum.show', compact('question'));
    }

    public function edit($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('update', $question);
        return view('forum.edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('update', $question);

        $validated = $request->validate([
            'title' => 'required|min:10',
            'category' => 'required',
            'description' => 'required|min:20',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['description'],
            'tags' => $tags,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            try {
                // Hapus gambar lama jika ada
                if ($question->image) {
                    $this->imageUploadService->delete($question->image);
                }
                // Upload gambar baru
                $data['image'] = $this->imageUploadService->upload(
                    $request->file('image'),
                    'forum'
                );
            } catch (\Exception $e) {
                return redirect()->back()->withError('Gagal mengupload gambar: ' . $e->getMessage());
            }
        }

        $question->update($data);
        return redirect()->route('forum.show', $question)->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('delete', $question);

        // Hapus gambar jika ada
        if ($question->image) {
            $this->imageUploadService->delete($question->image);
        }

        $question->delete();
        return redirect()->route('forum.index')->with('success', 'Pertanyaan berhasil dihapus!');
    }

    // Reply methods (simplified)
    public function storeReply(Request $request, $questionId)
    {
        $validated = $request->validate([
            'content' => 'required|min:10',
        ]);

        ForumReply::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'forum_question_id' => $questionId,
        ]);

        return redirect()->back()->with('success', 'Balasan berhasil ditambahkan!');
    }

    public function deleteReply($replyId)
    {
        $reply = ForumReply::findOrFail($replyId);
        $this->authorize('delete', $reply);
        $questionId = $reply->forum_question_id;

        $reply->delete();

        return redirect()->route('forum.show', $questionId)->with('success', 'Balasan berhasil dihapus!');
    }
}
