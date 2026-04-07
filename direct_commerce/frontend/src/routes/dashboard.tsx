import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { useTranslation } from "react-i18next";
import {
  Bird, HeartPulse, Egg, Scale, Recycle, Wallet, Users, Bell,
  Activity, Camera, AlertTriangle, CheckCircle2, Droplets,
} from "lucide-react";
import {
  AreaChart, Area, BarChart, Bar, LineChart, Line, PieChart, Pie, Cell,
  RadarChart, Radar, PolarGrid, PolarAngleAxis, PolarRadiusAxis,
  ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend,
  ComposedChart, LabelList,
} from "recharts";
import { AppSidebar } from "@/components/app-sidebar";
import { BottomBar } from "@/components/bottom-bar";
import { TopBar } from "@/components/top-bar";
import { KpiCard } from "@/components/kpi-card";
import { ChartCard } from "@/components/chart-card";
import { Badge } from "@/components/ui/badge";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
  productionData, mortalityData, feedWaterData, revenueSplit,
  compostCycle, diseaseDetection, cooperatorTiers, cashflow,
  marketPrice, radarPerf, weightHistogram, mortalityAgeHistogram,
  buildingProduction, topCooperators, opCosts,
} from "@/lib/mock-data";

export const Route = createFileRoute("/dashboard")({
  beforeLoad: ({ location }) => requireAuth(location),
  component: Dashboard,
});

const CHART_COLORS = [
  "var(--color-chart-1)",
  "var(--color-chart-2)",
  "var(--color-chart-3)",
  "var(--color-chart-4)",
  "var(--color-chart-5)",
  "var(--color-chart-6)",
];

const tooltipStyle = {
  background: "var(--color-popover)",
  border: "1px solid var(--color-border)",
  borderRadius: "0.75rem",
  fontSize: "12px",
  color: "var(--color-popover-foreground)",
  boxShadow: "var(--shadow-elegant)",
};

function Dashboard() {
  const { t } = useTranslation();

  return (
    <div className="min-h-screen bg-background">
      <AppSidebar />
      <div className="lg:pl-64">
        <TopBar />
        <main className="px-4 lg:px-8 py-6 pb-24 lg:pb-10 space-y-6">
          {/* Hero greeting */}
          <Card className="relative overflow-hidden border-0 gradient-hero text-primary-foreground p-6 lg:p-8">
            <div className="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
              <div>
                <p className="text-xs uppercase tracking-widest opacity-80">{t("brand")}</p>
                <h2 className="text-2xl lg:text-3xl font-bold mt-1">
                  {t("dash.welcome")} ECOTEC 👋
                </h2>
                <p className="text-sm opacity-90 mt-1 max-w-xl">{t("dash.subtitle")}</p>
              </div>
              <div className="flex items-center gap-3">
                <Badge className="bg-white/20 hover:bg-white/25 text-white border-0 backdrop-blur">
                  <span className="h-2 w-2 rounded-full bg-success mr-2 animate-pulse" />
                  {t("common.live")}
                </Badge>
                <Button variant="secondary" size="sm">{t("common.export")}</Button>
              </div>
            </div>
            <div className="absolute -right-20 -bottom-20 h-72 w-72 rounded-full bg-primary-glow/30 blur-3xl" />
            <div className="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-accent/30 blur-3xl" />
          </Card>

          {/* KPIs */}
          <div className="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3 lg:gap-4">
            <KpiCard label={t("kpi.flocks")} value="24" change={8} icon={Bird} accent="primary" />
            <KpiCard label={t("kpi.chickens")} value="42 580" change={12} icon={Activity} accent="success" />
            <KpiCard label={t("kpi.mortality")} value="2.8%" change={-1.4} icon={HeartPulse} accent="info" />
            <KpiCard label={t("kpi.eggsToday")} value="5 240" change={4.2} icon={Egg} accent="warning" />
            <KpiCard label={t("kpi.avgWeight")} value="1.84 kg" change={3.1} icon={Scale} accent="primary" />
            <KpiCard label={t("kpi.feedConv")} value="1.72" change={-2.1} icon={Droplets} accent="info" />
            <KpiCard label={t("kpi.compost")} value="248 T" change={18} icon={Recycle} accent="success" />
            <KpiCard label={t("kpi.revenue")} value="38.4 M" change={22} icon={Wallet} accent="primary" />
            <KpiCard label={t("kpi.cooperators")} value="52" change={15} icon={Users} accent="success" />
            <KpiCard label={t("kpi.aiAlerts")} value="7" change={-12} icon={Bell} accent="destructive" />
          </div>

          {/* Production area + Disease pie */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <ChartCard
              title={t("dash.production")}
              description={t("dash.productionDesc")}
              className="lg:col-span-2"
            >
              <ResponsiveContainer width="100%" height={300}>
                <AreaChart data={productionData(t)} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                  <defs>
                    <linearGradient id="g1" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stopColor="var(--color-chart-1)" stopOpacity={0.5} />
                      <stop offset="100%" stopColor="var(--color-chart-1)" stopOpacity={0} />
                    </linearGradient>
                    <linearGradient id="g2" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stopColor="var(--color-chart-2)" stopOpacity={0.5} />
                      <stop offset="100%" stopColor="var(--color-chart-2)" stopOpacity={0} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
                  <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                  <Area type="monotone" dataKey="broilers" stroke="var(--color-chart-1)" fill="url(#g1)" strokeWidth={2.5} name="Poulets (T)" />
                  <Area type="monotone" dataKey="eggs" stroke="var(--color-chart-2)" fill="url(#g2)" strokeWidth={2.5} name="Œufs (k)" />
                </AreaChart>
              </ResponsiveContainer>
            </ChartCard>

            <ChartCard title={t("dash.diseases")} description={t("dash.diseasesDesc")}>
              <ResponsiveContainer width="100%" height={300}>
                <PieChart>
                  <Pie
                    data={diseaseDetection(t)}
                    dataKey="value"
                    nameKey="name"
                    innerRadius={55}
                    outerRadius={90}
                    paddingAngle={2}
                  >
                    {diseaseDetection(t).map((_, i) => (
                      <Cell key={i} fill={CHART_COLORS[i % CHART_COLORS.length]} />
                    ))}
                  </Pie>
                  <Tooltip contentStyle={tooltipStyle} />
                  <Legend wrapperStyle={{ fontSize: 11 }} />
                </PieChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>

          {/* HISTOGRAMS — weights + mortality by age */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <ChartCard title={t("dash.weightHist")} description={t("dash.weightHistDesc")}>
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={weightHistogram} margin={{ top: 10, right: 10, left: -10, bottom: 0 }} barCategoryGap={1}>
                  <defs>
                    <linearGradient id="histBlue" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stopColor="var(--color-chart-1)" stopOpacity={1} />
                      <stop offset="100%" stopColor="var(--color-chart-1)" stopOpacity={0.5} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                  <XAxis dataKey="bucket" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Bar dataKey="count" fill="url(#histBlue)" name="Effectif">
                    <LabelList dataKey="count" position="top" fontSize={10} fill="var(--color-muted-foreground)" />
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            </ChartCard>

            <ChartCard title={t("dash.mortAgeHist")} description={t("dash.mortAgeHistDesc")}>
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={mortalityAgeHistogram} margin={{ top: 10, right: 10, left: -10, bottom: 0 }} barCategoryGap={1}>
                  <defs>
                    <linearGradient id="histGreen" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="0%" stopColor="var(--color-chart-2)" stopOpacity={1} />
                      <stop offset="100%" stopColor="var(--color-chart-2)" stopOpacity={0.5} />
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                  <XAxis dataKey="bucket" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Bar dataKey="count" fill="url(#histGreen)" name="Décès">
                    <LabelList dataKey="count" position="top" fontSize={10} fill="var(--color-muted-foreground)" />
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>

          {/* Grouped bars per building + Mortality per flock */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <ChartCard title={t("dash.buildings")} description={t("dash.buildingsDesc")}>
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={buildingProduction} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                  <XAxis dataKey="b" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                  <Bar dataKey="broilers" fill="var(--color-chart-1)" radius={[6, 6, 0, 0]} name="Poulets" />
                  <Bar dataKey="eggs" fill="var(--color-chart-2)" radius={[6, 6, 0, 0]} name="Œufs" />
                </BarChart>
              </ResponsiveContainer>
            </ChartCard>

            <ChartCard title={t("dash.mortality")} description={t("dash.mortalityDesc")}>
              <ResponsiveContainer width="100%" height={300}>
                <ComposedChart data={mortalityData} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                  <XAxis dataKey="flock" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} unit="%" />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                  <Bar dataKey="rate" radius={[8, 8, 0, 0]} name="Mortalité %">
                    {mortalityData.map((d, i) => (
                      <Cell key={i} fill={d.rate > d.target ? "var(--color-destructive)" : "var(--color-chart-1)"} />
                    ))}
                  </Bar>
                  <Line dataKey="target" stroke="var(--color-warning)" strokeDasharray="4 4" name="Seuil" dot={false} />
                </ComposedChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>

          {/* Stacked bars OPEX + horizontal bars top cooperators */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <ChartCard title={t("dash.opCosts")} description={t("dash.opCostsDesc")} className="lg:col-span-2">
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={opCosts(t)} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                  <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                  <Bar dataKey="feed" stackId="a" fill="var(--color-chart-1)" name="Aliment" />
                  <Bar dataKey="labor" stackId="a" fill="var(--color-chart-2)" name="Main d'œuvre" />
                  <Bar dataKey="energy" stackId="a" fill="var(--color-chart-3)" name="Énergie" />
                  <Bar dataKey="vet" stackId="a" fill="var(--color-chart-5)" name="Vétérinaire" radius={[6, 6, 0, 0]} />
                </BarChart>
              </ResponsiveContainer>
            </ChartCard>

            <ChartCard title={t("dash.topCooperators")} description="M FCFA">
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={topCooperators} layout="vertical" margin={{ top: 5, right: 25, left: 10, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" horizontal={false} />
                  <XAxis type="number" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis type="category" dataKey="name" stroke="var(--color-muted-foreground)" fontSize={11} width={75} />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Bar dataKey="revenue" fill="var(--color-chart-2)" radius={[0, 6, 6, 0]} name="CA">
                    <LabelList dataKey="revenue" position="right" fontSize={10} fill="var(--color-muted-foreground)" formatter={(v: number) => `${v}M`} />
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>

          {/* Compost + Revenue split + Performance Radar */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <ChartCard title={t("dash.compostCycle")} description={t("dash.compostDesc")} className="lg:col-span-2">
              <ResponsiveContainer width="100%" height={280}>
                <LineChart data={compostCycle} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
                  <XAxis dataKey="day" stroke="var(--color-muted-foreground)" fontSize={11} unit="j" />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                  <Line type="monotone" dataKey="temp" stroke="var(--color-chart-5)" strokeWidth={2.5} name="Temp °C" dot={false} />
                  <Line type="monotone" dataKey="humidity" stroke="var(--color-chart-3)" strokeWidth={2.5} name="Humidité %" dot={false} />
                  <Line type="monotone" dataKey="maturity" stroke="var(--color-chart-2)" strokeWidth={2.5} name="Maturité %" dot={false} />
                </LineChart>
              </ResponsiveContainer>
            </ChartCard>

            <ChartCard title={t("dash.revenueSplit")}>
              <ResponsiveContainer width="100%" height={280}>
                <PieChart>
                  <Pie data={revenueSplit} dataKey="value" nameKey="name" outerRadius={95} label={({ value }) => `${value}%`}>
                    {revenueSplit.map((_, i) => (
                      <Cell key={i} fill={CHART_COLORS[i % CHART_COLORS.length]} />
                    ))}
                  </Pie>
                  <Tooltip contentStyle={tooltipStyle} />
                  <Legend wrapperStyle={{ fontSize: 11 }} />
                </PieChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>

          {/* Feed/water + Cooperators tier bars */}
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <ChartCard title={t("dash.feed")} description="kg / litres — 7j">
              <ResponsiveContainer width="100%" height={280}>
                <ComposedChart data={feedWaterData(t)} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                  <XAxis dataKey="d" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                  <Bar dataKey="feed" fill="var(--color-chart-1)" radius={[6, 6, 0, 0]} name="Aliment (kg)" />
                  <Line type="monotone" dataKey="water" stroke="var(--color-chart-3)" strokeWidth={2.5} name="Eau (L)" dot={{ r: 4 }} />
                </ComposedChart>
              </ResponsiveContainer>
            </ChartCard>

            <ChartCard title={t("dash.cooperators")} description="Effectif par formule">
              <ResponsiveContainer width="100%" height={280}>
                <BarChart data={cooperatorTiers(t)} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                  <XAxis dataKey="tier" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
                  <Bar dataKey="count" radius={[8, 8, 0, 0]} name="Coopérants">
                    {cooperatorTiers(t).map((_, i) => (
                      <Cell key={i} fill={CHART_COLORS[i % CHART_COLORS.length]} />
                    ))}
                    <LabelList dataKey="count" position="top" fontSize={11} fill="var(--color-muted-foreground)" />
                  </Bar>
                </BarChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>

          {/* Cashflow */}
          <ChartCard title={t("dash.cashflow")} description={t("dash.cashflowDesc")}>
            <ResponsiveContainer width="100%" height={320}>
              <ComposedChart data={cashflow} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
                <defs>
                  <linearGradient id="gp" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stopColor="var(--color-chart-2)" stopOpacity={0.5} />
                    <stop offset="100%" stopColor="var(--color-chart-2)" stopOpacity={0} />
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" vertical={false} />
                <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={10} interval={2} />
                <YAxis stroke="var(--color-muted-foreground)" fontSize={11} unit="M" />
                <Tooltip contentStyle={tooltipStyle} />
                <Legend wrapperStyle={{ fontSize: 12 }} />
                <Bar dataKey="cost" fill="var(--color-chart-5)" radius={[4, 4, 0, 0]} name="Coût" opacity={0.85} />
                <Area type="monotone" dataKey="profit" stroke="var(--color-chart-2)" fill="url(#gp)" strokeWidth={2.5} name="Bénéfice" />
                <Line type="monotone" dataKey="revenue" stroke="var(--color-chart-1)" strokeWidth={2.5} name="Revenu" dot={false} />
              </ComposedChart>
            </ResponsiveContainer>
          </ChartCard>

          {/* Market + Radar */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <ChartCard title={t("dash.market")} className="lg:col-span-2">
              <ResponsiveContainer width="100%" height={260}>
                <LineChart data={marketPrice} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>
                  <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
                  <XAxis dataKey="w" stroke="var(--color-muted-foreground)" fontSize={11} />
                  <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
                  <Tooltip contentStyle={tooltipStyle} />
                  <Legend wrapperStyle={{ fontSize: 12 }} />
                  <Line type="monotone" dataKey="ecotec" stroke="var(--color-chart-1)" strokeWidth={2.5} name="ECOTEC" dot={{ r: 3 }} />
                  <Line type="monotone" dataKey="market" stroke="var(--color-chart-3)" strokeWidth={2.5} name="Marché" strokeDasharray="4 4" dot={{ r: 3 }} />
                </LineChart>
              </ResponsiveContainer>
            </ChartCard>

            <ChartCard title={t("dash.perf")} description={t("dash.perfDesc")}>
              <ResponsiveContainer width="100%" height={260}>
                <RadarChart data={radarPerf}>
                  <PolarGrid stroke="var(--color-border)" />
                  <PolarAngleAxis dataKey="axis" tick={{ fontSize: 11, fill: "var(--color-muted-foreground)" }} />
                  <PolarRadiusAxis domain={[0, 100]} tick={{ fontSize: 10 }} />
                  <Radar dataKey="A" stroke="var(--color-chart-1)" fill="var(--color-chart-1)" fillOpacity={0.4} />
                  <Tooltip contentStyle={tooltipStyle} />
                </RadarChart>
              </ResponsiveContainer>
            </ChartCard>
          </div>

          {/* Live cams + alerts */}
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <Card className="lg:col-span-2 p-5 gradient-card">
              <div className="flex items-center justify-between mb-4">
                <div>
                  <h3 className="text-sm font-semibold">{t("dash.liveCams")}</h3>
                  <p className="text-xs text-muted-foreground mt-0.5">YOLO v8 · 91% précision</p>
                </div>
                <Badge variant="secondary" className="gap-1.5">
                  <span className="h-2 w-2 rounded-full bg-destructive animate-pulse" />
                  {t("common.live")}
                </Badge>
              </div>
              <div className="grid grid-cols-2 md:grid-cols-3 gap-3">
                {["B1", "B2", "B3", "B4", "B5", "B6"].map((cam, i) => (
                  <div key={cam} className="aspect-video rounded-xl bg-gradient-to-br from-muted to-secondary border border-border relative overflow-hidden group">
                    <div className="absolute inset-0 flex items-center justify-center">
                      <Camera className="h-8 w-8 text-muted-foreground/50 group-hover:scale-110 transition-transform" />
                    </div>
                    <div className="absolute top-2 left-2 flex items-center gap-1.5">
                      <span className="h-1.5 w-1.5 rounded-full bg-destructive animate-pulse" />
                      <span className="text-[10px] font-mono font-semibold bg-background/70 backdrop-blur px-1.5 rounded">{cam}</span>
                    </div>
                    <div className="absolute bottom-2 right-2">
                      {i === 2 ? (
                        <Badge variant="destructive" className="text-[9px] h-5">ALERTE</Badge>
                      ) : (
                        <Badge className="text-[9px] h-5 bg-success/90 text-success-foreground border-0">OK</Badge>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            </Card>

            <Card className="p-5 gradient-card">
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-sm font-semibold">{t("dash.recentAlerts")}</h3>
                <Badge variant="outline">7</Badge>
              </div>
              <div className="space-y-3">
                {[
                  { type: "warning", text: t("alerts.a1"), time: "5m" },
                  { type: "info", text: t("alerts.a2"), time: "22m" },
                  { type: "destructive", text: t("alerts.a3"), time: "1h" },
                  { type: "success", text: t("alerts.a4"), time: "3h" },
                ].map((a, i) => {
                  const Icon = a.type === "destructive" ? AlertTriangle : a.type === "success" ? CheckCircle2 : Bell;
                  const color =
                    a.type === "destructive" ? "text-destructive bg-destructive/10" :
                    a.type === "warning" ? "text-warning bg-warning/15" :
                    a.type === "success" ? "text-success bg-success/10" :
                    "text-info bg-info/10";
                  return (
                    <div key={i} className="flex gap-3 p-3 rounded-lg hover:bg-muted/50 transition-colors">
                      <div className={`h-8 w-8 rounded-lg flex items-center justify-center shrink-0 ${color}`}>
                        <Icon className="h-4 w-4" />
                      </div>
                      <div className="flex-1 min-w-0">
                        <p className="text-xs font-medium leading-snug">{a.text}</p>
                        <p className="text-[10px] text-muted-foreground mt-1">il y a {a.time}</p>
                      </div>
                    </div>
                  );
                })}
              </div>
            </Card>
          </div>
        </main>
      </div>
      <BottomBar />
    </div>
  );
}
