import { createFileRoute, Link } from "@tanstack/react-router";
import { Calendar, Clock, CheckCircle2, XCircle, Loader2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { bookings, formatXAF } from "@/lib/mock-data";
import { RequireAuth } from "@/components/RequireAuth";

export const Route = createFileRoute("/_client/bookings")({
  head: () => ({ meta: [{ title: "Mes réservations — SERVLINK" }] }),
  component: () => <RequireAuth roles={["client"]}><BookingsPage /></RequireAuth>,
});

const statusMap = {
  pending: { label: "En attente", icon: Loader2, color: "bg-warning/15 text-warning" },
  confirmed: { label: "Confirmée", icon: CheckCircle2, color: "bg-primary/15 text-primary" },
  completed: { label: "Terminée", icon: CheckCircle2, color: "bg-success/15 text-success" },
  cancelled: { label: "Annulée", icon: XCircle, color: "bg-destructive/15 text-destructive" },
} as const;

function BookingsPage() {
  return (
    <div className="container mx-auto px-4 py-8">
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="font-display text-3xl font-bold">Mes réservations</h1>
          <p className="text-muted-foreground mt-1">Suivez vos prestations en cours et passées.</p>
        </div>
        <Link to="/search"><Button>Nouvelle réservation</Button></Link>
      </div>

      <div className="flex gap-2 mb-5 overflow-x-auto">
        {["Toutes", "En attente", "Confirmées", "Terminées", "Annulées"].map((t, i) => (
          <button key={t} className={`px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap ${i === 0 ? "bg-primary text-primary-foreground" : "bg-muted hover:bg-accent"}`}>{t}</button>
        ))}
      </div>

      <div className="space-y-3">
        {bookings.map((b) => {
          const s = statusMap[b.status];
          const Icon = s.icon;
          return (
            <div key={b.id} className="bg-card border border-border rounded-2xl p-5 flex flex-col md:flex-row md:items-center gap-4">
              <div className="h-14 w-14 rounded-xl bg-accent flex items-center justify-center shrink-0">
                <Calendar className="h-6 w-6 text-primary" />
              </div>
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2 flex-wrap">
                  <h3 className="font-display font-semibold">{b.service}</h3>
                  <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold ${s.color}`}>
                    <Icon className="h-3 w-3" /> {s.label}
                  </span>
                </div>
                <div className="text-sm text-muted-foreground mt-1">avec <span className="font-medium text-foreground">{b.provider}</span> · {b.id}</div>
                <div className="text-xs text-muted-foreground mt-1 flex items-center gap-1"><Clock className="h-3 w-3" /> {b.date}</div>
              </div>
              <div className="text-right">
                <div className="font-display font-bold text-lg">{formatXAF(b.amount)}</div>
                <div className="flex gap-2 mt-2 justify-end">
                  <Button size="sm" variant="outline">Détails</Button>
                  {b.status === "completed" && <Button size="sm">Noter</Button>}
                  {b.status === "confirmed" && <Button size="sm" variant="outline">Message</Button>}
                </div>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
}
