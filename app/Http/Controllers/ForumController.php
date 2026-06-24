<?php

namespace App\Http\Controllers;

use App\Models\ForumQuestion;
use App\Models\ForumReply;
use App\Models\ForumUpvote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ForumController extends Controller
{
    use AuthorizesRequests;

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
            'image' => 'nullable|image|max:2048'
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        $user = Auth::user();
        ForumQuestion::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['description'],
            'tags' => $tags,
            'author' => $user->full_name ?? $user->username ?? $user->email,
            'user_id' => $user->id,
        ]);

        return redirect()->route('forum.index')->with('success', 'Question posted successfully!');
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
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        $question->update([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['description'],
            'tags' => $tags,
        ]);

        return redirect()->route('forum.show', $question)->with('success', 'Question updated successfully!');
    }

    public function destroy($id)
    {
        $question = ForumQuestion::findOrFail($id);
        $this->authorize('delete', $question);
        $question->delete();

        return redirect()->route('forum.index')->with('success', 'Question deleted successfully!');
    }

    public function reply(Request $request, $id)
    {
        $question = ForumQuestion::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|min:5',
        ]);

        $user = Auth::user();
        ForumReply::create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'author' => $user->full_name ?? $user->username ?? $user->email,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Reply posted successfully!');
    }

    public function deleteReply($replyId)
    {
        $reply = ForumReply::findOrFail($replyId);
        $this->authorize('delete', $reply);
        $questionId = $reply->question_id;
        $reply->delete();

        return back()->with('success', 'Reply deleted successfully!');
    }

    public function upvoteQuestion($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to upvote');
        }

        $question = ForumQuestion::findOrFail($id);
        $userId = Auth::id();

        $upvote = ForumUpvote::where('user_id', $userId)
            ->where('question_id', $id)
            ->first();

        if ($upvote) {
            // Already upvoted, so remove it
            $upvote->delete();
            $question->decrement('upvotes');
            return back()->with('success', 'Upvote removed');
        } else {
            // New upvote
            ForumUpvote::create([
                'user_id' => $userId,
                'question_id' => $id,
            ]);
            $question->increment('upvotes');
            return back()->with('success', 'Question upvoted!');
        }
    }

    public function upvoteReply($replyId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to upvote');
        }

        $reply = ForumReply::findOrFail($replyId);
        $userId = Auth::id();

        $upvote = ForumUpvote::where('user_id', $userId)
            ->where('reply_id', $replyId)
            ->first();

        if ($upvote) {
            // Already upvoted, so remove it
            $upvote->delete();
            $reply->decrement('upvotes');
            return back()->with('success', 'Upvote removed');
        } else {
            // New upvote
            ForumUpvote::create([
                'user_id' => $userId,
                'reply_id' => $replyId,
            ]);
            $reply->increment('upvotes');
            return back()->with('success', 'Reply upvoted!');
        }
    }
}
