import 'package:flutter/material.dart';
import '../services/api_service.dart';
import 'login_screen.dart';

class AdminDashboard extends StatefulWidget {
  const AdminDashboard({Key? key}) : super(key: key);

  @override
  State<AdminDashboard> createState() => _AdminDashboardState();
}

class _AdminDashboardState extends State<AdminDashboard> {
  late Future<List<dynamic>> _futureAduan;

  @override
  void initState() {
    super.initState();
    // Memanggil fungsi fetchAduan dari ApiService yang mengarah ke Laravel kamu
    _futureAduan = ApiService.fetchAduan();
  }

  // Fungsi untuk refresh data saat ditarik ke bawah
  Future<void> _refreshData() async {
    setState(() {
      _futureAduan = ApiService.fetchAduan();
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F5FA),
      appBar: AppBar(
        title: const Text('Dashboard Admin', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
        backgroundColor: const Color(0xFF1A1F36),
        elevation: 0,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout, color: Colors.white),
            onPressed: () => Navigator.pushReplacement(
              context, 
              MaterialPageRoute(builder: (_) => const LoginScreen())
            ),
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
                child: Padding(
                  padding: const EdgeInsets.all(20.0),
                  child: Text(
                    "Error: ${snapshot.error}",
                    textAlign: TextAlign.center,
                    style: const TextStyle(color: Colors.red),
                  ),
                ),
              );
            } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
              return const Center(child: Text("Belum ada aduan masuk."));
            }

            final dataAduan = snapshot.data!;
            
            // LOGIKA HITUNG REKAP DATA SECARA OTOMATIS DARI SQLITE
            int total = dataAduan.length;
            int pending = dataAduan.where((item) => item['status'].toString().toLowerCase() == 'pending').length;
            int selesai = dataAduan.where((item) => item['status'].toString().toLowerCase() == 'selesai').length;

            return SingleChildScrollView(
              physics: const AlwaysScrollableScrollPhysics(),
              padding: const EdgeInsets.all(20.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    "Ringkasan Aduan Masuk", 
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Color(0xFF1A1F36))
                  ),
                  const SizedBox(height: 16),
                  
                  // RINGKASAN REKAP DATA REAL TIME
                  Row(
                    children: [
                      _buildStatBox("Total", total.toString(), Colors.purple),
                      const SizedBox(width: 12),
                      _buildStatBox("Pending", pending.toString(), Colors.red),
                      const SizedBox(width: 12),
                      _buildStatBox("Selesai", selesai.toString(), Colors.green),
                    ],
                  ),
                  const SizedBox(height: 25),
                  const Text(
                    "Daftar Aduan Perlu Validasi", 
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Color(0xFF1A1F36))
                  ),
                  const SizedBox(height: 12),
                  
                  // LIST DAFTAR ADUAN DARI BACKEND
                  ListView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: dataAduan.length,
                    itemBuilder: (context, index) {
                      final aduan = dataAduan[index];
                      return _buildAdminTaskCard(
                        aduan['judul'] ?? 'Tanpa Judul',
                        "Kategori: ${aduan['kategori']}",
                        aduan['status'] ?? 'Pending',
                      );
                    },
                  )
                ],
              ),
            );
          },
        ),
      ),
    );
  }

  Widget _buildStatBox(String title, String count, Color color) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white, 
          borderRadius: BorderRadius.circular(12), 
          border: Border.all(color: color.withOpacity(0.3))
        ),
        child: Column(
          children: [
            Text(title, style: TextStyle(color: Colors.grey.shade600, fontSize: 12)),
            const SizedBox(height: 4),
            Text(count, style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: color)),
          ],
        ),
      ),
    );
  }

  Widget _buildAdminTaskCard(String title, String subtitle, String status) {
    Color statusColor = Colors.amber.shade900;
    Color statusBg = Colors.amber.shade100;

    if (status.toLowerCase() == 'selesai') {
      statusColor = Colors.green.shade900;
      statusBg = Colors.green.shade100;
    }

    return Card(
      margin: const EdgeInsets.symmetric(vertical: 6),
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      child: ListTile(
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text(subtitle),
        trailing: Container(
          padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
          decoration: BoxDecoration(color: statusBg, borderRadius: BorderRadius.circular(6)),
          child: Text(
            status, 
            style: TextStyle(color: statusColor, fontSize: 11, fontWeight: FontWeight.bold)
          ),
        ),
      ),
    );
  }
}