import 'package:flutter/material.dart';
import 'config/theme.dart';
import 'screens/home_screen.dart';

class EbrystoreeApp extends StatelessWidget {
  const EbrystoreeApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Ebrystoree',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.darkTheme,
      home: const HomeScreen(),
    );
  }
}

