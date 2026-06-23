import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'login_screen.dart';
import 'user_dashboard.dart';
import 'admin_dashboard.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({Key? key}) : super(key: key);

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkLoginStatus();
  }

  void _checkLoginStatus() async {
    // Durasi jeda splash screen (misal: 3 detik) agar logo terlihat
    await Future.delayed(const Duration(seconds: 3));

    // Ambil data session dari penyimpanan lokal HP
    final prefs = await SharedPreferences.getInstance();
    final String? token = prefs.getString('token');
    final String? role = prefs.getString('role');

    if (!mounted) return;

    // Logika Percabangan Navigasi sesuai Aturan Dosen
    if (token != null && token.isNotEmpty) {
      // Jika sudah login, cek role-nya untuk diarahkan ke dashboard yang tepat
      if (role == 'admin') {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (_) => const AdminDashboard()),
        );
      } else {
        Navigator.pushReplacement(
          context,
          MaterialPageRoute(builder: (_) => const UserDashboard()),
        );
      }
    } else {
      // Jika belum pernah login, arahkan ke halaman Login
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => const LoginScreen()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white, // Menyesuaikan tema putih Sikeluh
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Logo / Icon Aplikasi Sikeluh (Disamakan dengan Login Screen)
            const Icon(
              Icons.lock_person_rounded,
              size: 120,
              color: Color(0xFF1976D2),
            ),
            const SizedBox(height: 20),
            // Nama Aplikasi
            const Text(
              "Sikeluh App",
              style: TextStyle(
                fontSize: 32,
                fontWeight: FontWeight.bold,
                color: Colors.black87,
                letterSpacing: 1.5,
              ),
            ),
            const SizedBox(height: 10),
            const Text(
              "Sistem Informasi Keluhan Masyarakat",
              style: TextStyle(
                fontSize: 14,
                color: Colors.grey,
              ),
            ),
            const SizedBox(height: 50),
            // Loading Indikator di bagian bawah
            const CircularProgressIndicator(
              valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF1976D2)),
            ),
          ],
        ),
      ),
    );
  }
}