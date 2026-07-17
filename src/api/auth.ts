import { User, Role } from './users';

const API_BASE = '/api';

export interface AuthResponse {
  access_token: string;
  token_type: string;
  user: User & { role?: Role & { permissions?: any[] } };
}

export async function login(credentials: any): Promise<AuthResponse> {
  // MOCK FOR PREVIEW ENVIRONMENT (Since Laravel PHP is not running here)
  if (credentials.email === 'admin@erp.com' && credentials.password === '123456') {
    return {
      access_token: 'mock-token',
      token_type: 'Bearer',
      user: {
        id: 1,
        first_name: 'مدير',
        last_name: 'النظام',
        username: 'admin',
        email: 'admin@erp.com',
        phone: '12345678',
        role_id: 1,
        role: { id: 1, name: 'admin', display_name: 'مدير النظام' }
      }
    };
  }

  const res = await fetch(`${API_BASE}/login`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(credentials),
  });
  if (!res.ok) {
    const errorData = await res.json().catch(() => ({}));
    throw new Error(errorData.message || 'فشل تسجيل الدخول');
  }
  return res.json();
}

export async function logout(): Promise<void> {
  const token = localStorage.getItem('token');
  if (!token) return;
  await fetch(`${API_BASE}/logout`, {
    method: 'POST',
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json',
    },
  });
}

export async function fetchCurrentUser(): Promise<User> {
  const token = localStorage.getItem('token');
  
  // MOCK FOR PREVIEW ENVIRONMENT
  if (token === 'mock-token') {
    return {
      id: 1,
      first_name: 'مدير',
      last_name: 'النظام',
      username: 'admin',
      email: 'admin@erp.com',
      phone: '12345678',
      role_id: 1,
      role: { id: 1, name: 'admin', display_name: 'مدير النظام' }
    };
  }

  const res = await fetch(`${API_BASE}/user`, {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json',
    },
  });
  if (!res.ok) throw new Error('فشل جلب بيانات المستخدم');
  return res.json();
}
