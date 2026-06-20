<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function index()
    {
        return response()->json(
            Attachment::with('complaint')->get()
        );
    }

    public function show($id)
    {
        $attachment = Attachment::with('complaint')->find($id);

        if (!$attachment) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json($attachment);
    }

    public function store(Request $request)
    {
        $request->validate([
            'complaint_id' => 'required|exists:complaints,id',
            'file_path' => 'required|string'
        ]);

        $attachment = Attachment::create([
            'complaint_id' => $request->complaint_id,
            'file_path' => $request->file_path
        ]);

        return response()->json([
            'message' => 'Attachment berhasil ditambahkan',
            'data' => $attachment
        ], 201);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'file_path' => 'required|string'
    ]);

    $attachment = Attachment::find($id);

    if (!$attachment) {
        return response()->json([
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    $attachment->file_path = $request->file_path;
    $attachment->save();

    return response()->json([
        'message' => 'Attachment berhasil diupdate',
        'data' => $attachment
    ]);
}

    public function destroy($id)
    {
        $attachment = Attachment::find($id);

        if (!$attachment) {
            return response()->json([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $attachment->delete();

        return response()->json([
            'message' => 'Attachment berhasil dihapus'
        ]);
    }
}