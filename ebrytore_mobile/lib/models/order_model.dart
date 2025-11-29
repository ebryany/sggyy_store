class OrderModel {
  final String id;
  final String orderNumber;
  final String type; // 'product' or 'service'
  final int userId;
  final int? productId;
  final int? serviceId;
  final double total;
  final String status;
  final String? paymentMethod;
  final DateTime? paymentExpiresAt;
  final DateTime? downloadExpiresAt;
  final DateTime? deadline;
  final DateTime createdAt;
  final DateTime updatedAt;
  
  // Relations
  final ProductModel? product;
  final ServiceModel? service;
  final PaymentModel? payment;

  OrderModel({
    required this.id,
    required this.orderNumber,
    required this.type,
    required this.userId,
    this.productId,
    this.serviceId,
    required this.total,
    required this.status,
    this.paymentMethod,
    this.paymentExpiresAt,
    this.downloadExpiresAt,
    this.deadline,
    required this.createdAt,
    required this.updatedAt,
    this.product,
    this.service,
    this.payment,
  });

  factory OrderModel.fromJson(Map<String, dynamic> json) {
    return OrderModel(
      id: json['id'].toString(),
      orderNumber: json['order_number'] as String,
      type: json['type'] as String,
      userId: json['user_id'] as int,
      productId: json['product_id'] as int?,
      serviceId: json['service_id'] as int?,
      total: (json['total'] as num).toDouble(),
      status: json['status'] as String,
      paymentMethod: json['payment_method'] as String?,
      paymentExpiresAt: json['payment_expires_at'] != null
          ? DateTime.parse(json['payment_expires_at'] as String)
          : null,
      downloadExpiresAt: json['download_expires_at'] != null
          ? DateTime.parse(json['download_expires_at'] as String)
          : null,
      deadline: json['deadline'] != null
          ? DateTime.parse(json['deadline'] as String)
          : null,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
      product: json['product'] != null
          ? ProductModel.fromJson(json['product'] as Map<String, dynamic>)
          : null,
      service: json['service'] != null
          ? ServiceModel.fromJson(json['service'] as Map<String, dynamic>)
          : null,
      payment: json['payment'] != null
          ? PaymentModel.fromJson(json['payment'] as Map<String, dynamic>)
          : null,
    );
  }

  bool get isProduct => type == 'product';
  bool get isService => type == 'service';
  bool get canDownload => isProduct && 
                         downloadExpiresAt != null && 
                         downloadExpiresAt!.isAfter(DateTime.now());
}

class PaymentModel {
  final int id;
  final String orderId;
  final String method;
  final String status;
  final double amount;
  final String? proofPath;
  final DateTime? verifiedAt;
  final DateTime createdAt;
  final DateTime updatedAt;

  PaymentModel({
    required this.id,
    required this.orderId,
    required this.method,
    required this.status,
    required this.amount,
    this.proofPath,
    this.verifiedAt,
    required this.createdAt,
    required this.updatedAt,
  });

  factory PaymentModel.fromJson(Map<String, dynamic> json) {
    return PaymentModel(
      id: json['id'] as int,
      orderId: json['order_id'].toString(),
      method: json['method'] as String,
      status: json['status'] as String,
      amount: (json['amount'] as num).toDouble(),
      proofPath: json['proof_path'] as String?,
      verifiedAt: json['verified_at'] != null
          ? DateTime.parse(json['verified_at'] as String)
          : null,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
    );
  }

  bool get isVerified => status == 'verified';
  bool get isPending => status == 'pending';
  bool get isFailed => status == 'failed';
}

