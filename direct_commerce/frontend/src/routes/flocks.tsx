import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { Bird, Search, Plus } from "lucide-react";
import {
  AreaChart, Area, BarChart, Bar, PieChart, Pie, Cell,
  ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend, Line, ComposedChart,
} from "recharts";
import { PageShell, tooltipStyle, CHART_COLORS } from "@/components/page-shell";
import { ChartCard } from "@/components/chart-card";
import { KpiCard } from "@/components/kpi-card";
import { Card } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import { Bird as BirdIcon, Activity, Scale, HeartPulse } from "lucide-react";
import { flocksList, flockGrowth, flockBreedSplit } from "@/lib/page-data";
import { buildingProduction } from "@/lib/mock-data";

export const Route = createFileRoute("/flocks")({ beforeLoad: ({ location }) => requireAuth(location), component: FlocksPage });

function FlocksPage() {
  return (
    <PageShell
      title="Bandes"
      subtitle="Gestion en temps réel des 24 bandes actives — IA & IoT"
      icon={Bird}
      actions={
        <Button variant="secondary" size="sm">
          <Plus className="h-4 w-4 mr-1" /> Nouvelle bande
        </Button>
      }
    >
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4">
        <KpiCard label="Bandes actives" value="24" change={8} icon={BirdIcon} accent="primary" />
        <KpiCard label="Volailles" value="42 580" change={12} icon={Activity} accent="success" />
        <KpiCard label="Poids moyen" value="1.84 kg" change={3.1} icon={Scale} accent="info" />
        <KpiCard label="Mortalité" value="2.8%" change={-1.4} icon={HeartPulse} accent="warning" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <ChartCard title="Courbe de croissance" description="Réel vs cible — 42 jours" className="lg:col-span-2">
          <ResponsiveContainer width="100%" height={300}>
            <AreaChart data={flockGrowth} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
              <defs>
                <linearGradient id="gActual" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="var(--color-chart-1)" stopOpacity={0.5} />
                  <stop offset="100%" stopColor="var(--color-chart-1)" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
              <XAxis dataKey="day" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis stroke="var(--color-muted-foreground)" fontSize={11} unit="kg" />
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Area type="monotone" dataKey="actual" stroke="var(--color-chart-1)" fill="url(#gActual)" strokeWidth={2.5} name="Réel" />
              <Area type="monotone" dataKey="target" stroke="var(--color-chart-2)" fill="transparent" strokeWidth={2} strokeDasharray="5 5" name="Cible" />
            </AreaChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Répartition par souche" description="% des effectifs">
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie data={flockBreedSplit} dataKey="value" nameKey="name" innerRadius={50} outerRadius={90} paddingAngle={2}>
                {flockBreedSplit.map((_, i) => (<Cell key={i} fill={CHART_COLORS[i]} />))}
              </Pie>
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 11 }} />
            </PieChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <ChartCard title="Production hebdomadaire par bâtiment" description="Poulets vs œufs">
        <ResponsiveContainer width="100%" height={280}>
          <ComposedChart data={buildingProduction} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
            <XAxis dataKey="b" stroke="var(--color-muted-foreground)" fontSize={11} />
            <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
            <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
            <Legend wrapperStyle={{ fontSize: 12 }} />
            <Bar dataKey="broilers" fill="var(--color-chart-1)" radius={[6, 6, 0, 0]} name="Poulets" />
            <Bar dataKey="eggs" fill="var(--color-chart-2)" radius={[6, 6, 0, 0]} name="Œufs" />
            <Line dataKey="broilers" stroke="var(--color-chart-5)" strokeWidth={2} dot={false} name="Tendance" />
          </ComposedChart>
        </ResponsiveContainer>
      </ChartCard>

      <Card className="p-5 gradient-card">
        <div className="flex items-center justify-between mb-4 gap-3">
          <h3 className="text-sm font-semibold">Liste des bandes</h3>
          <div className="relative w-64 max-w-full">
            <Search className="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
            <Input placeholder="Rechercher..." className="pl-9 h-9" />
          </div>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="text-left text-xs text-muted-foreground border-b border-border">
                <th className="py-2 px-2">ID</th>
                <th className="py-2 px-2">Bâtiment</th>
                <th className="py-2 px-2">Souche</th>
                <th className="py-2 px-2">Âge (j)</th>
                <th className="py-2 px-2">Effectif</th>
                <th className="py-2 px-2">Poids (kg)</th>
                <th className="py-2 px-2">Mortalité</th>
                <th className="py-2 px-2">Statut</th>
              </tr>
            </thead>
            <tbody>
              {flocksList.map((f) => (
                <tr key={f.id} className="border-b border-border/50 hover:bg-muted/40">
                  <td className="py-2.5 px-2 font-medium">{f.id}</td>
                  <td className="py-2.5 px-2">{f.building}</td>
                  <td className="py-2.5 px-2">{f.breed}</td>
                  <td className="py-2.5 px-2">{f.age}</td>
                  <td className="py-2.5 px-2">{f.count.toLocaleString("fr-FR")}</td>
                  <td className="py-2.5 px-2">{f.weight}</td>
                  <td className="py-2.5 px-2">{f.mortality}%</td>
                  <td className="py-2.5 px-2">
                    <Badge className={
                      f.status === "ok" ? "bg-success/15 text-success border-0" :
                      f.status === "warn" ? "bg-warning/20 text-warning border-0" :
                      "bg-destructive/15 text-destructive border-0"
                    }>
                      {f.status === "ok" ? "Sain" : f.status === "warn" ? "Surveillance" : "Alerte"}
                    </Badge>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </Card>
    </PageShell>
  );
}
