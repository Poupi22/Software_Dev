import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { PackageMinus, Loader2, Search } from "lucide-react";
import { toast } from "sonner";
import { ventesApi, type Vente, type VenteLigne } from "@/lib/ventes";

export const Route = createFileRoute("/admin/sorties")({
  component: SortiesPage,
});

interface Sortie {
  id: string;
  medicament_nom: string;
  medicament_id: string;
  quantite: number;
  date: string;
  vendeur_nom: string;
  vente_numero: string;
  vente_id: string;
  prix_unitaire: number;
  total_ligne: number;
}

function SortiesPage() {
  const [sorties, setSorties] = useState<Sortie[]>([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");

  useEffect(() => {
    fetchSorties();
  }, []);

  const fetchSorties = async () => {
    setLoading(true);
    try {
      const res = await ventesApi.getAll();
      const ventesData: Vente[] = res.data.data;
      
      const allSorties: Sortie[] = [];
      
      for (const vente of ventesData) {
        try {
          const detailRes = await ventesApi.getById(vente.id);
          const lignes: VenteLigne[] = detailRes.data.data.lignes || [];
          
          for (const ligne of lignes) {
            allSorties.push({
              id: `${vente.id}-${ligne.id}`,
              medicament_nom: ligne.nom_snapshot || "Medicament",
              medicament_id: ligne.medicament_id,
              quantite: ligne.quantite,
              date: vente.created_at,
              vendeur_nom: vente.vendeur_nom || "Inconnu",
              vente_numero: vente.numero,
              vente_id: vente.id,
              prix_unitaire: typeof ligne.prix_unitaire === 'string' ? parseFloat(ligne.prix_unitaire) : ligne.prix_unitaire,
              total_ligne: typeof ligne.total_ligne === 'string' ? parseFloat(ligne.total_ligne) : ligne.total_ligne
            });
          }
        } catch (e) {
          console.error("Erreur récupération lignes:", e);
        }
      }
      
      allSorties.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime());
      setSorties(allSorties);
    } catch (error) {
      console.error("Error fetching sorties:", error);
      toast.error("Erreur lors du chargement des sorties");
    } finally {
      setLoading(false);
    }
  };

  const filteredSorties = sorties.filter(s => 
    s.medicament_nom.toLowerCase().includes(search.toLowerCase()) ||
    s.vente_numero.toLowerCase().includes(search.toLowerCase()) ||
    s.vendeur_nom.toLowerCase().includes(search.toLowerCase())
  );

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

  const formatPrice = (price: number) => {
    if (!price || isNaN(price)) return "0 FCFA";
    return price.toLocaleString('fr-FR') + ' FCFA';
  };

  const totalUnites = filteredSorties.reduce((sum, s) => sum + s.quantite, 0);
  const totalValeur = filteredSorties.reduce((sum, s) => sum + (s.total_ligne || 0), 0);

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
        title="Sorties de stock" 
        description="Mouvements de sortie generes automatiquement apres chaque vente" 
      />

      <div className="mb-4 relative">
        <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <input
          type="search"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Rechercher par medicament, facture ou vendeur..."
          className="h-10 w-full rounded-lg border bg-background pl-9 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
        />
      </div>

      <div className="overflow-hidden rounded-2xl border bg-card shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-secondary/50 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
              <tr>
                <th className="px-4 py-3">Medicament</th>
                <th className="px-4 py-3">Quantite</th>
                <th className="px-4 py-3">Prix unitaire</th>
                <th className="px-4 py-3">Total</th>
                <th className="px-4 py-3">Facture</th>
                <th className="px-4 py-3">Vendeur</th>
                <th className="px-4 py-3">Date</th>
              </tr>
            </thead>
            <tbody>
              {filteredSorties.length === 0 ? (
                <tr>
                  <td colSpan={7} className="px-4 py-16 text-center">
                    <div className="flex flex-col items-center gap-2 text-muted-foreground">
                      <PackageMinus className="h-10 w-10 opacity-30" />
                      <div className="text-sm">Aucune sortie enregistree</div>
                    </div>
                  </td>
                </tr>
              ) : (
                filteredSorties.map((s, index) => (
                  <tr key={s.id || index} className="border-t text-sm hover:bg-muted/30">
                    <td className="px-4 py-3 font-semibold">{s.medicament_nom}</td>
                    <td className="px-4 py-3">
                      <span className="rounded-md bg-red-100 px-2 py-0.5 font-semibold text-red-700">
                        -{s.quantite}
                      </span>
                    </td>
                    <td className="px-4 py-3 text-muted-foreground">{formatPrice(s.prix_unitaire)}</td>
                    <td className="px-4 py-3 font-medium">{formatPrice(s.total_ligne)}</td>
                    <td className="px-4 py-3">
                      <span className="font-mono text-xs text-primary">{s.vente_numero}</span>
                    </td>
                    <td className="px-4 py-3">
                      <span className="rounded-md bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary">
                        {s.vendeur_nom}
                      </span>
                    </td>
                    <td className="px-4 py-3 text-muted-foreground">{formatDate(s.date)}</td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {filteredSorties.length > 0 && (
        <div className="mt-4 rounded-lg bg-primary/5 p-4 text-sm">
          <div className="flex items-center justify-between">
            <span className="font-semibold">Total des sorties :</span>
            <span className="text-lg font-bold text-primary">
              {totalUnites} unites
            </span>
          </div>
          <div className="flex items-center justify-between mt-1">
            <span className="font-semibold">Valeur totale :</span>
            <span className="text-lg font-bold text-primary">
              {formatPrice(totalValeur)}
            </span>
          </div>
        </div>
      )}
    </AdminLayout>
  );
}
