<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        return response()->json(Comment::with('complaint')->get(), 200);
    }

    public function show($id)
    {
        $comment = Comment::with('complaint')->find($id);
        return $comment ? response()->json($comment, 200) : response()->json(['message' => 'Not Found'], 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required',
            'message' => 'required'
        ]);

        $user = Auth::user();

        $comment = Comment::create([
            'complaint_id' => $request->input('complaint_id'),
            'message'      => $request->input('message'),
            'user_id'      => $user ? $user->id : 1,
        ]);

        return response()->json($comment, 201);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not Found'], 404);

        $comment->update([
            'complaint_id' => $request->input('complaint_id'),
            'message'      => $request->input('message'),
        ]);

        return response()->json($comment, 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) return response()->json(['message' => 'Not Found'], 404);
        $comment->delete();
        return response()->json(['message' => 'Deleted'], 200);
    }
}