class UserModel {
  final int id;
  final String name;
  final String email;
  final String? username;
  final String? phone;
  final String? avatar;
  final String role;
  final bool isVerifiedSeller;
  final double? walletBalance;
  final DateTime? emailVerifiedAt;
  final DateTime createdAt;
  final DateTime updatedAt;

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    this.username,
    this.phone,
    this.avatar,
    required this.role,
    this.isVerifiedSeller = false,
    this.walletBalance,
    this.emailVerifiedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      username: json['username'] as String?,
      phone: json['phone'] as String?,
      avatar: json['avatar'] as String?,
      role: json['role'] as String? ?? 'user',
      isVerifiedSeller: json['is_verified_seller'] as bool? ?? false,
      walletBalance: json['wallet_balance'] != null
          ? (json['wallet_balance'] as num).toDouble()
          : null,
      emailVerifiedAt: json['email_verified_at'] != null
          ? DateTime.parse(json['email_verified_at'] as String)
          : null,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
    );
  }

  bool get isAdmin => role == 'admin';
  bool get isSeller => role == 'seller';
  bool get isUser => role == 'user';
}

