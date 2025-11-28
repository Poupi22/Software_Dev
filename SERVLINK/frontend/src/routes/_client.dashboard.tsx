import { createFileRoute, Link } from "@tanstack/react-router";
import {
  CalendarCheck, Star, Heart, ArrowUpRight, ArrowDownRight,
  TrendingUp, Clock, CheckCircle2, MessageCircle, Plus, Bell, CreditCard,
} from "lucide-react";
import { ResponsiveContainer, XAxis, YAxis, Tooltip, CartesianGrid, AreaChart, Area } from "recharts";
import { Button } from "@/components/ui/button";
import { bookings, threads, formatXAF } from "@/lib/mock-data";
import { RequireAuth } from "@/components/RequireAuth";

export const Route = createFileRoute("/_client/dashboard")({
  head: () => ({ meta: [{ title: "Mon espace — SERVLINK" }] }),
  component: () => <RequireAuth roles={["client"]}><ClientDashboard /></RequireAuth>,
});

const spendingSeries = [
  { m: "Déc", v: 18 }, { m: "Jan", v: 24 }, { m: "Fév", v: 32 },
  { m: "Mar", v: 28 }, { m: "Avr", v: 41 }, { m: "Mai", v: 55 },
];

const clientTx = [
  { id: "TX-2042", label: "Réparation fuite cuisine", provider: "Awa Nkomo", method: "MTN MoMo", amount: 15000, status: "Réussie", date: "24 mai 2026" },
  { id: "TX-2041", label: "Installation tableau électrique", provider: "Patrick Mbarga", method: "Orange Money", amount: 35000, status: "En attente", date: "22 mai 2026" },
  { id: "TX-2038", label: "Ménage hebdomadaire 4h", provider: "Sandrine Etoa", method: "Carte VISA", amount: 12000, status: "Réussie", date: "18 mai 2026" },
  { id: "TX-2035", label: "Coiffure à domicile", provider: "Marie Tchamba", method: "MTN MoMo", amount: 8000, status: "Réussie", date: "12 mai 2026" },
  { id: "TX-2029", label: "Dépannage PC", provider: "Yves Talla", method: "Orange Money", amount: 10000, status: "Remboursée", date: "08 mai 2026" },
  { id: "TX-2024", label: "Cours de mathématiques", provider: "Hervé Kameni", method: "Carte VISA", amount: 18000, status: "Réussie", date: "03 mai 2026" },
];

function ClientDashboard() {
  const totalSpent = clientTx.filter(t => t.status === "Réussie").reduce((s, t) => s + t.amount, 0);
  const upcoming = bookings.filter(b => b.status === "confirmed" || b.status === "pending").length;

  const kpis = [
    { label: "Dépenses du mois", value: formatXAF(totalSpent), delta: "+12,4%", up: true, icon: CreditCard, color: "bg-primary/15 text-primary" },
    { label: "Réservations à venir", value: String(upcoming), delta: "+2", up: true, icon: CalendarCheck, color: "bg-secondary/15 text-secondary" },
    { label: "Avis donnés", value: "5", delta: "+1", up: true, icon: Star, color: "bg-gold/30 text-secondary" },
    { label: "Prestataires favoris", value: "8", delta: "+3", up: true, icon: Heart, color: "bg-destructive/10 text-destructive" },
  ];

  return (
    <div className="container mx-auto px-4 py-8 space-y-6">
      {/* Greeting */}
      <div className="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
          <h1 className="font-display text-3xl font-bold">Bonjour Adèle 👋</h1>
          <p className="text-muted-foreground mt-1">Voici un récapitulatif de votre activité sur SERVLINK.</p>
        </div>
        <div className="flex gap-2">
          <Link to="/search"><Button><Plus className="h-4 w-4 mr-1" /> Nouvelle réservation</Button></Link>
          <Link to="/messages"><Button variant="outline"><MessageCircle className="h-4 w-4 mr-1" /> Messages</Button></Link>
        </div>
      </div>

      {/* KPIs */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {kpis.map((k) => (
          <div key={k.label} className="bg-card border border-border rounded-2xl p-5">
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
        {/* Spending chart */}
        <div className="lg:col-span-2 bg-card border border-border rounded-2xl p-5">
          <div className="flex items-center justify-between mb-4">
            <div>
              <h2 className="font-display font-bold">Mes dépenses</h2>
              <p className="text-xs text-muted-foreground">En milliers de FCFA — 6 derniers mois</p>
            </div>
            <span className="text-xs flex items-center gap-1 text-success font-semibold">
              <TrendingUp className="h-3.5 w-3.5" /> +24 % vs mois dernier
            </span>
          </div>
          <ResponsiveContainer width="100%" height={250}>
            <AreaChart data={spendingSeries}>
              <defs>
                <linearGradient id="g1" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#3B82F6" stopOpacity={0.35} />
                  <stop offset="100%" stopColor="#3B82F6" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" />
              <XAxis dataKey="m" stroke="#64748b" fontSize={12} />
              <YAxis stroke="#64748b" fontSize={12} />
              <Tooltip contentStyle={{ borderRadius: 8, border: "1px solid #e2e8f0" }} />
              <Area type="monotone" dataKey="v" stroke="#3B82F6" strokeWidth={3} fill="url(#g1)" />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        {/* Quick actions */}
        <div className="bg-card border border-border rounded-2xl p-5 flex flex-col">
          <h2 className="font-display font-bold">Raccourcis</h2>
          <p className="text-xs text-muted-foreground mt-0.5">Vos actions les plus fréquentes</p>
          <div className="mt-4 space-y-2">
            <Link to="/search" className="flex items-center justify-between p-3 rounded-lg border border-border hover:border-primary hover:bg-accent/40 transition">
              <div className="flex items-center gap-3"><div className="h-9 w-9 rounded-lg bg-primary/15 text-primary flex items-center justify-center"><Plus className="h-4 w-4" /></div><span className="text-sm font-medium">Nouvelle réservation</span></div>
              <span className="text-xs text-muted-foreground">→</span>
            </Link>
            <Link to="/bookings" className="flex items-center justify-between p-3 rounded-lg border border-border hover:border-primary hover:bg-accent/40 transition">
              <div className="flex items-center gap-3"><div className="h-9 w-9 rounded-lg bg-secondary/15 text-secondary flex items-center justify-center"><CalendarCheck className="h-4 w-4" /></div><span className="text-sm font-medium">Mes réservations</span></div>
              <span className="text-xs text-muted-foreground">→</span>
            </Link>
            <Link to="/messages" className="flex items-center justify-between p-3 rounded-lg border border-border hover:border-primary hover:bg-accent/40 transition">
              <div className="flex items-center gap-3"><div className="h-9 w-9 rounded-lg bg-accent text-primary flex items-center justify-center"><MessageCircle className="h-4 w-4" /></div><span className="text-sm font-medium">Messagerie</span></div>
              <span className="text-xs text-muted-foreground">→</span>
            </Link>
            <Link to="/profile" className="flex items-center justify-between p-3 rounded-lg border border-border hover:border-primary hover:bg-accent/40 transition">
              <div className="flex items-center gap-3"><div className="h-9 w-9 rounded-lg bg-gold/30 text-secondary flex items-center justify-center"><Star className="h-4 w-4" /></div><span className="text-sm font-medium">Mon profil</span></div>
              <span className="text-xs text-muted-foreground">→</span>
            </Link>
          </div>
        </div>
      </div>

      {/* Transactions table */}
      <div className="bg-card border border-border rounded-2xl p-5">
        <div className="flex items-center justify-between mb-4">
          <div>
            <h2 className="font-display font-bold">Mes transactions</h2>
            <p className="text-xs text-muted-foreground">Historique complet de vos paiements</p>
          </div>
          <Button variant="outline" size="sm">Exporter</Button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm min-w-[640px]">
            <thead className="text-xs text-muted-foreground uppercase border-b border-border">
              <tr>
                <th className="text-left py-2 px-2">ID</th>
                <th className="text-left py-2 px-2">Prestation</th>
                <th className="text-left py-2 px-2">Prestataire</th>
                <th className="text-left py-2 px-2">Moyen</th>
                <th className="text-left py-2 px-2">Date</th>
                <th className="text-right py-2 px-2">Montant</th>
                <th className="text-left py-2 px-2">Statut</th>
              </tr>
            </thead>
            <tbody>
              {clientTx.map((t) => (
                <tr key={t.id} className="border-b border-border last:border-0 hover:bg-muted/40">
                  <td className="py-3 px-2 font-mono text-xs text-muted-foreground">{t.id}</td>
                  <td className="py-3 px-2 font-medium">{t.label}</td>
                  <td className="py-3 px-2 text-muted-foreground">{t.provider}</td>
                  <td className="py-3 px-2"><span className="text-xs px-2 py-0.5 rounded-md bg-muted">{t.method}</span></td>
                  <td className="py-3 px-2 text-muted-foreground">{t.date}</td>
                  <td className="py-3 px-2 text-right font-semibold">{formatXAF(t.amount)}</td>
                  <td className="py-3 px-2">
                    <span className={`text-[10px] px-2 py-0.5 rounded-full font-semibold ${
                      t.status === "Réussie" ? "bg-success/15 text-success" :
                      t.status === "En attente" ? "bg-warning/15 text-warning" :
                      "bg-destructive/15 text-destructive"
                    }`}>{t.status}</span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      <div className="grid lg:grid-cols-2 gap-4">
        {/* Upcoming bookings */}
        <div className="bg-card border border-border rounded-2xl p-5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-display font-bold">Prochaines réservations</h2>
            <Link to="/bookings" className="text-xs text-primary hover:underline font-medium">Voir tout</Link>
          </div>
          <div className="space-y-3">
            {bookings.slice(0, 4).map((b) => (
              <div key={b.id} className="flex items-center gap-3 p-3 rounded-lg hover:bg-muted/60">
                <div className="h-10 w-10 rounded-lg bg-accent text-primary flex items-center justify-center">
                  <CalendarCheck className="h-5 w-5" />
                </div>
                <div className="flex-1 min-w-0">
                  <div className="text-sm font-medium truncate">{b.service}</div>
                  <div className="text-xs text-muted-foreground flex items-center gap-1"><Clock className="h-3 w-3" /> {b.date} · {b.provider}</div>
                </div>
                <div className="text-right">
                  <div className="text-sm font-semibold">{formatXAF(b.amount)}</div>
                  <span className={`text-[10px] px-2 py-0.5 rounded-full font-semibold ${
                    b.status === "completed" ? "bg-success/15 text-success" :
                    b.status === "confirmed" ? "bg-primary/15 text-primary" :
                    b.status === "pending" ? "bg-warning/15 text-warning" :
                    "bg-destructive/15 text-destructive"
                  }`}>{b.status}</span>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Activity */}
        <div className="bg-card border border-border rounded-2xl p-5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="font-display font-bold">Activité récente</h2>
            <Bell className="h-4 w-4 text-muted-foreground" />
          </div>
          <div className="space-y-4">
            {[
              { icon: CheckCircle2, color: "text-success bg-success/15", title: "Paiement validé", desc: "MTN MoMo — 15 000 FCFA → Awa Nkomo", time: "Il y a 2h" },
              { icon: MessageCircle, color: "text-primary bg-primary/15", title: "Nouveau message", desc: "Awa Nkomo : « Je serai chez vous à 10h pile »", time: "Il y a 3h" },
              { icon: Star, color: "text-gold-foreground bg-gold/40", title: "Avis publié", desc: "Vous avez noté Sandrine Etoa 5/5", time: "Hier" },
              { icon: Heart, color: "text-destructive bg-destructive/10", title: "Ajout aux favoris", desc: "Marie Tchamba — Coiffure & Beauté", time: "Il y a 3 jours" },
            ].map((a, i) => (
              <div key={i} className="flex items-start gap-3">
                <div className={`h-9 w-9 rounded-lg flex items-center justify-center shrink-0 ${a.color}`}><a.icon className="h-4 w-4" /></div>
                <div className="flex-1 min-w-0">
                  <div className="text-sm font-medium">{a.title}</div>
                  <div className="text-xs text-muted-foreground truncate">{a.desc}</div>
                </div>
                <div className="text-[11px] text-muted-foreground whitespace-nowrap">{a.time}</div>
              </div>
            ))}
            <div className="pt-2 border-t border-border">
              <div className="text-xs font-semibold text-muted-foreground uppercase mb-2">Messages récents</div>
              {threads.slice(0, 2).map((t) => (
                <Link key={t.id} to="/messages" className="flex items-center gap-3 py-2 hover:bg-muted/60 rounded-lg px-2">
                  <img src={t.avatar} alt="" className="h-9 w-9 rounded-full object-cover" />
                  <div className="flex-1 min-w-0">
                    <div className="text-sm font-medium truncate">{t.provider}</div>
                    <div className="text-xs text-muted-foreground truncate">{t.lastMessage}</div>
                  </div>
                  {t.unread > 0 && <span className="h-5 w-5 rounded-full bg-primary text-primary-foreground text-[10px] font-bold flex items-center justify-center">{t.unread}</span>}
                </Link>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
