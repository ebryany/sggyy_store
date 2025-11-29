import 'package:flutter/material.dart';
import 'config/theme.dart';
import 'app.dart';

void main() {
  runApp(const EbrystoreeApp());
}

class EbrystoreeApp extends StatelessWidget {
  const EbrystoreeApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Ebrystoree',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.darkTheme,
      home: const App(),
    );
  }
}

