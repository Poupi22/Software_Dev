import { motion } from "framer-motion";
import { Link } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { useLang } from "@/lib/i18n";
import { api, type Category } from "@/lib/api";

// ✅ CHANGEMENT 1 : Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

export default function CategoryCards() {
  const { t, lang } = useLang();
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadCategories();
  }, []);

  const loadCategories = async () => {
    try {
      const data = await api.getCategories();
      setCategories(data);
    } catch (err) {
      console.error('Erreur chargement catégories:', err);
    } finally {
      setLoading(false);
    }
  };

  // ✅ CHANGEMENT 2 : Utiliser l'URL de l'API pour construire les URLs des images
  const getImageUrl = (imagePath: string | null): string => {
    if (!imagePath) return 'https://via.placeholder.com/400x240?text=No+Image';
    if (imagePath.startsWith('http')) return imagePath;
    // Extraire l'URL de base sans le /api à la fin
    const baseUrl = API_URL.replace('/api', '');
    return `${baseUrl}${imagePath}`;
  };

  if (loading) {
    return (
      <section className="bg-background py-20">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="flex h-64 items-center justify-center">
            <div className="h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          </div>
        </div>
      </section>
    );
  }

  if (categories.length === 0) {
    return null;
  }

  return (
    <section className="bg-background py-20">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="text-center"
        >
          <h2 className="text-3xl font-bold text-foreground sm:text-4xl">{t("cat.title")}</h2>
          <p className="mt-2 text-muted-foreground">{t("cat.subtitle")}</p>
        </motion.div>

        <div className="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          {categories.slice(0, 6).map((cat, i) => (
            <motion.div
              key={cat.id}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ delay: i * 0.08 }}
              whileHover={{ y: -8, scale: 1.02 }}
            >
              <Link 
                to="/shop" 
                search={{ category: cat.id }} 
                className="group block overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all hover:shadow-2xl hover:shadow-primary/10 hover:border-primary/30"
              >
                <div className="relative aspect-[5/3] overflow-hidden bg-gray-100">
                  <img
                    src={getImageUrl(cat.image)}
                    alt={cat.name}
                    loading="lazy"
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                    onError={(e) => {
                      (e.target as HTMLImageElement).src = 'https://via.placeholder.com/400x240?text=No+Image';
                    }}
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-foreground/70 via-foreground/10 to-transparent" />
                  <div className="absolute inset-x-0 bottom-0 p-4">
                    <h3 className="text-lg font-semibold text-white">{cat.name}</h3>
                    <p className="text-sm text-white/80">{cat.quantity} {t("cat.models")}</p>
                  </div>
                </div>
              </Link>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}