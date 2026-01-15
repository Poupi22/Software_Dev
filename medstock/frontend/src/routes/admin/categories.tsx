import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { Plus, Pencil, Trash2, Loader2 } from "lucide-react";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, DialogFooter } from "@/components/ui/dialog";
import { ImagePicker } from "@/components/admin/ImagePicker";
import { toast } from "sonner";
import { categoriesApi, type Category } from "@/lib/categories";
import { useAuth } from "@/lib/auth";

export const Route = createFileRoute("/admin/categories")({
  component: CategoriesPage,
});

function CategoriesPage() {
  const { user } = useAuth();
  const isAdmin = user?.role === "ADMIN";
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [editOpen, setEditOpen] = useState(false);
  const [selectedCategory, setSelectedCategory] = useState<Category | null>(null);
  const [formData, setFormData] = useState({
    nom: "",
    description: "",
    couleur: "#3b82f6",
  });
  const [imageFile, setImageFile] = useState<File | null>(null);
  const [submitting, setSubmitting] = useState(false);

  useEffect(() => {
    fetchCategories();
  }, []);

  const fetchCategories = async () => {
    setLoading(true);
    try {
      const res = await categoriesApi.getAll();
      setCategories(res.data.data);
    } catch (error) {
      console.error("Error fetching categories:", error);
      toast.error("Erreur lors du chargement des categories");
    } finally {
      setLoading(false);
    }
  };

  const handleCreate = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      const formDataToSend = new FormData();
      formDataToSend.append("nom", formData.nom);
      formDataToSend.append("description", formData.description);
      formDataToSend.append("couleur", formData.couleur);
      if (imageFile) {
        formDataToSend.append("image", imageFile);
      }
      
      await categoriesApi.create(formDataToSend);
      toast.success("Categorie cree avec succes");
      setOpen(false);
      resetForm();
      fetchCategories();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la creation";
      toast.error(msg);
    } finally {
      setSubmitting(false);
    }
  };

  const handleUpdate = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedCategory) return;
    setSubmitting(true);
    try {
      const formDataToSend = new FormData();
      formDataToSend.append("nom", formData.nom);
      formDataToSend.append("description", formData.description);
      formDataToSend.append("couleur", formData.couleur);
      if (imageFile) {
        formDataToSend.append("image", imageFile);
      }
      
      await categoriesApi.update(selectedCategory.id, formDataToSend);
      toast.success("Categorie modifiee avec succes");
      setEditOpen(false);
      resetForm();
      fetchCategories();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la modification";
      toast.error(msg);
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (id: string, nom: string) => {
    if (!confirm(`Supprimer la categorie "${nom}" ?`)) return;
    try {
      await categoriesApi.delete(id);
      toast.success("Categorie supprimee avec succes");
      fetchCategories();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la suppression";
      toast.error(msg);
    }
  };

  const openEditDialog = (category: Category) => {
    setSelectedCategory(category);
    setFormData({
      nom: category.nom,
      description: category.description || "",
      couleur: category.couleur || "#3b82f6",
    });
    setImageFile(null);
    setEditOpen(true);
  };

  const resetForm = () => {
    setFormData({ nom: "", description: "", couleur: "#3b82f6" });
    setImageFile(null);
    setSelectedCategory(null);
  };

  if (loading) {
    return (
      <AdminLayout>
        <div className="flex h-96 items-center justify-center">
          <Loader2 className="h-8 w-8 animate-spin text-primary" />
        </div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <PageHeader
        title="Categories"
        description="Gestion des categories de medicaments"
        actions={
          isAdmin && (
            <Dialog open={open} onOpenChange={setOpen}>
              <DialogTrigger asChild>
                <button className="flex h-10 items-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground">
                  <Plus className="h-4 w-4" /> Nouvelle categorie
                </button>
              </DialogTrigger>
              <DialogContent>
                <DialogHeader><DialogTitle>Nouvelle categorie</DialogTitle></DialogHeader>
                <form onSubmit={handleCreate} className="space-y-4">
                  <ImagePicker 
                    label="Image de la categorie"
                    hint="PNG, JPG, WEBP - max 5 Mo"
                    onImageSelect={setImageFile}
                  />
                  <input
                    className="h-10 w-full rounded-lg border px-3"
                    placeholder="Nom"
                    value={formData.nom}
                    onChange={(e) => setFormData({ ...formData, nom: e.target.value })}
                    required
                  />
                  <input
                    type="color"
                    className="h-10 w-full rounded-lg border"
                    value={formData.couleur}
                    onChange={(e) => setFormData({ ...formData, couleur: e.target.value })}
                  />
                  <textarea
                    className="min-h-20 w-full rounded-lg border p-3"
                    placeholder="Description"
                    value={formData.description}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  />
                  <DialogFooter>
                    <button type="button" onClick={() => setOpen(false)} className="h-10 rounded-lg border px-4">Annuler</button>
                    <button type="submit" disabled={submitting} className="h-10 rounded-lg bg-primary px-4 text-white">
                      {submitting ? <Loader2 className="h-4 w-4 animate-spin" /> : "Creer"}
                    </button>
                  </DialogFooter>
                </form>
              </DialogContent>
            </Dialog>
          )
        }
      />

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        {categories.map((cat) => (
          <div key={cat.id} className="rounded-2xl border bg-card overflow-hidden">
            <div className="relative h-32 bg-gradient-to-r from-blue-500 to-blue-600">
              {cat.image_url && (
                <img src={cat.image_url} alt={cat.nom} className="h-full w-full object-cover" />
              )}
              {isAdmin && (
                <div className="absolute right-2 top-2 flex gap-1">
                  <button onClick={() => openEditDialog(cat)} className="rounded bg-black/50 p-1 text-white">
                    <Pencil className="h-4 w-4" />
                  </button>
                  <button onClick={() => handleDelete(cat.id, cat.nom)} className="rounded bg-black/50 p-1 text-white">
                    <Trash2 className="h-4 w-4" />
                  </button>
                </div>
              )}
              <div className="absolute bottom-2 left-3">
                <h3 className="text-lg font-bold text-white">{cat.nom}</h3>
              </div>
            </div>
            {cat.description && (
              <div className="p-3">
                <p className="text-sm text-muted-foreground">{cat.description}</p>
              </div>
            )}
          </div>
        ))}
      </div>

      {/* Edit Dialog */}
      <Dialog open={editOpen} onOpenChange={setEditOpen}>
        <DialogContent>
          <DialogHeader><DialogTitle>Modifier la categorie</DialogTitle></DialogHeader>
          <form onSubmit={handleUpdate} className="space-y-4">
            <ImagePicker 
              label="Image de la categorie"
              initial={selectedCategory?.image_url}
              hint="PNG, JPG, WEBP - max 5 Mo"
              onImageSelect={setImageFile}
            />
            <input
              className="h-10 w-full rounded-lg border px-3"
              value={formData.nom}
              onChange={(e) => setFormData({ ...formData, nom: e.target.value })}
              required
            />
            <input
              type="color"
              className="h-10 w-full rounded-lg border"
              value={formData.couleur}
              onChange={(e) => setFormData({ ...formData, couleur: e.target.value })}
            />
            <textarea
              className="min-h-20 w-full rounded-lg border p-3"
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
            />
            <DialogFooter>
              <button type="button" onClick={() => setEditOpen(false)} className="h-10 rounded-lg border px-4">Annuler</button>
              <button type="submit" disabled={submitting} className="h-10 rounded-lg bg-primary px-4 text-white">
                {submitting ? <Loader2 className="h-4 w-4 animate-spin" /> : "Enregistrer"}
              </button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </AdminLayout>
  );
}