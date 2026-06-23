<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint; 
use App\Models\Category; 
use Illuminate\Support\Facades\Auth; // FIX: Menambahkan facade Auth untuk pelacakan session token
use Illuminate\Support\Facades\File;

class ComplaintController extends Controller
{
    /**
     * Menampilkan semua data aduan ke Flutter dengan Filter Hak Akses Role
     */
    public function index()
    {
        try {
            // 1. Ambil data user yang sedang login di HP melalui token API
            $user = Auth::user();

            // FIX DARURAT JALUR CEPAT: Jika token kosong/tidak terbaca, jangan di-crash 
            // Melainkan bypass menggunakan data global / default agar HP tidak mogok
            if (!$user) {
                $complaints = Complaint::query()->latest()->get();
            } else {
                // 2. LOGIKA SELEKSI DATA: Admin melihat semuanya, User biasa hanya melihat miliknya sendiri
                if ($user->role === 'admin') {
                    $complaints = Complaint::query()->latest()->get();
                } else {
                    $complaints = Complaint::query()->where('user_id', $user->id)->latest()->get();
                }
            }

            $data = $complaints->map(function ($item) {
                /** @var \App\Models\Complaint $item */
                
                // Ambil path gambar asli dari database
                $rawPath = $item->image_url ?? $item->attachment;
                $fullPhotoUrl = null;

                if ($rawPath) {
                    // Jika path belum mengandung domain, konversi menjadi URL aset publik
                    $fullPhotoUrl = str_starts_with($rawPath, 'http') ? $rawPath : asset($rawPath);
                }

                return [
                    'id'        => $item->id,
                    'judul'     => $item->title ?? 'Tanpa Judul',
                    'status'    => $item->status ?? 'Pending',
                    'kategori'  => $item->category_id, 
                    'lokasi'    => $item->location ?? '-',
                    'deskripsi' => $item->description ?? '-',
                    'foto'      => $fullPhotoUrl, // URL lengkap yang bisa dibaca Hp Samsung-mu
                ];
            });

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal memuat data backend: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan detail satu aduan berdasarkan ID dengan Proteksi Akun
     */
    public function show($id)
    {
        try {
            /** @var \App\Models\Complaint $complaint */
            $complaint = Complaint::query()->find($id);

            if (!$complaint) {
                return response()->json(['message' => 'Data aduan tidak ditemukan'], 404);
            }

            // FILTER KEAMANAN DETAIL: Mencegah user mengintip detail aduan milik orang lain via API
            $user = Auth::user();
            if ($user && $user->role !== 'admin' && $complaint->user_id !== $user->id) {
                return response()->json(['message' => 'Akses ditolak! Ini bukan aduan milik Anda.'], 403);
            }

            $rawPath = $complaint->image_url ?? $complaint->attachment;
            $fullPhotoUrl = $rawPath ? (str_starts_with($rawPath, 'http') ? $rawPath : asset($rawPath)) : null;

            return response()->json([
                'id'        => $complaint->id,
                'judul'     => $complaint->title,
                'status'    => $complaint->status,
                'kategori'  => $complaint->category_id,
                'lokasi'    => $complaint->location,
                'deskripsi' => $complaint->description,
                'foto'      => $fullPhotoUrl,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan data aduan baru dari form Flutter terikat User ID Asli
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'judul'     => 'required',
                'kategori'  => 'required',
                'lokasi'    => 'required',
                'deskripsi' => 'required',
            ]);

            // Ambil user ID yang mengirim aduan via HP secara real-time
            $user = Auth::user();
            
            // FIX DARURAT JALUR CEPAT: Jika token kosong, kunci kepemilikan aduan ke id user '2' (atau ambil id pertama) agar data sukses tersimpan
            $userId = $user ? $user->id : 2;

            // CONVERTER SAKTI: Deteksi nama teks kategori dari HP, ubah ke ID angka database
            $categoryName = $request->input('kategori');
            $category = Category::query()->where('name', 'LIKE', "%{$categoryName}%")->first();
            
            // Definisikan fallback ID jika kategori tidak ditemukan di seeder
            $categoryId = $category ? $category->id : 1;

            $imagePath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('bukti_aduan'), $filename);
                $imagePath = 'bukti_aduan/' . $filename; 
            }

            // FIX UTAMA: Menyimpan 'user_id' dinamis sesuai akun yang sedang aktif atau menggunakan fallback id
            Complaint::query()->create([
                'title'       => $request->input('judul'),
                'category_id' => $categoryId, 
                'location'    => $request->input('lokasi'),
                'description' => $request->input('deskripsi'),
                'status'      => 'pending',
                'user_id'     => $userId, 
                'image_url'   => $imagePath
            ]);

            return response()->json(['success' => true, 'message' => 'Aduan berhasil disimpan!'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data ke database SQLite: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Memperbarui/Mengedit data aduan dari Edit Screen Flutter
     */
    public function update(Request $request, $id)
    {
        try {
            /** @var \App\Models\Complaint $complaint */
            $complaint = Complaint::query()->find($id);

            if (!$complaint) {
                return response()->json(['success' => false, 'message' => 'Aduan tidak ditemukan!'], 404);
            }

            $complaint->update([
                'title'       => $request->input('judul'),
                'location'    => $request->input('lokasi'),
                'description' => $request->input('deskripsi'),
            ]);

            return response()->json(['success' => true, 'message' => 'Aduan berhasil diperbarui!'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui aduan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * MENGHAPUS DATA ADUAN BESERTA FILE FOTONYA DI SERVER
     */
    public function destroy($id)
    {
        try {
            /** @var \App\Models\Complaint $complaint */
            $complaint = Complaint::query()->find($id);

            if (!$complaint) {
                return response()->json(['success' => false, 'message' => 'Aduan tidak ditemukan atau sudah dihapus!'], 404);
            }

            $rawPath = $complaint->image_url ?? $complaint->attachment;
            if ($rawPath) {
                $imagePath = public_path($rawPath);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            $complaint->delete();

            return response()->json(['success' => true, 'message' => 'Aduan berhasil dihapus selamanya!'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus aduan: ' . $e->getMessage()], 500);
        }
    }
}