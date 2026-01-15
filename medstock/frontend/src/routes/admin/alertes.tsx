import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { AlertTriangle, Calendar, Package, XCircle, Loader2, CheckCircle, Eye, Clock } from "lucide-react";
import { cn } from "@/lib/utils";
import { toast } from "sonner";
import api from "@/lib/api";
import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { useAuth } from "@/lib/auth";

export const Route = createFileRoute("/admin/alertes")({
  component: AlertesPage,
});

interface Alerte {
  id: string;
  medicament_id: string | null;
  type: string;
  statut: string;
  titre: string;
  message: string;
  traite_par: string | null;
  traite_at: string | null;
  created_at: string;
  medicament_nom?: string;
}

function AlertesPage() {
  const { user } = useAuth();
  const [alertes, setAlertes] = useState<Alerte[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedAlerte, setSelectedAlerte] = useState<Alerte | null>(null);
  const [detailOpen, setDetailOpen] = useState(false);

  useEffect(() => {
    fetchAlertes();
  }, []);

  const fetchAlertes = async () => {
    setLoading(true);
    try {
      const res = await api.get('/alertes');
      setAlertes(res.data.data);
    } catch (error) {
      console.error("Error fetching alertes:", error);
      toast.error("Erreur lors du chargement des alertes");
    } finally {
      setLoading(false);
    }
  };

  const marquerLue = async (id: string) => {
    try {
      await api.put(`/alertes/${id}/lue`);
      toast.success("Alerte marquee comme lue");
      fetchAlertes();
    } catch (error: any) {
      console.error("Erreur:", error);
      toast.error(error.response?.data?.message || "Erreur lors de la mise a jour");
    }
  };

  const traiterAlerte = async (id: string) => {
    try {
      await api.put(`/alertes/${id}/traiter`, { commentaire: "Traite par " + user?.nom });
      toast.success("Alerte traitee avec succes");
      fetchAlertes();
    } catch (error: any) {
      console.error("Erreur:", error);
      toast.error(error.response?.data?.message || "Erreur lors du traitement");
    }
  };

  const genererAlertes = async () => {
    try {
      await api.post('/alertes/generer');
      toast.success("Alertes generees avec succes");
      fetchAlertes();
    } catch (error: any) {
      console.error("Erreur:", error);
      toast.error(error.response?.data?.message || "Erreur lors de la generation");
    }
  };

  const openDetail = (alerte: Alerte) => {
    setSelectedAlerte(alerte);
    setDetailOpen(true);
  };

  const formatDate = (date: string) => {
    if (!date) return "Date inconnue";
    return new Date(date).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const getStatusBadge = (statut: string) => {
    switch (statut) {
      case 'NOUVELLE':
        return <span className="rounded-full bg-red-100 px-2 py-0.5 text-xs font-semibold text-red-700">Nouvelle</span>;
      case 'LUE':
        return <span className="rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-700">Lue</span>;
      case 'TRAITEE':
        return <span className="rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700">Traitee</span>;
      default:
        return <span className="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-700">{statut}</span>;
    }
  };

  const alertesNonTraitees = alertes.filter(a => a.statut !== 'TRAITEE');
  const alertesNouvelles = alertes.filter(a => a.statut === 'NOUVELLE');
  const alertesLues = alertes.filter(a => a.statut === 'LUE');
  const alertesTraitees = alertes.filter(a => a.statut === 'TRAITEE');

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
        title="Alertes intelligentes" 
        description="Surveillance temps reel du stock et des expirations"
        actions={
          <button
            onClick={genererAlertes}
            className="flex h-10 items-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground hover:bg-primary/90"
          >
            <Clock className="h-4 w-4" />
            Generer les alertes
          </button>
        }
      />

      {/* Resume cards */}
      <div className="mb-6 grid gap-4 sm:grid-cols-4">
        <div className="rounded-xl border bg-card p-4">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-muted-foreground">Nouvelles</p>
              <p className="text-2xl font-bold text-red-600">{alertesNouvelles.length}</p>
            </div>
            <AlertTriangle className="h-8 w-8 text-red-500 opacity-50" />
          </div>
        </div>
        <div className="rounded-xl border bg-card p-4">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-muted-foreground">Lues</p>
              <p className="text-2xl font-bold text-yellow-600">{alertesLues.length}</p>
            </div>
            <Eye className="h-8 w-8 text-yellow-500 opacity-50" />
          </div>
        </div>
        <div className="rounded-xl border bg-card p-4">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-muted-foreground">Traitees</p>
              <p className="text-2xl font-bold text-green-600">{alertesTraitees.length}</p>
            </div>
            <CheckCircle className="h-8 w-8 text-green-500 opacity-50" />
          </div>
        </div>
        <div className="rounded-xl border bg-card p-4">
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm text-muted-foreground">Total</p>
              <p className="text-2xl font-bold text-primary">{alertes.length}</p>
            </div>
            <Package className="h-8 w-8 text-primary opacity-50" />
          </div>
        </div>
      </div>

      {/* Alertes list */}
      <div className="overflow-hidden rounded-2xl border bg-card shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-secondary/50 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
              <tr>
                <th className="px-4 py-3">Type</th>
                <th className="px-4 py-3">Titre</th>
                <th className="px-4 py-3">Medicament</th>
                <th className="px-4 py-3">Statut</th>
                <th className="px-4 py-3">Date</th>
                <th className="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {alertes.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-4 py-16 text-center">
                    <div className="flex flex-col items-center gap-2 text-muted-foreground">
                      <CheckCircle className="h-10 w-10 opacity-30" />
                      <div className="text-sm">Aucune alerte</div>
                    </div>
                  </td>
                </tr>
              ) : (
                alertes.map((alerte) => {
                  const isNouvelle = alerte.statut === 'NOUVELLE';
                  const isTraitee = alerte.statut === 'TRAITEE';
                  
                  return (
                    <tr key={alerte.id} className={`border-t text-sm hover:bg-muted/30 ${isNouvelle ? 'bg-red-50/50' : isTraitee ? 'opacity-60' : ''}`}>
                      <td className="px-4 py-3">
                        <div className={cn("inline-flex rounded-full px-2 py-0.5 text-xs font-semibold",
                          alerte.type === 'STOCK_CRITIQUE' ? "bg-red-100 text-red-700" :
                          alerte.type === 'STOCK_BAS' ? "bg-yellow-100 text-yellow-700" :
                          alerte.type === 'EXPIRATION_PROCHE' ? "bg-orange-100 text-orange-700" :
                          alerte.type === 'EXPIRE' ? "bg-purple-100 text-purple-700" :
                          "bg-gray-100 text-gray-700"
                        )}>
                          {alerte.type === 'STOCK_CRITIQUE' ? "Stock critique" :
                           alerte.type === 'STOCK_BAS' ? "Stock bas" :
                           alerte.type === 'EXPIRATION_PROCHE' ? "Expiration proche" :
                           alerte.type === 'EXPIRE' ? "Expire" : alerte.type}
                        </div>
                       </td>
                      <td className="px-4 py-3 font-semibold">{alerte.titre}</td>
                      <td className="px-4 py-3 text-muted-foreground">{alerte.medicament_nom || "General"}</td>
                      <td className="px-4 py-3">{getStatusBadge(alerte.statut)}</td>
                      <td className="px-4 py-3 text-muted-foreground">{formatDate(alerte.created_at)}</td>
                      <td className="px-4 py-3 text-right">
                        <div className="flex items-center justify-end gap-1">
                          <button 
                            onClick={() => openDetail(alerte)}
                            className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-primary"
                            title="Voir details"
                          >
                            <Eye className="h-4 w-4" />
                          </button>
                          {!isTraitee && (
                            <button 
                              onClick={() => traiterAlerte(alerte.id)}
                              className="rounded-lg p-1.5 text-green-600 hover:bg-green-50"
                              title="Traiter"
                            >
                              <CheckCircle className="h-4 w-4" />
                            </button>
                          )}
                        </div>
                       </td>
                     </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Detail Dialog */}
      <Dialog open={detailOpen} onOpenChange={setDetailOpen}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>Details de l'alerte</DialogTitle>
          </DialogHeader>
          {selectedAlerte && (
            <div className="space-y-3">
              <div className="grid grid-cols-2 gap-2 text-sm">
                <div className="font-semibold">Type:</div>
                <div>{selectedAlerte.type}</div>
                
                <div className="font-semibold">Titre:</div>
                <div>{selectedAlerte.titre}</div>
                
                <div className="font-semibold">Statut:</div>
                <div>{selectedAlerte.statut}</div>
                
                <div className="font-semibold">Date creation:</div>
                <div>{formatDate(selectedAlerte.created_at)}</div>
                
                {selectedAlerte.traite_at && (
                  <>
                    <div className="font-semibold">Date traitement:</div>
                    <div>{formatDate(selectedAlerte.traite_at)}</div>
                  </>
                )}
                
                <div className="font-semibold">Message:</div>
                <div className="col-span-2">{selectedAlerte.message}</div>
              </div>
            </div>
          )}
        </DialogContent>
      </Dialog>
    </AdminLayout>
  );
}
