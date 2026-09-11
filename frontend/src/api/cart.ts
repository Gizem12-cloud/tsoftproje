import api from './axios';
import type { Cart } from '../types/cart';

export async function getCart(): Promise<Cart> {
  const response = await api.get<{ data: Cart }>('/cart');
  return response.data.data;
}

export async function addToCart(productId: number, quantity: number = 1): Promise<Cart> {
  const response = await api.post<{ data: Cart }>('/cart', { product_id: productId, quantity });
  return response.data.data;
}

export async function decreaseFromCart(productId: number): Promise<Cart> {
  const response = await api.patch<{ data: Cart }>(`/cart/${productId}/decrease`);
  return response.data.data;
}

export async function removeFromCart(productId: number): Promise<Cart> {
  const response = await api.delete<{ data: Cart }>(`/cart/${productId}`);
  return response.data.data;
}