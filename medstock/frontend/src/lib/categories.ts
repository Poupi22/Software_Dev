import api from './api';

export interface Category {
  id: string;
  nom: string;
  description: string | null;
  image_url: string | null;
  couleur: string | null;
  created_at: string;
  updated_at: string;
}

export const categoriesApi = {
  getAll: () => api.get('/categories'),
  getById: (id: string) => api.get(`/categories/${id}`),
  create: (data: FormData) => api.post('/categories', data, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  update: (id: string, data: FormData) => api.put(`/categories/${id}`, data, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  delete: (id: string) => api.delete(`/categories/${id}`),
};
