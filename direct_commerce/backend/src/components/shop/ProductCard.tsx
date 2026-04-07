import { motion } from "framer-motion";
import { Link } from "@tanstack/react-router";
import { useLang } from "@/lib/i18n";
import type { Product } from "@/lib/api";

// ✅ CHANGEMENT 1 : Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

const formatFCFA = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XAF',
    maximumFractionDigits: 0
  }).format(price);
};

// ✅ CHANGEMENT 2 : Fonction utilitaire pour obtenir l'URL complète des images
const getImageUrl = (imagePath: string | null): string => {
  if (!imagePath) return 'https://via.placeholder.com/300x200?text=No+Image';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

export default function ProductCard({ product, index }: { product: Product; index: number }) {
  const { lang } = useLang();
  
  // ✅ CHANGEMENT 3 : Utiliser getImageUrl au lieu de l'URL en dur
  const imageUrl = getImageUrl(product.main_image);
  
  const badge = product.tag === 'best seller' 
    ? (lang === "fr" ? "Meilleure vente" : "Best Seller")
    : product.tag === 'new' 
    ? (lang === "fr" ? "Nouveau" : "New")
    : product.tag === 'sale'
    ? (lang === "fr" ? "En solde" : "Sale")
    : product.tag === 'featured'
    ? (lang === "fr" ? "En vedette" : "Featured")
    : null;

  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ delay: Math.min(index * 0.05, 0.6), duration: 0.5 }}
      whileHover={{ y: -8 }}
    >
      <Link to="/products/$productId" params={{ productId: product.id }}>
        <div className="group overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all hover:shadow-2xl hover:shadow-primary/10 hover:border-primary/30">
          <div className="relative aspect-[4/3] overflow-hidden bg-gray-100">
            <img
              src={imageUrl}
              alt={product.name}
              loading="lazy"
              className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
              onError={(e) => {
                (e.target as HTMLImageElement).src = 'https://via.placeholder.com/300x200?text=No+Image';
              }}
            />
            {badge && (
              <span className="absolute top-3 left-3 rounded-full bg-gradient-blue px-3 py-1 text-xs font-semibold text-white shadow-lg">
                {badge}
              </span>
            )}
          </div>
          <div className="p-5">
            <div className="text-xs font-medium uppercase tracking-wider text-primary">
              {product.category_name || 'Sans catégorie'}
            </div>
            <h3 className="mt-1 font-semibold text-foreground">{product.name}</h3>
            <p className="mt-1 line-clamp-2 text-sm text-muted-foreground">
              {product.description_title || product.description || ''}
            </p>
            <div className="mt-3 flex items-center justify-between gap-2">
              <span className="text-base font-bold text-primary">
                {formatFCFA(product.price)}
              </span>
              {product.sub_images && product.sub_images.length > 0 && (
                <span className="rounded-md bg-secondary px-2 py-0.5 text-xs text-muted-foreground">
                  +{product.sub_images.length}
                </span>
              )}
            </div>
          </div>
        </div>
      </Link>
    </motion.div>
  );
}