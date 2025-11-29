import '../core/api/api_client.dart';

class WalletService {
  final ApiClient _api = ApiClient();

  Future<Map<String, dynamic>> getWallet() async {
    final response = await _api.get('/wallet');

    if (response.statusCode == 200) {
      return response.data['data'] as Map<String, dynamic>;
    }

    throw Exception('Failed to load wallet');
  }

  Future<Map<String, dynamic>> topUp({
    required double amount,
    required String paymentMethod,
  }) async {
    final response = await _api.post('/wallet/top-up', data: {
      'amount': amount,
      'payment_method': paymentMethod,
    });

    if (response.statusCode == 200 || response.statusCode == 201) {
      return response.data['data'] as Map<String, dynamic>;
    }

    throw Exception(response.data['message'] ?? 'Top-up failed');
  }

  Future<List<Map<String, dynamic>>> getTransactions({
    int page = 1,
    int perPage = 20,
  }) async {
    final response = await _api.get('/wallet/transactions', queryParameters: {
      'page': page,
      'per_page': perPage,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'] as List<dynamic>;
      return data.cast<Map<String, dynamic>>();
    }

    throw Exception('Failed to load transactions');
  }
}

