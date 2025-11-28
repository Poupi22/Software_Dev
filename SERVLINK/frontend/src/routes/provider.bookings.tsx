import { createFileRoute } from "@tanstack/react-router";
import { CheckCircle2, Clock, XCircle, Loader2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { formatXAF } from "@/lib/mock-data";

export const Route = createFileRoute("/provider/bookings")({
  component: ProviderBookings,
});

const all = [
  { id: "BK-2089", client: "Claire Dipita", service: "Réparation fuite cuisine", date: "2026-05-24 14:00", amount: 15000, status: "Confirmée" },
  { id: "BK-2088", client: "Marc Tchana",   service: "Installation robinet",     date: "2026-05-25 09:00", amount: 8000,  status: "En attente" },
  { id: "BK-2086", client: "Léa Kouamé",    service: "Débouchage évier",          date: "2026-05-26 11:00", amount: 12000, status: "Confirmée" },
  { id: "BK-2084", client: "Paul Nlend",    service: "Devis salle de bain",       date: "2026-05-28 16:00", amount: 5000,  status: "En attente" },
  { id: "BK-2080", client: "Sylvie Manga",  service: "Réparation chasse d'eau",   date: "2026-05-20 10:00", amount: 9000,  status: "Terminée" },
  { id: "BK-2078", client: "Aïcha Bello",   service: "Remplacement chauffe-eau",  date: "2026-05-15 08:00", amount: 45000, status: "Terminée" },
  { id: "BK-2075", client: "Yves Talla",    service: "Diagnostic fuite",          date: "2026-05-10 13:00", amount: 6000,  status: "Annulée"  },
];

const icons = { "Confirmée": CheckCircle2, "En attente": Loader2, "Terminée": CheckCircle2, "Annulée": XCircle } as const;

function ProviderBookings() {
  return (
    <div className="space-y-6">
      <div>
        <h1 className="font-display text-2xl font-bold">Mes réservations</h1>
        <p className="text-muted-foreground text-sm">Acceptez, refusez ou suivez vos interventions.</p>
      </div>
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
        {[
          { l: "À venir", v: 4, c: "text-primary bg-primary/10" },
          { l: "En attente", v: 2, c: "text-warning bg-warning/10" },
          { l: "Terminées", v: 38, c: "text-success bg-success/10" },
          { l: "Annulées", v: 3, c: "text-destructive bg-destructive/10" },
        ].map((s) => (
          <div key={s.l} className="bg-card border border-border rounded-xl p-4">
            <div className={`text-xs font-semibold inline-block px-2 py-0.5 rounded-full ${s.c}`}>{s.l}</div>
            <div className="font-display text-2xl font-bold mt-2">{s.v}</div>
          </div>
        ))}
      </div>
      <div className="bg-card border border-border rounded-2xl divide-y divide-border">
        {all.map((b) => {
          const Icon = icons[b.status as keyof typeof icons];
          return (
            <div key={b.id} className="p-4 flex flex-col md:flex-row md:items-center gap-3">
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2">
                  <span className="font-mono text-[11px] text-muted-foreground">{b.id}</span>
                  <span className="text-sm font-semibold truncate">{b.service}</span>
                </div>
                <div className="text-xs text-muted-foreground mt-1">
                  Client : <span className="font-medium text-foreground">{b.client}</span> · <Clock className="inline h-3 w-3" /> {b.date}
                </div>
              </div>
              <div className="font-semibold text-primary">{formatXAF(b.amount)}</div>
              <span className={`inline-flex items-center gap-1 text-xs font-semibold px-2 py-1 rounded-full ${
                b.status === "Confirmée" ? "bg-primary/15 text-primary" :
                b.status === "Terminée"  ? "bg-success/15 text-success" :
                b.status === "Annulée"   ? "bg-destructive/15 text-destructive" :
                                            "bg-warning/15 text-warning"
              }`}>
                <Icon className="h-3 w-3" /> {b.status}
              </span>
              <div className="flex gap-2">
                {b.status === "En attente" && (
                  <>
                    <Button size="sm" variant="outline" className="text-destructive border-destructive/30">Refuser</Button>
                    <Button size="sm">Accepter</Button>
                  </>
                )}
                {b.status === "Confirmée" && <Button size="sm" variant="outline">Détails</Button>}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
