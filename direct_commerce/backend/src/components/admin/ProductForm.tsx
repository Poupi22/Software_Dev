import { useState, useEffect } from "react";
import { api, type Category } from "@/lib/api";

// ✅ Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

interface ProductFormProps {
  initial?: any;
  onSubmit: (formData: FormData) => void;
  onCancel: () => void;
}

// ✅ Fonction utilitaire pour obtenir l'URL complète des images
const getImageUrl = (imagePath: string | null): string => {
  if (!imagePath) return '';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

export default function ProductForm({ initial, onSubmit, onCancel }: ProductFormProps) {
  const [categories, setCategories] = useState<Category[]>([]);
  const [name, setName] = useState(initial?.name ?? "");
  const [descriptionTitle, setDescriptionTitle] = useState(initial?.description_title ?? "");
  const [description, setDescription] = useState(initial?.description ?? "");
  const [price, setPrice] = useState(initial?.price?.toString() ?? "");
  const [soldPrice, setSoldPrice] = useState(initial?.sold_price?.toString() ?? "");
  const [tag, setTag] = useState(initial?.tag ?? "");
  const [categoryId, setCategoryId] = useState(initial?.category_id ?? "");
  const [mainImage, setMainImage] = useState<File | null>(null);
  const [subImages, setSubImages] = useState<FileList | null>(null);
  
  // ✅ Utilisation de getImageUrl pour l'aperçu
  const [mainImagePreview, setMainImagePreview] = useState<string>(
    initial?.main_image ? getImageUrl(initial.main_image) : ""
  );
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    loadCategories();
  }, []);

  const loadCategories = async () => {
    try {
      const data = await api.getCategories();
      setCategories(data);
      if (!categoryId && data.length > 0) {
        setCategoryId(data[0].id);
      }
    } catch (err) {
      console.error("Erreur chargement catégories:", err);
    }
  };

  const handleMainImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setMainImage(file);
      setMainImagePreview(URL.createObjectURL(file));
    }
  };

  const handleSubImagesChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setSubImages(e.target.files);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!name || !price || !categoryId) {
      alert("Veuillez remplir tous les champs obligatoires");
      return;
    }

    if (!initial && !mainImage) {
      alert("Veuillez ajouter une image principale");
      return;
    }

    setLoading(true);

    const formData = new FormData();
    formData.append("name", name);
    formData.append("description_title", descriptionTitle);
    formData.append("description", description);
    formData.append("price", price);
    if (soldPrice) formData.append("sold_price", soldPrice);
    if (tag) formData.append("tag", tag);
    formData.append("category_id", categoryId);
    
    if (mainImage) {
      formData.append("main_image", mainImage);
    }
    
    if (subImages) {
      Array.from(subImages).forEach((file) => {
        formData.append("sub_images", file);
      });
    }

    await onSubmit(formData);
    setLoading(false);
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">Nom du produit *</label>
        <input
          required
          value={name}
          onChange={(e) => setName(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          placeholder="Ex: Smartphone Pro"
        />
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">Titre description</label>
        <input
          value={descriptionTitle}
          onChange={(e) => setDescriptionTitle(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          placeholder="Ex: Flagship Killer"
        />
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">Description</label>
        <textarea
          rows={3}
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          placeholder="Description détaillée du produit..."
        />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div>
          <label className="mb-1.5 block text-sm font-medium text-foreground">Prix (FCFA) *</label>
          <input
            type="number"
            required
            min={0}
            step="0.01"
            value={price}
            onChange={(e) => setPrice(e.target.value)}
            className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
            placeholder="Ex: 199.99"
          />
        </div>
        <div>
          <label className="mb-1.5 block text-sm font-medium text-foreground">
            Prix soldé (FCFA) <span className="text-xs text-muted-foreground">(optionnel)</span>
          </label>
          <input
            type="number"
            min={0}
            step="0.01"
            value={soldPrice}
            onChange={(e) => setSoldPrice(e.target.value)}
            className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
            placeholder="Ex: 149.99"
          />
        </div>
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">Catégorie *</label>
        <select
          required
          value={categoryId}
          onChange={(e) => setCategoryId(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
        >
          <option value="">Sélectionner une catégorie</option>
          {categories.map((c) => (
            <option key={c.id} value={c.id}>{c.name}</option>
          ))}
        </select>
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">Tag</label>
        <select
          value={tag}
          onChange={(e) => setTag(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
        >
          <option value="">Aucun</option>
          <option value="best seller">Best Seller</option>
          <option value="new">Nouveau</option>
          <option value="sale">En solde</option>
          <option value="featured">En vedette</option>
          <option value="trending">Tendance</option>
          <option value="limited">Édition limitée</option>
        </select>
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">
          Image principale {!initial && "*"}
        </label>
        <input
          type="file"
          accept="image/*"
          onChange={handleMainImageChange}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
        />
        {mainImagePreview && (
          <img src={mainImagePreview} alt="Preview" className="mt-2 h-20 w-20 rounded-lg object-cover" />
        )}
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">
          Sous-images <span className="text-xs text-muted-foreground">(optionnel, max 10)</span>
        </label>
        <input
          type="file"
          accept="image/*"
          multiple
          onChange={handleSubImagesChange}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
        />
        {subImages && (
          <p className="mt-1 text-xs text-muted-foreground">{subImages.length} image(s) sélectionnée(s)</p>
        )}
      </div>

      <div className="flex gap-3 pt-2">
        <button 
          type="button" 
          onClick={onCancel} 
          disabled={loading}
          className="flex-1 rounded-lg border border-border bg-card px-4 py-2.5 text-sm font-medium text-foreground hover:bg-accent disabled:opacity-50"
        >
          Annuler
        </button>
        <button 
          type="submit" 
          disabled={loading}
          className="flex-1 rounded-lg bg-gradient-blue px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:scale-[1.02] transition-transform disabled:opacity-50"
        >
          {loading ? "Chargement..." : initial?.name ? "Modifier" : "Ajouter"}
        </button>
      </div>
    </form>
  );
}