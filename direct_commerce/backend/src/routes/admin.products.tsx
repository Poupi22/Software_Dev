import { createFileRoute } from "@tanstack/react-router";
import { motion } from "framer-motion";
import { Plus, Edit, Trash2, Search } from "lucide-react";
import { useState, useEffect } from "react";
import AdminModal from "@/components/admin/AdminModal";
import ProductForm from "@/components/admin/ProductForm";
import { api, type Product } from "@/lib/api";

// ✅ CHANGEMENT 1 : Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

export const Route = createFileRoute("/admin/products")({
  component: AdminProducts,
});

// ✅ CHANGEMENT 2 : Fonction utilitaire pour obtenir l'URL complète des images
const getImageUrl = (imagePath: string | null): string => {
  if (!imagePath) return 'https://via.placeholder.com/40x40?text=No+Image';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

function AdminProducts() {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [search, setSearch] = useState("");
  const [modalOpen, setModalOpen] = useState(false);
  const [editing, setEditing] = useState<Product | null>(null);

  useEffect(() => {
    loadProducts();
  }, []);

  const loadProducts = async () => {
    try {
      setLoading(true);
      const data = await api.getProducts();
      setProducts(data);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const filtered = products.filter((p) => 
    p.name.toLowerCase().includes(search.toLowerCase())
  );

  const handleAdd = () => { 
    setEditing(null); 
    setModalOpen(true); 
  };

  const handleEdit = (p: Product) => { 
    setEditing(p); 
    setModalOpen(true); 
  };

  const handleDelete = async (id: string) => {
    if (!confirm("Supprimer ce produit ?")) return;
    
    try {
      await api.deleteProduct(id);
      setProducts(products.filter((p) => p.id !== id));
    } catch (err: any) {
      alert(err.message);
    }
  };

  const handleSubmit = async (formData: FormData) => {
    try {
      if (editing) {
        const updated = await api.updateProduct(editing.id, formData);
        setProducts(products.map((p) => p.id === editing.id ? updated : p));
      } else {
        const created = await api.createProduct(formData);
        setProducts([...products, created]);
      }
      setModalOpen(false);
    } catch (err: any) {
      alert(err.message);
    }
  };

  const formatFCFA = (price: number) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'XAF',
      maximumFractionDigits: 0
    }).format(price);
  };

  if (loading) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement des produits...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center text-destructive">
          <p>Erreur lors du chargement</p>
          <p className="text-sm text-muted-foreground">{error}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-foreground">Gestion des Produits</h1>
          <p className="text-muted-foreground">{products.length} produits au total</p>
        </div>
        <button onClick={handleAdd} className="inline-flex items-center gap-2 rounded-lg bg-gradient-blue px-4 py-2.5 text-sm font-medium text-white shadow-md transition-transform hover:scale-105">
          <Plus className="h-4 w-4" /> Ajouter un produit
        </button>
      </div>

      <div className="relative">
        <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <input
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Rechercher un produit..."
          className="w-full rounded-lg border border-input bg-card py-2.5 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
        />
      </div>

      <div className="overflow-x-auto rounded-2xl border border-border bg-card shadow-sm">
        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-border text-left">
              <th className="px-4 py-3 font-medium text-muted-foreground">Produit</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Catégorie</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Prix</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Tag</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-border">
            {filtered.map((product, i) => (
              <tr key={product.id}>
                <td className="px-4 py-3">
                  <div className="flex items-center gap-3">
                    {/* ✅ CHANGEMENT 3 : Utiliser getImageUrl au lieu de l'URL en dur */}
                    <img 
                      src={getImageUrl(product.main_image)} 
                      alt={product.name} 
                      className="h-10 w-10 rounded-lg object-cover"
                      onError={(e) => {
                        (e.target as HTMLImageElement).src = 'https://via.placeholder.com/40x40?text=No+Image';
                      }}
                    />
                    <div>
                      <div className="font-medium text-foreground">{product.name}</div>
                      <div className="text-xs text-muted-foreground">{product.id.slice(0, 8)}...</div>
                    </div>
                  </div>
                </td>
                <td className="px-4 py-3 text-muted-foreground">{product.category_name}</td>
                <td className="px-4 py-3 font-medium text-foreground">{formatFCFA(product.price)}</td>
                <td className="px-4 py-3">
                  {product.tag && (
                    <span className="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-medium text-primary">
                      {product.tag}
                    </span>
                  )}
                </td>
                <td className="px-4 py-3">
                  <div className="flex gap-2">
                    <button onClick={() => handleEdit(product)} className="rounded-lg p-1.5 text-muted-foreground hover:bg-accent hover:text-foreground">
                      <Edit className="h-4 w-4" />
                    </button>
                    <button onClick={() => handleDelete(product.id)} className="rounded-lg p-1.5 text-muted-foreground hover:bg-destructive/10 hover:text-destructive">
                      <Trash2 className="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <AdminModal open={modalOpen} onClose={() => setModalOpen(false)} title={editing ? "Modifier le produit" : "Ajouter un produit"}>
        <ProductForm initial={editing ?? undefined} onSubmit={handleSubmit} onCancel={() => setModalOpen(false)} />
      </AdminModal>
    </div>
  );
}