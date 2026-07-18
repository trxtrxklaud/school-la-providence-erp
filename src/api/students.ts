export const API_BASE = '/api';

export async function getStudents() {
  const token = localStorage.getItem('token');

  const res = await fetch(`${API_BASE}/students`, {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json',
    },
  });

  if (!res.ok) {
    throw new Error('حدث خطأ أثناء جلب قائمة التلاميذ');
  }

  const data = await res.json();
  return data.data || data;
}

export async function enrollStudent(formData: FormData) {
  const token = localStorage.getItem('token');

  const res = await fetch(`${API_BASE}/students/enroll`, {
    method: 'POST',
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json',
    },
    body: formData,
  });

  if (!res.ok) {
    const error = await res.json().catch(() => ({}));
    throw new Error(error.message || 'حدث خطأ أثناء التسجيل');
  }

  return await res.json();
}
