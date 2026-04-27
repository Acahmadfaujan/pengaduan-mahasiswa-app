<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Complaint;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with([
            'user',
            'category',
            'comments.user',
            'attachments'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $complaints
        ], 200);
    }

    public function show($id)
    {
        $complaint = Complaint::with([
            'user',
            'category',
            'comments.user',
            'attachments'
        ])->find($id);

        if (!$complaint) {
            return response()->json([
                'status' => 'error',
                'message' => 'Complaint tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $complaint
        ], 200);
    }

    public function store(Request $request)
    {
        $complaint = Complaint::create($request->validate([
            'user_id' => 'required',
            'category_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]));

        return response()->json([
            'status' => 'success',
            'data' => $complaint
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json([
                'status' => 'error',
                'message' => 'Complaint tidak ditemukan'
            ], 404);
        }

        $complaint->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $complaint
        ]);
    }

    public function destroy($id)
    {
        $complaint = Complaint::find($id);

        if (!$complaint) {
            return response()->json([
                'status' => 'error',
                'message' => 'Complaint tidak ditemukan'
            ], 404);
        }

        $complaint->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Complaint berhasil dihapus'
        ]);
    }
}