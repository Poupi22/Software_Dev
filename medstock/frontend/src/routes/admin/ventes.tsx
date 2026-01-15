import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { Receipt, Eye, Loader2 } from "lucide-react";
import { toast } from "sonner";
import { ventesApi, type Vente, type VenteLigne } from "@/lib/ventes";
import { Dialog, DialogContent, DialogHeader, DialogTitle } from "@/components/ui/dialog";
import { useAuth } from "@/lib/auth";

export const Route = createFileRoute("/admin/ventes")({
  component: VentesPage,
});

function VentesPage() {
  const { user } = useAuth();
  const [ventes, setVentes] = useState<Vente[]>([]);
  const [loading, setLoading] = useState(true);
  const [detailOpen, setDetailOpen] = useState(false);
  const [selectedVente, setSelectedVente] = useState<Vente | null>(null);
  const [selectedLignes, setSelectedLignes] = useState<VenteLigne[]>([]);

  const isAdmin = user?.role === "ADMIN";

  useEffect(() => {
    fetchVentes();
  }, []);

  const fetchVentes = async () => {
    setLoading(true);
    try {
      const res = await ventesApi.getAll();
      let allVentes = res.data.data;
      
      // Si l'utilisateur n'est pas admin, filtrer ses propres ventes
      if (!isAdmin && user) {
        allVentes = allVentes.filter((vente: Vente) => vente.utilisateur_id === user.id);
      }
      
      setVentes(allVentes);
    } catch (error) {
      console.error("Error fetching ventes:", error);
      toast.error("Erreur lors du chargement des ventes");
    } finally {
      setLoading(false);
    }
  };

  const openDetail = async (vente: Vente) => {
    try {
      const res = await ventesApi.getById(vente.id);
      setSelectedVente(res.data.data.vente);
      setSelectedLignes(res.data.data.lignes);
      setDetailOpen(true);
    } catch (error) {
      toast.error("Erreur lors du chargement des details");
    }
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
  };

  const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const totalVentes = ventes.reduce((sum, v) => sum + v.total, 0);

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
        title="Historique des ventes" 
        description={isAdmin 
          ? `${ventes.length} ventes · Total : ${formatPrice(totalVentes)}` 
          : `Mes ventes · ${ventes.length} ventes · Total : ${formatPrice(totalVentes)}`
        } 
      />

      <div className="overflow-hidden rounded-2xl border bg-card shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-secondary/50 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
              <tr>
                <th className="px-4 py-3">N° Facture</th>
                <th className="px-4 py-3">Date</th>
                <th className="px-4 py-3">Client</th>
                <th className="px-4 py-3">WhatsApp</th>
                <th className="px-4 py-3">Mode</th>
                <th className="px-4 py-3 text-right">Total</th>
                <th className="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {ventes.length === 0 ? (
                <tr>
                  <td colSpan={7} className="px-4 py-16 text-center">
                    <div className="flex flex-col items-center gap-2 text-muted-foreground">
                      <Receipt className="h-10 w-10 opacity-30" />
                      <div className="text-sm">Aucune vente enregistree</div>
                      {!isAdmin && <div className="text-xs">Les ventes que vous effectuez apparaitront ici</div>}
                    </div>
                  </td>
                </tr>
              ) : (
                ventes.map((v) => (
                  <tr key={v.id} className="border-t text-sm hover:bg-muted/30">
                    <td className="px-4 py-3 font-mono text-xs font-semibold text-primary">{v.numero}</td>
                    <td className="px-4 py-3 text-muted-foreground">{formatDate(v.created_at)}</td>
                    <td className="px-4 py-3">{v.client_nom || "-"}</td>
                    <td className="px-4 py-3">{v.client_whatsapp || "-"}</td>
                    <td className="px-4 py-3">
                      <span className="rounded-md bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary">
                        {v.mode_paiement}
                      </span>
                    </td>
                    <td className="px-4 py-3 text-right font-bold">{formatPrice(v.total)}</td>
                    <td className="px-4 py-3 text-right">
                      <div className="flex items-center justify-end gap-1">
                        <button 
                          onClick={() => openDetail(v)}
                          className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-primary"
                          title="Voir details"
                        >
                          <Eye className="h-4 w-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Detail Dialog */}
      <Dialog open={detailOpen} onOpenChange={setDetailOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle>Details de la vente</DialogTitle>
          </DialogHeader>
          {selectedVente && (
            <div className="space-y-3">
              <div className="grid grid-cols-2 gap-2 text-sm">
                <div className="font-semibold">N° Facture:</div>
                <div className="font-mono text-primary">{selectedVente.numero}</div>
                
                <div className="font-semibold">Date:</div>
                <div>{formatDate(selectedVente.created_at)}</div>
                
                <div className="font-semibold">Client:</div>
                <div>{selectedVente.client_nom || "-"}</div>
                
                <div className="font-semibold">WhatsApp:</div>
                <div>{selectedVente.client_whatsapp || "-"}</div>
                
                <div className="font-semibold">Telephone:</div>
                <div>{selectedVente.client_telephone || "-"}</div>
                
                <div className="font-semibold">Mode:</div>
                <div>{selectedVente.mode_paiement}</div>
              </div>
              
              <div className="rounded-xl border">
                <table className="w-full text-sm">
                  <thead className="border-b text-left text-xs text-muted-foreground">
                    <tr>
                      <th className="px-3 py-2">Produit</th>
                      <th className="px-3 py-2 text-center">Qte</th>
                      <th className="px-3 py-2 text-right">Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    {selectedLignes.map((ligne) => (
                      <tr key={ligne.id} className="border-b">
                        <td className="px-3 py-2">{ligne.nom_snapshot}</td>
                        <td className="px-3 py-2 text-center">{ligne.quantite}</td>
                        <td className="px-3 py-2 text-right font-medium">{formatPrice(ligne.total_ligne)}</td>
                      </tr>
                    ))}
                  </tbody>
                  <tfoot className="border-t">
                    <tr>
                      <td colSpan={2} className="px-3 py-2 text-right font-semibold">Total:</td>
                      <td className="px-3 py-2 text-right font-bold text-primary">{formatPrice(selectedVente.total)}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          )}
        </DialogContent>
      </Dialog>
    </AdminLayout>
  );
}