import api from './axios';
import type { Product } from '../types/product';
import type { PaginatedResponse } from '../types/pagination';

export async function getProducts(page: number = 1, categoryId: number | null = null): Promise<PaginatedResponse<Product>> {
  const params: Record<string, number> = { page };
  if (categoryId !== null) {
    params.category = categoryId;
  }

  const response = await api.get<PaginatedResponse<Product>>('/products', {
    params,
  });
  return response.data;
}

export async function getProduct(id: string): Promise<Product> {
  const response = await api.get<{ data: Product }>(`/products/${id}`);
  return response.data.data;
}