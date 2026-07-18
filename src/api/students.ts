export const API_BASE = '/api';

function authHeaders() {
  const token = localStorage.getItem('token');
  return {
    'Authorization': `Bearer ${token}`,
    'Accept': 'application/json',
  };
}

export async function getStudents(params?: { search?: string; level_id?: number }) {
  const url = new URL(`${API_BASE}/students`, window.location.origin);
  if (params?.search)   url.searchParams.set('search',   params.search);
  if (params?.level_id) url.searchParams.set('level_id', String(params.level_id));

  const res = await fetch(url.toString(), { headers: authHeaders() });

  if (!res.ok) throw new Error('حدث خطأ أثناء جلب قائمة التلاميذ');

  const data = await res.json();
  return data.data || data;
}

export async function enrollStudent(formData: FormData) {
  const token = localStorage.getItem('token');

  const res = await fetch(`${API_BASE}/students/enroll`, {
    method: 'POST',
    headers: {
      // لا تضيف Content-Type — المتصفح يضيفه تلقائياً مع boundary لـ FormData
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json',
    },
    body: formData,
  });

  if (!res.ok) {
    const error = await res.json().catch(() => ({}));
    throw new Error(error.message || 'حدث خطأ أثناء تسجيل التلميذ');
  }

  return res.json();
}
