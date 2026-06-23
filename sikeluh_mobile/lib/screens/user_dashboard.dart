import 'package:flutter/material.dart';
import '../services/api_service.dart';
import 'create_complaint_screen.dart';
import 'login_screen.dart';
import 'complaint_detail_screen.dart';

class UserDashboard extends StatefulWidget {
  const UserDashboard({Key? key}) : super(key: key);

  @override
  State<UserDashboard> createState() => _UserDashboardState();
}

class _UserDashboardState extends State<UserDashboard> {
  int _selectedIndex = 0;
  late Future<List<dynamic>> _futureAduan;

  @override
  void initState() {
    super.initState();
    // Inisialisasi API di awal agar tidak terpanggil ulang saat ganti tab
    _futureAduan = ApiService.fetchAduan();
  }

  // Fungsi refresh data saat layar ditarik ke bawah (Pull to Refresh)
  Future<void> _refreshData() async {
    setState(() {
      _futureAduan = ApiService.fetchAduan();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        title: const Text('Sikeluh', style: TextStyle(color: Colors.black87, fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications_outlined, color: Colors.black87),
            onPressed: () {},
          )
        ],
      ),
      body: RefreshIndicator(
        onRefresh: _refreshData,
        child: FutureBuilder<List<dynamic>>(
          future: _futureAduan,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            } else if (snapshot.hasError) {
              return Center(
                child: SingleChildScrollView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  child: Padding(
                    padding: const EdgeInsets.all(20.0),
                    child: Text("Error: ${snapshot.error}", style: const TextStyle(color: Colors.red)),
                  ),
                ),
              );
            } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
              // Tetap bisa ganti ke tab profil walau data aduan di SQLite masih kosong
              if (_selectedIndex == 2) return _buildProfilTab();
              return Center(
                child: SingleChildScrollView(
                  physics: const AlwaysScrollableScrollPhysics(),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const SizedBox(height: 40),
                      const Text("Belum ada aduan.", style: TextStyle(color: Colors.grey, fontSize: 15)),
                      const SizedBox(height: 8),
                      TextButton(onPressed: _refreshData, child: const Text("Muat Ulang"))
                    ],
                  ),
                ),
              );
            }

            final listAduan = snapshot.data!;

            // PEMILIHAN TAB KONTEN
            if (_selectedIndex == 0) {
              return _buildHomeTab(listAduan);
            } else if (_selectedIndex == 1) {
              return _buildAduanSayaTab(listAduan);
            } else {
              return _buildProfilTab();
            }
          },
        ),
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () async {
          // Menunggu callback 'true' dari formulir aduan untuk reload otomatis
          final result = await Navigator.push(context, MaterialPageRoute(builder: (_) => const CreateComplaintScreen()));
          if (result == true) _refreshData();
        },
        label: const Text("Buat Aduan", style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
        icon: const Icon(Icons.add, color: Colors.white),
        backgroundColor: const Color(0xFF1976D2),
      ),
      bottomNavigationBar: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) => setState(() => _selectedIndex = index),
        selectedItemColor: const Color(0xFF1976D2),
        unselectedItemColor: Colors.grey,
        elevation: 10,
        items: const [
          BottomNavigationBarItem(icon: Icon(Icons.home_rounded), label: "Home"),
          BottomNavigationBarItem(icon: Icon(Icons.assignment_rounded), label: "Aduan Saya"),
          BottomNavigationBarItem(icon: Icon(Icons.person_rounded), label: "Profil"),
        ],
      ),
    );
  }

  // TAB 1: HALAMAN UTAMA (BATASI MAKSIMAL 3 DATA TERBARU)
  Widget _buildHomeTab(List<dynamic> listAduan) {
    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.all(20),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text("Halo, Fauzan 👋", style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.black87)),
          const Text("Pantau terus status keluhan infrastruktur kampus Anda", style: TextStyle(fontSize: 13, color: Colors.grey)),
          const SizedBox(height: 25),
          const Text("Aduan Terbaru", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Color(0xFF1A1F36))),
          const SizedBox(height: 8),
          ListView.builder(
            shrinkWrap: true,
            physics: const NeverScrollableScrollPhysics(),
            itemCount: listAduan.length > 3 ? 3 : listAduan.length,
            itemBuilder: (context, index) {
              final item = listAduan[index];
              return _buildAduanCard(item);
            },
          ),
        ],
      ),
    );
  }

  // TAB 2: RIWAYAT SEMUA ADUAN USER
  Widget _buildAduanSayaTab(List<dynamic> listAduan) {
    return ListView.builder(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.all(20),
      itemCount: listAduan.length,
      itemBuilder: (context, index) {
        final item = listAduan[index];
        return _buildAduanCard(item);
      },
    );
  }

  // TAB 3: PROFIL USER
  Widget _buildProfilTab() {
    return SingleChildScrollView(
      physics: const AlwaysScrollableScrollPhysics(),
      padding: const EdgeInsets.all(24),
      child: Column(
        children: [
          const SizedBox(height: 20),
          const CircleAvatar(radius: 50, backgroundColor: Color(0xFFE3F2FD), child: Icon(Icons.person_rounded, size: 60, color: Color(0xFF1976D2))),
          const SizedBox(height: 16),
          const Text("Fauzan Achmad", style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.black87)),
          const Text("Masyarakat / Mahasiswa", style: TextStyle(color: Colors.grey, fontSize: 14)),
          const Divider(height: 50, thickness: 1),
          Card(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            child: ListTile(
              leading: const Icon(Icons.logout_rounded, color: Colors.red),
              title: const Text("Keluar (Logout)", style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold)),
              trailing: const Icon(Icons.chevron_right, color: Colors.grey),
              onTap: () => Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const LoginScreen())),
            ),
          ),
        ],
      ),
    );
  }

  // LOGIKA DINAMIS: Deteksi string status langsung diubah ke Objek Color
  Color _getStatusColor(String? status) {
    switch (status?.toLowerCase()) {
      case 'diproses':
      case 'process': 
        return Colors.orange;
      case 'pending': 
        return Colors.red;
      case 'done':
      case 'selesai': 
        return Colors.green;
      default: 
        return Colors.grey;
    }
  }

  // FIX UTAMA: Tipe data parameter diganti jadi dynamic agar fleksibel membaca data JSON backend Laravel yang baru
  Widget _buildAduanCard(dynamic item) {
    final String currentStatus = (item['status'] ?? 'Pending').toString();
    final Color currentStatusColor = _getStatusColor(currentStatus);

    return Card(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      margin: const EdgeInsets.symmetric(vertical: 8),
      elevation: 1.5,
      child: InkWell(
        borderRadius: BorderRadius.circular(12),
        onTap: () => Navigator.push(
          context, 
          MaterialPageRoute(
            builder: (_) => ComplaintDetailScreen(
              title: (item['judul'] ?? 'Tanpa Judul').toString(), 
              status: currentStatus, 
              category: (item['kategori'] ?? '-').toString(), 
              location: (item['lokasi'] ?? '-').toString(), 
              description: (item['deskripsi'] ?? '-').toString(),
              imageUrl: item['foto'] != null ? item['foto'].toString() : null,
            ),
          ),
        ),
        child: ListTile(
          contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
          leading: const CircleAvatar(
            backgroundColor: Color(0xFFE3F2FD), 
            child: Icon(Icons.report_problem_outlined, color: Color(0xFF1976D2))
          ),
          title: Text(
            item['judul'] ?? 'Tanpa Judul', 
            style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 15, color: Colors.black87)
          ),
          subtitle: Padding(
            padding: const EdgeInsets.only(top: 4),
            child: Text(
              currentStatus.toUpperCase(), 
              style: TextStyle(color: currentStatusColor, fontSize: 12, fontWeight: FontWeight.bold)
            ),
          ),
          trailing: const Icon(Icons.chevron_right, color: Colors.grey),
        ),
      ),
    );
  }
}