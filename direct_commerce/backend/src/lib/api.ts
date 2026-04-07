const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

interface LoginResponse {
  id: string;
  username: string;
  email: string;
  role_id: string;
  token: string;
}

export interface Product {
  id: string;
  name: string;
  description_title: string;
  description: string;
  price: number;
  sold_price: number | null;
  tag: string | null;
  category_id: string;
  category_name: string;
  main_image: string;
  sub_images: string[];
  created_at: string;
}

export interface Category {
  id: string;
  name: string;
  description: string;
  quantity: number;
  image: string;
  created_at: string;
}

export interface WhatsAppInquiry {
  id: string;
  name: string;
  surname: string;
  email: string;
  phone_number: string;
  country_code: string;
  country: string;
  town: string;
  address: string;
  product_id: string;
  created_at: string;
}

export interface ContactMessage {
  id: string;
  name: string;
  surname: string;
  email: string;
  phone_number: string;
  message: string;
  created_at: string;
}

export interface NewsletterSubscriber {
  id: string;
  email: string;
  is_active: boolean;
  created_at: string;
}

export const api = {
  async login(email: string, password: string): Promise<LoginResponse> {
    const response = await fetch(`${API_URL}/auth/login`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email, password }),
    });

    if (!response.ok) {
      const error = await response.json();
      throw new Error(error.message || 'Erreur de connexion');
    }

    const data = await response.json();
    localStorage.setItem('dr_token', data.token);
    localStorage.setItem('dr_user', JSON.stringify(data));
    localStorage.setItem('dr_role', 'admin');
    
    return data;
  },

  async logout(): Promise<void> {
    const token = this.getToken();
    if (token) {
      await fetch(`${API_URL}/auth/logout`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });
    }
    localStorage.removeItem('dr_token');
    localStorage.removeItem('dr_user');
    localStorage.removeItem('dr_role');
  },

  async getProfile() {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/auth/profile`, {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Non autorisé');
    return response.json();
  },

  getToken(): string | null {
    return localStorage.getItem('dr_token');
  },

  getUser() {
    const user = localStorage.getItem('dr_user');
    return user ? JSON.parse(user) : null;
  },

  isAuthenticated(): boolean {
    return !!this.getToken();
  },

  async get(endpoint: string) {
    const token = this.getToken();
    const response = await fetch(`${API_URL}${endpoint}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
    });
    if (!response.ok) throw new Error('Erreur API');
    return response.json();
  },

  async post(endpoint: string, data: any) {
    const token = this.getToken();
    const response = await fetch(`${API_URL}${endpoint}`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });
    if (!response.ok) throw new Error('Erreur API');
    return response.json();
  },

  async delete(endpoint: string) {
    const token = this.getToken();
    const response = await fetch(`${API_URL}${endpoint}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Erreur API');
    return response.json();
  },

  async getProducts(): Promise<Product[]> {
    const response = await fetch(`${API_URL}/products`);
    if (!response.ok) throw new Error('Erreur lors du chargement des produits');
    return response.json();
  },

  async getProduct(id: string): Promise<Product> {
    const response = await fetch(`${API_URL}/products/${id}`);
    if (!response.ok) throw new Error('Produit non trouvé');
    return response.json();
  },

  async createProduct(formData: FormData): Promise<Product> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/products`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData,
    });
    if (!response.ok) throw new Error('Erreur lors de la création du produit');
    return response.json();
  },

  async updateProduct(id: string, formData: FormData): Promise<Product> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/products/${id}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData,
    });
    if (!response.ok) throw new Error('Erreur lors de la mise à jour du produit');
    return response.json();
  },

  async deleteProduct(id: string): Promise<void> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/products/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Erreur lors de la suppression du produit');
  },

  async getCategories(): Promise<Category[]> {
    const response = await fetch(`${API_URL}/categories`);
    if (!response.ok) throw new Error('Erreur lors du chargement des catégories');
    return response.json();
  },

  async getCategory(id: string): Promise<Category> {
    const response = await fetch(`${API_URL}/categories/${id}`);
    if (!response.ok) throw new Error('Catégorie non trouvée');
    return response.json();
  },

  async createCategory(formData: FormData): Promise<Category> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/categories`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData,
    });
    if (!response.ok) throw new Error('Erreur lors de la création de la catégorie');
    return response.json();
  },

  async updateCategory(id: string, formData: FormData): Promise<Category> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/categories/${id}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData,
    });
    if (!response.ok) throw new Error('Erreur lors de la mise à jour de la catégorie');
    return response.json();
  },

  async deleteCategory(id: string): Promise<void> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/categories/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Erreur lors de la suppression de la catégorie');
  },

  async createWhatsAppInquiry(data: {
    name: string;
    surname: string;
    email: string;
    phone_number: string;
    country_code: string;
    country: string;
    town: string;
    address: string;
    product_id: string;
  }): Promise<{ inquiry: WhatsAppInquiry; whatsapp_url: string }> {
    const response = await fetch(`${API_URL}/whatsapp`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!response.ok) throw new Error('Erreur lors de la création de la demande');
    return response.json();
  },

  async getWhatsAppInquiries(): Promise<WhatsAppInquiry[]> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/whatsapp`, {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Erreur lors du chargement des demandes');
    return response.json();
  },

  async getWhatsAppInquiry(id: string): Promise<WhatsAppInquiry> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/whatsapp/${id}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Demande non trouvée');
    return response.json();
  },

  async deleteWhatsAppInquiry(id: string): Promise<void> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/whatsapp/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Erreur lors de la suppression de la demande');
  },

  async createContactMessage(data: {
    name: string;
    surname: string;
    email: string;
    phone_number: string;
    message: string;
  }): Promise<ContactMessage> {
    const response = await fetch(`${API_URL}/contact`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data)
    });
    if (!response.ok) throw new Error('Erreur lors de l\'envoi du message');
    return response.json();
  },

  async getContactMessages(): Promise<ContactMessage[]> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/contact`, {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Erreur lors du chargement des messages');
    return response.json();
  },

  async getContactMessage(id: string): Promise<ContactMessage> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/contact/${id}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Message non trouvé');
    return response.json();
  },

  async deleteContactMessage(id: string): Promise<void> {
    const token = this.getToken();
    const response = await fetch(`${API_URL}/contact/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
    });
    if (!response.ok) throw new Error('Erreur lors de la suppression du message');
  },

  // Newsletter
  async subscribeNewsletter(email: string): Promise<{ message: string; subscriber: NewsletterSubscriber }> {
    const response = await fetch(`${API_URL}/newsletter/subscribe`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });
    if (!response.ok) throw new Error('Erreur lors de l\'inscription');
    return response.json();
  },

  async getNewsletterSubscribers(): Promise<NewsletterSubscriber[]> {
    return this.get('/newsletter/subscribers');
  },

  async deleteNewsletterSubscriber(email: string): Promise<void> {
    await this.delete(`/newsletter/subscribers/${encodeURIComponent(email)}`);
  },

  async sendNewsletter(data: { subject: string; content: string }): Promise<{ message: string }> {
    return this.post('/newsletter/send', data);
  }
};