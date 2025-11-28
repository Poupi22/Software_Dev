import { createFileRoute } from "@tanstack/react-router";
import { Star, Check, Trash2, Mail, Flag } from "lucide-react";
import { Button } from "@/components/ui/button";
import { adminFlaggedReviews } from "@/lib/mock-data";

export const Route = createFileRoute("/admin/reviews")({
  component: ReviewsPage,
});

function ReviewsPage() {
  return (
    <div className="space-y-5">
      <div>
        <h1 className="font-display text-2xl font-bold">Avis signalés</h1>
        <p className="text-sm text-muted-foreground">File de modération des avis signalés par les utilisateurs.</p>
      </div>

      <div className="space-y-3">
        {adminFlaggedReviews.map((r) => (
          <div key={r.id} className="bg-card border border-border rounded-xl p-5">
            <div className="flex items-start justify-between gap-4 flex-wrap">
              <div className="flex-1 min-w-[260px]">
                <div className="flex items-center gap-2 flex-wrap">
                  <span className="font-mono text-xs text-muted-foreground">{r.id}</span>
                  <span className="text-[10px] px-2 py-0.5 rounded-full bg-destructive/15 text-destructive font-semibold inline-flex items-center gap-1"><Flag className="h-3 w-3" /> {r.flagged}</span>
                  <div className="flex gap-0.5">
                    {Array.from({ length: 5 }).map((_, i) => (
                      <Star key={i} className={`h-3.5 w-3.5 ${i < r.rating ? "fill-gold text-gold" : "text-muted"}`} />
                    ))}
                  </div>
                </div>
                <p className="text-sm mt-2 italic">"{r.comment}"</p>
                <div className="text-xs text-muted-foreground mt-2">Par {r.author} · concerne {r.provider} · {r.date}</div>
              </div>
              <div className="flex gap-2">
                <Button size="sm"><Check className="h-3.5 w-3.5 mr-1" /> Approuver</Button>
                <Button size="sm" variant="destructive"><Trash2 className="h-3.5 w-3.5 mr-1" /> Supprimer</Button>
                <Button size="sm" variant="outline"><Mail className="h-3.5 w-3.5 mr-1" /> Contacter</Button>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="bg-card border border-border rounded-xl p-5">
        <h2 className="font-display font-bold mb-3">Historique des décisions</h2>
        <ul className="text-sm divide-y divide-border">
          {[
            { d: "20 mai 2026", a: "Avis AV-72 supprimé", by: "Admin" },
            { d: "18 mai 2026", a: "Avis AV-69 approuvé", by: "Admin" },
            { d: "15 mai 2026", a: "Auteur AV-65 averti", by: "Admin" },
          ].map((h, i) => (
            <li key={i} className="py-2 flex items-center justify-between">
              <span>{h.a}</span>
              <span className="text-xs text-muted-foreground">{h.d} · par {h.by}</span>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}
