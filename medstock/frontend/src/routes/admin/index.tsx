import { createFileRoute, Link } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { StatCard, PanelCard } from "@/components/admin/cards";
import {
  Pill, AlertTriangle, Calendar, XCircle, ShoppingCart, DollarSign, BellRing, Loader2,
} from "lucide-react";
import {
  AreaChart, Area, BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer, CartesianGrid, Cell,
} from "recharts";
import { formatDistanceToNow } from "date-fns";
import { fr } from "date-fns/locale";
import { ventesApi } from "@/lib/ventes";
import { medicamentsApi } from "@/lib/medicaments";
import { categoriesApi } from "@/lib/categories";
import api from "@/lib/api";
import { toast } from "sonner";

export const Route = createFileRoute("/admin/")({
  component: DashboardPage,
});

function DashboardPage() {
  const [loading, setLoading] = useState(true);
  const [stats, setStats] = useState({
    totalMedicaments: 0,
    stockFaible: 0,
    stockCritique: 0,
    procheExpiration: 0,
    expires: 0,
    ventesAujourdhui: 0,
    revenuMois: 0,
    revenuJour: 0,
    alertesActives: 0,
  });
  const [revenusMensuels, setRevenusMensuels] = useState<any[]>([]);
  const [ventesQuotidiennes, setVentesQuotidiennes] = useState<any[]>([]);
  const [topMedicaments, setTopMedicaments] = useState<any[]>([]);
  const [ventesRecentes, setVentesRecentes] = useState<any[]>([]);
  const [activitesRecentes, setActivitesRecentes] = useState<any[]>([]);

  useEffect(() => {
    fetchDashboardData();
  }, []);

  const parseNumber = (value: any): number => {
    if (typeof value === 'number') return value;
    if (typeof value === 'string') {
      const cleaned = value.replace(/[^\d,.-]/g, '').replace(',', '.');
      const parsed = parseFloat(cleaned);
      return isNaN(parsed) ? 0 : parsed;
    }
    return 0;
  };

  const fetchDashboardData = async () => {
    setLoading(true);
    try {
      // Récupérer les médicaments
      const medicamentsRes = await medicamentsApi.getAll();
      const medicaments = medicamentsRes.data.data;
      
      // Récupérer les catégories
      const categoriesRes = await categoriesApi.getAll();
      
      // Récupérer les ventes
      const ventesRes = await ventesApi.getAll();
      const ventes = ventesRes.data.data;
      
      // Récupérer les activités
      const activitesRes = await api.get('/activites');
      const activites = activitesRes.data.data;
      
      // Calculer les stats
      const now = new Date();
      const today = now.toISOString().split('T')[0];
      const currentMonth = now.getMonth();
      const currentYear = now.getFullYear();
      
      const ventesAujourdhui = ventes.filter((v: any) => v.created_at.split('T')[0] === today);
      const ventesDuMois = ventes.filter((v: any) => {
        const date = new Date(v.created_at);
        return date.getMonth() === currentMonth && date.getFullYear() === currentYear;
      });
      
      const stockFaible = medicaments.filter((m: any) => m.quantite > 0 && m.quantite <= m.seuil_alerte).length;
      const stockCritique = medicaments.filter((m: any) => m.quantite === 0).length;
      
      const procheExpiration = medicaments.filter((m: any) => {
        if (!m.date_expiration) return false;
        const expDate = new Date(m.date_expiration);
        const diffDays = Math.ceil((expDate.getTime() - now.getTime()) / (1000 * 60 * 60 * 24));
        return diffDays > 0 && diffDays <= 90;
      }).length;
      
      const expires = medicaments.filter((m: any) => {
        if (!m.date_expiration) return false;
        const expDate = new Date(m.date_expiration);
        return expDate < now;
      }).length;
      
      // Revenus mensuels (6 derniers mois)
      const last6Months = Array.from({ length: 6 }, (_, i) => {
        const d = new Date();
        d.setMonth(d.getMonth() - i);
        return { year: d.getFullYear(), month: d.getMonth(), label: d.toLocaleDateString('fr-FR', { month: 'short' }) };
      }).reverse();
      
      const revenusParMois = last6Months.map(({ year, month, label }) => {
        const ventesDuMois = ventes.filter((v: any) => {
          const date = new Date(v.created_at);
          return date.getFullYear() === year && date.getMonth() === month;
        });
        return {
          mois: label,
          revenu: ventesDuMois.reduce((sum: number, v: any) => sum + parseNumber(v.total), 0)
        };
      });
      setRevenusMensuels(revenusParMois);
      
      // Ventes quotidiennes (7 derniers jours)
      const last7Days = Array.from({ length: 7 }, (_, i) => {
        const d = new Date();
        d.setDate(d.getDate() - i);
        return d.toISOString().split('T')[0];
      }).reverse();
      
      const ventesParJour = last7Days.map(jour => {
        const ventesDuJour = ventes.filter((v: any) => v.created_at.split('T')[0] === jour);
        return {
          jour: new Date(jour).toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' }),
          ventes: ventesDuJour.length,
          total: ventesDuJour.reduce((sum: number, v: any) => sum + parseNumber(v.total), 0)
        };
      });
      setVentesQuotidiennes(ventesParJour);
      
      // Top médicaments par quantité vendue
      const ventesParMedicament: Record<string, { nom: string; ventes: number }> = {};
      
      for (const vente of ventes.slice(0, 20)) {
        try {
          const detailRes = await ventesApi.getById(vente.id);
          const lignes = detailRes.data.data.lignes || [];
          for (const ligne of lignes) {
            const nom = ligne.nom_snapshot || 'Inconnu';
            const quantite = typeof ligne.quantite === 'string' ? parseInt(ligne.quantite) : ligne.quantite;
            if (!ventesParMedicament[nom]) {
              ventesParMedicament[nom] = { nom, ventes: 0 };
            }
            ventesParMedicament[nom].ventes += quantite;
          }
        } catch (e) {
          console.error("Erreur récupération détails vente:", vente.id);
        }
      }
      
      const top5 = Object.values(ventesParMedicament)
        .sort((a, b) => b.ventes - a.ventes)
        .slice(0, 5);
      setTopMedicaments(top5);
      
      // Ventes récentes
      const recentes = ventes.slice(0, 5).map((v: any) => ({
        id: v.id,
        numero: v.numero,
        total: parseNumber(v.total),
        created_at: v.created_at,
      }));
      setVentesRecentes(recentes);
      
      // Activités récentes
      const recentesActivites = activites.slice(0, 6).map((a: any) => ({
        id: a.id,
        utilisateur_nom: a.utilisateur_nom || "Système",
        action: a.action,
        entite: a.entite,
        created_at: a.created_at,
      }));
      setActivitesRecentes(recentesActivites);
      
      setStats({
        totalMedicaments: medicaments.length,
        stockFaible,
        stockCritique,
        procheExpiration,
        expires,
        ventesAujourdhui: ventesAujourdhui.length,
        revenuMois: ventesDuMois.reduce((sum: number, v: any) => sum + parseNumber(v.total), 0),
        revenuJour: ventesAujourdhui.reduce((sum: number, v: any) => sum + parseNumber(v.total), 0),
        alertesActives: stockFaible + stockCritique + procheExpiration + expires,
      });
      
    } catch (error) {
      console.error("Error fetching dashboard data:", error);
      toast.error("Erreur lors du chargement du tableau de bord");
    } finally {
      setLoading(false);
    }
  };

  const formatPrice = (price: number) => {
    if (isNaN(price)) return "0 FCFA";
    return price.toLocaleString('fr-FR') + ' FCFA';
  };

  if (loading) {
    return (
      <AdminLayout>
        <div className="flex h-96 items-center justify-center">
          <Loader2 className="h-8 w-8 animate-spin text-primary" />
        </div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <PageHeader
        title="Tableau de bord"
        description={`Bienvenue. Vue globale de votre pharmacie · ${new Date().toLocaleDateString("fr-FR", { weekday: "long", day: "numeric", month: "long" })}`}
        actions={
          <Link
            to="/admin/nouvelle-vente"
            className="inline-flex h-10 items-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground shadow transition hover:opacity-95"
          >
            <ShoppingCart className="h-4 w-4" />
            Nouvelle vente
          </Link>
        }
      />

      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard title="Médicaments" value={stats.totalMedicaments} icon={Pill} accent="primary" />
        <StatCard title="Stock faible" value={stats.stockFaible} icon={AlertTriangle} accent="warning" description="Réapprovisionnement requis" />
        <StatCard title="Proche expiration" value={stats.procheExpiration} icon={Calendar} accent="warning" description="< 90 jours" />
        <StatCard title="Médicaments expirés" value={stats.expires} icon={XCircle} accent="destructive" description="À retirer" />
      </div>

      <div className="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <StatCard title="Ventes aujourd'hui" value={stats.ventesAujourdhui} icon={ShoppingCart} accent="info" />
        <StatCard title="Revenu du jour" value={formatPrice(stats.revenuJour)} icon={DollarSign} accent="success" />
        <StatCard title="Alertes actives" value={stats.alertesActives} icon={BellRing} accent="destructive" description="Notifications système" />
      </div>

      <div className="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
        <PanelCard title="Revenus mensuels" className="lg:col-span-2">
          <ResponsiveContainer width="100%" height={280}>
            <AreaChart data={revenusMensuels} margin={{ top: 10, right: 10, left: -10, bottom: 0 }}>
              <defs>
                <linearGradient id="grad-revenu" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stopColor="#3b82f6" stopOpacity={0.4} />
                  <stop offset="100%" stopColor="#3b82f6" stopOpacity={0} />
                </linearGradient>
              </defs>
              <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" vertical={false} />
              <XAxis dataKey="mois" fontSize={11} tickLine={false} axisLine={false} />
              <YAxis fontSize={11} tickLine={false} axisLine={false} tickFormatter={(v) => `${(v / 1000000).toFixed(1)}M`} />
              <Tooltip
                contentStyle={{ borderRadius: 12, border: "1px solid #e2e8f0" }}
                formatter={(v) => formatPrice(Number(v))}
              />
              <Area type="monotone" dataKey="revenu" stroke="#3b82f6" strokeWidth={2.5} fill="url(#grad-revenu)" />
            </AreaChart>
          </ResponsiveContainer>
        </PanelCard>

        <PanelCard title="Ventes hebdomadaires">
          <ResponsiveContainer width="100%" height={280}>
            <BarChart data={ventesQuotidiennes} margin={{ top: 10, right: 0, left: -20, bottom: 0 }}>
              <CartesianGrid strokeDasharray="3 3" stroke="#e2e8f0" vertical={false} />
              <XAxis dataKey="jour" fontSize={11} tickLine={false} axisLine={false} />
              <YAxis fontSize={11} tickLine={false} axisLine={false} />
              <Tooltip contentStyle={{ borderRadius: 12, border: "1px solid #e2e8f0" }} />
              <Bar dataKey="ventes" radius={[6, 6, 0, 0]} fill="#3b82f6" />
            </BarChart>
          </ResponsiveContainer>
        </PanelCard>
      </div>

      <div className="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
        <PanelCard title="Médicaments les plus vendus" className="lg:col-span-2">
          {topMedicaments.length === 0 ? (
            <div className="py-8 text-center text-muted-foreground">Aucune donnée disponible</div>
          ) : (
            <div className="space-y-3">
              {topMedicaments.map((m, i) => {
                const pct = (m.ventes / topMedicaments[0].ventes) * 100;
                return (
                  <div key={m.nom}>
                    <div className="mb-1 flex items-center justify-between text-sm">
                      <div className="flex items-center gap-2">
                        <span className="flex h-6 w-6 items-center justify-center rounded-md bg-primary/10 text-xs font-bold text-primary">{i + 1}</span>
                        <span className="font-medium">{m.nom}</span>
                      </div>
                      <span className="text-sm font-semibold text-muted-foreground">{m.ventes} ventes</span>
                    </div>
                    <div className="h-2 overflow-hidden rounded-full bg-muted">
                      <div className="h-full bg-primary transition-all" style={{ width: `${pct}%` }} />
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </PanelCard>

        <PanelCard title="Activité récente">
          {activitesRecentes.length === 0 ? (
            <div className="py-8 text-center text-muted-foreground">Aucune activité récente</div>
          ) : (
            <div className="space-y-3">
              {activitesRecentes.map((a) => (
                <div key={a.id} className="flex items-start gap-3">
                  <div className="mt-1 h-2 w-2 shrink-0 rounded-full bg-primary animate-pulse" />
                  <div className="min-w-0 flex-1">
                    <div className="text-sm font-medium truncate">{a.utilisateur_nom}</div>
                    <div className="text-xs text-muted-foreground truncate">{a.action}</div>
                    <div className="mt-0.5 text-[11px] text-muted-foreground">
                      {formatDistanceToNow(new Date(a.created_at), { addSuffix: true, locale: fr })}
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </PanelCard>
      </div>

      <div className="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-2">
        <PanelCard title="Ventes récentes">
          {ventesRecentes.length === 0 ? (
            <div className="py-8 text-center text-muted-foreground">Aucune vente récente</div>
          ) : (
            <div className="space-y-2">
              {ventesRecentes.map((v) => (
                <div key={v.id} className="flex items-center justify-between rounded-lg p-2 hover:bg-muted/50">
                  <div>
                    <div className="text-sm font-semibold">{v.numero}</div>
                    <div className="text-xs text-muted-foreground">{new Date(v.created_at).toLocaleDateString('fr-FR')}</div>
                  </div>
                  <div className="text-sm font-bold text-primary">{formatPrice(v.total)}</div>
                </div>
              ))}
            </div>
          )}
        </PanelCard>

        <PanelCard title="Alertes rapides">
          <div className="space-y-2">
            {stats.stockFaible > 0 && (
              <div className="flex items-center justify-between rounded-lg bg-yellow-50 p-3">
                <div>
                  <div className="text-sm font-semibold">Stock faible</div>
                  <div className="text-xs text-muted-foreground">{stats.stockFaible} médicament(s) sous seuil</div>
                </div>
                <AlertTriangle className="h-5 w-5 text-yellow-600" />
              </div>
            )}
            {stats.procheExpiration > 0 && (
              <div className="flex items-center justify-between rounded-lg bg-orange-50 p-3">
                <div>
                  <div className="text-sm font-semibold">Expiration proche</div>
                  <div className="text-xs text-muted-foreground">{stats.procheExpiration} médicament(s) expirent bientôt</div>
                </div>
                <Calendar className="h-5 w-5 text-orange-600" />
              </div>
            )}
            {stats.expires > 0 && (
              <div className="flex items-center justify-between rounded-lg bg-red-50 p-3">
                <div>
                  <div className="text-sm font-semibold">Médicaments expirés</div>
                  <div className="text-xs text-muted-foreground">{stats.expires} médicament(s) à retirer</div>
                </div>
                <XCircle className="h-5 w-5 text-red-600" />
              </div>
            )}
            {stats.alertesActives === 0 && (
              <div className="py-8 text-center text-muted-foreground">Aucune alerte active</div>
            )}
          </div>
        </PanelCard>
      </div>
    </AdminLayout>
  );
}