import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { Wallet, TrendingUp, TrendingDown, PiggyBank } from "lucide-react";
import {
  AreaChart, Area, BarChart, Bar, LineChart, Line, PieChart, Pie, Cell,
  ComposedChart, ResponsiveContainer, XAxis, YAxis, CartesianGrid, Tooltip, Legend, LabelList,
} from "recharts";
import { PageShell, tooltipStyle, CHART_COLORS } from "@/components/page-shell";
import { ChartCard } from "@/components/chart-card";
import { KpiCard } from "@/components/kpi-card";
import { monthlyPL, costBreakdown, investments } from "@/lib/page-data";
import { cashflow, opCosts } from "@/lib/mock-data";
import { useTranslation } from "react-i18next";

export const Route = createFileRoute("/finance")({ beforeLoad: ({ location }) => requireAuth(location), component: FinancePage });

function FinancePage() {
  const { t } = useTranslation();
  return (
    <PageShell
      title="Finance"
      subtitle="P&L · Cashflow · CAPEX · Modèle financier ECOTEC"
      icon={Wallet}
    >
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3 lg:gap-4">
        <KpiCard label="CA YTD" value="127.6 M" change={22} icon={TrendingUp} accent="success" />
        <KpiCard label="Charges" value="80.8 M" change={14} icon={TrendingDown} accent="warning" />
        <KpiCard label="Marge nette" value="36.7%" change={4.2} icon={Wallet} accent="primary" />
        <KpiCard label="Cash" value="46.8 M" change={28} icon={PiggyBank} accent="info" />
      </div>

      <ChartCard title="Compte de résultat mensuel" description="Revenus · Charges · Bénéfice (M FCFA)">
        <ResponsiveContainer width="100%" height={320}>
          <ComposedChart data={monthlyPL} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
            <defs>
              <linearGradient id="profitG" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor="var(--color-chart-2)" stopOpacity={0.5} />
                <stop offset="100%" stopColor="var(--color-chart-2)" stopOpacity={0} />
              </linearGradient>
            </defs>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
            <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={11} />
            <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
            <Tooltip contentStyle={tooltipStyle} />
            <Legend wrapperStyle={{ fontSize: 12 }} />
            <Bar dataKey="revenue" fill="var(--color-chart-1)" radius={[6, 6, 0, 0]} name="Revenus" />
            <Bar dataKey="cost" fill="var(--color-chart-5)" radius={[6, 6, 0, 0]} name="Charges" />
            <Area type="monotone" dataKey="profit" stroke="var(--color-chart-2)" fill="url(#profitG)" strokeWidth={2.5} name="Bénéfice" />
          </ComposedChart>
        </ResponsiveContainer>
      </ChartCard>

      <ChartCard title="Trésorerie prévisionnelle 36 mois" description="Modèle financier ECOTEC (M FCFA)">
        <ResponsiveContainer width="100%" height={300}>
          <AreaChart data={cashflow} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
            <defs>
              <linearGradient id="cfRev" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor="var(--color-chart-1)" stopOpacity={0.5} />
                <stop offset="100%" stopColor="var(--color-chart-1)" stopOpacity={0} />
              </linearGradient>
              <linearGradient id="cfCost" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stopColor="var(--color-chart-5)" stopOpacity={0.4} />
                <stop offset="100%" stopColor="var(--color-chart-5)" stopOpacity={0} />
              </linearGradient>
            </defs>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" />
            <XAxis dataKey="m" stroke="var(--color-muted-foreground)" fontSize={10} interval={2} />
            <YAxis stroke="var(--color-muted-foreground)" fontSize={11} />
            <Tooltip contentStyle={tooltipStyle} />
            <Legend wrapperStyle={{ fontSize: 12 }} />
            <Area type="monotone" dataKey="revenue" stroke="var(--color-chart-1)" fill="url(#cfRev)" strokeWidth={2} name="Revenus" />
            <Area type="monotone" dataKey="cost" stroke="var(--color-chart-5)" fill="url(#cfCost)" strokeWidth={2} name="Coûts" />
            <Line type="monotone" dataKey="profit" stroke="var(--color-chart-2)" strokeWidth={2.5} dot={false} name="Profit" />
          </AreaChart>
        </ResponsiveContainer>
      </ChartCard>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <ChartCard title="Coûts opérationnels empilés" description="M FCFA / mois" className="lg:col-span-2">
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

        <ChartCard title="Structure des coûts" description="% du total">
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie data={costBreakdown} dataKey="value" nameKey="name" outerRadius={95} label={({ value }) => `${value}%`}>
                {costBreakdown.map((_, i) => (<Cell key={i} fill={CHART_COLORS[i % CHART_COLORS.length]} />))}
              </Pie>
              <Tooltip contentStyle={tooltipStyle} />
              <Legend wrapperStyle={{ fontSize: 11 }} />
            </PieChart>
          </ResponsiveContainer>
        </ChartCard>
      </div>

      <ChartCard title="CAPEX — allocation des investissements" description="% du budget total">
        <ResponsiveContainer width="100%" height={260}>
          <BarChart data={investments} layout="vertical" margin={{ top: 5, right: 30, left: 10, bottom: 0 }}>
            <CartesianGrid strokeDasharray="3 3" stroke="var(--color-border)" horizontal={false} />
            <XAxis type="number" stroke="var(--color-muted-foreground)" fontSize={11} unit="%" />
            <YAxis type="category" dataKey="item" stroke="var(--color-muted-foreground)" fontSize={11} width={110} />
            <Tooltip contentStyle={tooltipStyle} cursor={{ fill: "var(--color-muted)" }} />
            <Bar dataKey="value" fill="var(--color-chart-1)" radius={[0, 6, 6, 0]}>
              <LabelList dataKey="value" position="right" fontSize={10} fill="var(--color-muted-foreground)" formatter={(v: number) => `${v}%`} />
            </Bar>
          </BarChart>
        </ResponsiveContainer>
      </ChartCard>
    </PageShell>
  );
}
