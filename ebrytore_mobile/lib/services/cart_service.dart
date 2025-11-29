import '../core/api/api_client.dart';
import '../models/cart_model.dart';

class CartService {
  final ApiClient _api = ApiClient();

  Future<CartModel> getCart() async {
    final response = await _api.get('/cart');

    if (response.statusCode == 200) {
      return CartModel.fromJson(response.data['data']);
    }

    throw Exception('Failed to load cart');
  }

  Future<void> addToCart({
    required String type, // 'product' or 'service'
    required int itemId,
    int quantity = 1,
  }) async {
    final response = await _api.post('/cart/add', data: {
      'type': type,
      'product_id': type == 'product' ? itemId : null,
      'service_id': type == 'service' ? itemId : null,
      'quantity': quantity,
    });

    if (response.statusCode != 200 && response.statusCode != 201) {
      throw Exception(response.data['message'] ?? 'Failed to add to cart');
    }
  }

  Future<void> removeFromCart(String cartItemId) async {
    final response = await _api.post('/cart/remove', data: {
      'cart_item_id': cartItemId,
    });

    if (response.statusCode != 200) {
      throw Exception(response.data['message'] ?? 'Failed to remove from cart');
    }
  }

  Future<void> clearCart() async {
    final response = await _api.post('/cart/clear');

    if (response.statusCode != 200) {
      throw Exception(response.data['message'] ?? 'Failed to clear cart');
    }
  }
}

