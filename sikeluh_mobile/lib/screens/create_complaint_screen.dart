import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:http/http.dart' as http;
import '../services/api_service.dart';

class CreateComplaintScreen extends StatefulWidget {
  const CreateComplaintScreen({Key? key}) : super(key: key);

  @override
  State<CreateComplaintScreen> createState() => _CreateComplaintScreenState();
}

class _CreateComplaintScreenState extends State<CreateComplaintScreen> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _locationController = TextEditingController();
  final _contentController = TextEditingController();
  
  File? _selectedImage;
  final ImagePicker _picker = ImagePicker();
  bool _isUploading = false;

  String? _selectedCategory;
  final List<String> _categories = [
    'Fasilitas & Infrastruktur',
    'Layanan Akademik',
    'Lingkungan Kampus'
  ];

  Future<void> _pickImage() async {
    final XFile? image = await _picker.pickImage(
      source: ImageSource.gallery,
      imageQuality: 70, // Kompres gambar sedikit agar upload ke SQLite lebih enteng
    );
    if (image != null) {
      setState(() {
        _selectedImage = File(image.path);
      });
    }
  }

  Future<void> _submitComplaint() async {
    // Validasi form otomatis sebelum menembak API Laravel
    if (!_formKey.currentState!.validate()) return;

    setState(() { _isUploading = true; });

    try {
      var request = http.MultipartRequest('POST', Uri.parse('${ApiService.baseUrl}/aduan/store'));
      
      // Sinkronisasi data field dengan ComplaintController Laravel
      request.fields['judul'] = _titleController.text.trim();
      request.fields['kategori'] = _selectedCategory!;
      request.fields['lokasi'] = _locationController.text.trim();
      request.fields['deskripsi'] = _contentController.text.trim();

      if (_selectedImage != null) {
        request.files.add(await http.MultipartFile.fromPath('foto', _selectedImage!.path));
      }

      var response = await request.send();

      if (response.statusCode == 200) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Aduan Berhasil Dikirim!"), backgroundColor: Colors.green),
        );
        Navigator.pop(context, true); // Kirim passing true agar dashboard otomatis me-refresh data
      } else {
        throw Exception('Gagal menyimpan data ke database SQLite');
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: $e"), backgroundColor: Colors.red),
      );
    } finally {
      if (mounted) {
        setState(() { _isUploading = false; });
      }
    }
  }

  @override
  void dispose() {
    _titleController.dispose();
    _locationController.dispose();
    _contentController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8F9FA),
      appBar: AppBar(
        title: const Text("Buat Aduan Baru", style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold, fontSize: 18)),
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
                validator: (v) => v!.isEmpty ? "Judul aduan tidak boleh kosong" : null,
                decoration: _buildInputDecoration("Masukkan inti keluhan Anda...", Icons.title_rounded),
              ),
              const SizedBox(height: 18),
              
              _buildLabel("Kategori"),
              DropdownButtonFormField<String>(
                value: _selectedCategory,
                validator: (v) => v == null ? "Silakan pilih kategori aduan" : null,
                decoration: _buildInputDecoration("Pilih kategori...", Icons.category_outlined),
                items: _categories.map((c) => DropdownMenuItem(value: c, child: Text(c))).toList(),
                onChanged: (val) => setState(() => _selectedCategory = val),
              ),
              const SizedBox(height: 18),
              
              _buildLabel("Lokasi Kejadian"),
              TextFormField(
                controller: _locationController,
                validator: (v) => v!.isEmpty ? "Detail lokasi wajib diisi" : null,
                decoration: _buildInputDecoration("Contoh: Gedung H Lt. 3", Icons.location_on_outlined),
              ),
              const SizedBox(height: 18),
              
              _buildLabel("Deskripsi Lengkap Kronologi"),
              TextFormField(
                controller: _contentController,
                maxLines: 4,
                validator: (v) => v!.isEmpty ? "Tuliskan deskripsi keluhan secara jelas" : null,
                decoration: _buildInputDecoration("Tulis rincian masalah di sini...", Icons.description_outlined),
              ),
              const SizedBox(height: 24),
              
              _buildLabel("Foto Bukti Lampiran (Opsional)"),
              GestureDetector(
                onTap: _pickImage,
                child: Container(
                  width: double.infinity,
                  height: 160,
                  decoration: BoxDecoration(
                    color: Colors.white,
                    borderRadius: BorderRadius.circular(14),
                    border: Border.all(color: Colors.grey.shade300, style: BorderStyle.solid),
                    boxShadow: [
                      BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))
                    ],
                  ),
                  child: _selectedImage == null
                      ? Column(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Icon(Icons.add_a_photo_outlined, size: 40, color: Colors.grey.shade400),
                            const SizedBox(height: 8),
                            Text("Klik untuk upload foto dari galeri", style: TextStyle(color: Colors.grey.shade500, fontSize: 13)),
                          ],
                        )
                      : ClipRRect(
                          borderRadius: BorderRadius.circular(14),
                          child: Image.file(_selectedImage!, fit: BoxFit.cover),
                        ),
                ),
              ),
              const SizedBox(height: 35),
              
              // Tombol Submit Premium dengan Loading Terintegrasi
              SizedBox(
                width: double.infinity,
                height: 55,
                child: ElevatedButton(
                  onPressed: _isUploading ? null : _submitComplaint,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color(0xFF1976D2),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                    elevation: 2,
                  ),
                  child: _isUploading
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text(
                          "KIRIM ADUAN SEKARANG",
                          style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white, letterSpacing: 0.5),
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
        style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 14, color: Colors.black87),
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
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: BorderSide(color: Colors.grey.shade300),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: BorderSide(color: Colors.grey.shade200),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(color: Color(0xFF1976D2), width: 1.5),
      ),
      errorBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(color: Colors.red, width: 1),
      ),
    );
  }
}