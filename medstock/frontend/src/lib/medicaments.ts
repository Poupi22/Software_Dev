import api from './api';

export interface Medicament {
  id: string;
  nom: string;
  description: string;
  code_barre: string;
  image_url: string;
  categorie_id: string;
  categorie_nom?: string;
  prix_achat: number;
  prix_vente: number;
  quantite: number;
  seuil_alerte: number;
  date_expiration: string;
  ordonnance: boolean;
  actif: boolean;
}

export const medicamentsApi = {
  getAll: () => api.get('/medicaments'),
  getById: (id: string) => api.get(`/medicaments/${id}`),
  search: (q: string) => api.get(`/medicaments/search?q=${q}`),
  create: (data: FormData) => api.post('/medicaments', data, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  update: (id: string, data: FormData) => api.put(`/medicaments/${id}`, data, {
    headers: { 'Content-Type': 'multipart/form-data' }
  }),
  delete: (id: string) => api.delete(`/medicaments/${id}`),
};
