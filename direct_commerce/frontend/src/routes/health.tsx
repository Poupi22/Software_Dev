import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { HeartPulse, ShieldCheck, AlertTriangle, Activity, Camera } from "lucide-react";
import {
  BarChart, Bar, LineChart, Line, RadarChart, Radar, PolarGrid, PolarAngleAxis, PolarRadiusAxis,
  AreaChart, Area, ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend, LabelList,
} from "recharts";
import { PageShell, tooltipStyle } from "@/components/page-shell";
import { ChartCard } from "@/components/chart-card";
import { KpiCard } from "@/components/kpi-card";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { healthAlerts7d, aiPrecisionTrend, vaccineCoverage, bodyTempData } from "@/lib/page-data";
import { mortalityData, mortalityAgeHistogram, radarPerf } from "@/lib/mock-data";

export const Route = createFileRoute("/health")({ beforeLoad: ({ location }) => requireAuth(location), component: HealthPage });

function HealthPage() {
  const alerts = [
    { lvl: "critical", t: "B3 — regroupement anormal", time: "il y a 8 min" },
    { lvl: "moderate", t: "Bande #128 — gain de poids -12%", time: "il y a 22 min" },
    { lvl: "info", t: "Caméra C7 — qualité dégradée", time: "il y a 1 h" },
    { lvl: "moderate", t: "B5 — toux détectée (audio IA)", time: "il y a 2 h" },
    { lvl: "info", t: "Vaccination Newcastle programmée", time: "il y a 3 h" },
  ];

  return (
    <PageShell
      title="Santé IA"
      subtitle="Surveillance YOLOv8 · détection précoce 72h avant symptômes"
      icon={HeartPulse}
    >
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4">
        <KpiCard label="Précision IA" value="91%" change={2.4} icon={ShieldCheck} accent="primary" />
        <KpiCard label="Alertes 24h" value="7" change={-12} icon={AlertTriangle} accent="destructive" />
        <KpiCard label="Caméras actives" value="18/20" change={0} icon={Camera} accent="info" />
        <KpiCard label="Mortalité" value="2.8%" change={-1.4} icon={Activity} accent="success" />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <ChartCard title="Alertes IA — 7 derniers jours" description="Critiques · modérées · info" className="lg:col-span-2">
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={healthAlerts7d} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
              <XAxis dataKey="d" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
              <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Bar dataKey="critical" stackId="a" fill="var(--color-destructive)" name="Critique" />
              <Bar dataKey="moderate" stackId="a" fill="var(--color-chart-5)" name="Modérée" />
              <Bar dataKey="info" stackId="a" fill="var(--color-chart-3)" name="Info" radius={[6, 6, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Performance modèle IA" description="Multi-critères (radar)">
          <ResponsiveContainer width="100%" height={300}>
            <RadarChart data={radarPerf} outerRadius={90}>
              <PolarGrid stroke="var(--color-border)" />
              <PolarAngleAxis dataKey="axis" tick={{ fontSize: 11, fill: "var(--color-muted-foreground)" }} />
              <PolarRadiusAxis tick={{ fontSize: 10 }} stroke="var(--color-border)" />
              <Radar dataKey="A" stroke="var(--color-chart-1)" fill="var(--color-chart-1)" fillOpacity={0.3} />
              <Tooltip contentStyle={tooltipStyle} />
            </RadarChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <ChartCard title="Évolution précision / rappel" description="8 dernières semaines">
          <ResponsiveContainer width="100%" height={280}>
            <LineChart data={aiPrecisionTrend} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
              <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis stroke="var(--color-muted-foreground)" fontSize={11} unit="%" domain={[70, 100]} />
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Line type="monotone" dataKey="precision" stroke="var(--color-chart-1)" strokeWidth={2.5} name="Précision" dot={{ r: 3 }} />
              <Line type="monotone" dataKey="recall" stroke="var(--color-chart-2)" strokeWidth={2.5} name="Rappel" dot={{ r: 3 }} />
            </LineChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Couverture vaccinale" description="% des bandes vaccinées">
          <ResponsiveContainer width="100%" height={280}>
            <BarChart data={vaccineCoverage} layout="vertical" margin={{ top: 5, right: 30, left: 10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" horizontal={false} />
              <XAxis type="number" stroke="var(--color-muted-foreground)" fontSize={11} unit="%" domain={[0, 100]} />
              <YAxis type="category" dataKey="vaccine" stroke="var(--color-muted-foreground)" fontSize={11} width={90} />
              <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
              <Bar dataKey="coverage" fill="var(--color-chart-2)" radius={[0, 6, 6, 0]}>
                <LabelList dataKey="coverage" position="right" fontSize={10} fill="var(--color-muted-foreground)" formatter={(v: number) => `${v}%`} />
              </Bar>
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <ChartCard title="Température corporelle 24h" description="Moyenne et max — IoT thermique">
          <ResponsiveContainer width="100%" height={260}>
            <AreaChart data={bodyTempData} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
              <defs>
                <linearGradient id="tempG" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="var(--color-chart-5)" stopOpacity={0.4} />
                  <stop offset="100%" stopColor="var(--color-chart-5)" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
              <XAxis dataKey="h" stroke="var(--color-muted-foreground)" fontSize={10} interval={2} />
              <YAxis stroke="var(--color-muted-foreground)" fontSize={11} domain={[39.5, 42]} unit="°C" />
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 12 }} />
              <Area type="monotone" dataKey="avg" stroke="var(--color-chart-5)" fill="url(#tempG)" strokeWidth={2} name="Moyenne" />
              <Line type="monotone" dataKey="max" stroke="var(--color-destructive)" strokeWidth={2} dot={false} name="Max" />
            </AreaChart>
          </ResponsiveContainer>
        </ChartCard>

        <ChartCard title="Mortalité par âge" description="Total décès par tranche">
          <ResponsiveContainer width="100%" height={260}>
            <BarChart data={mortalityAgeHistogram} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
              <XAxis dataKey="bucket" stroke="var(--color-muted-foreground)" fontSize={11} />
              <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
              <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
              <Bar dataKey="count" fill="var(--color-chart-2)" radius={[6, 6, 0, 0]}>
                <LabelList dataKey="count" position="top" fontSize={10} fill="var(--color-muted-foreground)" />
              </Bar>
            </BarChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <Card className="p-5 gradient-card">
        <h3 className="text-sm font-semibold mb-4 flex items-center gap-2">
          <AlertTriangle className="h-4 w-4 text-warning" /> Alertes récentes
        </h3>
        <div className="space-y-2">
          {alerts.map((a, i) => (
            <div key={i} className="flex items-center justify-between gap-3 p-3 rounded-lg border border-border bg-card">
              <div className="flex items-center gap-3 min-w-0">
                <span className={`h-2.5 w-2.5 rounded-full shrink-0 ${
                  a.lvl === "critical" ? "bg-destructive animate-pulse" :
                  a.lvl === "moderate" ? "bg-warning" : "bg-info"
                }`} />
                <p className="text-sm truncate">{a.t}</p>
              </div>
              <span className="text-xs text-muted-foreground shrink-0">{a.time}</span>
            </div>
          ))}
        </div>
      </Card>
    </PageShell>
  );
}
