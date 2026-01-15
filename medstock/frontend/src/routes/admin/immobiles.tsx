import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { Archive, Loader2 } from "lucide-react";
import { toast } from "sonner";
import { medicamentsApi, type Medicament } from "@/lib/medicaments";
import { ventesApi, type VenteLigne } from "@/lib/ventes";

export const Route = createFileRoute("/admin/immobiles")({
  component: ImmobilesPage,
});

interface MedicamentWithMovement extends Medicament {
  derniereVente: string | null;
  joursSansVente: number;
}

function ImmobilesPage() {
  const [immobiles, setImmobiles] = useState<MedicamentWithMovement[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchImmobiles();
  }, []);

  const fetchImmobiles = async () => {
    setLoading(true);
    try {
      // Récupérer tous les médicaments
      const medocsRes = await medicamentsApi.getAll();
      const medicamentsList: Medicament[] = medocsRes.data.data;
      
      // Récupérer toutes les ventes pour connaître la dernière vente de chaque médicament
      const ventesRes = await ventesApi.getAll();
      const ventesData = ventesRes.data.data;
      
      // Pour chaque vente, récupérer les lignes pour savoir quels médicaments ont été vendus
      const derniereVenteParMedicament: Record<string, string> = {};
      
      for (const vente of ventesData) {
        const detailRes = await ventesApi.getById(vente.id);
        const lignes: VenteLigne[] = detailRes.data.data.lignes || [];
        
        for (const ligne of lignes) {
          const medId = ligne.medicament_id;
          const dateVente = vente.created_at;
          
          // Garder la date la plus récente
          if (!derniereVenteParMedicament[medId] || new Date(dateVente) > new Date(derniereVenteParMedicament[medId])) {
            derniereVenteParMedicament[medId] = dateVente;
          }
        }
      }
      
      // Calculer les jours sans vente pour chaque médicament
      const now = new Date();
      const immobilesList: MedicamentWithMovement[] = medicamentsList.map(med => {
        const derniereVente = derniereVenteParMedicament[med.id] || null;
        let joursSansVente = 999;
        
        if (derniereVente) {
          const derniereVenteDate = new Date(derniereVente);
          const diffTime = now.getTime() - derniereVenteDate.getTime();
          joursSansVente = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        }
        
        return {
          ...med,
          derniereVente,
          joursSansVente
        };
      });
      
      // Filtrer ceux qui n'ont pas eu de vente depuis plus de 30 jours
      const filtered = immobilesList
        .filter(m => m.joursSansVente > 30)
        .sort((a, b) => b.joursSansVente - a.joursSansVente);
      
      setImmobiles(filtered);
    } catch (error) {
      console.error("Error fetching immobiles:", error);
      toast.error("Erreur lors du chargement des produits immobiles");
    } finally {
      setLoading(false);
    }
  };

  const formatDate = (date: string | null) => {
    if (!date) return "Jamais vendu";
    return new Date(date).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
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
        title="Produits immobiles" 
        description="Medicaments sans mouvement de vente depuis plus de 30 jours" 
      />

      <div className="overflow-hidden rounded-2xl border bg-card shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-secondary/50 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
              <tr>
                <th className="px-4 py-3">Medicament</th>
                <th className="px-4 py-3">Categorie</th>
                <th className="px-4 py-3">Stock</th>
                <th className="px-4 py-3">Prix vente</th>
                <th className="px-4 py-3">Derniere vente</th>
                <th className="px-4 py-3">Jours sans mouvement</th>
              </tr>
            </thead>
            <tbody>
              {immobiles.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-4 py-16 text-center">
                    <div className="flex flex-col items-center gap-2 text-muted-foreground">
                      <Archive className="h-10 w-10 opacity-30" />
                      <div className="text-sm">Tous vos medicaments tournent bien ??</div>
                    </div>
                  </td>
                </tr>
              ) : (
                immobiles.map((m) => (
                  <tr key={m.id} className="border-t text-sm hover:bg-muted/30">
                    <td className="px-4 py-3 font-semibold">{m.nom}</td>
                    <td className="px-4 py-3">
                      <span className="rounded-md bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary">
                        {m.categorie_nom || "N/A"}
                      </span>
                    </td>
                    <td className="px-4 py-3 font-bold">{m.quantite}</td>
                    <td className="px-4 py-3 text-muted-foreground">{formatPrice(m.prix_vente)}</td>
                    <td className="px-4 py-3 text-muted-foreground">{formatDate(m.derniereVente)}</td>
                    <td className="px-4 py-3">
                      <span className={`rounded-full px-2.5 py-0.5 font-bold ${
                        m.joursSansVente > 90 
                          ? "bg-red-100 text-red-700" 
                          : "bg-yellow-100 text-yellow-700"
                      }`}>
                        {m.joursSansVente} j
                      </span>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>
      
      {/* Resume */}
      {immobiles.length > 0 && (
        <div className="mt-4 rounded-lg bg-yellow-50 p-4 text-sm border border-yellow-200">
          <div className="flex items-center justify-between">
            <span className="font-semibold">Nombre de produits immobiles :</span>
            <span className="text-lg font-bold text-yellow-700">{immobiles.length} produits</span>
          </div>
          <div className="flex items-center justify-between mt-1">
            <span className="font-semibold">Valeur totale en stock :</span>
            <span className="text-lg font-bold text-yellow-700">
              {new Intl.NumberFormat('fr-FR').format(immobiles.reduce((sum, m) => sum + (m.prix_vente * m.quantite), 0))} FCFA
            </span>
          </div>
          <p className="mt-2 text-xs text-yellow-600">
            Ces produits n'ont pas été vendus depuis plus de 30 jours. Envisagez une promotion ou un retour fournisseur.
          </p>
        </div>
      )}
    </AdminLayout>
  );
}
