<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ComplaintWebController extends Controller
{
    /**
     * Menampilkan semua daftar data aduan (Web)
     */
    public function index()
    {
        if (Auth::user() && Auth::user()->role === 'admin') {
            $complaints = Complaint::query()->with(['user', 'category'])->latest()->get();
        } else {
            $complaints = Complaint::query()->with(['user', 'category'])
                ->where('user_id', Auth::id())
                ->latest()
                ->get();
        }
        
        return view('complaints.index', compact('complaints'));
    }

    /**
     * Membuka formulir buat aduan baru (Web)
     */
    public function create()
    {
        $categories = Category::all();
        return view('complaints.create', compact('categories'));
    }

    /**
     * Menyimpan data aduan baru (Web)
     */
    public function store(Request $request)
    {
        // 1. Cek Keamanan: Pastikan user benar-benar sudah login di web
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk mengirim aduan!');
        }

        // 2. Validasi Keamanan Masukan Data
        $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required',
            'description' => 'required|string',
            'location'    => 'nullable|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $file->move(public_path('bukti_aduan'), $filename);
            $imagePath = 'bukti_aduan/' . $filename;
        }

        // FIX JALUR INSTAN: Menggunakan 'image_url' agar lolos dari batasan SQLite kamu
        Complaint::create([
            'user_id'     => Auth::id(),
            'category_id' => $request['category_id'],
            'title'       => $request['title'],
            'description' => $request['description'],
            'location'    => $request['location'], 
            'image_url'   => $imagePath, // Kolom disesuaikan dengan DB asli     
            'status'      => 'pending'
        ]);

        return redirect('/complaints')->with('success', 'Aduan berhasil dikirim dengan berkas bukti!');
    }

    /**
     * Menampilkan detail informasi aduan (Web)
     */
    public function show($id)
    {
        $complaint = Complaint::query()->with(['user', 'category', 'comments.user'])->findOrFail($id);
        return view('complaints.show', compact('complaint'));
    }

    /**
     * Membuka form edit aduan (Web)
     */
    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);
        $categories = Category::all();
        return view('complaints.edit', compact('complaint', 'categories'));
    }

    /**
     * Memperbarui data aduan (Web)
     */
    public function update(Request $request, $id)
    {
        $complaint = Complaint::findOrFail($id);

        if (Auth::user() && Auth::user()->role === 'admin') {
            $complaint->update(['status' => $request['status']]);
        } 
        else {
            $request->validate([
                'title'       => 'required|string|max:255',
                'category_id' => 'required',
                'description' => 'required|string',
                'location'    => 'nullable|string|max:255',
                'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
            ]);

            // Ambil data path gambar lama dari kolom image_url
            $imagePath = $complaint->image_url; 

            if ($request->hasFile('image')) {
                if ($complaint->image_url && File::exists(public_path($complaint->image_url))) {
                    File::delete(public_path($complaint->image_url));
                }
                
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('bukti_aduan'), $filename);
                $imagePath = 'bukti_aduan/' . $filename;
            }

            $complaint->update([
                'title'       => $request['title'],
                'category_id' => $request['category_id'],
                'description' => $request['description'],
                'location'    => $request['location'],
                'image_url'   => $imagePath // Kolom disesuaikan dengan DB asli
            ]);
        }

        return redirect('/complaints')->with('success', 'Aduan berhasil diperbarui!');
    }

    /**
     * Menghapus aduan dari database (Web)
     */
    public function destroy($id)
    {
        $complaint = Complaint::findOrFail($id);
        
        if ($complaint->image_url && File::exists(public_path($complaint->image_url))) {
            File::delete(public_path($complaint->image_url));
        }
        
        $complaint->delete();
        return redirect('/complaints')->with('success', 'Aduan berhasil dihapus.');
    }
}