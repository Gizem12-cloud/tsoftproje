import api from './axios';
import type { AuthResponse } from '../types/auth';

export async function login(email: string, password: string): Promise<AuthResponse> {
  const response = await api.post<AuthResponse>('/login', { email, password });
  return response.data;
}
export async function register(
    name: string,
    email: string,
    password: string,
    passwordConfirmation: string
  ): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>('/register', {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
    });
    return response.data;
  }