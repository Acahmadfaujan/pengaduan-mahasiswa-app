<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index()
    {
        $data = Comment::with(['complaint', 'user'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = Comment::with(['complaint', 'user'])->find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'message' => 'required|string'
        ]);

        $data = Comment::create([
            'complaint_id' => $request->complaint_id,
            'user_id' => 1,
            'message' => $request->message
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $data = Comment::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'message' => 'required|string'
        ]);

        $data->update([
            'message' => $request->message
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil diupdate',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Comment::find($id);

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil dihapus'
        ]);
    }
}