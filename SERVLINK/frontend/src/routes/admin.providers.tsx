import { createFileRoute } from "@tanstack/react-router";
import { Search, Check, X, Star, BadgeCheck } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { providers } from "@/lib/mock-data";

export const Route = createFileRoute("/admin/providers")({
  component: ProvidersAdmin,
});

function ProvidersAdmin() {
  return (
    <div className="space-y-5">
      <div>
        <h1 className="font-display text-2xl font-bold">Prestataires</h1>
        <p className="text-sm text-muted-foreground">Validez les comptes, gérez les mises en avant et les pièces.</p>
      </div>

      <div className="flex gap-2 flex-wrap">
        {["Tous", "En attente de validation", "Vérifiés", "Mis en avant", "Suspendus"].map((t, i) => (
          <button key={t} className={`px-4 py-2 rounded-full text-sm font-medium ${i === 0 ? "bg-primary text-primary-foreground" : "bg-card border border-border hover:border-primary"}`}>{t}</button>
        ))}
      </div>

      <div className="bg-card border border-border rounded-xl p-4">
        <div className="relative max-w-sm mb-4">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
          <Input placeholder="Rechercher…" className="pl-9 h-9" />
        </div>

        <div className="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
          {providers.slice(0, 9).map((p) => (
            <div key={p.id} className="rounded-xl border border-border p-4 flex gap-3">
              <img src={p.avatar} alt={p.name} className="h-14 w-14 rounded-xl object-cover" />
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-1">
                  <h3 className="font-semibold truncate">{p.name}</h3>
                  {p.verified && <BadgeCheck className="h-4 w-4 text-primary shrink-0" />}
                </div>
                <p className="text-xs text-muted-foreground truncate">{p.category} · {p.city}</p>
                <div className="text-xs flex items-center gap-2 mt-1">
                  <span className="flex items-center gap-1"><Star className="h-3 w-3 fill-gold text-gold" />{p.rating}</span>
                  <span className="text-muted-foreground">{p.completed} missions</span>
                </div>
                <div className="flex gap-1 mt-3">
                  <Button size="sm" className="h-7 px-2 text-xs"><Check className="h-3 w-3 mr-1" /> Valider</Button>
                  <Button size="sm" variant="outline" className="h-7 px-2 text-xs"><X className="h-3 w-3 mr-1" /> Refuser</Button>
                  <Button size="sm" variant="ghost" className="h-7 px-2 text-xs">Détails</Button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
