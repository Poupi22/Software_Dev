import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { Recycle, Thermometer, Droplets, Sprout } from "lucide-react";
import {
  LineChart, Line, BarChart, Bar, RadarChart, Radar, PolarGrid, PolarAngleAxis, PolarRadiusAxis,
  ComposedChart, Area, ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend,
} from "recharts";
import { PageShell, tooltipStyle } from "@/components/page-shell";
import { ChartCard } from "@/components/chart-card";
import { KpiCard } from "@/components/kpi-card";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Progress } from "@/components/ui/progress";
import { composterStatus, compostMonthly, compostNPK } from "@/lib/page-data";
import { compostCycle } from "@/lib/mock-data";

export const Route = createFileRoute("/compost")({ beforeLoad: ({ location }) => requireAuth(location), component: CompostPage });

function CompostPage() {
  return (
    <PageShell
      title="Compost IoT"
      subtitle="6 andains connectés · NPK certifié · valorisation des déjections"
      icon={Recycle}
    >
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4">
        <KpiCard label="Production (T)" value="248" change={18} icon={Sprout} accent="success" />
        <KpiCard label="Andains actifs" value="6" change={0} icon={Recycle} accent="primary" />
        <KpiCard label="Temp. moy." value="56°C" change={2} icon={Thermometer} accent="warning" />
        <KpiCard label="Humidité moy." value="51%" change={-3} icon={Droplets} accent="info" />
      </div>

      <ChartCard title="Cycle de compostage IoT" description="Température · Humidité · Maturité — 40 jours">
        <ResponsiveContainer width="100%" height={320}>
          <ComposedChart data={compostCycle} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
            <defs>
              <linearGradient id="matG" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor="var(--color-chart-2)" stopOpacity={0.4} />
                <stop offset="100%" stopColor="var(--color-chart-2)" stopOpacity={0} />
              </linearGradient>
            </defs>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
            <XAxis dataKey="day" stroke="var(--color-muted-foreground)" fontSize={11} unit="j" />
            <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
            <Tooltip contentStyle={tooltipStyle} />
            <Legend wrapperStyle={{ fontSize: 12 }} />
            <Area type="monotone" dataKey="maturity" stroke="var(--color-chart-2)" fill="url(#matG)" strokeWidth={2.5} name="Maturité %" />
            <Line type="monotone" dataKey="temp" stroke="var(--color-chart-5)" strokeWidth={2.5} name="Temp °C" dot={false} />
            <Line type="monotone" dataKey="humidity" stroke="var(--color-chart-3)" strokeWidth={2.5} name="Humidité %" dot={false} />
          </ComposedChart>
        </ResponsiveContainer>
      </ChartCard>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <ChartCard title="Production vs ventes mensuelles" description="Tonnes" className="lg:col-span-2">
          <ResponsiveContainer width="100%" height={280}>
            <BarChart data={compostMonthly} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
              <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
              <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Bar dataKey="produced" fill="var(--color-chart-1)" radius={[6, 6, 0, 0]} name="Produit" />
              <Bar dataKey="sold" fill="var(--color-chart-2)" radius={[6, 6, 0, 0]} name="Vendu" />
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Qualité NPK" description="Profil agronomique">
          <ResponsiveContainer width="100%" height={280}>
            <RadarChart data={compostNPK} outerRadius={90}>
              <PolarGrid stroke="var(--color-border)" />
              <PolarAngleAxis dataKey="metric" tick={{ fontSize: 10, fill: "var(--color-muted-foreground)" }} />
              <PolarRadiusAxis tick={{ fontSize: 9 }} stroke="var(--color-border)" />
              <Radar dataKey="value" stroke="var(--color-chart-2)" fill="var(--color-chart-2)" fillOpacity={0.4} />
              <Tooltip contentStyle={tooltipStyle} />
            </RadarChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <Card className="p-5 gradient-card">
        <h3 className="text-sm font-semibold mb-4">État des andains en temps réel</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
          {composterStatus.map((c) => (
            <div key={c.name} className="p-4 rounded-xl border border-border bg-card">
              <div className="flex items-center justify-between mb-3">
                <p className="font-semibold">Andain {c.name}</p>
                <Badge className={
                  c.status === "ready" ? "bg-success/15 text-success border-0" :
                  c.status === "warn" ? "bg-warning/20 text-warning border-0" :
                  "bg-info/15 text-info border-0"
                }>
                  {c.status === "ready" ? "Prêt" : c.status === "warn" ? "À retourner" : "Actif"}
                </Badge>
              </div>
              <div className="space-y-2 text-xs">
                <div className="flex items-center justify-between">
                  <span className="text-muted-foreground flex items-center gap-1"><Thermometer className="h-3 w-3" /> Temp.</span>
                  <span className="font-medium">{c.temp}°C</span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-muted-foreground flex items-center gap-1"><Droplets className="h-3 w-3" /> Humidité</span>
                  <span className="font-medium">{c.hum}%</span>
                </div>
                <div className="pt-1">
                  <div className="flex items-center justify-between mb-1">
                    <span className="text-muted-foreground">Maturité</span>
                    <span className="font-medium">{c.maturity}%</span>
                  </div>
                  <Progress value={c.maturity} className="h-1.5" />
                </div>
              </div>
            </div>
          ))}
        </div>
      </Card>
    </PageShell>
  );
}
