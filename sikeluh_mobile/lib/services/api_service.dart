import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  // IP lokal laptop/Laragon kamu
  static const String baseUrl = 'http://192.168.18.94:8000/api';

  // FUNGSI LOGIN
  static Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse('$baseUrl/login'),
        headers: {
          'Content-Type': 'application/json', 
          'Accept': 'application/json'
        },
        body: jsonEncode({'email': email, 'password': password}),
      );

      final data = jsonDecode(response.body);

      // Sinkronisasi status code 200 dan key success dari Laravel AuthController
      if (response.statusCode == 200 && (data['success'] == true || data['status'] == 'success')) {
        
        // Simpan token dan data user ke penyimpanan lokal HP Samsung-mu
        final prefs = await SharedPreferences.getInstance();
        if (data['token'] != null) {
          await prefs.setString('token', data['token']);
        }
        
        final user = data['user'];
        return {
          'success': true,
          'role': user != null ? user['role'] : 'user',
          'message': data['message'] ?? 'Login berhasil'
        };
      } else {
        return {
          'success': false, 
          'message': data['message'] ?? 'Email atau Password salah'
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Koneksi error: $e'};
    }
  }

  // FUNGSI AMBIL DATA ADUAN
  static Future<List<dynamic>> fetchAduan() async {
    try {
      final response = await http.get(
        Uri.parse('$baseUrl/aduan'),
        headers: {'Accept': 'application/json'},
      );
      
      if (response.statusCode == 200) {
        return jsonDecode(response.body);
      } else {
        throw Exception('Gagal mengambil data dari database SQLite');
      }
    } catch (e) {
      throw Exception('Gagal ambil data: $e');
    }
  }
}