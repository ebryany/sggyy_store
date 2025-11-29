import '../core/api/api_client.dart';
import '../models/order_model.dart';

class OrderService {
  final ApiClient _api = ApiClient();

  Future<List<OrderModel>> getOrders({
    String? status,
    String? search,
    int page = 1,
    int perPage = 20,
  }) async {
    final response = await _api.get('/orders', queryParameters: {
      if (status != null) 'status': status,
      if (search != null) 'search': search,
      'page': page,
      'per_page': perPage,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => OrderModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load orders');
  }

  Future<OrderModel> getOrder(String orderNumber) async {
    final response = await _api.get('/orders/$orderNumber');

    if (response.statusCode == 200) {
      return OrderModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to load order');
  }

  Future<OrderModel> checkout({
    required String type, // 'product' or 'service'
    int? productId,
    int? serviceId,
    required String paymentMethod,
    String? taskFile, // For services
  }) async {
    final response = await _api.post('/checkout', data: {
      'type': type,
      'product_id': productId,
      'service_id': serviceId,
      'payment_method': paymentMethod,
      if (taskFile != null) 'task_file': taskFile,
    });

    if (response.statusCode == 200 || response.statusCode == 201) {
      return OrderModel.fromJson(response.data['data']);
    }

    throw Exception(response.data['message'] ?? 'Checkout failed');
  }
}


import '../models/order_model.dart';

class OrderService {
  final ApiClient _api = ApiClient();

  Future<List<OrderModel>> getOrders({
    String? status,
    String? search,
    int page = 1,
    int perPage = 20,
  }) async {
    final response = await _api.get('/orders', queryParameters: {
      if (status != null) 'status': status,
      if (search != null) 'search': search,
      'page': page,
      'per_page': perPage,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => OrderModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load orders');
  }

  Future<OrderModel> getOrder(String orderNumber) async {
    final response = await _api.get('/orders/$orderNumber');

    if (response.statusCode == 200) {
      return OrderModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to load order');
  }

  Future<OrderModel> checkout({
    required String type, // 'product' or 'service'
    int? productId,
    int? serviceId,
    required String paymentMethod,
    String? taskFile, // For services
  }) async {
    final response = await _api.post('/checkout', data: {
      'type': type,
      'product_id': productId,
      'service_id': serviceId,
      'payment_method': paymentMethod,
      if (taskFile != null) 'task_file': taskFile,
    });

    if (response.statusCode == 200 || response.statusCode == 201) {
      return OrderModel.fromJson(response.data['data']);
    }

    throw Exception(response.data['message'] ?? 'Checkout failed');
  }
}

