import '../core/api/api_client.dart';
import '../models/product_model.dart';

class ProductService {
  final ApiClient _api = ApiClient();

  Future<List<ProductModel>> getProducts({
    String? search,
    String? category,
    String? sort,
    int page = 1,
    int perPage = 20,
  }) async {
    final response = await _api.get('/products', queryParameters: {
      if (search != null) 'search': search,
      if (category != null) 'category': category,
      if (sort != null) 'sort': sort,
      'page': page,
      'per_page': perPage,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ProductModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load products');
  }

  Future<ProductModel> getProduct(String slug) async {
    final response = await _api.get('/products/$slug');

    if (response.statusCode == 200) {
      return ProductModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to load product');
  }

  Future<List<ProductModel>> getFeaturedProducts() async {
    final response = await _api.get('/featured/products');

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ProductModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load featured products');
  }
}


import '../models/product_model.dart';

class ProductService {
  final ApiClient _api = ApiClient();

  Future<List<ProductModel>> getProducts({
    String? search,
    String? category,
    String? sort,
    int page = 1,
    int perPage = 20,
  }) async {
    final response = await _api.get('/products', queryParameters: {
      if (search != null) 'search': search,
      if (category != null) 'category': category,
      if (sort != null) 'sort': sort,
      'page': page,
      'per_page': perPage,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ProductModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load products');
  }

  Future<ProductModel> getProduct(String slug) async {
    final response = await _api.get('/products/$slug');

    if (response.statusCode == 200) {
      return ProductModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to load product');
  }

  Future<List<ProductModel>> getFeaturedProducts() async {
    final response = await _api.get('/featured/products');

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ProductModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load featured products');
  }
}

