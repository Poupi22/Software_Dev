import { createFileRoute } from "@tanstack/react-router";
import { Star } from "lucide-react";

export const Route = createFileRoute("/provider/reviews")({
  component: ProviderReviews,
});

const reviews = [
  { id: "r1", author: "Claire Dipita", rating: 5, comment: "Travail impeccable, ponctuel et soigné. Je recommande à 100% !", service: "Réparation fuite cuisine", date: "Il y a 2 jours" },
  { id: "r2", author: "Marc Tchana",   rating: 5, comment: "Très professionnel, problème résolu en 30 min.",                  service: "Installation robinet",      date: "Il y a 4 jours" },
  { id: "r3", author: "Léa Kouamé",    rating: 4, comment: "Bon service, juste un petit retard mais excellent travail.",       service: "Débouchage évier",          date: "La semaine dernière" },
  { id: "r4", author: "Paul Nlend",    rating: 5, comment: "Devis clair et prix honnête. Awa connaît son métier.",             service: "Devis salle de bain",       date: "Il y a 10 jours" },
  { id: "r5", author: "Sylvie Manga",  rating: 5, comment: "Toujours disponible et de bons conseils, merci !",                  service: "Réparation chasse d'eau",   date: "Il y a 2 semaines" },
];

function ProviderReviews() {
  const avg = reviews.reduce((s, r) => s + r.rating, 0) / reviews.length;
  return (
    <div className="space-y-6">
      <div>
        <h1 className="font-display text-2xl font-bold">Mes avis</h1>
        <p className="text-muted-foreground text-sm">L'opinion de vos clients construit votre réputation.</p>
      </div>

      <div className="bg-card border border-border rounded-2xl p-6 flex flex-col md:flex-row items-center gap-6">
        <div className="text-center">
          <div className="font-display text-5xl font-bold text-primary">{avg.toFixed(1)}</div>
          <div className="flex gap-0.5 justify-center mt-2">
            {Array.from({ length: 5 }).map((_, i) => (
              <Star key={i} className={`h-4 w-4 ${i < Math.round(avg) ? "fill-gold text-gold" : "text-muted-foreground/30"}`} />
            ))}
          </div>
          <div className="text-xs text-muted-foreground mt-1">{reviews.length} avis</div>
        </div>
        <div className="flex-1 w-full space-y-1.5">
          {[5, 4, 3, 2, 1].map((n) => {
            const count = reviews.filter((r) => r.rating === n).length;
            const pct = (count / reviews.length) * 100;
            return (
              <div key={n} className="flex items-center gap-2 text-sm">
                <span className="w-6 text-muted-foreground">{n}★</span>
                <div className="flex-1 h-2 rounded-full bg-muted overflow-hidden">
                  <div className="h-full bg-gold" style={{ width: `${pct}%` }} />
                </div>
                <span className="w-8 text-right text-muted-foreground text-xs">{count}</span>
              </div>
            );
          })}
        </div>
      </div>

      <div className="space-y-3">
        {reviews.map((r) => (
          <div key={r.id} className="bg-card border border-border rounded-2xl p-5">
            <div className="flex items-center justify-between">
              <div>
                <div className="font-semibold">{r.author}</div>
                <div className="text-xs text-muted-foreground">{r.service} · {r.date}</div>
              </div>
              <div className="flex gap-0.5">
                {Array.from({ length: 5 }).map((_, i) => (
                  <Star key={i} className={`h-4 w-4 ${i < r.rating ? "fill-gold text-gold" : "text-muted-foreground/30"}`} />
                ))}
              </div>
            </div>
            <p className="text-sm text-muted-foreground mt-3">{r.comment}</p>
          </div>
        ))}
      </div>
    </div>
  );
}
