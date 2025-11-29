class ServiceModel {
  final int id;
  final String title;
  final String slug;
  final String description;
  final double price;
  final int durationDays;
  final String? image;
  final String category;
  final List<String> tags;
  final int userId;
  final String? userName;
  final double? rating;
  final int? reviewCount;
  final bool isActive;
  final bool isFeatured;
  final DateTime createdAt;
  final DateTime updatedAt;

  ServiceModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.description,
    required this.price,
    required this.durationDays,
    this.image,
    required this.category,
    this.tags = const [],
    required this.userId,
    this.userName,
    this.rating,
    this.reviewCount,
    this.isActive = true,
    this.isFeatured = false,
    required this.createdAt,
    required this.updatedAt,
  });

  factory ServiceModel.fromJson(Map<String, dynamic> json) {
    return ServiceModel(
      id: json['id'] as int,
      title: json['title'] as String,
      slug: json['slug'] as String,
      description: json['description'] as String? ?? '',
      price: (json['price'] as num).toDouble(),
      durationDays: json['duration_days'] as int? ?? 7,
      image: json['image'] as String?,
      category: json['category'] as String? ?? 'other',
      tags: json['tags'] != null
          ? List<String>.from(json['tags'] as List)
          : [],
      userId: json['user_id'] as int,
      userName: json['user']?['name'] as String?,
      rating: json['rating'] != null
          ? (json['rating'] as num).toDouble()
          : null,
      reviewCount: json['review_count'] as int? ?? 0,
      isActive: json['is_active'] as bool? ?? true,
      isFeatured: json['is_featured'] as bool? ?? false,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
    );
  }
}

