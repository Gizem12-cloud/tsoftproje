import api from './axios';
import type { Order } from '../types/order';
import type { PaginatedResponse } from '../types/pagination';

export async function createOrder(): Promise<Order> {
  const response = await api.post<{ data: Order }>('/orders');
  return response.data.data;
}

export async function getOrders(page: number = 1): Promise<PaginatedResponse<Order>> {
  const response = await api.get<PaginatedResponse<Order>>('/orders', {
    params: { page },
  });
  return response.data;
}