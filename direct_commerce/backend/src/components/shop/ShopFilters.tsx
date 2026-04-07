import { Search, SlidersHorizontal, X } from "lucide-react";
import { useLang } from "@/lib/i18n";
import type { Category, Product } from "@/lib/api";

// ✅ CHANGEMENT 1 : Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

interface FilterSidebarProps {
  category: string;
  setCategory: (v: string) => void;
  available: boolean;
  setAvailable: (v: boolean) => void;
  open: boolean;
  setOpen: (v: boolean) => void;
  categories: Category[];
  products: Product[];
}

interface SearchBarProps {
  search: string;
  setSearch: (v: string) => void;
  onOpenFilters: () => void;
}

export function SearchBar({ search, setSearch, onOpenFilters }: SearchBarProps) {
  const { t } = useLang();
  return (
    <div className="mb-6 flex gap-3">
      <div className="relative flex-1">
        <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <input
          type="text"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder={t("nav.search.placeholder")}
          className="w-full rounded-xl border border-input bg-card py-2.5 pr-4 pl-10 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
        />
      </div>
      <button onClick={onOpenFilters} className="rounded-xl border border-border bg-card px-4 text-muted-foreground lg:hidden">
        <SlidersHorizontal className="h-4 w-4" />
      </button>
    </div>
  );
}

// ✅ CHANGEMENT 2 : Fonction utilitaire pour obtenir l'URL complète des images
const getImageUrl = (imagePath: string | null): string => {
  if (!imagePath) return 'https://via.placeholder.com/24x24?text=Cat';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

export function FilterSidebar({ category, setCategory, available, setAvailable, open, setOpen, categories, products }: FilterSidebarProps) {
  const { t } = useLang();

  const getProductCount = (categoryId: string): number => {
    return products.filter(p => p.category_id === categoryId).length;
  };

  const filterContent = (
    <div className="space-y-6">
      <div>
        <h3 className="mb-3 text-sm font-semibold text-foreground">{t("shop.category")}</h3>
        <div className="space-y-2">
          <button
            onClick={() => setCategory("")}
            className={`block w-full rounded-lg px-3 py-2 text-left text-sm transition-colors ${!category ? "bg-gradient-blue text-white" : "text-muted-foreground hover:bg-accent"}`}
          >
            {t("shop.allCategories")} ({products.length})
          </button>
          {categories.map((c) => {
            const count = getProductCount(c.id);
            return (
              <button
                key={c.id}
                onClick={() => setCategory(c.id === category ? "" : c.id)}
                className={`flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm transition-colors ${c.id === category ? "bg-gradient-blue text-white" : "text-muted-foreground hover:bg-accent"}`}
              >
                {/* ✅ CHANGEMENT 3 : Utiliser getImageUrl au lieu de l'URL en dur */}
                <img 
                  src={getImageUrl(c.image)} 
                  alt={c.name} 
                  className="h-6 w-6 rounded object-cover"
                  onError={(e) => {
                    (e.target as HTMLImageElement).src = 'https://via.placeholder.com/24x24?text=Cat';
                  }}
                />
                <span>{c.name}</span>
                <span className="ml-auto text-xs opacity-60">{count}</span>
              </button>
            );
          })}
        </div>
      </div>

      <div>
        <h3 className="mb-3 text-sm font-semibold text-foreground">{t("shop.availability")}</h3>
        <label className="flex cursor-pointer items-center gap-2 text-sm text-muted-foreground">
          <input 
            type="checkbox" 
            checked={available} 
            onChange={(e) => setAvailable(e.target.checked)} 
            className="rounded accent-primary" 
          />
          {t("shop.onlyAvailable")}
        </label>
      </div>
    </div>
  );

  return (
    <>
      <div className="hidden w-64 shrink-0 lg:block">
        <div className="sticky top-24 max-h-[calc(100vh-7rem)] overflow-y-auto rounded-2xl border border-border bg-card p-5 shadow-sm">
          <div className="mb-4 flex items-center justify-between">
            <h2 className="font-semibold text-foreground">{t("shop.filters")}</h2>
            <SlidersHorizontal className="h-4 w-4 text-muted-foreground" />
          </div>
          {filterContent}
        </div>
      </div>

      {open && (
        <div className="fixed inset-0 z-50 lg:hidden">
          <div className="absolute inset-0 bg-foreground/30 backdrop-blur-sm" onClick={() => setOpen(false)} />
          <div className="absolute top-0 left-0 h-full w-72 overflow-y-auto border-r border-border bg-background p-5">
            <div className="mb-6 flex items-center justify-between">
              <h2 className="font-semibold text-foreground">{t("shop.filters")}</h2>
              <button onClick={() => setOpen(false)} className="text-muted-foreground"><X className="h-5 w-5" /></button>
            </div>
            {filterContent}
          </div>
        </div>
      )}
    </>
  );
}