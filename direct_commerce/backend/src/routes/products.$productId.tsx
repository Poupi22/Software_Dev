import { createFileRoute, Link, useParams } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { motion } from "framer-motion";
import { ChevronLeft, ChevronRight, Truck, RotateCcw, Shield, Check } from "lucide-react";
import { api, type Product } from "@/lib/api";
import { useLang } from "@/lib/i18n";
import ProductInquiryForm from "@/components/shop/ProductInquiryForm";

// Variable d'environnement pour l'API
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

export const Route = createFileRoute("/products/$productId")({
  component: ProductDetailPage,
});

const formatFCFA = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XAF',
    maximumFractionDigits: 0
  }).format(price);
};

// Fonction utilitaire pour obtenir l'URL complète des images
const getImageUrl = (imagePath: string | null): string => {
  if (!imagePath) return 'https://via.placeholder.com/600x600?text=No+Image';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

// Descriptions statiques des produits
const staticFeatures = {
  fr: [
    "Mousse à mémoire de forme haute densité",
    "Tissu respirant anti-acariens",
    "Support lombaire renforcé",
    "Certifié Oeko-Tex Standard 100",
    "Garantie 10 ans"
  ],
  en: [
    "High-density memory foam",
    "Breathable anti-dust mite fabric",
    "Reinforced lumbar support",
    "Oeko-Tex Standard 100 certified",
    "10-year warranty"
  ]
};

const staticDescription = {
  fr: "Découvrez un confort exceptionnel avec ce matelas premium. Conçu avec des matériaux de haute qualité pour un sommeil réparateur nuit après nuit. La technologie avancée de soutien s'adapte parfaitement à votre morphologie.",
  en: "Experience exceptional comfort with this premium mattress. Designed with high-quality materials for restorative sleep night after night. Advanced support technology perfectly adapts to your body shape."
};

function ProductDetailPage() {
  const { productId } = useParams({ from: "/products/$productId" });
  const { t, lang } = useLang();
  const [product, setProduct] = useState<Product | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [activeImage, setActiveImage] = useState(0);

  useEffect(() => {
    loadProduct();
  }, [productId]);

  const loadProduct = async () => {
    try {
      setLoading(true);
      const data = await api.getProduct(productId);
      setProduct(data);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const images = product
    ? [product.main_image, ...(product.sub_images || [])].filter(Boolean)
    : [];

  const nextImage = () => setActiveImage((prev) => (prev + 1) % images.length);
  const prevImage = () => setActiveImage((prev) => (prev - 1 + images.length) % images.length);

  const features = staticFeatures[lang as keyof typeof staticFeatures] || staticFeatures.fr;
  const fallbackDescription = staticDescription[lang as keyof typeof staticDescription] || staticDescription.fr;

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center pt-16">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement du produit...</p>
        </div>
      </div>
    );
  }

  if (error || !product) {
    return (
      <div className="flex min-h-screen flex-col items-center justify-center pt-16 px-4">
        <h1 className="text-2xl font-bold text-foreground text-center">Produit introuvable</h1>
        <p className="mt-2 text-muted-foreground text-center">Ce produit n'existe pas ou a été supprimé.</p>
        <Link to="/shop" className="mt-6 rounded-lg bg-primary px-6 py-2.5 text-sm font-medium text-white">
          Retour à la boutique
        </Link>
      </div>
    );
  }

  const name = product.name;
  const description = product.description || fallbackDescription;
  const originalPrice = product.sold_price && product.sold_price > product.price ? product.sold_price : null;
  const discount = originalPrice ? Math.round(((originalPrice - product.price) / originalPrice) * 100) : 0;

  return (
    <div className="mx-auto max-w-7xl px-4 pt-20 pb-16 sm:px-6 lg:px-8">
      {/* ✅ Fil d'Ariane responsive */}
      <nav className="mb-6 flex flex-wrap items-center gap-2 text-sm text-muted-foreground">
        <Link to="/" className="hover:text-foreground transition-colors">Accueil</Link>
        <span>/</span>
        <Link to="/shop" className="hover:text-foreground transition-colors">Boutique</Link>
        <span>/</span>
        <span className="text-foreground truncate max-w-[200px] sm:max-w-none">{name}</span>
      </nav>

      {/* ✅ Grille responsive: colonne sur mobile, 2 colonnes sur desktop */}
      <div className="grid gap-6 md:gap-8 lg:grid-cols-2">
        
        {/* ✅ Section Image - responsive */}
        <motion.div
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          className="space-y-4"
        >
          {/* Image principale avec taille responsive */}
          <div className="relative aspect-square overflow-hidden rounded-2xl border border-border bg-secondary/20">
            <img
              src={getImageUrl(images[activeImage])}
              alt={name}
              className="h-full w-full object-contain md:object-cover"
              onError={(e) => {
                (e.target as HTMLImageElement).src = 'https://via.placeholder.com/600x600?text=No+Image';
              }}
            />
            {images.length > 1 && (
              <>
                <button
                  onClick={prevImage}
                  className="absolute left-2 top-1/2 -translate-y-1/2 rounded-full bg-background/80 p-2 shadow-md backdrop-blur-sm transition hover:bg-background sm:left-3"
                >
                  <ChevronLeft className="h-4 w-4 sm:h-5 sm:w-5" />
                </button>
                <button
                  onClick={nextImage}
                  className="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-background/80 p-2 shadow-md backdrop-blur-sm transition hover:bg-background sm:right-3"
                >
                  <ChevronRight className="h-4 w-4 sm:h-5 sm:w-5" />
                </button>
              </>
            )}
            {product.tag && (
              <span className="absolute top-3 left-3 rounded-full bg-gradient-blue px-2 py-0.5 text-xs font-semibold text-white shadow-lg sm:px-3 sm:py-1">
                {product.tag === 'best seller' 
                  ? (lang === "fr" ? "Meilleure vente" : "Best Seller")
                  : product.tag === 'new' 
                  ? (lang === "fr" ? "Nouveau" : "New")
                  : product.tag === 'sale'
                  ? (lang === "fr" ? "En solde" : "Sale")
                  : product.tag}
              </span>
            )}
            {discount > 0 && (
              <span className="absolute top-3 right-3 rounded-full bg-red-500 px-2 py-0.5 text-xs font-semibold text-white shadow-lg sm:px-3 sm:py-1">
                -{discount}%
              </span>
            )}
          </div>

          {/* ✅ Miniatures - défilement horizontal sur mobile */}
          {images.length > 1 && (
            <div className="flex gap-2 overflow-x-auto pb-2 scrollbar-thin">
              {images.map((img, i) => (
                <button
                  key={i}
                  onClick={() => setActiveImage(i)}
                  className={`relative h-16 w-16 shrink-0 overflow-hidden rounded-lg border-2 transition-all sm:h-20 sm:w-20 ${
                    i === activeImage ? 'border-primary ring-2 ring-primary/20' : 'border-transparent'
                  }`}
                >
                  <img
                    src={getImageUrl(img)}
                    alt={`${name} ${i + 1}`}
                    className="h-full w-full object-cover"
                    onError={(e) => {
                      (e.target as HTMLImageElement).src = 'https://via.placeholder.com/100x100?text=No+Image';
                    }}
                  />
                </button>
              ))}
            </div>
          )}
        </motion.div>

        {/* ✅ Section Informations - responsive */}
        <motion.div
          initial={{ opacity: 0, x: 20 }}
          animate={{ opacity: 1, x: 0 }}
          className="space-y-4 sm:space-y-6"
        >
          <div>
            <div className="text-xs sm:text-sm font-medium text-primary">{product.category_name || 'Sans catégorie'}</div>
            <h1 className="mt-2 text-xl font-bold text-foreground sm:text-2xl md:text-3xl">{name}</h1>
            <p className="mt-3 text-sm text-muted-foreground sm:text-base">{description}</p>
          </div>

          {/* ✅ Prix responsive */}
          <div className="flex items-baseline gap-2 sm:gap-3">
            <span className="text-2xl font-bold text-primary sm:text-3xl">{formatFCFA(product.price)}</span>
            {originalPrice && (
              <span className="text-base text-muted-foreground line-through sm:text-lg">{formatFCFA(originalPrice)}</span>
            )}
          </div>

          {/* ✅ Caractéristiques */}
          <div className="space-y-2 border-y border-border py-3 sm:space-y-3 sm:py-4">
            {features.map((feature, i) => (
              <div key={i} className="flex items-center gap-2 text-xs sm:gap-3 sm:text-sm">
                <Check className="h-4 w-4 text-primary sm:h-5 sm:w-5" />
                <span>{feature}</span>
              </div>
            ))}
          </div>

          {/* ✅ Garanties - responsive */}
          <div className="flex flex-wrap gap-3 border-b border-border pb-3 text-xs text-muted-foreground sm:gap-6 sm:pb-4 sm:text-sm">
            <div className="flex items-center gap-1 sm:gap-2">
              <Truck className="h-3 w-3 sm:h-4 sm:w-4" />
              <span>Livraison gratuite</span>
            </div>
            <div className="flex items-center gap-1 sm:gap-2">
              <RotateCcw className="h-3 w-3 sm:h-4 sm:w-4" />
              <span>Retours faciles</span>
            </div>
            <div className="flex items-center gap-1 sm:gap-2">
              <Shield className="h-3 w-3 sm:h-4 sm:w-4" />
              <span>Garantie 10 ans</span>
            </div>
          </div>

          {/* ✅ Formulaire de commande */}
          <ProductInquiryForm product={product} />
        </motion.div>
      </div>
    </div>
  );
}