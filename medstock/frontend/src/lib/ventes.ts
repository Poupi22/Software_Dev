import api from './api';

export interface Vente {
  id: string;
  numero: string;
  utilisateur_id: string;
  vendeur_nom?: string;
  client_nom: string | null;
  client_telephone: string | null;
  client_whatsapp: string | null;
  sous_total: number;
  remise: number;
  tva: number;
  total: number;
  mode_paiement: string;
  statut: string;
  created_at: string;
}

export interface VenteLigne {
  id: string;
  vente_id: string;
  medicament_id: string;
  nom_snapshot: string;
  prix_unitaire: number;
  quantite: number;
  total_ligne: number;
}

export const ventesApi = {
  getAll: () => api.get('/ventes'),
  getById: (id: string) => api.get(`/ventes/${id}`),
  getStats: () => api.get('/ventes/stats'),
  create: (data: {
    client_nom?: string;
    client_telephone?: string;
    client_whatsapp?: string;
    items: Array<{ medicament_id: string; quantite: number; prix_unitaire?: number }>;
    remise?: number;
    tva?: number;
    mode_paiement?: string;
  }) => api.post('/ventes', data),
};
