<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAduan = Complaint::count();
        $pending = Complaint::where([['status', '=', 'pending']])->count();
        $diproses = Complaint::where([['status', '=', 'process']])->count();
        $selesai = Complaint::where([['status', '=', 'done']])->count();

        if (Auth::user()->role === 'admin') {
            $aduanTerbaru = Complaint::with(['user', 'category'])->latest()->take(5)->get();
        } else {
            $aduanTerbaru = Complaint::with(['user', 'category'])
                ->where([['user_id', '=', Auth::id()]])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact('totalAduan', 'pending', 'diproses', 'selesai', 'aduanTerbaru'));
    }
}