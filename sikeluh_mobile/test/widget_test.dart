import 'package:flutter_test/flutter_test.dart';
import 'package:sikeluh_mobile/main.dart';
import 'package:sikeluh_mobile/screens/login_screen.dart';

void main() {
  testWidgets('Sikeluh App Smoke Test', (WidgetTester tester) async {
    // 1. Build aplikasi Sikeluh dengan mengarahkan langsung ke LoginScreen sebagai fallback
    await tester.pumpWidget(const MyApp(
      initialScreen: LoginScreen(),
    ));

    // 2. Memastikan aplikasi berhasil terbuka dan menampilkan teks "MASUK"
    expect(find.text('MASUK'), findsOneWidget);
    
    // 3. Memastikan teks counter bawaan (0) yang bikin eror tadi sudah tidak dicari lagi
    expect(find.text('0'), findsNothing);
  });
}