import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { Store, TrendingUp, ShoppingCart, Truck } from "lucide-react";
import {
  LineChart, Line, BarChart, Bar, PieChart, Pie, Cell, ComposedChart, Area,
  ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend,
} from "recharts";
import { PageShell, tooltipStyle, CHART_COLORS } from "@/components/page-shell";
import { ChartCard } from "@/components/chart-card";
import { KpiCard } from "@/components/kpi-card";
import { Card } from "@/components/ui/card";
import { priceVsCompetitors, channelSales, demandForecast } from "@/lib/page-data";

export const Route = createFileRoute("/market")({ beforeLoad: ({ location }) => requireAuth(location), component: MarketPage });

function MarketPage() {
  return (
    <PageShell
      title="Marché"
      subtitle="Veille concurrentielle · Prévisions · Canaux de distribution"
      icon={Store}
    >
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4">
        <KpiCard label="Prix ECOTEC" value="3 040 F" change={6.7} icon={TrendingUp} accent="primary" />
        <KpiCard label="Part de marché" value="14.2%" change={3.1} icon={ShoppingCart} accent="success" />
        <KpiCard label="Commandes/sem" value="312" change={11} icon={Truck} accent="info" />
        <KpiCard label="Premium gap" value="+9%" change={2.4} icon={Store} accent="warning" />
      </div>

      <ChartCard title="Prix vs concurrents (FCFA/kg)" description="Comparatif 8 dernières semaines">
        <ResponsiveContainer width="100%" height={320}>
          <LineChart data={priceVsCompetitors} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
            <XAxis dataKey="w" stroke="var(--color-muted-foreground)" fontSize={11} />
            <YAxis stroke="var(--color-muted-foreground)" fontSize={11} domain={[2500, 3100]} />
            <Tooltip contentStyle={tooltipStyle} />
            <Legend wrapperStyle={{ fontSize: 12 }} />
            <Line type="monotone" dataKey="ecotec" stroke="var(--color-chart-1)" strokeWidth={3} name="ECOTEC" dot={{ r: 4 }} />
            <Line type="monotone" dataKey="akwa" stroke="var(--color-chart-2)" strokeWidth={2} name="Marché Akwa" dot={false} />
            <Line type="monotone" dataKey="ndogbong" stroke="var(--color-chart-3)" strokeWidth={2} name="Ndogbong" dot={false} />
            <Line type="monotone" dataKey="mokolo" stroke="var(--color-chart-5)" strokeWidth={2} name="Mokolo" dot={false} />
          </LineChart>
        </ResponsiveContainer>
      </ChartCard>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <ChartCard title="Prévision demande vs offre" description="12 mois — unités" className="lg:col-span-2">
          <ResponsiveContainer width="100%" height={300}>
            <ComposedChart data={demandForecast} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
              <defs>
                <linearGradient id="dG" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="var(--color-chart-1)" stopOpacity={0.4} />
                  <stop offset="100%" stopColor="var(--color-chart-1)" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
              <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Area type="monotone" dataKey="demand" stroke="var(--color-chart-1)" fill="url(#dG)" strokeWidth={2.5} name="Demande" />
              <Bar dataKey="supply" fill="var(--color-chart-2)" radius={[6, 6, 0, 0]} name="Offre" />
            </ComposedChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Répartition canaux" description="% des ventes">
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie data={channelSales} dataKey="value" nameKey="channel" innerRadius={55} outerRadius={95} paddingAngle={2}>
                {channelSales.map((_, i) => (<Cell key={i} fill={CHART_COLORS[i]} />))}
              </Pie>
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 11 }} />
            </PieChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <Card className="p-5 gradient-card">
        <h3 className="text-sm font-semibold mb-4">Insights marché</h3>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
          {[
            { t: "Premium maintenu", d: "Prix ECOTEC 9% au-dessus du marché — qualité IA reconnue.", c: "success" },
            { t: "Demande croissante", d: "Restaurants haut de gamme : +18% commandes ce trimestre.", c: "info" },
            { t: "Risque saisonnier", d: "Septembre-Décembre : tension sur l'offre, prévoir +10% capacité.", c: "warning" },
          ].map((i) => (
            <div key={i.t} className={`p-4 rounded-xl border-l-4 bg-card border ${
              i.c === "success" ? "border-l-success" : i.c === "info" ? "border-l-info" : "border-l-warning"
            }`}>
              <p className="font-semibold text-sm">{i.t}</p>
              <p className="text-xs text-muted-foreground mt-1">{i.d}</p>
            </div>
          ))}
        </div>
      </Card>
    </PageShell>
  );
}
