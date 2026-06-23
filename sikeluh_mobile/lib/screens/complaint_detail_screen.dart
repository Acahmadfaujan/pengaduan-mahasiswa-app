import 'package:flutter/material.dart';
import 'edit_complaint_screen.dart';

class ComplaintDetailScreen extends StatelessWidget {
  final String title;
  final String status;
  final String category;
  final String location;
  final String description;
  final String? imageUrl; // Ditambahkan untuk menangkap link foto dari Laravel

  const ComplaintDetailScreen({
    Key? key,
    required this.title,
    required this.status,
    required this.category,
    required this.location,
    required this.description,
    this.imageUrl, // Bersifat opsional jika aduan tidak pakai foto
  }) : super(key: key);

  // FUNGSI OTOMATIS: Mengatur warna status secara dinamis di dalam screen
  Color _getStatusColor(String statusText) {
    switch (statusText.toLowerCase()) {
      case 'selesai':
        return Colors.green;
      case 'proses':
        return Colors.blue;
      default:
        return Colors.red; // Default untuk 'Pending' atau 'Belum Ditanggapi'
    }
  }

  @override
  Widget build(BuildContext context) {
    final Color currentStatusColor = _getStatusColor(status);

    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        title: const Text(
          "Detail Aduan", 
          style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)
        ),
        backgroundColor: Colors.white,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.black87),
        actions: [
          // Tombol Edit HANYA muncul jika statusnya bukan 'Selesai'
          if (status.toLowerCase() != 'selesai')
            IconButton(
              icon: const Icon(Icons.edit, color: Color(0xFF1976D2)),
              onPressed: () {
                Navigator.push(
                  context,
                  MaterialPageRoute(
                    builder: (_) => EditComplaintScreen(
                      title: title,
                      category: category,
                      location: location,
                      description: description,
                    ),
                  ),
                );
              },
            )
        ],
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // BAGIAN FOTO: Menampilkan gambar asli dari backend jika ada
            Container(
              width: double.infinity,
              height: 220,
              color: Colors.grey.shade200,
              child: imageUrl != null && imageUrl!.isNotEmpty
                  ? Image.network(
                      imageUrl!,
                      fit: BoxFit.cover,
                      errorBuilder: (context, error, stackTrace) {
                        // Jika gambar gagal dimuat dari server, tampilkan fallback icon
                        return const Center(
                          child: Icon(Icons.broken_image_outlined, size: 60, color: Colors.grey),
                        );
                      },
                    )
                  : const Center(
                      child: Icon(Icons.image_outlined, size: 80, color: Colors.grey),
                    ),
            ),
            
            Padding(
              padding: const EdgeInsets.all(20),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Status Badge & Tanggal
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: currentStatusColor.withOpacity(0.1), 
                          borderRadius: BorderRadius.circular(8)
                        ),
                        child: Text(
                          status, 
                          style: TextStyle(color: currentStatusColor, fontWeight: FontWeight.bold)
                        ),
                      ),
                      const Text(
                        "22 Jun 2026", 
                        style: TextStyle(color: Colors.grey, fontSize: 13)
                      ),
                    ],
                  ),
                  const SizedBox(height: 20),
                  
                  // Judul Aduan
                  Text(
                    title, 
                    style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.black87)
                  ),
                  const SizedBox(height: 20),
                  
                  // Detail Info Row
                  _buildInfoRow(Icons.category_outlined, "Kategori", category),
                  const SizedBox(height: 14),
                  _buildInfoRow(Icons.location_on_outlined, "Lokasi", location),
                  
                  const Divider(height: 40, thickness: 1),
                  
                  // Deskripsi
                  const Text(
                    "Deskripsi Aduan", 
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.black87)
                  ),
                  const SizedBox(height: 10),
                  Text(
                    description, 
                    style: const TextStyle(fontSize: 14, color: Colors.black54, height: 1.6)
                  ),
                ],
              ),
            )
          ],
        ),
      ),
    );
  }

  Widget _buildInfoRow(IconData icon, String label, String value) {
    return Row(
      children: [
        Icon(icon, color: Colors.grey.shade600, size: 22),
        const SizedBox(width: 10),
        Text(
          "$label: ", 
          style: TextStyle(color: Colors.grey.shade600, fontSize: 14)
        ),
        Expanded(
          child: Text(
            value, 
            style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14, color: Colors.black87)
          )
        ),
      ],
    );
  }
}