class CartItemModel {
  final String id;
  final String type; // 'product' or 'service'
  final int itemId; // product_id or service_id
  final String title;
  final double price;
  final String? image;
  final int quantity;
  final DateTime createdAt;
  final DateTime updatedAt;

  CartItemModel({
    required this.id,
    required this.type,
    required this.itemId,
    required this.title,
    required this.price,
    this.image,
    this.quantity = 1,
    required this.createdAt,
    required this.updatedAt,
  });

  factory CartItemModel.fromJson(Map<String, dynamic> json) {
    return CartItemModel(
      id: json['id'].toString(),
      type: json['type'] as String,
      itemId: json['item_id'] as int,
      title: json['title'] as String,
      price: (json['price'] as num).toDouble(),
      image: json['image'] as String?,
      quantity: json['quantity'] as int? ?? 1,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
    );
  }

  double get subtotal => price * quantity;
  bool get isProduct => type == 'product';
  bool get isService => type == 'service';
}

class CartModel {
  final List<CartItemModel> items;
  final double total;

  CartModel({
    required this.items,
    required this.total,
  });

  factory CartModel.fromJson(Map<String, dynamic> json) {
    final itemsJson = json['items'] as List<dynamic>? ?? [];
    return CartModel(
      items: itemsJson
          .map((item) => CartItemModel.fromJson(item as Map<String, dynamic>))
          .toList(),
      total: (json['total'] as num?)?.toDouble() ?? 0.0,
    );
  }

  int get itemCount => items.length;
  int get totalQuantity => items.fold(0, (sum, item) => sum + item.quantity);
}


  final String id;
  final String type; // 'product' or 'service'
  final int itemId; // product_id or service_id
  final String title;
  final double price;
  final String? image;
  final int quantity;
  final DateTime createdAt;
  final DateTime updatedAt;

  CartItemModel({
    required this.id,
    required this.type,
    required this.itemId,
    required this.title,
    required this.price,
    this.image,
    this.quantity = 1,
    required this.createdAt,
    required this.updatedAt,
  });

  factory CartItemModel.fromJson(Map<String, dynamic> json) {
    return CartItemModel(
      id: json['id'].toString(),
      type: json['type'] as String,
      itemId: json['item_id'] as int,
      title: json['title'] as String,
      price: (json['price'] as num).toDouble(),
      image: json['image'] as String?,
      quantity: json['quantity'] as int? ?? 1,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
    );
  }

  double get subtotal => price * quantity;
  bool get isProduct => type == 'product';
  bool get isService => type == 'service';
}

class CartModel {
  final List<CartItemModel> items;
  final double total;

  CartModel({
    required this.items,
    required this.total,
  });

  factory CartModel.fromJson(Map<String, dynamic> json) {
    final itemsJson = json['items'] as List<dynamic>? ?? [];
    return CartModel(
      items: itemsJson
          .map((item) => CartItemModel.fromJson(item as Map<String, dynamic>))
          .toList(),
      total: (json['total'] as num?)?.toDouble() ?? 0.0,
    );
  }

  int get itemCount => items.length;
  int get totalQuantity => items.fold(0, (sum, item) => sum + item.quantity);
}

