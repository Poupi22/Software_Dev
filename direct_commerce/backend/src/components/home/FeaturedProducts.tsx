import { motion } from "framer-motion";
import { Link } from "@tanstack/react-router";
import { ArrowRight } from "lucide-react";
import { useState, useEffect } from "react";
import { useLang } from "@/lib/i18n";
import { api, type Product } from "@/lib/api";

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
  if (!imagePath) return 'https://via.placeholder.com/400x300?text=No+Image';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

export default function FeaturedProducts() {
  const { t, lang } = useLang();
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadFeaturedProducts();
  }, []);

  const loadFeaturedProducts = async () => {
    try {
      const allProducts = await api.getProducts();
      console.log('Products loaded:', allProducts);
      setProducts(allProducts.slice(0, 6));
    } catch (err) {
      console.error('Erreur chargement produits:', err);
    } finally {
      setLoading(false);
    }
  };

  const getTagLabel = (tag: string | null): string => {
    if (!tag) return '';
    const tagLabels: Record<string, { fr: string; en: string }> = {
      'best seller': { fr: 'Meilleure vente', en: 'Best Seller' },
      'new': { fr: 'Nouveau', en: 'New' },
      'sale': { fr: 'En solde', en: 'Sale' }
    };
    return tagLabels[tag]?.[lang as 'fr' | 'en'] || tag;
  };

  if (loading) {
    return (
      <section className="bg-secondary py-20">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="flex h-64 items-center justify-center">
            <div className="h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          </div>
        </div>
      </section>
    );
  }

  if (products.length === 0) return null;

  const loop = [...products, ...products, ...products];

  return (
    <section className="overflow-hidden bg-secondary py-20">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          className="flex items-end justify-between"
        >
          <div>
            <h2 className="text-3xl font-bold text-foreground sm:text-4xl">{t("feat.title")}</h2>
            <p className="mt-2 text-muted-foreground">{t("feat.subtitle")}</p>
          </div>
          <Link to="/shop" className="hidden items-center gap-1 text-sm font-medium text-primary transition-colors hover:opacity-80 md:flex">
            {t("feat.viewAll")} <ArrowRight className="h-4 w-4" />
          </Link>
        </motion.div>
      </div>

      <div className="relative mt-12 overflow-hidden">
        <div className="pointer-events-none absolute left-0 top-0 z-10 h-full w-20 bg-gradient-to-r from-secondary to-transparent" />
        <div className="pointer-events-none absolute right-0 top-0 z-10 h-full w-20 bg-gradient-to-l from-secondary to-transparent" />
        <div className="flex w-max gap-6 animate-marquee">
          {loop.map((product, i) => (
            <Link
              key={`${product.id}-${i}-${Date.now()}`}
              to="/products/$productId"
              params={{ productId: product.id }}
              className="block w-[300px] shrink-0"
            >
              <div className="group overflow-hidden rounded-2xl border border-border bg-card shadow-sm transition-all hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2">
                <div className="relative aspect-[4/3] overflow-hidden bg-gray-100">
                  {/* ✅ CHANGEMENT 3 : Utiliser getImageUrl au lieu de l'URL en dur */}
                  <img
                    key={`img-${product.id}-${i}`}
                    src={getImageUrl(product.main_image)}
                    alt={product.name}
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                    loading="lazy"
                    onLoad={() => console.log('✅ Image loaded:', product.name)}
                    onError={(e) => {
                      console.log('❌ Image failed:', product.main_image);
                      (e.target as HTMLImageElement).src = 'https://via.placeholder.com/400x300?text=No+Image';
                    }}
                  />
                  {product.tag && (
                    <span className="absolute top-3 left-3 rounded-full bg-gradient-blue px-3 py-1 text-xs font-semibold text-white shadow-lg">
                      {getTagLabel(product.tag)}
                    </span>
                  )}
                </div>
                <div className="p-5">
                  <h3 className="font-semibold text-foreground">{product.name}</h3>
                  <p className="mt-1 line-clamp-2 text-sm text-muted-foreground">
                    {product.description_title || product.description}
                  </p>
                  <div className="mt-3 flex items-center justify-between">
                    <span className="text-base font-bold text-primary">{formatFCFA(product.price)}</span>
                    <span className="text-sm text-muted-foreground">{t("common.viewMore")}</span>
                  </div>
                </div>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}