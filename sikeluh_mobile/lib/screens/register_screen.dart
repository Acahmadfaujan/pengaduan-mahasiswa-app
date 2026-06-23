import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import '../services/api_service.dart';
import 'login_screen.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({Key? key}) : super(key: key);

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();

  bool _isLoading = false;
  bool _isPasswordVisible = false;
  bool _isConfirmVisible = false;

  // FUNGSI UTAMA: Kirim data pendaftaran ke Laravel (SQLite)
  Future<void> _handleRegister() async {
    if (!_formKey.currentState!.validate()) return;

    setState(() { _isLoading = true; });

    try {
      final response = await http.post(
        Uri.parse('${ApiService.baseUrl}/register'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'name': _nameController.text.trim(),
          'email': _emailController.text.trim(),
          'password': _passwordController.text.trim(),
          'role': 'user', // Otomatis mendaftar sebagai masyarakat umum / mahasiswa
        }),
      );

      final data = jsonDecode(response.body);

      // FIX UTAMA: Menambahkan toleransi status 211 sesuai return dari AuthController backend kamu
      if (response.statusCode == 200 || response.statusCode == 201 || response.statusCode == 211) {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Registrasi Berhasil! Silakan Masuk."), backgroundColor: Colors.green),
        );
        // Jika sukses, langsung lempar kembali ke halaman Login
        Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const LoginScreen()));
      } else {
        String errMsg = data['message'] ?? "Pendaftaran gagal.";
        throw Exception(errMsg);
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: ${e.toString().replaceAll('Exception: ', '')}"), backgroundColor: Colors.red),
      );
    } finally {
      if (mounted) {
        setState(() { _isLoading = false; });
      }
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(28.0),
            child: Form(
              key: _formKey,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.app_registration_rounded, size: 85, color: Color(0xFF1976D2)),
                  const SizedBox(height: 16),
                  const Text("Daftar Akun", style: TextStyle(fontSize: 26, fontWeight: FontWeight.bold, color: Colors.black87)),
                  const SizedBox(height: 4),
                  const Text("Lengkapi form untuk membuat akun baru", style: TextStyle(color: Colors.grey, fontSize: 14)),
                  const SizedBox(height: 35),

                  // INPUT NAMA LENGKAP
                  TextFormField(
                    controller: _nameController,
                    validator: (v) => v!.isEmpty ? "Nama lengkap wajib diisi" : null,
                    decoration: _buildInputDecoration("Nama Lengkap", Icons.person_outline),
                  ),
                  const SizedBox(height: 18),

                  // INPUT EMAIL / NIM
                  TextFormField(
                    controller: _emailController,
                    keyboardType: TextInputType.emailAddress,
                    validator: (v) {
                      if (v!.isEmpty) return "Email tidak boleh kosong";
                      if (!v.contains('@')) return "Format email tidak valid";
                      return null;
                    },
                    decoration: _buildInputDecoration("Alamat Email", Icons.email_outlined),
                  ),
                  const SizedBox(height: 18),

                  // INPUT PASSWORD
                  TextFormField(
                    controller: _passwordController,
                    obscureText: !_isPasswordVisible,
                    validator: (v) => v!.length < 6 ? "Password minimal 6 karakter" : null,
                    decoration: _buildInputDecoration("Password", Icons.lock_outline).copyWith(
                      suffixIcon: IconButton(
                        icon: Icon(_isPasswordVisible ? Icons.visibility : Icons.visibility_off),
                        onPressed: () => setState(() => _isPasswordVisible = !_isPasswordVisible),
                      ),
                    ),
                  ),
                  const SizedBox(height: 18),

                  // INPUT KONFIRMASI PASSWORD
                  TextFormField(
                    controller: _confirmPasswordController,
                    obscureText: !_isConfirmVisible,
                    validator: (v) {
                      if (v!.isEmpty) return "Konfirmasi password wajib diisi";
                      if (v != _passwordController.text) return "Password tidak cocok";
                      return null;
                    },
                    decoration: _buildInputDecoration("Konfirmasi Password", Icons.lock_clock_outlined).copyWith(
                      suffixIcon: IconButton(
                        icon: Icon(_isConfirmVisible ? Icons.visibility : Icons.visibility_off),
                        onPressed: () => setState(() => _isConfirmVisible = !_isConfirmVisible),
                      ),
                    ),
                  ),
                  const SizedBox(height: 30),

                  // TOMBOL DAFTAR
                  SizedBox(
                    width: double.infinity,
                    height: 55,
                    child: ElevatedButton(
                      onPressed: _isLoading ? null : _handleRegister,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF1976D2),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(14)),
                        elevation: 2,
                      ),
                      child: _isLoading
                          ? const CircularProgressIndicator(color: Colors.white)
                          : const Text("DAFTAR SEKARANG", style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.white)),
                    ),
                  ),
                  const SizedBox(height: 20),

                  // LINK KEMBALI KE LOGIN
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Text("Sudah punya akun? ", style: TextStyle(color: Colors.black54)),
                      TextButton(
                        onPressed: () => Navigator.pushReplacement(context, MaterialPageRoute(builder: (_) => const LoginScreen())),
                        child: const Text("Masuk", style: TextStyle(fontWeight: FontWeight.bold, color: Color(0xFF1976D2))),
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }

  InputDecoration _buildInputDecoration(String label, IconData icon) {
    return InputDecoration(
      labelText: label,
      labelStyle: const TextStyle(color: Colors.black54, fontSize: 14),
      prefixIcon: Icon(icon, color: Colors.grey.shade500, size: 22),
      filled: true,
      fillColor: Colors.grey.shade50,
      contentPadding: const EdgeInsets.symmetric(vertical: 16, horizontal: 16),
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: BorderSide(color: Colors.grey.shade300)),
      enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: BorderSide(color: Colors.grey.shade200)),
      focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: const BorderSide(color: Color(0xFF1976D2), width: 1.5)),
      errorBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(14), borderSide: const BorderSide(color: Colors.red, width: 1)),
    );
  }
}