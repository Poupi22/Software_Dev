import { createFileRoute, Link } from "@tanstack/react-router";
import { motion } from "framer-motion";
import { useState, useEffect } from "react";
import { ArrowRight, Sparkles, Star, Grid2X2 } from "lucide-react";
import Footer from "@/components/layout/Footer";
import { useLang } from "@/lib/i18n";
import { api, type Category } from "@/lib/api";

export const Route = createFileRoute("/categories")({
  head: () => ({
    meta: [
      { title: "Catégories — DreamRest" },
      { name: "description", content: "Toutes les catégories de matelas DreamRest." },
    ],
  }),
  component: CategoriesPage,
});

function CategoriesPage() {
  const { t } = useLang();
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

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

  const getImageUrl = (imagePath: string | null): string => {
    if (!imagePath) return "https://via.placeholder.com/800x600?text=DreamRest";
    if (imagePath.startsWith("http")) return imagePath;
    const baseUrl = API_URL.replace("/api", "");
    return `${baseUrl}${imagePath}`;
  };

  if (loading) {
    return (
      <div className="flex h-screen items-center justify-center bg-background">
        <div className="flex flex-col items-center gap-4">
          <div className="h-10 w-10 animate-spin rounded-full border-2 border-primary/20 border-t-primary" />
          <p className="text-xs tracking-widest uppercase text-muted-foreground">Chargement…</p>
        </div>
      </div>
    );
  }

  if (error || categories.length === 0) {
    return (
      <div className="flex h-screen items-center justify-center">
        <p className="text-muted-foreground">{error ?? "Aucune catégorie disponible."}</p>
      </div>
    );
  }

  const cat = categories[0];

  return (
    <>
      {/* ── Ambient blobs ── */}
      <div className="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div className="absolute -top-32 -left-32 h-[500px] w-[500px] rounded-full bg-primary/8 blur-[120px]" />
        <div className="absolute top-1/2 -right-40 h-[400px] w-[400px] rounded-full bg-blue-400/6 blur-[100px]" />
        <div className="absolute -bottom-20 left-1/3 h-[350px] w-[350px] rounded-full bg-indigo-300/5 blur-[90px]" />
      </div>

      <div className="mx-auto max-w-6xl px-5 pt-28 pb-24 sm:px-8 lg:px-12">

        {/* ── Top label ── */}
        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
          className="mb-6 flex items-center gap-2"
        >
          <span className="inline-flex items-center gap-1.5 rounded-full border border-primary/20 bg-primary/5 px-3.5 py-1.5">
            <Sparkles className="h-3 w-3 text-primary" />
            <span className="text-[10px] font-bold uppercase tracking-widest text-primary">
              Collection du moment
            </span>
          </span>
        </motion.div>

        {/* ── Headline ── */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.1, duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
          className="mb-12"
        >
          <h1
            className="text-5xl font-black leading-[1.05] tracking-tight text-foreground sm:text-6xl lg:text-7xl"
            style={{ fontFamily: "'Georgia', 'Times New Roman', serif" }}
          >
            La qualité,<br />
            <span className="relative inline-block text-primary">
              à votre portée.
              <motion.span
                initial={{ scaleX: 0 }}
                animate={{ scaleX: 1 }}
                transition={{ delay: 0.7, duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
                className="absolute -bottom-1 left-0 right-0 h-[3px] origin-left rounded-full bg-primary opacity-40"
              />
            </span>
          </h1>
          <p className="mt-5 max-w-xl text-base leading-relaxed text-muted-foreground">
            Chez DreamRest, chaque produit est sélectionné avec soin pour vous garantir qualité,
            durabilité et satisfaction. Parcourez nos collections et trouvez ce qui vous correspond.
          </p>
        </motion.div>

        {/* ── Main card ── */}
        <motion.div
          initial={{ opacity: 0, y: 28 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.25, duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
          className="relative overflow-hidden rounded-3xl border border-border/60 bg-card shadow-xl shadow-black/5"
        >
          <div className="grid lg:grid-cols-2">

            {/* Image side */}
            <div className="relative h-72 overflow-hidden lg:h-auto lg:min-h-[480px]">
              <img
                src={getImageUrl(cat.image)}
                alt={cat.name}
                className="h-full w-full object-cover"
                onError={(e) => {
                  (e.target as HTMLImageElement).src =
                    "https://via.placeholder.com/600x480?text=DreamRest";
                }}
              />
              {/* Subtle gradient edge */}
              <div className="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent lg:bg-gradient-to-r lg:from-transparent lg:to-card/30" />

              {/* Floating badge on image */}
              <div className="absolute bottom-4 left-4 flex items-center gap-2 rounded-xl border border-white/20 bg-black/50 px-3 py-2 backdrop-blur-md">
                <div className="flex gap-0.5">
                  {[...Array(5)].map((_, i) => (
                    <Star key={i} className="h-3 w-3 fill-amber-400 text-amber-400" />
                  ))}
                </div>
                <span className="text-xs font-semibold text-white">4.9 / 5 · 200+ avis</span>
              </div>
            </div>

            {/* Text side */}
            <div className="flex flex-col justify-center gap-7 p-8 lg:p-12">

              {/* Category name */}
              <div>
                <p className="mb-1.5 text-[10px] font-bold uppercase tracking-[0.25em] text-muted-foreground">
                  Catégorie vedette
                </p>
                <h2
                  className="text-4xl font-black leading-tight text-foreground lg:text-5xl"
                  style={{ fontFamily: "'Georgia', serif" }}
                >
                  {cat.name}
                </h2>
              </div>

              {/* Description or default pitch */}
              <p className="text-sm leading-relaxed text-muted-foreground">
                {cat.description ||
                  "Une collection soigneusement sélectionnée pour répondre à vos besoins. Des produits de qualité premium, durables et pensés pour vous satisfaire au quotidien."}
              </p>

              {/* Feature pills */}
              <div className="flex flex-wrap gap-2">
                {["Livraison offerte", "Garantie 10 ans", "Qualité premium"].map((feat) => (
                  <span
                    key={feat}
                    className="rounded-full border border-border bg-muted px-3 py-1 text-xs font-medium text-foreground"
                  >
                    {feat}
                  </span>
                ))}
              </div>

              {/* Primary CTA */}
              <Link
                to={`/shop?category=${cat.id}&q=` as any}
                className="group inline-flex w-fit items-center gap-3 rounded-xl bg-primary px-7 py-3.5 text-sm font-bold text-primary-foreground shadow-lg shadow-primary/25 transition-all duration-300 hover:opacity-90 hover:shadow-primary/40 hover:gap-4"
              >
                Voir la collection
                <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
              </Link>
            </div>
          </div>
        </motion.div>

        {/* ── See all categories CTA ── */}
        <motion.div
          initial={{ opacity: 0, y: 16 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.5, duration: 0.5 }}
          className="mt-10 flex flex-col items-center gap-4 rounded-2xl border border-dashed border-border/80 bg-muted/40 px-6 py-8 text-center"
        >
          <div className="flex h-11 w-11 items-center justify-center rounded-full border border-border bg-background shadow-sm">
            <Grid2X2 className="h-5 w-5 text-primary" />
          </div>
          <div>
            <p className="font-semibold text-foreground">Vous cherchez autre chose ?</p>
            <p className="mt-1 text-sm text-muted-foreground">
              Explorez toutes nos collections et trouvez le matelas fait pour vous.
            </p>
          </div>
          <Link
            to="/shop" as any
            className="group inline-flex items-center gap-2 rounded-xl border border-primary/30 bg-primary/5 px-6 py-2.5 text-sm font-semibold text-primary transition-all hover:bg-primary/10 hover:gap-3"
          >
            Voir toutes nos catégories
            <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
          </Link>
        </motion.div>

        {/* ── Trust bar ── */}
        <motion.div
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.7 }}
          className="mt-12 grid grid-cols-3 gap-4 border-t border-border pt-10 text-center sm:grid-cols-3"
        >
          {[
            { value: "10 ans", label: "de garantie" },
            { value: "100%", label: "satisfaction client" },
            { value: "200+", label: "produits disponibles" },
          ].map(({ value, label }) => (
            <div key={label}>
              <p className="text-2xl font-black text-foreground" style={{ fontFamily: "'Georgia', serif" }}>
                {value}
              </p>
              <p className="mt-1 text-xs text-muted-foreground">{label}</p>
            </div>
          ))}
        </motion.div>
      </div>

      <Footer />
    </>
  );
}