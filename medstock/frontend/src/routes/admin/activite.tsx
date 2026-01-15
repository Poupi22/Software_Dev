import { createFileRoute } from "@tanstack/react-router";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { Activity, ShoppingCart, Package, UserPlus, LogIn, Pill, Loader2 } from "lucide-react";
import { useEffect, useState } from "react";
import { formatDistanceToNow } from "date-fns";
import { fr } from "date-fns/locale";
import api from "@/lib/api";
import { toast } from "sonner";

export const Route = createFileRoute("/admin/activite")({
  component: ActivitePage,
});

interface Activite {
  id: string;
  utilisateur_id: string;
  utilisateur_nom: string;
  action: string;
  entite: string;
  entite_id: string;
  details: any;
  ip_address: string;
  created_at: string;
}

const getIconAndColor = (action: string, entite: string) => {
  const actionLower = action.toLowerCase();
  const entiteLower = entite?.toLowerCase() || "";
  
  if (actionLower.includes("vente") || entiteLower === "vente") {
    return { icon: ShoppingCart, cls: "bg-primary/10 text-primary" };
  }
  if (actionLower.includes("stock") || actionLower.includes("medicament") || entiteLower === "medicament") {
    return { icon: Pill, cls: "bg-primary/10 text-primary" };
  }
  if (actionLower.includes("user") || actionLower.includes("utilisateur") || actionLower.includes("gerant")) {
    return { icon: UserPlus, cls: "bg-info/10 text-info" };
  }
  if (actionLower.includes("login") || actionLower.includes("logout") || actionLower.includes("auth")) {
    return { icon: LogIn, cls: "bg-warning/15 text-warning" };
  }
  if (actionLower.includes("categorie")) {
    return { icon: Package, cls: "bg-success/10 text-success" };
  }
  return { icon: Activity, cls: "bg-muted/10 text-muted-foreground" };
};

const getFrenchAction = (action: string) => {
  const actionMap: Record<string, string> = {
    "LOGIN": "Connexion",
    "LOGOUT": "Déconnexion",
    "CREATE_MEDICAMENT": "Création médicament",
    "UPDATE_MEDICAMENT": "Modification médicament",
    "DELETE_MEDICAMENT": "Suppression médicament",
    "CREATE_CATEGORIE": "Création catégorie",
    "UPDATE_CATEGORIE": "Modification catégorie",
    "DELETE_CATEGORIE": "Suppression catégorie",
    "CREATE_VENTE": "Nouvelle vente",
    "CREATE_GERANT": "Création gérant",
    "UPDATE_GERANT": "Modification gérant",
    "DELETE_GERANT": "Suppression gérant",
    "RESET_PASSWORD": "Réinitialisation mot de passe",
  };
  return actionMap[action] || action;
};

function ActivitePage() {
  const [activites, setActivites] = useState<Activite[]>([]);
  const [loading, setLoading] = useState(true);
  const [tick, setTick] = useState(0);

  useEffect(() => {
    fetchActivites();
    const interval = setInterval(() => {
      setTick((t) => t + 1);
      fetchActivites();
    }, 30000);
    return () => clearInterval(interval);
  }, []);

  const fetchActivites = async () => {
    try {
      const res = await api.get('/activites');
      setActivites(res.data.data);
    } catch (error) {
      console.error("Error fetching activites:", error);
      toast.error("Erreur lors du chargement de l'activité");
    } finally {
      setLoading(false);
    }
  };

  const getInitials = (nom: string) => {
    return nom.split(" ").map(n => n[0]).slice(0, 2).join("").toUpperCase();
  };

  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString("fr-FR", {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };

  const formatTime = (date: string) => {
    return new Date(date).toLocaleTimeString("fr-FR", { 
      hour: "2-digit", 
      minute: "2-digit",
      second: "2-digit"
    });
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
        title="Activité système temps réel"
        description="Monitoring en direct des actions des utilisateurs"
        actions={
          <div className="flex items-center gap-2 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
            <span className="relative flex h-2 w-2">
              <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-500 opacity-75" />
              <span className="relative inline-flex h-2 w-2 rounded-full bg-green-500" />
            </span>
            Live · {tick > 0 ? "actualisé" : "en direct"}
          </div>
        }
      />

      <div className="overflow-hidden rounded-2xl border bg-card shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-secondary/50 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
              <tr>
                <th className="px-4 py-3">Utilisateur</th>
                <th className="px-4 py-3">Action</th>
                <th className="px-4 py-3">Cible</th>
                <th className="px-4 py-3">IP</th>
                <th className="px-4 py-3">Date</th>
                <th className="px-4 py-3">Heure</th>
              </tr>
            </thead>
            <tbody>
              {activites.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-4 py-16 text-center">
                    <div className="flex flex-col items-center gap-2 text-muted-foreground">
                      <Activity className="h-10 w-10 opacity-30" />
                      <div className="text-sm">Aucune activité récente</div>
                    </div>
                  </td>
                </tr>
              ) : (
                activites.map((a) => {
                  const { icon: Icon, cls } = getIconAndColor(a.action, a.entite);
                  const date = new Date(a.created_at);
                  return (
                    <tr key={a.id} className="border-t text-sm hover:bg-muted/30">
                      <td className="px-4 py-3">
                        <div className="flex items-center gap-3">
                          <div className="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-primary-foreground">
                            {getInitials(a.utilisateur_nom || "Utilisateur")}
                          </div>
                          <span className="font-medium">{a.utilisateur_nom || "Inconnu"}</span>
                        </div>
                      </td>
                      <td className="px-4 py-3">
                        <div className="flex items-center gap-2">
                          <div className={`flex h-7 w-7 items-center justify-center rounded-lg ${cls}`}>
                            <Icon className="h-3.5 w-3.5" />
                          </div>
                          <span className="font-medium">{getFrenchAction(a.action)}</span>
                        </div>
                       </td>
                      <td className="px-4 py-3 text-muted-foreground">
                        {a.entite ? `${a.entite}${a.entite_id ? ` (${a.entite_id.slice(0, 8)})` : ''}` : "-"}
                       </td>
                      <td className="px-4 py-3 text-muted-foreground font-mono text-xs">{a.ip_address || "-"}</td>
                      <td className="px-4 py-3 text-muted-foreground">{formatDate(a.created_at)}</td>
                      <td className="px-4 py-3 text-muted-foreground">
                        <div>{formatTime(a.created_at)}</div>
                        <div className="text-xs">{formatDistanceToNow(date, { addSuffix: true, locale: fr })}</div>
                      </td>
                     </tr>
                  );
                })
              )}
            </tbody>
           </table>
        </div>
      </div>
    </AdminLayout>
  );
}