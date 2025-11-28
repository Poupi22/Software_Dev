import { createFileRoute } from "@tanstack/react-router";
import { Download, Search, ArrowUpRight, ArrowDownRight } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { adminTransactions, formatXAF } from "@/lib/mock-data";

export const Route = createFileRoute("/admin/transactions")({
  component: TransactionsPage,
});

function TransactionsPage() {
  const total = adminTransactions.reduce((s, t) => s + t.amount, 0);
  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between flex-wrap gap-3">
        <div>
          <h1 className="font-display text-2xl font-bold">Transactions</h1>
          <p className="text-sm text-muted-foreground">Suivi des paiements Mobile Money et carte bancaire.</p>
        </div>
        <Button variant="outline" size="sm"><Download className="h-4 w-4 mr-2" /> Exporter CSV</Button>
      </div>

      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        {[
          { l: "Volume total", v: formatXAF(total), d: "+12%", up: true },
          { l: "Commission SERVLINK", v: formatXAF(Math.round(total * 0.1)), d: "+10%", up: true },
          { l: "Remboursements", v: formatXAF(15000), d: "-3%", up: false },
          { l: "Taux de succès", v: "98.4%", d: "+0.2pt", up: true },
        ].map((k) => (
          <div key={k.l} className="bg-card border border-border rounded-xl p-4">
            <div className="text-xs text-muted-foreground">{k.l}</div>
            <div className="font-display text-xl font-bold mt-1">{k.v}</div>
            <div className={`text-xs flex items-center gap-0.5 mt-1 font-semibold ${k.up ? "text-success" : "text-destructive"}`}>
              {k.up ? <ArrowUpRight className="h-3 w-3" /> : <ArrowDownRight className="h-3 w-3" />} {k.d}
            </div>
          </div>
        ))}
      </div>

      <div className="bg-card border border-border rounded-xl">
        <div className="p-4 border-b border-border flex flex-wrap items-center gap-2">
          <div className="relative flex-1 min-w-[200px]">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input placeholder="Rechercher par ID, client…" className="pl-9 h-9" />
          </div>
          <select className="h-9 px-3 rounded-md border border-border bg-background text-sm">
            <option>Tous les moyens</option><option>Mobile Money</option><option>Carte</option>
          </select>
          <select className="h-9 px-3 rounded-md border border-border bg-background text-sm">
            <option>Tous les statuts</option><option>Réussie</option><option>En attente</option><option>Remboursée</option>
          </select>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="text-xs text-muted-foreground uppercase border-b border-border bg-muted/40">
              <tr><th className="text-left py-3 px-4">ID</th><th className="text-left py-3 px-4">Date</th><th className="text-left py-3 px-4">Client</th><th className="text-left py-3 px-4">Prestataire</th><th className="text-left py-3 px-4">Moyen</th><th className="text-right py-3 px-4">Montant</th><th className="text-left py-3 px-4">Statut</th></tr>
            </thead>
            <tbody>
              {adminTransactions.map((t) => (
                <tr key={t.id} className="border-b border-border last:border-0 hover:bg-muted/40">
                  <td className="py-3 px-4 font-mono text-xs">{t.id}</td>
                  <td className="py-3 px-4 text-muted-foreground">{t.date}</td>
                  <td className="py-3 px-4">{t.client}</td>
                  <td className="py-3 px-4 text-muted-foreground">{t.provider}</td>
                  <td className="py-3 px-4"><span className="text-xs px-2 py-0.5 rounded-md bg-muted">{t.method}</span></td>
                  <td className="py-3 px-4 text-right font-semibold">{formatXAF(t.amount)}</td>
                  <td className="py-3 px-4"><span className={`text-[10px] px-2 py-0.5 rounded-full font-semibold ${t.status === "Réussie" ? "bg-success/15 text-success" : t.status === "En attente" ? "bg-warning/15 text-warning" : "bg-destructive/15 text-destructive"}`}>{t.status}</span></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
