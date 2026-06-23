import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'screens/login_screen.dart';
import 'screens/user_dashboard.dart';
import 'screens/admin_dashboard.dart';

void main() async {
  // Wajib ditambahkan jika fungsi main menggunakan async-await sebelum runApp
  WidgetsFlutterBinding.ensureInitialized();
  
  // Cek status login data token yang tersimpan di memori HP
  final prefs = await SharedPreferences.getInstance();
  final String? token = prefs.getString('token');
  
  // Ambil data role yang tersimpan dari login sebelumnya dari kunci 'user_role'
  String role = prefs.getString('user_role') ?? 'user'; 

  // Menentukan target halaman berikutnya yang akan dipanggil oleh Splash Screen
  final Widget targetScreen = token != null 
      ? (role == 'admin' ? const AdminDashboard() : const UserDashboard())
      : const LoginScreen();

  runApp(MyApp(
    initialScreen: targetScreen,
  ));
}

class MyApp extends StatelessWidget {
  final Widget initialScreen;

  const MyApp({Key? key, required this.initialScreen}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Sikeluh Mobile',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.blue,
        useMaterial3: false, // Menjaga kecocokan UI desain lama
      ),
      // FIX UTAMA: Menjadikan SplashScreen sebagai gerbang utama saat pertama kali aplikasi di-run
      home: SplashScreen(nextScreen: initialScreen), 
    );
  }
}

// ==========================================
// FIX TAMBAHAN: WIDGET SPLASH SCREEN MANDIRI
// ==========================================
class SplashScreen extends StatefulWidget {
  final Widget nextScreen;
  const SplashScreen({Key? key, required this.nextScreen}) : super(key: key);

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _startSplashScreen();
  }

  void _startSplashScreen() async {
    // Memberikan jeda waktu loading selama 3 detik sesuai permintaan dosen
    await Future.delayed(const Duration(seconds: 3));
    if (!mounted) return;
    
    // Pindah ke halaman target (Login/Dashboard) secara halus dan menghapus tumpukan splash
    Navigator.pushReplacement(
      context,
      MaterialPageRoute(builder: (_) => widget.nextScreen),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white, // Menjaga keselarasan tema putih Sikeluh
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Icon Pengaman utama (Sinkron dengan Logo Login Screen)
            const Icon(
              Icons.lock_person_rounded,
              size: 110,
              color: Color(0xFF1976D2),
            ),
            const SizedBox(height: 20),
            // Teks Judul Aplikasi
            const Text(
              "Sikeluh Mobile",
              style: TextStyle(
                fontSize: 28,
                fontWeight: FontWeight.bold,
                color: Colors.black87,
                letterSpacing: 1.2,
              ),
            ),
            const SizedBox(height: 6),
            const Text(
              "Sistem Pengaduan Keluhan Kampus",
              style: TextStyle(
                fontSize: 13,
                color: Colors.grey,
              ),
            ),
            const SizedBox(height: 50),
            // Indikator loading melingkar di bawah logo
            const CircularProgressIndicator(
              valueColor: AlwaysStoppedAnimation<Color>(Color(0xFF1976D2)),
            ),
          ],
        ),
      ),
    );
  }
}