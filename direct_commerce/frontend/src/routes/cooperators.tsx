import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { Users, TrendingUp, Award, MapPin } from "lucide-react";
import {
  AreaChart, Area, BarChart, Bar, ComposedChart, Line, PieChart, Pie, Cell,
  ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend, LabelList,
} from "recharts";
import { PageShell, tooltipStyle, CHART_COLORS } from "@/components/page-shell";
import { ChartCard } from "@/components/chart-card";
import { KpiCard } from "@/components/kpi-card";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { coopGrowth, coopRegions, coopList } from "@/lib/page-data";
import { cooperatorTiers, topCooperators } from "@/lib/mock-data";
import { useTranslation } from "react-i18next";

export const Route = createFileRoute("/cooperators")({ beforeLoad: ({ location }) => requireAuth(location), component: CoopPage });

function CoopPage() {
  const { t } = useTranslation();
  return (
    <PageShell
      title="Coopérants"
      subtitle="Réseau de 52 coopérants — formation, suivi & rémunération"
      icon={Users}
    >
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4">
        <KpiCard label="Coopérants" value="52" change={15} icon={Users} accent="primary" />
        <KpiCard label="CA généré" value="38.4 M" change={22} icon={TrendingUp} accent="success" />
        <KpiCard label="Niveau Or+" value="10" change={25} icon={Award} accent="warning" />
        <KpiCard label="Régions" value="5" change={0} icon={MapPin} accent="info" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <ChartCard title="Croissance du réseau" description="Coopérants vs CA mensuel" className="lg:col-span-2">
          <ResponsiveContainer width="100%" height={300}>
            <ComposedChart data={coopGrowth} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
              <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis yAxisId="l" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis yAxisId="r" orientation="right" stroke="var(--color-muted-foreground)" fontSize={11} unit="M" />
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Bar yAxisId="l" dataKey="count" fill="var(--color-chart-1)" radius={[6, 6, 0, 0]} name="Coopérants" />
              <Line yAxisId="r" type="monotone" dataKey="revenue" stroke="var(--color-chart-2)" strokeWidth={2.5} name="CA (M FCFA)" dot={{ r: 4 }} />
            </ComposedChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Répartition par niveau">
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie data={cooperatorTiers(t)} dataKey="count" nameKey="tier" innerRadius={50} outerRadius={90} paddingAngle={3}>
                {cooperatorTiers(t).map((_, i) => (<Cell key={i} fill={CHART_COLORS[i]} />))}
              </Pie>
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 11 }} />
            </PieChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <ChartCard title="Distribution par région" description="Coopérants & CA généré">
          <ResponsiveContainer width="100%" height={280}>
            <BarChart data={coopRegions} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
              <XAxis dataKey="region" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis yAxisId="l" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis yAxisId="r" orientation="right" stroke="var(--color-muted-foreground)" fontSize={11} unit="M" />
              <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Bar yAxisId="l" dataKey="count" fill="var(--color-chart-1)" radius={[6, 6, 0, 0]} name="Coopérants" />
              <Bar yAxisId="r" dataKey="revenue" fill="var(--color-chart-2)" radius={[6, 6, 0, 0]} name="CA (M)" />
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Top performers" description="CA — M FCFA">
          <ResponsiveContainer width="100%" height={280}>
            <BarChart data={topCooperators} layout="vertical" margin={{ top: 5, right: 30, left: 10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" horizontal={false} />
              <XAxis type="number" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis type="category" dataKey="name" stroke="var(--color-muted-foreground)" fontSize={11} width={75} />
              <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
              <Bar dataKey="revenue" fill="var(--color-chart-2)" radius={[0, 6, 6, 0]}>
                <LabelList dataKey="revenue" position="right" fontSize={10} fill="var(--color-muted-foreground)" formatter={(v: number) => `${v}M`} />
              </Bar>
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <Card className="p-5 gradient-card">
        <h3 className="text-sm font-semibold mb-4">Annuaire des coopérants</h3>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="text-left text-xs text-muted-foreground border-b border-border">
                <th className="py-2 px-2">Nom</th>
                <th className="py-2 px-2">Région</th>
                <th className="py-2 px-2">Niveau</th>
                <th className="py-2 px-2">Bandes</th>
                <th className="py-2 px-2">CA (M)</th>
                <th className="py-2 px-2">Score</th>
              </tr>
            </thead>
            <tbody>
              {coopList.map((c) => (
                <tr key={c.name} className="border-b border-border/50 hover:bg-muted/40">
                  <td className="py-2.5 px-2 font-medium">{c.name}</td>
                  <td className="py-2.5 px-2">{c.region}</td>
                  <td className="py-2.5 px-2">
                    <Badge className={
                      c.tier === "Or" ? "bg-warning/20 text-warning border-0" :
                      c.tier === "Argent" ? "bg-info/15 text-info border-0" :
                      "bg-muted text-muted-foreground border-0"
                    }>{c.tier}</Badge>
                  </td>
                  <td className="py-2.5 px-2">{c.flocks}</td>
                  <td className="py-2.5 px-2">{c.revenue}</td>
                  <td className="py-2.5 px-2">
                    <div className="flex items-center gap-2">
                      <div className="h-1.5 w-20 bg-muted rounded-full overflow-hidden">
                        <div className="h-full bg-primary" style={{ width: `${c.score}%` }} />
                      </div>
                      <span className="text-xs font-medium">{c.score}</span>
                    </div>
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
