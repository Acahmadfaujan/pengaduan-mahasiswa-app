import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../services/api_service.dart';

class EditComplaintScreen extends StatefulWidget {
  final int? id; // Tambahkan ID aduan agar Laravel tahu data mana yang di-update
  final String title;
  final String category;
  final String location;
  final String description;

  const EditComplaintScreen({
    Key? key,
    this.id, // Bersifat opsional, sesuaikan navigasi dari halaman detail
    required this.title,
    required this.category,
    required this.location,
    required this.description,
  }) : super(key: key);

  @override
  State<EditComplaintScreen> createState() => _EditComplaintScreenState();
}

class _EditComplaintScreenState extends State<EditComplaintScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _titleController;
  late TextEditingController _locationController;
  late TextEditingController _contentController;
  String? _selectedCategory;
  bool _isUpdating = false;

  // SINKRONISASI: Samakan nama kategori dengan DatabaseSeeder Laravel kamu
  final List<String> _categories = [
    'Fasilitas & Infrastruktur',
    'Layanan Academic',
    'Lingkungan Kampus'
  ];

  @override
  void initState() {
    super.initState();
    _titleController = TextEditingController(text: widget.title);
    _locationController = TextEditingController(text: widget.location);
    _contentController = TextEditingController(text: widget.description);
    
    // Normalisasi string jika ada perbedaan karakter 'dan' atau '&'
    String normalizedCategory = widget.category.replaceAll('dan', '&');
    if (_categories.contains(normalizedCategory)) {
      _selectedCategory = normalizedCategory;
    } else {
      _selectedCategory = _categories[0];
    }
  }

  @override
  void dispose() {
    _titleController.dispose();
    _locationController.dispose();
    _contentController.dispose();
    super.dispose();
  }

  // FUNGSI UTAMA: Kirim data perubahan ke SQLite Laravel melalui API
  Future<void> _updateComplaint() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() { _isUpdating = true; });

    try {
      // Menembak rute update di Laravel, sesuaikan endpoint ID-nya jika dibutuhkan
      // Contoh: /api/complaints/id atau jika kamu buat rute custom /api/aduan/update/id
      final String url = widget.id != null 
          ? '${ApiService.baseUrl}/complaints/${widget.id}' 
          : '${ApiService.baseUrl}/aduan'; // Fallback route

      final response = await http.put(
        Uri.parse(url),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'judul': _titleController.text.trim(),
          'kategori': _selectedCategory,
          'lokasi': _locationController.text.trim(),
          'deskripsi': _contentController.text.trim(),
        }),
      );

      if (response.statusCode == 200 || response.statusCode == 201) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Aduan Berhasil Diperbarui!"), backgroundColor: Colors.orange),
        );
        
        // Pop 2 kali agar langsung kembali ke halaman Dashboard utama dan me-refresh data
        Navigator.pop(context, true); 
      } else {
        throw Exception('Gagal memperbarui data di server.');
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: $e"), backgroundColor: Colors.red),
      );
    } finally {
      if (mounted) {
        setState(() { _isUpdating = false; });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8F9FA),
      appBar: AppBar(
        title: const Text("Edit Aduan", style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold, fontSize: 18)),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.black87),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildLabel("Judul Aduan"),
              TextFormField(
                controller: _titleController,
                validator: (v) => v!.isEmpty ? "Judul tidak boleh kosong" : null,
                decoration: _buildInputDecoration("Masukkan judul aduan", Icons.title_rounded),
              ),
              const SizedBox(height: 18),
              
              _buildLabel("Kategori"),
              DropdownButtonFormField<String>(
                value: _selectedCategory,
                validator: (v) => v == null ? "Pilih kategori" : null,
                decoration: _buildInputDecoration("Pilih kategori", Icons.category_outlined),
                items: _categories.map((c) => DropdownMenuItem(value: c, child: Text(c, style: const TextStyle(fontSize: 14)))).toList(),
                onChanged: (val) => setState(() => _selectedCategory = val),
              ),
              const SizedBox(height: 18),
              
              _buildLabel("Lokasi Kejadian"),
              TextFormField(
                controller: _locationController,
                validator: (v) => v!.isEmpty ? "Lokasi tidak boleh kosong" : null,
                decoration: _buildInputDecoration("Contoh: Ruang Kelas B.2", Icons.location_on_outlined),
              ),
              const SizedBox(height: 18),
              
              _buildLabel("Deskripsi Masalah"),
              TextFormField(
                controller: _contentController,
                maxLines: 5,
                validator: (v) => v!.isEmpty ? "Deskripsi tidak boleh kosong" : null,
                decoration: _buildInputDecoration("Tulis perubahan detail laporan...", Icons.description_outlined),
              ),
              const SizedBox(height: 35),
              
              SizedBox(
                width: double.infinity,
                height: 55,
                child: ElevatedButton(
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.orange.shade700, 
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                    elevation: 2,
                  ),
                  onPressed: _isUpdating ? null : _updateComplaint,
                  child: _isUpdating
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          "SIMPAN PERUBAHAN", 
                          style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white, letterSpacing: 0.5)
                        ),
                ),
              )
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8, left: 2),
      child: Text(
        text, 
        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.black87)
      ),
    );
  }

  InputDecoration _buildInputDecoration(String hint, IconData icon) {
    return InputDecoration(
      hintText: hint,
      hintStyle: TextStyle(color: Colors.grey.shade400, fontSize: 14),
      prefixIcon: Icon(icon, color: Colors.grey.shade500, size: 22),
      filled: true,
      fillColor: Colors.white,
      contentPadding: const EdgeInsets.symmetric(vertical: 16, horizontal: 16),
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: BorderSide(color: Colors.grey.shade300)),
      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: BorderSide(color: Colors.grey.shade200)),
      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: const BorderSide(color: Colors.orange, width: 1.5)),
      errorBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: const BorderSide(color: Colors.red, width: 1)),
    );
  }
}