import { createFileRoute } from "@tanstack/react-router";
import { Users, Briefcase, CreditCard, AlertTriangle, TrendingUp, ArrowUpRight, ArrowDownRight } from "lucide-react";
import { LineChart, Line, ResponsiveContainer, XAxis, YAxis, Tooltip, PieChart, Pie, Cell, BarChart, Bar, CartesianGrid } from "recharts";
import { revenueSeries, cityDistribution, adminTransactions, adminDisputes, formatXAF } from "@/lib/mock-data";

export const Route = createFileRoute("/admin/")({
  component: AdminDashboard,
});

const COLORS = ["#3B82F6", "#1E3A8A", "#60A5FA", "#1D4ED8", "#93C5FD"];

function AdminDashboard() {
  const kpis = [
    { label: "Utilisateurs", value: "12 480", delta: "+8.2%", up: true, icon: Users, color: "bg-primary/15 text-primary" },
    { label: "Prestataires actifs", value: "1 286", delta: "+3.1%", up: true, icon: Briefcase, color: "bg-secondary/15 text-secondary" },
    { label: "Revenus du mois", value: formatXAF(11_840_000), delta: "+14.7%", up: true, icon: CreditCard, color: "bg-gold/20 text-gold-foreground" },
    { label: "Litiges ouverts", value: "8", delta: "-2", up: false, icon: AlertTriangle, color: "bg-destructive/15 text-destructive" },
  ];

  return (
    <div className="space-y-6">
      <div>
        <h1 className="font-display text-2xl font-bold">Tableau de bord</h1>
        <p className="text-sm text-muted-foreground">Aperçu de l'activité SERVLINK — Mai 2026.</p>
      </div>

      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {kpis.map((k) => (
          <div key={k.label} className="bg-card border border-border rounded-xl p-5">
            <div className="flex items-center justify-between">
              <div className={`h-10 w-10 rounded-lg flex items-center justify-center ${k.color}`}><k.icon className="h-5 w-5" /></div>
              <span className={`inline-flex items-center gap-0.5 text-xs font-semibold ${k.up ? "text-success" : "text-destructive"}`}>
                {k.up ? <ArrowUpRight className="h-3 w-3" /> : <ArrowDownRight className="h-3 w-3" />} {k.delta}
              </span>
            </div>
            <div className="mt-3 font-display text-2xl font-bold">{k.value}</div>
            <div className="text-xs text-muted-foreground">{k.label}</div>
          </div>
        ))}
      </div>

      <div className="grid lg:grid-cols-3 gap-4">
        <div className="lg:col-span-2 bg-card border border-border rounded-xl p-5">
          <div className="flex items-center justify-between mb-4">
            <div>
              <h2 className="font-display font-bold">Revenus mensuels</h2>
              <p className="text-xs text-muted-foreground">En millions de FCFA</p>
            </div>
            <span className="text-xs flex items-center gap-1 text-success font-semibold"><TrendingUp className="h-3.5 w-3.5" /> Tendance haussière</span>
          </div>
          <ResponsiveContainer width="100%" height={260}>
            <LineChart data={revenueSeries}>
              <CartesianGrid strokeDasharray="3 3" stroke="oklch(0.92 0.008 250)" />
              <XAxis dataKey="m" stroke="oklch(0.5 0.02 250)" fontSize={12} />
              <YAxis stroke="oklch(0.5 0.02 250)" fontSize={12} />
              <Tooltip contentStyle={{ borderRadius: 8, border: "1px solid oklch(0.92 0.008 250)" }} />
              <Line type="monotone" dataKey="v" stroke="#3B82F6" strokeWidth={3} dot={{ fill: "#3B82F6", r: 4 }} />
            </LineChart>
          </ResponsiveContainer>
        </div>

        <div className="bg-card border border-border rounded-xl p-5">
          <h2 className="font-display font-bold mb-2">Répartition par ville</h2>
          <ResponsiveContainer width="100%" height={220}>
            <PieChart>
              <Pie data={cityDistribution} dataKey="value" nameKey="name" innerRadius={50} outerRadius={80} paddingAngle={2}>
                {cityDistribution.map((_, i) => <Cell key={i} fill={COLORS[i % COLORS.length]} />)}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
          <div className="space-y-1.5 mt-2">
            {cityDistribution.map((c, i) => (
              <div key={c.name} className="flex items-center gap-2 text-xs">
                <span className="h-2.5 w-2.5 rounded-sm" style={{ backgroundColor: COLORS[i] }} />
                <span className="flex-1">{c.name}</span>
                <span className="font-semibold">{c.value}%</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="grid lg:grid-cols-2 gap-4">
        <div className="bg-card border border-border rounded-xl p-5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-display font-bold">Catégories les plus actives</h2>
          </div>
          <ResponsiveContainer width="100%" height={220}>
            <BarChart data={[{ n: "Ménage", v: 212 }, { n: "Coiffure", v: 187 }, { n: "Cours", v: 165 }, { n: "Info", v: 143 }, { n: "Plomb.", v: 124 }, { n: "Photo", v: 102 }]}>
              <CartesianGrid strokeDasharray="3 3" stroke="oklch(0.92 0.008 250)" />
              <XAxis dataKey="n" fontSize={12} stroke="oklch(0.5 0.02 250)" />
              <YAxis fontSize={12} stroke="oklch(0.5 0.02 250)" />
              <Tooltip />
              <Bar dataKey="v" fill="#1E3A8A" radius={[6, 6, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </div>

        <div className="bg-card border border-border rounded-xl p-5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-display font-bold">Litiges récents</h2>
            <span className="text-xs px-2 py-0.5 rounded-full bg-destructive/15 text-destructive font-semibold">{adminDisputes.filter(d => d.status === "Ouvert").length} ouverts</span>
          </div>
          <div className="space-y-3">
            {adminDisputes.slice(0, 4).map((d) => (
              <div key={d.id} className="flex items-center gap-3 p-3 rounded-lg hover:bg-muted/60">
                <div className={`h-2 w-2 rounded-full ${d.priority === "Haute" ? "bg-destructive" : d.priority === "Moyenne" ? "bg-warning" : "bg-muted-foreground"}`} />
                <div className="flex-1 min-w-0">
                  <div className="text-sm font-medium truncate">{d.reason}</div>
                  <div className="text-xs text-muted-foreground">{d.id} · {d.client} ↔ {d.provider}</div>
                </div>
                <span className={`text-[10px] px-2 py-0.5 rounded-full font-semibold ${d.status === "Ouvert" ? "bg-destructive/15 text-destructive" : d.status === "Résolu" ? "bg-success/15 text-success" : "bg-warning/15 text-warning"}`}>{d.status}</span>
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="bg-card border border-border rounded-xl p-5">
        <div className="flex items-center justify-between mb-4">
          <h2 className="font-display font-bold">Dernières transactions</h2>
          <a href="/admin/transactions" className="text-xs text-primary hover:underline font-medium">Voir tout</a>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="text-xs text-muted-foreground uppercase border-b border-border">
              <tr><th className="text-left py-2 px-2">ID</th><th className="text-left py-2 px-2">Client</th><th className="text-left py-2 px-2">Prestataire</th><th className="text-left py-2 px-2">Moyen</th><th className="text-right py-2 px-2">Montant</th><th className="text-left py-2 px-2">Statut</th></tr>
            </thead>
            <tbody>
              {adminTransactions.slice(0, 6).map((t) => (
                <tr key={t.id} className="border-b border-border last:border-0">
                  <td className="py-3 px-2 font-mono text-xs">{t.id}</td>
                  <td className="py-3 px-2">{t.client}</td>
                  <td className="py-3 px-2 text-muted-foreground">{t.provider}</td>
                  <td className="py-3 px-2"><span className="text-xs px-2 py-0.5 rounded-md bg-muted">{t.method}</span></td>
                  <td className="py-3 px-2 text-right font-semibold">{formatXAF(t.amount)}</td>
                  <td className="py-3 px-2"><span className={`text-[10px] px-2 py-0.5 rounded-full font-semibold ${t.status === "Réussie" ? "bg-success/15 text-success" : t.status === "En attente" ? "bg-warning/15 text-warning" : "bg-destructive/15 text-destructive"}`}>{t.status}</span></td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
