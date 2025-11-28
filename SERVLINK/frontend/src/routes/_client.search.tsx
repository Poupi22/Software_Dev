import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { Search, SlidersHorizontal, MapPin, LayoutGrid, Map as MapIcon, Star } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { ProviderCard } from "@/components/ProviderCard";
import { providers, categories } from "@/lib/mock-data";

export const Route = createFileRoute("/_client/search")({
  head: () => ({
    meta: [
      { title: "Explorer les prestataires — SERVLINK" },
      { name: "description", content: "Recherchez et filtrez parmi plus de 1 200 prestataires vérifiés." },
    ],
  }),
  component: SearchPage,
});

function SearchPage() {
  const [view, setView] = useState<"list" | "map">("list");
  const [q, setQ] = useState("");
  const [cat, setCat] = useState<string | null>(null);
  const [sort, setSort] = useState("pertinence");

  let list = providers.filter((p) =>
    (!q || p.name.toLowerCase().includes(q.toLowerCase()) || p.category.toLowerCase().includes(q.toLowerCase())) &&
    (!cat || p.categorySlug === cat)
  );
  if (sort === "rating") list = [...list].sort((a, b) => b.rating - a.rating);
  if (sort === "price-asc") list = [...list].sort((a, b) => a.priceFrom - b.priceFrom);
  if (sort === "price-desc") list = [...list].sort((a, b) => b.priceFrom - a.priceFrom);

  return (
    <div className="container mx-auto px-4 py-6 md:py-10">
      {/* Search bar */}
      <div className="bg-card border border-border rounded-2xl p-3 flex flex-col md:flex-row gap-2 shadow-sm">
        <div className="flex-1 flex items-center gap-2 px-3">
          <Search className="h-5 w-5 text-muted-foreground" />
          <Input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Service, prestataire…" className="border-0 focus-visible:ring-0 shadow-none h-11 px-0" />
        </div>
        <div className="flex items-center gap-2 px-3 md:border-l border-border">
          <MapPin className="h-5 w-5 text-muted-foreground" />
          <Input placeholder="Ville" className="border-0 focus-visible:ring-0 shadow-none h-11 md:w-32 px-0" />
        </div>
        <Button size="lg" className="h-11">Rechercher</Button>
      </div>

      <div className="grid lg:grid-cols-[260px_1fr] gap-6 mt-6">
        {/* Filters */}
        <aside className="bg-card border border-border rounded-2xl p-5 h-fit lg:sticky lg:top-20">
          <div className="flex items-center gap-2 mb-4">
            <SlidersHorizontal className="h-4 w-4 text-primary" />
            <h2 className="font-display font-semibold">Filtres</h2>
          </div>

          <div className="space-y-5">
            <div>
              <h3 className="text-xs font-semibold uppercase text-muted-foreground mb-2">Catégorie</h3>
              <div className="flex flex-wrap gap-1.5">
                <button onClick={() => setCat(null)} className={`px-2.5 py-1 rounded-full text-xs ${!cat ? "bg-primary text-primary-foreground" : "bg-muted hover:bg-accent"}`}>Toutes</button>
                {categories.slice(0, 8).map((c) => (
                  <button key={c.slug} onClick={() => setCat(c.slug)} className={`px-2.5 py-1 rounded-full text-xs ${cat === c.slug ? "bg-primary text-primary-foreground" : "bg-muted hover:bg-accent"}`}>
                    {c.name}
                  </button>
                ))}
              </div>
            </div>

            <div>
              <h3 className="text-xs font-semibold uppercase text-muted-foreground mb-2">Note minimale</h3>
              <div className="flex gap-1">
                {[3, 4, 4.5, 5].map((r) => (
                  <button key={r} className="px-3 py-1.5 rounded-md border border-border text-sm hover:border-primary flex items-center gap-1">
                    <Star className="h-3 w-3 fill-gold text-gold" />{r}+
                  </button>
                ))}
              </div>
            </div>

            <div>
              <h3 className="text-xs font-semibold uppercase text-muted-foreground mb-2">Budget (FCFA)</h3>
              <div className="flex gap-2">
                <Input placeholder="Min" className="h-9" />
                <Input placeholder="Max" className="h-9" />
              </div>
            </div>

            <div>
              <h3 className="text-xs font-semibold uppercase text-muted-foreground mb-2">Disponibilité</h3>
              <div className="space-y-1.5 text-sm">
                {["Aujourd'hui", "Cette semaine", "Ce week-end"].map((d) => (
                  <label key={d} className="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" className="accent-primary" /> {d}
                  </label>
                ))}
              </div>
            </div>

            <Button variant="outline" className="w-full">Réinitialiser</Button>
          </div>
        </aside>

        {/* Results */}
        <div>
          <div className="flex flex-wrap items-center justify-between gap-3 mb-5">
            <div className="text-sm text-muted-foreground">
              <span className="font-semibold text-foreground">{list.length}</span> prestataires trouvés
            </div>
            <div className="flex items-center gap-2">
              <select value={sort} onChange={(e) => setSort(e.target.value)} className="text-sm border border-border rounded-md h-9 px-2 bg-background">
                <option value="pertinence">Pertinence</option>
                <option value="rating">Mieux notés</option>
                <option value="price-asc">Prix croissant</option>
                <option value="price-desc">Prix décroissant</option>
              </select>
              <div className="hidden md:flex border border-border rounded-md p-0.5">
                <button onClick={() => setView("list")} className={`p-1.5 rounded ${view === "list" ? "bg-accent text-primary" : "text-muted-foreground"}`}><LayoutGrid className="h-4 w-4" /></button>
                <button onClick={() => setView("map")} className={`p-1.5 rounded ${view === "map" ? "bg-accent text-primary" : "text-muted-foreground"}`}><MapIcon className="h-4 w-4" /></button>
              </div>
            </div>
          </div>

          {view === "list" ? (
            <div className="grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
              {list.map((p) => <ProviderCard key={p.id} p={p} />)}
            </div>
          ) : (
            <div className="rounded-2xl border border-border overflow-hidden h-[600px] relative bg-muted">
              <div className="absolute inset-0" style={{ backgroundImage: "linear-gradient(rgba(26,58,106,.06) 1px, transparent 1px), linear-gradient(90deg, rgba(26,58,106,.06) 1px, transparent 1px)", backgroundSize: "40px 40px" }} />
              {list.slice(0, 8).map((p, i) => (
                <div key={p.id} className="absolute" style={{ left: `${15 + (i * 11) % 70}%`, top: `${20 + (i * 17) % 60}%` }}>
                  <div className="relative">
                    <div className="h-10 w-10 rounded-full ring-4 ring-background shadow-lg overflow-hidden">
                      <img src={p.avatar} alt={p.name} className="h-full w-full object-cover" />
                    </div>
                    <div className="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 rotate-45 bg-background" />
                  </div>
                </div>
              ))}
              <div className="absolute bottom-4 left-4 right-4 bg-card rounded-lg p-3 text-sm shadow-lg">
                <MapPin className="inline h-4 w-4 text-primary mr-1" />
                Vue carte simplifiée — intégration Mapbox prévue.
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
