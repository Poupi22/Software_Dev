import { Link, useNavigate } from "@tanstack/react-router";
import { motion, AnimatePresence } from "framer-motion";
import { Menu, X, Moon, Sun, Search, Globe } from "lucide-react";
import { useState, useMemo, useEffect, useRef } from "react";
import { useLang } from "@/lib/i18n";
import { api } from "@/lib/api";
import logoEtcg from "@/assets/logo-etcg.png";

interface NavbarProps {
  isDark: boolean;
  toggleTheme: () => void;
}

export default function Navbar({ isDark, toggleTheme }: NavbarProps) {
  const [mobileOpen, setMobileOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [query, setQuery] = useState("");
  const [showSuggest, setShowSuggest] = useState(false);
  const [products, setProducts] = useState<any[]>([]);
  const navigate = useNavigate();
  const { lang, setLang, t } = useLang();
  const wrapRef = useRef<HTMLFormElement | null>(null);

  useEffect(() => {
    loadProducts();
  }, []);

  const loadProducts = async () => {
    try {
      const data = await api.getProducts();
      setProducts(data);
    } catch (err) {
      console.error('Erreur chargement produits:', err);
    }
  };

  const navLinks = [
    { to: "/" as const, label: t("nav.home") },
    { to: "/shop" as const, label: t("nav.shop") },
    { to: "/categories" as const, label: t("nav.categories") },
    { to: "/about" as const, label: t("nav.about") },
    { to: "/contact" as const, label: t("nav.contact") },
  ];

  const suggestions = useMemo(() => {
    const q = query.trim().toLowerCase();
    if (!q) return [];
    return products
      .filter((p) => {
        const hay = `${p.name} ${p.description_title || ""} ${p.description || ""} ${p.category_name || ""}`.toLowerCase();
        return hay.includes(q);
      })
      .slice(0, 6);
  }, [query, products]);

  useEffect(() => {
    function onDoc(e: MouseEvent) {
      if (!wrapRef.current) return;
      if (!wrapRef.current.contains(e.target as Node)) setShowSuggest(false);
    }
    if (searchOpen) document.addEventListener("mousedown", onDoc);
    return () => document.removeEventListener("mousedown", onDoc);
  }, [searchOpen]);

  const submitSearch = (e: React.FormEvent) => {
    e.preventDefault();
    navigate({ to: "/shop", search: { q: query, category: "" } });
    setSearchOpen(false);
    setMobileOpen(false);
    setShowSuggest(false);
  };

  const pickSuggestion = (id: string) => {
    setSearchOpen(false);
    setShowSuggest(false);
    setQuery("");
    navigate({ to: "/products/$productId", params: { productId: id } });
  };

  const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000";

  const getImageUrl = (imagePath: string | null): string => {
    if (!imagePath) return 'https://via.placeholder.com/50x50?text=No+Image';
    if (imagePath.startsWith('http')) return imagePath;
    return `http://localhost:5000${imagePath}`;
  };

  return (
    <motion.header
      initial={{ y: -100 }}
      animate={{ y: 0 }}
      transition={{ duration: 0.6, ease: "easeOut" }}
      className="fixed top-0 left-0 right-0 z-50 glass"
    >
      <div className="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
        <Link to="/" className="flex items-center gap-2.5">
          <motion.img
            src={logoEtcg}
            alt="ETCG"
            whileHover={{ rotate: [0, -6, 6, 0], scale: 1.05 }}
            transition={{ duration: 0.6 }}
            className="h-10 w-10 object-contain drop-shadow-md"
          />
          <span className="text-lg font-bold tracking-tight text-foreground">
            E.T.C.G
          </span>
        </Link>

        <nav className="hidden items-center gap-1 md:flex">
          {navLinks.map((link) => (
            <Link
              key={link.to}
              to={link.to}
              activeProps={{ className: "text-primary bg-accent" }}
              inactiveProps={{ className: "text-muted-foreground hover:text-foreground hover:bg-accent" }}
              className="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200"
            >
              {link.label}
            </Link>
          ))}
        </nav>

        <div className="flex items-center gap-1 sm:gap-2">
          <button
            onClick={() => setSearchOpen((s) => !s)}
            aria-label="Search"
            className="rounded-lg p-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
          >
            <Search className="h-4 w-4" />
          </button>
          <button
            onClick={() => setLang(lang === "fr" ? "en" : "fr")}
            aria-label="Language"
            className="flex items-center gap-1 rounded-lg p-2 text-xs font-semibold uppercase text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
          >
            <Globe className="h-4 w-4" /> {lang}
          </button>
          <button
            onClick={toggleTheme}
            aria-label="Theme"
            className="rounded-lg p-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
          >
            {isDark ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
          </button>

          <button
            onClick={() => setMobileOpen(!mobileOpen)}
            className="rounded-lg p-2 text-muted-foreground md:hidden"
            aria-label="Menu"
          >
            {mobileOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
          </button>
        </div>
      </div>

      <AnimatePresence>
        {searchOpen && (
          <motion.form
            ref={wrapRef}
            onSubmit={submitSearch}
            initial={{ opacity: 0, y: -10 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -10 }}
            className="border-t border-border bg-background/95 px-4 py-3 backdrop-blur"
          >
            <div className="relative mx-auto max-w-3xl">
              <div className="flex gap-2">
                <input
                  autoFocus
                  value={query}
                  onChange={(e) => { setQuery(e.target.value); setShowSuggest(true); }}
                  onFocus={() => setShowSuggest(true)}
                  placeholder={t("nav.search.placeholder")}
                  className="flex-1 rounded-lg border border-input bg-card px-4 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                />
                <button type="submit" className="rounded-lg bg-gradient-brand px-4 py-2 text-sm font-medium text-white">
                  <Search className="h-4 w-4" />
                </button>
              </div>

              <AnimatePresence>
                {showSuggest && suggestions.length > 0 && (
                  <motion.ul
                    initial={{ opacity: 0, y: -6 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -6 }}
                    className="absolute left-0 right-0 z-50 mt-2 overflow-hidden rounded-xl border border-border bg-popover shadow-2xl"
                  >
                    {suggestions.map((p) => {
                      const name = p.name;
                      return (
                        <li key={p.id}>
                          <button
                            type="button"
                            onClick={() => pickSuggestion(p.id)}
                            className="flex w-full items-center gap-3 px-3 py-2.5 text-left text-sm transition-colors hover:bg-accent"
                          >
                            <img src={getImageUrl(p.main_image)} alt="" className="h-10 w-12 shrink-0 rounded-md object-cover" />
                            <div className="min-w-0 flex-1">
                              <div className="truncate font-medium text-foreground">{name}</div>
                              <div className="truncate text-xs text-muted-foreground">{p.category_name || 'Sans catégorie'}</div>
                            </div>
                          </button>
                        </li>
                      );
                    })}
                    <li className="border-t border-border">
                      <button
                        type="submit"
                        className="block w-full px-3 py-2 text-left text-xs font-medium text-primary hover:bg-accent"
                      >
                        {lang === "fr" ? `Voir tous les résultats pour "${query}" →` : `See all results for "${query}" →`}
                      </button>
                    </li>
                  </motion.ul>
                )}
                {showSuggest && query && suggestions.length === 0 && (
                  <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    className="absolute left-0 right-0 z-50 mt-2 rounded-xl border border-border bg-popover px-4 py-3 text-sm text-muted-foreground shadow-2xl"
                  >
                    {lang === "fr" ? "Aucun produit ne correspond." : "No matching product."}
                  </motion.div>
                )}
              </AnimatePresence>
            </div>
          </motion.form>
        )}
      </AnimatePresence>

      <AnimatePresence>
        {mobileOpen && (
          <motion.div
            initial={{ opacity: 0, height: 0 }}
            animate={{ opacity: 1, height: "auto" }}
            exit={{ opacity: 0, height: 0 }}
            className="border-t border-border bg-background px-4 pb-4 md:hidden"
          >
            {navLinks.map((link) => (
              <Link
                key={link.to}
                to={link.to}
                onClick={() => setMobileOpen(false)}
                className="block rounded-lg px-3 py-2.5 text-sm font-medium text-foreground transition-colors hover:bg-accent"
              >
                {link.label}
              </Link>
            ))}
          </motion.div>
        )}
      </AnimatePresence>
    </motion.header>
  );
}