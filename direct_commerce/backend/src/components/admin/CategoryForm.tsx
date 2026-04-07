import { useState } from "react";

// ✅ Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

interface CategoryFormProps {
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

export default function CategoryForm({ initial, onSubmit, onCancel }: CategoryFormProps) {
  const [name, setName] = useState(initial?.name ?? "");
  const [description, setDescription] = useState(initial?.description ?? "");
  const [image, setImage] = useState<File | null>(null);
  
  // ✅ Utilisation de getImageUrl pour l'aperçu
  const [imagePreview, setImagePreview] = useState<string>(
    initial?.image ? getImageUrl(initial.image) : ""
  );
  const [loading, setLoading] = useState(false);

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setImage(file);
      setImagePreview(URL.createObjectURL(file));
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!name) {
      alert("Le nom est obligatoire");
      return;
    }

    if (!initial && !image) {
      alert("Veuillez ajouter une image");
      return;
    }

    setLoading(true);

    const formData = new FormData();
    formData.append("name", name);
    formData.append("description", description);
    if (image) {
      formData.append("image", image);
    }

    await onSubmit(formData);
    setLoading(false);
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">Nom de la catégorie *</label>
        <input
          required
          value={name}
          onChange={(e) => setName(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          placeholder="Ex: Électronique"
        />
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">Description</label>
        <textarea
          rows={3}
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          placeholder="Description de la catégorie..."
        />
      </div>

      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">
          Image {!initial && "*"}
        </label>
        {imagePreview && (
          <div className="mb-2">
            <img 
              src={imagePreview} 
              alt="Preview" 
              className="h-20 w-20 rounded-lg object-cover border border-border" 
            />
          </div>
        )}
        <input
          type="file"
          accept="image/jpeg,image/png,image/gif,image/webp"
          onChange={handleImageChange}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring file:mr-3 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1 file:text-sm file:font-medium file:text-primary"
        />
        <p className="mt-1 text-xs text-muted-foreground">
          Formats acceptés: JPG, PNG, GIF, WEBP (max 5MB)
        </p>
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