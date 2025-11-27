# Ebrystoree Mobile App

Aplikasi mobile untuk Ebrystoree marketplace, dibangun dengan Flutter.

## 📱 Tech Stack

- **Framework**: Flutter
- **Language**: Dart
- **State Management**: Provider
- **API Client**: Dio
- **Local Storage**: Shared Preferences
- **Icons**: Material Icons / Flutter SVG

## 🎨 Theme

Aplikasi menggunakan dark theme yang konsisten dengan web application:

### Color Palette
- **Primary**: `#E11D48` (Rose Red)
- **Primary Dark**: `#BE123C`
- **Primary Light**: `#F43F5E`
- **Dark**: `#0E0E10` (Background utama)
- **Dark Light**: `#1A1A1C` (Card background)
- **Dark Lighter**: `#262628` (Elevated surfaces)
- **Text**: `#FFFFFF` (White)
- **Text Secondary**: `rgba(255, 255, 255, 0.6)`

### Typography
- **Font Family**: Poppins (fallback to system fonts)
- **Font Sizes**: 
  - Display: 32px
  - Heading: 24px
  - Title: 20px
  - Body: 16px
  - Caption: 14px
  - Small: 12px

### Design System
- **Glass Effect**: Backdrop blur dengan opacity 0.05
- **Border Radius**: 8px (small), 12px (medium), 16px (large)
- **Spacing**: 4px base unit (4, 8, 12, 16, 24, 32)
- **Touch Target**: Minimum 44x44px

## 📁 Project Structure

```
lib/
├── main.dart                 # Entry point
├── app.dart                  # Root app widget
├── config/
│   ├── theme.dart           # Theme configuration
│   └── colors.dart          # Color constants
├── core/
│   ├── api/                 # API client & endpoints
│   ├── storage/             # Local storage
│   └── utils/                # Utilities
├── features/
│   ├── auth/                # Authentication
│   ├── home/                # Home screen
│   ├── products/            # Products listing & detail
│   ├── services/            # Services listing & detail
│   ├── orders/              # Orders management
│   ├── chat/                # Chat feature
│   ├── profile/            # User profile
│   └── seller/              # Seller dashboard
├── shared/
│   ├── widgets/             # Reusable widgets
│   ├── components/          # UI components
│   └── models/              # Shared models
└── screens/
    └── home_screen.dart     # Home screen (temporary)
```

## 🚀 Getting Started

### Prerequisites
- Flutter SDK (latest stable)
- Dart SDK
- Android Studio / VS Code
- Android SDK / Xcode (for iOS)

### Installation

1. **Install dependencies**
   ```bash
   flutter pub get
   ```

2. **Run app**
   ```bash
   flutter run
   ```

## 📋 Features

### Planned Features
- [ ] Authentication (Login, Register)
- [ ] Home screen dengan products & services
- [ ] Product detail & purchase
- [ ] Service detail & order
- [ ] Order management (buyer & seller)
- [ ] Chat system
- [ ] Profile & settings
- [ ] Seller dashboard
- [ ] Payment integration
- [ ] Push notifications

## 🔗 API Integration

Aplikasi akan terintegrasi dengan API backend di:
- **Base URL**: `http://127.0.0.1:8000/api/v1` (development)
- **Production**: TBD

## 📝 Development Notes

- Mengikuti design system dari web application
- Dark theme only (no light mode)
- Responsive design untuk berbagai ukuran layar
- Optimized untuk performance

