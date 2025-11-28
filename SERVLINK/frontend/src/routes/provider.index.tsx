import { createFileRoute } from "@tanstack/react-router";
import { TrendingUp, CalendarCheck, Star, CheckCircle2, Clock, ArrowUpRight, Wrench, MessageSquare, Eye, DollarSign } from "lucide-react";
import { ResponsiveContainer, XAxis, YAxis, Tooltip, CartesianGrid, AreaChart, Area, BarChart, Bar } from "recharts";
import { formatXAF } from "@/lib/mock-data";

export const Route = createFileRoute("/provider/")({
  component: ProviderDashboard,
});

const earnings = [
  { m: "Déc", v: 145 }, { m: "Jan", v: 168 }, { m: "Fév", v: 210 },
  { m: "Mar", v: 195 }, { m: "Avr", v: 260 }, { m: "Mai", v: 320 },
];

const weekly = [
  { d: "Lun", v: 3 }, { d: "Mar", v: 5 }, { d: "Mer", v: 2 },
  { d: "Jeu", v: 6 }, { d: "Ven", v: 8 }, { d: "Sam", v: 9 }, { d: "Dim", v: 1 },
];

const recentBookings = [
  { id: "BK-2089", client: "Claire Dipita", service: "Réparation fuite cuisine", date: "Aujourd'hui · 14:00", amount: 15000, status: "Confirmée" },
  { id: "BK-2088", client: "Marc Tchana",   service: "Installation robinet",     date: "Demain · 09:00",      amount: 8000,  status: "En attente" },
  { id: "BK-2086", client: "Léa Kouamé",    service: "Débouchage évier",          date: "26 mai · 11:00",      amount: 12000, status: "Confirmée" },
  { id: "BK-2084", client: "Paul Nlend",    service: "Devis salle de bain",       date: "28 mai · 16:00",      amount: 5000,  status: "En attente" },
  { id: "BK-2080", client: "Sylvie Manga",  service: "Réparation chasse d'eau",   date: "20 mai",              amount: 9000,  status: "Terminée" },
];

const reviews = [
  { id: "r1", author: "Claire D.", rating: 5, comment: "Travail impeccable, ponctuel et soigné. Je recommande !", date: "Il y a 2 jours" },
  { id: "r2", author: "Marc T.",   rating: 5, comment: "Très professionnel, problème résolu en 30 min.",           date: "Il y a 4 jours" },
  { id: "r3", author: "Léa K.",    rating: 4, comment: "Bon service, juste un petit retard.",                       date: "La semaine dernière" },
];

function ProviderDashboard() {
  const totalEarnings = 320000;
  const kpis = [
    { label: "Revenus du mois",    value: formatXAF(totalEarnings), delta: "+18,2%", icon: DollarSign,    color: "bg-primary/15 text-primary" },
    { label: "Réservations actives", value: "12",                    delta: "+4",     icon: CalendarCheck, color: "bg-secondary/15 text-secondary" },
    { label: "Note moyenne",        value: "4,9/5",                  delta: "+0,1",   icon: Star,          color: "bg-gold/30 text-secondary" },
    { label: "Vues du profil",      value: "1 247",                   delta: "+23%",   icon: Eye,           color: "bg-accent text-primary" },
  ];

  return (
    <div className="space-y-6">
      {/* Greeting */}
      <div className="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <h1 className="font-display text-3xl font-bold">Bonjour Awa 👋</h1>
          <p className="text-muted-foreground mt-1">Voici la performance de votre activité ce mois-ci.</p>
        </div>
        <div className="flex items-center gap-2 text-xs">
          <span className="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-success/15 text-success font-semibold">
            <CheckCircle2 className="h-3.5 w-3.5" /> Profil vérifié
          </span>
          <span className="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-gold/30 text-secondary font-semibold">
            <Star className="h-3.5 w-3.5 fill-current" /> Top prestataire
          </span>
        </div>
      </div>

      {/* KPIs */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {kpis.map((k) => (
          <div key={k.label} className="bg-card border border-border rounded-2xl p-5">
            <div className="flex items-center justify-between">
              <div className={`h-10 w-10 rounded-lg flex items-center justify-center ${k.color}`}><k.icon className="h-5 w-5" /></div>
              <span className="inline-flex items-center gap-0.5 text-xs font-semibold text-success">
                <ArrowUpRight className="h-3 w-3" /> {k.delta}
              </span>
            </div>
            <div className="mt-3 font-display text-2xl font-bold">{k.value}</div>
            <div className="text-xs text-muted-foreground">{k.label}</div>
          </div>
        ))}
      </div>

      {/* Charts */}
      <div className="grid lg:grid-cols-3 gap-4">
        <div className="lg:col-span-2 bg-card border border-border rounded-2xl p-5">
          <div className="flex items-center justify-between mb-4">
            <div>
              <h2 className="font-display font-bold">Revenus mensuels</h2>
              <p className="text-xs text-muted-foreground">En milliers de FCFA — 6 derniers mois</p>
            </div>
            <span className="text-xs flex items-center gap-1 text-success font-semibold">
              <TrendingUp className="h-3.5 w-3.5" /> +23 % vs mois dernier
            </span>
          </div>
          <ResponsiveContainer width="100%" height={250}>
            <AreaChart data={earnings}>
              <defs>
                <linearGradient id="gp1" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#1E3A8A" stopOpacity={0.4} />
                  <stop offset="100%" stopColor="#1E3A8A" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
              <XAxis dataKey="m" stroke="#64748b" fontSize={12} />
              <YAxis stroke="#64748b" fontSize={12} />
              <Tooltip contentStyle={{ borderRadius: 8, border: "1px solid #e2e8f0" }} />
              <Area type="monotone" dataKey="v" stroke="#1E3A8A" strokeWidth={3} fill="url(#gp1)" />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        <div className="bg-card border border-border rounded-2xl p-5">
          <h2 className="font-display font-bold">Réservations / semaine</h2>
          <p className="text-xs text-muted-foreground">Cette semaine</p>
          <ResponsiveContainer width="100%" height={210} className="mt-3">
            <BarChart data={weekly}>
              <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" vertical={false} />
              <XAxis dataKey="d" stroke="#64748b" fontSize={12} />
              <YAxis stroke="#64748b" fontSize={12} />
              <Tooltip contentStyle={{ borderRadius: 8, border: "1px solid #e2e8f0" }} />
              <Bar dataKey="v" fill="#3B82F6" radius={[6, 6, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* Recent bookings */}
      <div className="bg-card border border-border rounded-2xl p-5">
        <div className="flex items-center justify-between mb-4">
          <div>
            <h2 className="font-display font-bold">Réservations récentes</h2>
            <p className="text-xs text-muted-foreground">Vos prochaines interventions et activité récente</p>
          </div>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm min-w-[640px]">
            <thead className="text-xs text-muted-foreground uppercase border-b border-border">
              <tr>
                <th className="text-left py-2 px-2">ID</th>
                <th className="text-left py-2 px-2">Client</th>
                <th className="text-left py-2 px-2">Service</th>
                <th className="text-left py-2 px-2">Date</th>
                <th className="text-right py-2 px-2">Montant</th>
                <th className="text-left py-2 px-2">Statut</th>
              </tr>
            </thead>
            <tbody>
              {recentBookings.map((b) => (
                <tr key={b.id} className="border-b border-border last:border-0 hover:bg-muted/40">
                  <td className="py-3 px-2 font-mono text-xs text-muted-foreground">{b.id}</td>
                  <td className="py-3 px-2 font-medium">{b.client}</td>
                  <td className="py-3 px-2 text-muted-foreground">{b.service}</td>
                  <td className="py-3 px-2 text-muted-foreground"><Clock className="h-3 w-3 inline mr-1" />{b.date}</td>
                  <td className="py-3 px-2 text-right font-semibold">{formatXAF(b.amount)}</td>
                  <td className="py-3 px-2">
                    <span className={`text-[10px] px-2 py-0.5 rounded-full font-semibold ${
                      b.status === "Confirmée" ? "bg-primary/15 text-primary" :
                      b.status === "Terminée"  ? "bg-success/15 text-success" :
                                                  "bg-warning/15 text-warning"
                    }`}>{b.status}</span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      <div className="grid lg:grid-cols-2 gap-4">
        {/* Services */}
        <div className="bg-card border border-border rounded-2xl p-5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-display font-bold">Mes services</h2>
            <Wrench className="h-4 w-4 text-muted-foreground" />
          </div>
          <div className="space-y-2">
            {[
              { name: "Réparation de fuite", price: 15000, count: 32 },
              { name: "Installation robinetterie", price: 8000, count: 18 },
              { name: "Débouchage canalisation", price: 12000, count: 24 },
              { name: "Devis sur site", price: 5000, count: 11 },
            ].map((s) => (
              <div key={s.name} className="flex items-center justify-between p-3 rounded-lg hover:bg-muted/40">
                <div>
                  <div className="text-sm font-medium">{s.name}</div>
                  <div className="text-xs text-muted-foreground">{s.count} réservations</div>
                </div>
                <div className="text-sm font-semibold text-primary">À partir de {formatXAF(s.price)}</div>
              </div>
            ))}
          </div>
        </div>

        {/* Reviews */}
        <div className="bg-card border border-border rounded-2xl p-5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-display font-bold">Derniers avis</h2>
            <MessageSquare className="h-4 w-4 text-muted-foreground" />
          </div>
          <div className="space-y-4">
            {reviews.map((r) => (
              <div key={r.id} className="border-b border-border last:border-0 pb-3 last:pb-0">
                <div className="flex items-center justify-between">
                  <div className="text-sm font-semibold">{r.author}</div>
                  <div className="flex items-center gap-0.5">
                    {Array.from({ length: 5 }).map((_, i) => (
                      <Star key={i} className={`h-3 w-3 ${i < r.rating ? "fill-gold text-gold" : "text-muted-foreground/30"}`} />
                    ))}
                  </div>
                </div>
                <p className="text-sm text-muted-foreground mt-1">{r.comment}</p>
                <div className="text-[11px] text-muted-foreground mt-1">{r.date}</div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
