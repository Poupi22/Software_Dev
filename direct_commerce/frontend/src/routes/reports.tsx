import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { FileText, Download, Calendar, Filter } from "lucide-react";
import {
  LineChart, Line, ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend,
} from "recharts";
import { PageShell, tooltipStyle } from "@/components/page-shell";
import { ChartCard } from "@/components/chart-card";
import { KpiCard } from "@/components/kpi-card";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { reportsList, kpiHistory } from "@/lib/page-data";

export const Route = createFileRoute("/reports")({ beforeLoad: ({ location }) => requireAuth(location), component: ReportsPage });

function ReportsPage() {
  return (
    <PageShell
      title="Rapports"
      subtitle="Exports automatisés · KPIs historiques · synthèses périodiques"
      icon={FileText}
    >
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4">
        <KpiCard label="Rapports générés" value="142" change={12} icon={FileText} accent="primary" />
        <KpiCard label="Ce mois" value="18" change={20} icon={Calendar} accent="success" />
        <KpiCard label="Téléchargements" value="487" change={34} icon={Download} accent="info" />
        <KpiCard label="Catégories" value="6" change={0} icon={Filter} accent="warning" />
      </div>

      <ChartCard title="Évolution des KPIs clés (9 mois)" description="Mortalité · FCR · Poids moyen">
        <ResponsiveContainer width="100%" height={320}>
          <LineChart data={kpiHistory} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
            <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
            <YAxis yAxisId="l" stroke="var(--color-muted-foreground)" fontSize={11} />
            <YAxis yAxisId="r" orientation="right" stroke="var(--color-muted-foreground)" fontSize={11} />
            <Tooltip contentStyle={tooltipStyle} />
            <Legend wrapperStyle={{ fontSize: 12 }} />
            <Line yAxisId="l" type="monotone" dataKey="mortality" stroke="var(--color-destructive)" strokeWidth={2.5} name="Mortalité %" dot={{ r: 3 }} />
            <Line yAxisId="l" type="monotone" dataKey="fcr" stroke="var(--color-chart-1)" strokeWidth={2.5} name="FCR" dot={{ r: 3 }} />
            <Line yAxisId="r" type="monotone" dataKey="weight" stroke="var(--color-chart-2)" strokeWidth={2.5} name="Poids (kg)" dot={{ r: 3 }} />
          </LineChart>
        </ResponsiveContainer>
      </ChartCard>

      <Card className="p-5 gradient-card">
        <div className="flex items-center justify-between mb-4">
          <h3 className="text-sm font-semibold">Bibliothèque de rapports</h3>
          <Button size="sm" variant="outline">
            <Filter className="h-4 w-4 mr-1" /> Filtrer
          </Button>
        </div>
        <div className="space-y-2">
          {reportsList.map((r) => (
            <div key={r.id} className="flex items-center justify-between gap-3 p-3 rounded-lg border border-border bg-card hover:shadow-elegant transition-all">
              <div className="flex items-center gap-3 min-w-0">
                <div className="h-10 w-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                  <FileText className="h-5 w-5" />
                </div>
                <div className="min-w-0">
                  <p className="font-medium text-sm truncate">{r.title}</p>
                  <p className="text-xs text-muted-foreground">{r.id} · {r.date} · {r.size}</p>
                </div>
              </div>
              <div className="flex items-center gap-2 shrink-0">
                <Badge variant="secondary" className="hidden sm:inline-flex">{r.type}</Badge>
                <Button size="sm" variant="ghost">
                  <Download className="h-4 w-4" />
                </Button>
              </div>
            </div>
          ))}
        </div>
      </Card>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
        {[
          { t: "Rapport quotidien", d: "Synthèse journalière auto. à 18h00", c: "primary" },
          { t: "Bilan mensuel", d: "Performance & finance — début de mois", c: "success" },
          { t: "Rapport investisseurs", d: "Trimestriel — POESAM 2026", c: "info" },
        ].map((i) => (
          <Card key={i.t} className="p-5 gradient-card hover:shadow-elegant transition-all cursor-pointer">
            <div className={`h-10 w-10 rounded-lg flex items-center justify-center mb-3 ${
              i.c === "primary" ? "bg-primary/10 text-primary" :
              i.c === "success" ? "bg-success/10 text-success" : "bg-info/10 text-info"
            }`}>
              <FileText className="h-5 w-5" />
            </div>
            <p className="font-semibold text-sm">{i.t}</p>
            <p className="text-xs text-muted-foreground mt-1">{i.d}</p>
          </Card>
        ))}
      </div>
    </PageShell>
  );
}
