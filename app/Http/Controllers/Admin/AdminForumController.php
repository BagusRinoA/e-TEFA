<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumQuestion;
use App\Models\ForumReply;
use Illuminate\Http\Request;

class AdminForumController extends Controller
{
    public function index()
    {
        $questions = ForumQuestion::with('user', 'replies')
            ->withCount('replies')
            ->latest()
            ->paginate(15);

        $replies = ForumReply::with('user', 'question')
            ->latest()
            ->paginate(15);

        return view('admin.forum.index', compact('questions', 'replies'));
    }

    public function show(ForumQuestion $forumQuestion)
    {
        return view('admin.forum.show', ['question' => $forumQuestion]);
    }

    public function destroy(Request $request, $type, $id)
    {
        if ($type === 'question') {
            $question = ForumQuestion::find($id);
            if ($question) {
                // Delete all replies first
                ForumReply::where('question_id', $id)->delete();
                $question->delete();
                return redirect()->route('admin.forum.index')->with('success', 'Question and its replies deleted successfully.');
            }
        } elseif ($type === 'reply') {
            $reply = ForumReply::find($id);
            if ($reply) {
                $reply->delete();
                return redirect()->route('admin.forum.index')->with('success', 'Reply deleted successfully.');
            }
        }

        return redirect()->route('admin.forum.index')->with('error', 'Item not found.');
    }
}
