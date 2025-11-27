import 'package:flutter/material.dart';

/// Color constants untuk Ebrystoree Mobile App
/// Konsisten dengan web application theme
class AppColors {
  AppColors._(); // Private constructor untuk prevent instantiation

  // Primary Colors
  static const Color primary = Color(0xFFE11D48); // Rose Red
  static const Color primaryDark = Color(0xFFBE123C);
  static const Color primaryLight = Color(0xFFF43F5E);

  // Dark Theme Colors
  static const Color dark = Color(0xFF0E0E10); // Background utama
  static const Color darkLight = Color(0xFF1A1A1C); // Card background
  static const Color darkLighter = Color(0xFF262628); // Elevated surfaces

  // Text Colors
  static const Color textPrimary = Color(0xFFFFFFFF); // White
  static const Color textSecondary = Color(0x99FFFFFF); // White 60% opacity
  static const Color textTertiary = Color(0x66FFFFFF); // White 40% opacity

  // Border Colors
  static const Color borderPrimary = Color(0x1AFFFFFF); // White 10% opacity
  static const Color borderSecondary = Color(0x4DFFFFFF); // White 30% opacity

  // Status Colors
  static const Color success = Color(0xFF10B981); // Green
  static const Color warning = Color(0xFFF59E0B); // Yellow/Orange
  static const Color error = Color(0xFFEF4444); // Red
  static const Color info = Color(0xFF3B82F6); // Blue

  // Glass Effect
  static const Color glassBackground = Color(0x0DFFFFFF); // White 5% opacity
  static const Color glassHover = Color(0x1AFFFFFF); // White 10% opacity

  // Overlay
  static const Color overlay = Color(0x80000000); // Black 50% opacity
}

