import { createFileRoute } from "@tanstack/react-router";
import { AlertTriangle, MessageCircle, FileText } from "lucide-react";
import { Button } from "@/components/ui/button";
import { adminDisputes } from "@/lib/mock-data";

export const Route = createFileRoute("/admin/disputes")({
  component: DisputesPage,
});

function DisputesPage() {
  return (
    <div className="space-y-5">
      <div>
        <h1 className="font-display text-2xl font-bold">Litiges</h1>
        <p className="text-sm text-muted-foreground">Arbitrage et résolution des conflits entre clients et prestataires.</p>
      </div>

      <div className="grid grid-cols-3 gap-4">
        {[
          { l: "Ouverts", v: adminDisputes.filter(d => d.status === "Ouvert").length, c: "text-destructive" },
          { l: "En arbitrage", v: adminDisputes.filter(d => d.status === "En arbitrage").length, c: "text-warning" },
          { l: "Résolus (mois)", v: 24, c: "text-success" },
        ].map((k) => (
          <div key={k.l} className="bg-card border border-border rounded-xl p-4">
            <div className="text-xs text-muted-foreground">{k.l}</div>
            <div className={`font-display text-2xl font-bold mt-1 ${k.c}`}>{k.v}</div>
          </div>
        ))}
      </div>

      <div className="space-y-3">
        {adminDisputes.map((d) => (
          <div key={d.id} className="bg-card border border-border rounded-xl p-5">
            <div className="flex items-start gap-4">
              <div className={`h-10 w-10 rounded-lg flex items-center justify-center shrink-0 ${d.priority === "Haute" ? "bg-destructive/15 text-destructive" : d.priority === "Moyenne" ? "bg-warning/15 text-warning" : "bg-muted text-muted-foreground"}`}>
                <AlertTriangle className="h-5 w-5" />
              </div>
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2 flex-wrap">
                  <span className="font-mono text-xs text-muted-foreground">{d.id}</span>
                  <span className={`text-[10px] px-2 py-0.5 rounded-full font-semibold ${d.status === "Ouvert" ? "bg-destructive/15 text-destructive" : d.status === "Résolu" ? "bg-success/15 text-success" : "bg-warning/15 text-warning"}`}>{d.status}</span>
                  <span className="text-[10px] px-2 py-0.5 rounded-full bg-muted font-semibold">Priorité : {d.priority}</span>
                </div>
                <h3 className="font-display font-semibold mt-1">{d.reason}</h3>
                <p className="text-sm text-muted-foreground mt-1">
                  Client <span className="text-foreground font-medium">{d.client}</span> ↔ Prestataire <span className="text-foreground font-medium">{d.provider}</span>
                </p>
                <p className="text-xs text-muted-foreground mt-1">Ouvert le {d.opened}</p>
              </div>
              <div className="flex flex-col gap-2 shrink-0">
                <Button size="sm"><MessageCircle className="h-3.5 w-3.5 mr-1" /> Contacter</Button>
                <Button size="sm" variant="outline"><FileText className="h-3.5 w-3.5 mr-1" /> Rapport</Button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
