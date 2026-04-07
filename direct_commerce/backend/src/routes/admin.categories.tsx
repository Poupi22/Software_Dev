import { createFileRoute } from "@tanstack/react-router";
import { Plus, Edit, Trash2 } from "lucide-react";
import { useState, useEffect } from "react";
import { motion } from "framer-motion";
import AdminModal from "@/components/admin/AdminModal";
import CategoryForm from "@/components/admin/CategoryForm";
import { api, type Category } from "@/lib/api";

// ✅ Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

export const Route = createFileRoute("/admin/categories")({
  component: AdminCategories,
});

// ✅ Fonction utilitaire pour obtenir l'URL complète des images
const getImageUrl = (imagePath: string | null): string => {
  if (!imagePath) return 'https://via.placeholder.com/48x48?text=No+Image';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

function AdminCategories() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [modalOpen, setModalOpen] = useState(false);
  const [editing, setEditing] = useState<Category | null>(null);

  useEffect(() => {
    loadCategories();
  }, []);

  const loadCategories = async () => {
    try {
      setLoading(true);
      const data = await api.getCategories();
      setCategories(data);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleAdd = () => {
    setEditing(null);
    setModalOpen(true);
  };

  const handleEdit = (category: Category) => {
    setEditing(category);
    setModalOpen(true);
  };

  const handleDelete = async (id: string) => {
    if (!confirm("Supprimer cette catégorie ?")) return;
    
    try {
      await api.deleteCategory(id);
      setCategories(categories.filter((c) => c.id !== id));
    } catch (err: any) {
      alert(err.message);
    }
  };

  const handleSubmit = async (formData: FormData) => {
    try {
      if (editing) {
        const updated = await api.updateCategory(editing.id, formData);
        setCategories(categories.map((c) => c.id === editing.id ? updated : c));
      } else {
        const created = await api.createCategory(formData);
        setCategories([...categories, created]);
      }
      setModalOpen(false);
    } catch (err: any) {
      alert(err.message);
    }
  };

  if (loading) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement des catégories...</p>
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
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-foreground">Catégories</h1>
          <p className="text-muted-foreground">{categories.length} catégories</p>
        </div>
        <button
          onClick={handleAdd}
          className="inline-flex items-center gap-2 rounded-lg bg-gradient-blue px-4 py-2.5 text-sm font-medium text-white shadow-md hover:scale-105 transition-transform"
        >
          <Plus className="h-4 w-4" /> Ajouter
        </button>
      </div>

      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {categories.map((cat, i) => (
          <motion.div
            key={cat.id}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: i * 0.05 }}
            whileHover={{ y: -4 }}
            className="flex items-center justify-between rounded-2xl border border-border bg-card p-5 shadow-sm"
          >
            <div className="flex items-center gap-3">
              {/* ✅ Utilisation de getImageUrl au lieu de l'URL en dur */}
              <img 
                src={getImageUrl(cat.image)} 
                alt={cat.name} 
                className="h-12 w-12 rounded-lg object-cover"
                onError={(e) => {
                  (e.target as HTMLImageElement).src = 'https://via.placeholder.com/48x48?text=No+Image';
                }}
              />
              <div>
                <div className="font-medium text-foreground">{cat.name}</div>
                <div className="text-sm text-muted-foreground">{cat.quantity} produits</div>
              </div>
            </div>
            <div className="flex gap-1">
              <button onClick={() => handleEdit(cat)} className="rounded-lg p-1.5 text-muted-foreground hover:bg-accent">
                <Edit className="h-4 w-4" />
              </button>
              <button onClick={() => handleDelete(cat.id)} className="rounded-lg p-1.5 text-muted-foreground hover:bg-destructive/10 hover:text-destructive">
                <Trash2 className="h-4 w-4" />
              </button>
            </div>
          </motion.div>
        ))}
      </div>

      <AdminModal open={modalOpen} onClose={() => setModalOpen(false)} title={editing ? "Modifier la catégorie" : "Ajouter une catégorie"}>
        <CategoryForm initial={editing ?? undefined} onSubmit={handleSubmit} onCancel={() => setModalOpen(false)} />
      </AdminModal>
    </div>
  );
}