<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Comment;
use App\Models\Attachment;

class DashboardController extends Controller
{
    public function index()
    {
        $complaints = Complaint::count();
        $comments = Comment::count();
        $attachments = Attachment::count();

        return view('dashboard.index', compact(
            'complaints',
            'comments',
            'attachments'
        ));
    }
}