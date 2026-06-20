<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Complaint;
use Illuminate\Http\Request;

class CommentWebController extends Controller
{
    public function index()
    {
        $comments = Comment::with('complaint')->get();

        return view('comments.index', compact('comments'));
    }

    public function create()
    {
        $complaints = Complaint::all();

        return view('comments.create', compact('complaints'));
    }

    public function store(Request $request)
    {
        Comment::create([
            'complaint_id' => $request->complaint_id,
            'user_id' => 1,
            'message' => $request->message
        ]);

        return redirect('/comments');
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        $complaints = Complaint::all();

        return view('comments.edit', compact('comment','complaints'));
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $comment->update([
            'message' => $request->message
        ]);

        return redirect('/comments');
    }

    public function destroy($id)
    {
        Comment::destroy($id);

        return redirect('/comments');
    }
}