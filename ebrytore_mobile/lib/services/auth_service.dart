import '../core/api/api_client.dart';
import '../models/user_model.dart';

class AuthService {
  final ApiClient _api = ApiClient();

  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await _api.post('/auth/login', data: {
      'email': email,
      'password': password,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'];
      final token = data['token'];
      final user = UserModel.fromJson(data['user']);

      // Save token
      await _api.saveToken(token);

      return {
        'success': true,
        'user': user,
        'token': token,
      };
    }

    throw Exception(response.data['message'] ?? 'Login failed');
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? username,
    String? phone,
  }) async {
    final response = await _api.post('/auth/register', data: {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'username': username,
      'phone': phone,
    });

    if (response.statusCode == 200 || response.statusCode == 201) {
      final data = response.data['data'];
      final token = data['token'];
      final user = UserModel.fromJson(data['user']);

      // Save token
      await _api.saveToken(token);

      return {
        'success': true,
        'user': user,
        'token': token,
      };
    }

    throw Exception(response.data['message'] ?? 'Registration failed');
  }

  Future<UserModel> getCurrentUser() async {
    final response = await _api.get('/auth/me');

    if (response.statusCode == 200) {
      return UserModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to get user');
  }

  Future<void> logout() async {
    try {
      await _api.post('/auth/logout');
    } catch (e) {
      // Ignore errors on logout
    } finally {
      await _api.clearToken();
    }
  }
}


import '../models/user_model.dart';

class AuthService {
  final ApiClient _api = ApiClient();

  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await _api.post('/auth/login', data: {
      'email': email,
      'password': password,
    });

    if (response.statusCode == 200) {
      final data = response.data['data'];
      final token = data['token'];
      final user = UserModel.fromJson(data['user']);

      // Save token
      await _api.saveToken(token);

      return {
        'success': true,
        'user': user,
        'token': token,
      };
    }

    throw Exception(response.data['message'] ?? 'Login failed');
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
    String? username,
    String? phone,
  }) async {
    final response = await _api.post('/auth/register', data: {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
      'username': username,
      'phone': phone,
    });

    if (response.statusCode == 200 || response.statusCode == 201) {
      final data = response.data['data'];
      final token = data['token'];
      final user = UserModel.fromJson(data['user']);

      // Save token
      await _api.saveToken(token);

      return {
        'success': true,
        'user': user,
        'token': token,
      };
    }

    throw Exception(response.data['message'] ?? 'Registration failed');
  }

  Future<UserModel> getCurrentUser() async {
    final response = await _api.get('/auth/me');

    if (response.statusCode == 200) {
      return UserModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to get user');
  }

  Future<void> logout() async {
    try {
      await _api.post('/auth/logout');
    } catch (e) {
      // Ignore errors on logout
    } finally {
      await _api.clearToken();
    }
  }
}

