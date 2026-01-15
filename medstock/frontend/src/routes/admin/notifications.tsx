import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { AlertTriangle, Package, ShoppingCart, UserPlus, Calendar, CheckCircle2, Loader2, Eye } from "lucide-react";
import { useAuth } from "@/lib/auth";
import api from "@/lib/api";
import { toast } from "sonner";

export const Route = createFileRoute("/admin/notifications")({
  component: NotificationsPage,
});

interface Notification {
  id: string;
  utilisateur_id: string;
  titre: string;
  message: string;
  lien: string | null;
  lue: boolean;
  created_at: string;
}

function NotificationsPage() {
  const { user } = useAuth();
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchNotifications();
  }, []);

  const fetchNotifications = async () => {
    setLoading(true);
    try {
      const res = await api.get('/notifications');
      console.log("Notifications reçues:", res.data);
      setNotifications(res.data.data);
    } catch (error) {
      console.error("Error fetching notifications:", error);
      toast.error("Erreur lors du chargement des notifications");
    } finally {
      setLoading(false);
    }
  };

  const marquerLue = async (id: string) => {
    try {
      await api.put(`/notifications/${id}/lue`);
      setNotifications(prev => 
        prev.map(n => n.id === id ? { ...n, lue: true } : n)
      );
      toast.success("Notification marquee comme lue");
    } catch (error) {
      console.error("Error marking notification as read:", error);
      toast.error("Erreur lors du marquage");
    }
  };

  const toutMarquerLue = async () => {
    try {
      await api.put('/notifications/tout-lue');
      setNotifications(prev => 
        prev.map(n => ({ ...n, lue: true }))
      );
      toast.success("Toutes les notifications ont ete marquees comme lues");
    } catch (error) {
      console.error("Error marking all as read:", error);
      toast.error("Erreur lors du marquage");
    }
  };

  const getIcon = (titre: string) => {
    if (titre.includes("Stock") || titre.includes("critique")) return AlertTriangle;
    if (titre.includes("expire") || titre.includes("Expiration")) return Calendar;
    if (titre.includes("vente") || titre.includes("Vente")) return ShoppingCart;
    if (titre.includes("Utilisateur") || titre.includes("cree")) return UserPlus;
    if (titre.includes("entree") || titre.includes("stock")) return Package;
    return CheckCircle2;
  };

  const getColor = (titre: string) => {
    if (titre.includes("Stock") || titre.includes("critique")) return "destructive";
    if (titre.includes("expire") || titre.includes("Expiration")) return "destructive";
    if (titre.includes("vente") || titre.includes("Vente")) return "primary";
    if (titre.includes("Utilisateur") || titre.includes("cree")) return "info";
    if (titre.includes("entree") || titre.includes("stock")) return "success";
    return "success";
  };

  const formatRelativeTime = (date: string) => {
    const now = new Date();
    const notifDate = new Date(date);
    const diffMs = now.getTime() - notifDate.getTime();
    const diffMins = Math.floor(diffMs / (1000 * 60));
    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

    if (diffMins < 1) return "à l'instant";
    if (diffMins < 60) return `il y a ${diffMins} min`;
    if (diffHours < 24) return `il y a ${diffHours} h`;
    if (diffDays === 1) return "hier";
    return `il y a ${diffDays} jours`;
  };

  const nonLues = notifications.filter(n => !n.lue).length;

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
        title="Centre de notifications" 
        description={`${notifications.length} notification${notifications.length > 1 ? "s" : ""} · ${nonLues} non lue${nonLues > 1 ? "s" : ""}`}
        actions={
          nonLues > 0 && (
            <button
              onClick={toutMarquerLue}
              className="flex h-10 items-center gap-2 rounded-lg border border-primary px-4 text-sm font-semibold text-primary hover:bg-primary/10"
            >
              <Eye className="h-4 w-4" />
              Tout marquer comme lu
            </button>
          )
        }
      />

      <div className="rounded-2xl border bg-card shadow-sm">
        {notifications.length === 0 ? (
          <div className="flex flex-col items-center justify-center py-16 text-center">
            <div className="rounded-full bg-muted p-4">
              <CheckCircle2 className="h-8 w-8 text-muted-foreground" />
            </div>
            <h3 className="mt-4 text-lg font-semibold">Aucune notification</h3>
            <p className="mt-1 text-sm text-muted-foreground">
              Vous serez notifie des evenements importants ici
            </p>
          </div>
        ) : (
          notifications.map((notif) => {
            const Icon = getIcon(notif.titre);
            const color = getColor(notif.titre);
            const colorClasses = {
              destructive: "bg-red-100 text-red-700",
              primary: "bg-primary/10 text-primary",
              info: "bg-blue-100 text-blue-700",
              success: "bg-green-100 text-green-700",
              warning: "bg-yellow-100 text-yellow-700",
            }[color] || "bg-gray-100 text-gray-700";
            
            return (
              <div 
                key={notif.id} 
                className={`flex cursor-pointer gap-4 border-b px-5 py-4 last:border-0 hover:bg-muted/30 transition ${
                  !notif.lue ? "bg-primary-soft/30" : ""
                }`}
                onClick={() => !notif.lue && marquerLue(notif.id)}
              >
                <div className={`flex h-11 w-11 shrink-0 items-center justify-center rounded-xl ${colorClasses}`}>
                  <Icon className="h-5 w-5" />
                </div>
                <div className="min-w-0 flex-1">
                  <div className="font-semibold">{notif.titre}</div>
                  <div className="mt-0.5 text-sm text-muted-foreground">{notif.message}</div>
                  <div className="mt-1 text-xs text-muted-foreground">
                    {formatRelativeTime(notif.created_at)}
                  </div>
                </div>
                {!notif.lue && <div className="h-2 w-2 self-start rounded-full bg-primary mt-2" />}
              </div>
            );
          })
        )}
      </div>
    </AdminLayout>
  );
}
