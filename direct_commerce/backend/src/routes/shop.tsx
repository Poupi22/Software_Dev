import { createFileRoute } from "@tanstack/react-router";
import { useState, useMemo, useEffect } from "react";
import { zodValidator, fallback } from "@tanstack/zod-adapter";
import { z } from "zod";
import { api, type Product } from "@/lib/api";
import ProductCard from "@/components/shop/ProductCard";
import { SearchBar, FilterSidebar } from "@/components/shop/ShopFilters";
import Footer from "@/components/layout/Footer";
import { useLang } from "@/lib/i18n";

const shopSearchSchema = z.object({
  q: fallback(z.string(), "").default(""),
  category: fallback(z.string(), "").default(""),
});

export const Route = createFileRoute("/shop")({
  validateSearch: zodValidator(shopSearchSchema),
  head: () => ({
    meta: [
      { title: "Boutique — DreamRest Matelas Premium" },
      { description: "Découvrez tous nos matelas : mémoire de forme, ressorts, latex, hybride, orthopédique." },
      { property: "og:title", content: "Boutique — DreamRest Matelas Premium" },
      { property: "og:description", content: "Tous nos matelas premium." },
    ],
  }),
  component: ShopPage,
});

function ShopPage() {
  const { t } = useLang();
  const { q, category: catParam } = Route.useSearch();
  const [search, setSearch] = useState(q);
  const [category, setCategory] = useState(catParam);
  const [available, setAvailable] = useState(false);
  const [filterOpen, setFilterOpen] = useState(false);
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [categories, setCategories] = useState<any[]>([]);

  // ✅ CHANGEMENT 1 : Récupérer l'URL de l'API depuis les variables d'environnement
  const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:5000/api';

  useEffect(() => {
    loadData();
  }, []);

  useEffect(() => {
    setSearch(q);
  }, [q]);

  useEffect(() => {
    setCategory(catParam);
  }, [catParam]);

  const loadData = async () => {
    try {
      const [productsData, categoriesData] = await Promise.all([
        api.getProducts(),
        api.getCategories()
      ]);
      setProducts(productsData);
      setCategories(categoriesData);
    } catch (err) {
      console.error('Erreur chargement données:', err);
    } finally {
      setLoading(false);
    }
  };

  const filtered = useMemo(() => {
    return products.filter((p) => {
      const haystack = `${p.name} ${p.description_title || ""} ${p.description || ""} ${p.category_name || ""}`.toLowerCase();
      if (search && !haystack.includes(search.toLowerCase())) return false;
      if (category && p.category_id !== category) return false;
      return true;
    });
  }, [products, search, category]);

  if (loading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement de la boutique...</p>
        </div>
      </div>
    );
  }

  return (
    <>
      <div className="mx-auto max-w-7xl px-4 pt-24 pb-16 sm:px-6 lg:px-8">
        <div className="mb-8">
          <h1 className="text-3xl font-bold text-foreground sm:text-4xl">{t("shop.title")}</h1>
          <p className="mt-2 text-muted-foreground">{t("shop.subtitle")} ({filtered.length} produits)</p>
        </div>

        <SearchBar search={search} setSearch={setSearch} onOpenFilters={() => setFilterOpen(true)} />

        <div className="flex gap-8">
          <FilterSidebar
            category={category} 
            setCategory={setCategory}
            available={available} 
            setAvailable={setAvailable}
            open={filterOpen} 
            setOpen={setFilterOpen}
            categories={categories}
            products={products}
          />

          <div className="flex-1">
            {filtered.length === 0 ? (
              <div className="py-20 text-center text-muted-foreground">{t("shop.empty")}</div>
            ) : (
              <div className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                {filtered.map((product, i) => (
                  <ProductCard key={product.id} product={product} index={i} />
                ))}
              </div>
            )}
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
}