<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentWebController extends Controller
{
    public function index()
    {
        $comments = Comment::with(['complaint', 'user'])->latest()->get();
        return view('comments.index', compact('comments'));
    }

    public function create()
    {
        $complaints = Complaint::all();
        return view('comments.create', compact('complaints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required',
            'message' => 'required'
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        Comment::create([
            'complaint_id' => $request->input('complaint_id'),
            'message'      => $request->input('message'),
            'user_id'      => $user->id,
        ]);

        return redirect()->back();
    }

    public function edit($id)
    {
        $comment = Comment::findOrFail($id);
        $complaints = Complaint::all();
        return view('comments.edit', compact('comment', 'complaints'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update([
            'complaint_id' => $request->input('complaint_id') ?? $comment->complaint_id,
            'message'      => $request->input('message'),
        ]);

        return redirect('/complaints/' . $comment->complaint_id);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->back();
    }
}