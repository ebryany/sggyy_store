import '../core/api/api_client.dart';
import '../models/service_model.dart';

class ServiceService {
  final ApiClient _api = ApiClient();

  Future<List<ServiceModel>> getServices({
    String? search,
    String? category,
    String? sort,
    int page = 1,
    int perPage = 20,
  }) async {
    final response = await _api.get('/services', queryParameters: {
      if (search != null) 'search': search,
      if (category != null) 'category': category,
      if (sort != null) 'sort': sort,
      'page': page,
      'per_page': perPage,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ServiceModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load services');
  }

  Future<ServiceModel> getService(String slug) async {
    final response = await _api.get('/services/$slug');

    if (response.statusCode == 200) {
      return ServiceModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to load service');
  }

  Future<List<ServiceModel>> getFeaturedServices() async {
    final response = await _api.get('/featured/services');

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ServiceModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load featured services');
  }
}


import '../models/service_model.dart';

class ServiceService {
  final ApiClient _api = ApiClient();

  Future<List<ServiceModel>> getServices({
    String? search,
    String? category,
    String? sort,
    int page = 1,
    int perPage = 20,
  }) async {
    final response = await _api.get('/services', queryParameters: {
      if (search != null) 'search': search,
      if (category != null) 'category': category,
      if (sort != null) 'sort': sort,
      'page': page,
      'per_page': perPage,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ServiceModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load services');
  }

  Future<ServiceModel> getService(String slug) async {
    final response = await _api.get('/services/$slug');

    if (response.statusCode == 200) {
      return ServiceModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to load service');
  }

  Future<List<ServiceModel>> getFeaturedServices() async {
    final response = await _api.get('/featured/services');

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data
          .map((json) => ServiceModel.fromJson(json as Map<String, dynamic>))
          .toList();
    }

    throw Exception('Failed to load featured services');
  }
}

