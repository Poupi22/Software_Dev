import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { Search, Plus, Edit3, Trash2, Filter, Pill, Eye, Lock, Loader2 } from "lucide-react";
import { useAuth } from "@/lib/auth";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, DialogFooter } from "@/components/ui/dialog";
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from "@/components/ui/alert-dialog";
import { ImagePicker } from "@/components/admin/ImagePicker";
import { toast } from "sonner";
import { medicamentsApi, type Medicament } from "@/lib/medicaments";
import { categoriesApi, type Category } from "@/lib/categories";

export const Route = createFileRoute("/admin/medicaments")({
  component: MedicamentsPage,
});

function MedicamentsPage() {
  const { user } = useAuth();
  const isAdmin = user?.role === "ADMIN";
  const [medicaments, setMedicaments] = useState<Medicament[]>([]);
  const [categories, setCategories] = useState<Category[]>([]);
  const [loading, setLoading] = useState(true);
  const [q, setQ] = useState("");
  const [cat, setCat] = useState("all");
  const [stockFilter, setStockFilter] = useState("all");
  const [open, setOpen] = useState(false);
  const [editOpen, setEditOpen] = useState(false);
  const [selectedMedicament, setSelectedMedicament] = useState<Medicament | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const [imageFile, setImageFile] = useState<File | null>(null);
  const [detailOpen, setDetailOpen] = useState(false);
  const [detailMedicament, setDetailMedicament] = useState<Medicament | null>(null);
  const [formData, setFormData] = useState({
    nom: "",
    description: "",
    code_barre: "",
    categorie_id: "",
    prix_achat: "",
    prix_vente: "",
    quantite: "",
    seuil_alerte: "10",
    date_expiration: "",
    ordonnance: false,
  });

  useEffect(() => {
    fetchData();
  }, []);

  const fetchData = async () => {
    setLoading(true);
    try {
      const [medicamentsRes, categoriesRes] = await Promise.all([
        medicamentsApi.getAll(),
        categoriesApi.getAll(),
      ]);
      setMedicaments(medicamentsRes.data.data);
      setCategories(categoriesRes.data.data);
    } catch (error) {
      console.error("Error fetching data:", error);
      toast.error("Erreur lors du chargement des donnees");
    } finally {
      setLoading(false);
    }
  };

  const stockStatus = (quantite: number, seuil: number) => {
    if (quantite <= 0) return "critique";
    if (quantite <= seuil) return "faible";
    return "ok";
  };

  const filtered = medicaments.filter((m) => {
    if (q && !m.nom.toLowerCase().includes(q.toLowerCase())) return false;
    if (cat !== "all" && m.categorie_id !== cat) return false;
    const status = stockStatus(m.quantite, m.seuil_alerte);
    if (stockFilter !== "all" && status !== stockFilter) return false;
    return true;
  });

  const handleCreate = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      const formDataToSend = new FormData();
      formDataToSend.append("nom", formData.nom);
      formDataToSend.append("description", formData.description);
      formDataToSend.append("code_barre", formData.code_barre);
      formDataToSend.append("categorie_id", formData.categorie_id);
      formDataToSend.append("prix_achat", formData.prix_achat);
      formDataToSend.append("prix_vente", formData.prix_vente);
      formDataToSend.append("quantite", formData.quantite);
      formDataToSend.append("seuil_alerte", formData.seuil_alerte);
      formDataToSend.append("date_expiration", formData.date_expiration);
      formDataToSend.append("ordonnance", String(formData.ordonnance));
      if (imageFile) {
        formDataToSend.append("image", imageFile);
      }
      
      await medicamentsApi.create(formDataToSend);
      toast.success("Medicament cree avec succes");
      setOpen(false);
      resetForm();
      fetchData();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la creation";
      toast.error(msg);
    } finally {
      setSubmitting(false);
    }
  };

  const handleUpdate = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedMedicament) return;
    setSubmitting(true);
    try {
      const formDataToSend = new FormData();
      formDataToSend.append("nom", formData.nom);
      formDataToSend.append("description", formData.description);
      formDataToSend.append("code_barre", formData.code_barre);
      formDataToSend.append("categorie_id", formData.categorie_id);
      formDataToSend.append("prix_achat", formData.prix_achat);
      formDataToSend.append("prix_vente", formData.prix_vente);
      formDataToSend.append("quantite", formData.quantite);
      formDataToSend.append("seuil_alerte", formData.seuil_alerte);
      formDataToSend.append("date_expiration", formData.date_expiration);
      formDataToSend.append("ordonnance", String(formData.ordonnance));
      if (imageFile) {
        formDataToSend.append("image", imageFile);
      }
      
      await medicamentsApi.update(selectedMedicament.id, formDataToSend);
      toast.success("Medicament modifie avec succes");
      setEditOpen(false);
      resetForm();
      fetchData();
    } catch (error: any) {
      console.error("Update error:", error);
      const msg = error.response?.data?.message || "Erreur lors de la modification";
      toast.error(msg);
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (id: string, nom: string) => {
    try {
      await medicamentsApi.delete(id);
      toast.success(`Medicament "${nom}" supprime`);
      fetchData();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la suppression";
      toast.error(msg);
    }
  };

  const openEditDialog = (medicament: Medicament) => {
    setSelectedMedicament(medicament);
    setFormData({
      nom: medicament.nom,
      description: medicament.description || "",
      code_barre: medicament.code_barre || "",
      categorie_id: medicament.categorie_id,
      prix_achat: String(medicament.prix_achat || ""),
      prix_vente: String(medicament.prix_vente || ""),
      quantite: String(medicament.quantite || ""),
      seuil_alerte: String(medicament.seuil_alerte || "10"),
      date_expiration: medicament.date_expiration ? medicament.date_expiration.split('T')[0] : "",
      ordonnance: medicament.ordonnance || false,
    });
    setImageFile(null);
    setEditOpen(true);
  };

  const openDetailDialog = (medicament: Medicament) => {
    setDetailMedicament(medicament);
    setDetailOpen(true);
  };

  const resetForm = () => {
    setFormData({
      nom: "",
      description: "",
      code_barre: "",
      categorie_id: "",
      prix_achat: "",
      prix_vente: "",
      quantite: "",
      seuil_alerte: "10",
      date_expiration: "",
      ordonnance: false,
    });
    setImageFile(null);
    setSelectedMedicament(null);
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
  };

  const formatDate = (date: string) => {
    if (!date) return "Non definie";
    return new Date(date).toLocaleDateString('fr-FR');
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
        title={isAdmin ? "Gestion des medicaments" : "Catalogue medicaments"}
        description={
          isAdmin
            ? `${medicaments.length} medicaments references`
            : `Consultation seule - ${medicaments.length} produits disponibles`
        }
        actions={
          isAdmin ? (
            <Dialog open={open} onOpenChange={setOpen}>
              <DialogTrigger asChild>
                <button className="flex h-10 items-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground">
                  <Plus className="h-4 w-4" /> Ajouter
                </button>
              </DialogTrigger>
              <DialogContent className="max-w-lg max-h-[90vh] overflow-y-auto">
                <DialogHeader><DialogTitle>Nouveau medicament</DialogTitle></DialogHeader>
                <form onSubmit={handleCreate} className="grid grid-cols-2 gap-3 py-2">
                  <div className="col-span-2">
                    <ImagePicker 
                      label="Photo du produit"
                      hint="PNG, JPG, WEBP - max 5 Mo"
                      onImageSelect={setImageFile}
                    />
                  </div>
                  <div className="col-span-2">
                    <label className="mb-1 block text-xs font-medium">Nom *</label>
                    <input 
                      value={formData.nom} 
                      onChange={(e) => setFormData({...formData, nom: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                      required 
                    />
                  </div>
                  <div>
                    <label className="mb-1 block text-xs font-medium">Categorie *</label>
                    <select 
                      value={formData.categorie_id} 
                      onChange={(e) => setFormData({...formData, categorie_id: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                      required
                    >
                      <option value="">Selectionner</option>
                      {categories.map((c) => <option key={c.id} value={c.id}>{c.nom}</option>)}
                    </select>
                  </div>
                  <div>
                    <label className="mb-1 block text-xs font-medium">Prix achat (FCFA)</label>
                    <input 
                      type="number" 
                      value={formData.prix_achat} 
                      onChange={(e) => setFormData({...formData, prix_achat: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                    />
                  </div>
                  <div>
                    <label className="mb-1 block text-xs font-medium">Prix vente (FCFA) *</label>
                    <input 
                      type="number" 
                      value={formData.prix_vente} 
                      onChange={(e) => setFormData({...formData, prix_vente: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                      required 
                    />
                  </div>
                  <div>
                    <label className="mb-1 block text-xs font-medium">Quantite</label>
                    <input 
                      type="number" 
                      value={formData.quantite} 
                      onChange={(e) => setFormData({...formData, quantite: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                    />
                  </div>
                  <div>
                    <label className="mb-1 block text-xs font-medium">Seuil alerte</label>
                    <input 
                      type="number" 
                      value={formData.seuil_alerte} 
                      onChange={(e) => setFormData({...formData, seuil_alerte: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                    />
                  </div>
                  <div>
                    <label className="mb-1 block text-xs font-medium">Date expiration</label>
                    <input 
                      type="date" 
                      value={formData.date_expiration} 
                      onChange={(e) => setFormData({...formData, date_expiration: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                    />
                  </div>
                  <div className="col-span-2">
                    <label className="mb-1 block text-xs font-medium">Code-barre</label>
                    <input 
                      value={formData.code_barre} 
                      onChange={(e) => setFormData({...formData, code_barre: e.target.value})} 
                      className="h-10 w-full rounded-lg border px-3" 
                    />
                  </div>
                  <div className="col-span-2">
                    <label className="mb-1 block text-xs font-medium">Description</label>
                    <textarea 
                      value={formData.description} 
                      onChange={(e) => setFormData({...formData, description: e.target.value})} 
                      className="min-h-20 w-full rounded-lg border p-3" 
                    />
                  </div>
                  <div className="col-span-2">
                    <label className="flex items-center gap-2 text-sm">
                      <input 
                        type="checkbox" 
                        checked={formData.ordonnance} 
                        onChange={(e) => setFormData({...formData, ordonnance: e.target.checked})} 
                      />
                      Ordonnance requise
                    </label>
                  </div>
                  <DialogFooter className="col-span-2">
                    <button type="button" onClick={() => setOpen(false)} className="h-10 rounded-lg border px-4">Annuler</button>
                    <button type="submit" disabled={submitting} className="h-10 rounded-lg bg-primary px-4 text-white">
                      {submitting ? <Loader2 className="h-4 w-4 animate-spin" /> : "Enregistrer"}
                    </button>
                  </DialogFooter>
                </form>
              </DialogContent>
            </Dialog>
          ) : (
            <div className="flex items-center gap-2 rounded-lg border bg-muted/40 px-3 py-2 text-xs font-medium text-muted-foreground">
              <Lock className="h-3.5 w-3.5" /> Lecture seule
            </div>
          )
        }
      />

      {/* Filters */}
      <div className="mb-4 flex flex-col gap-3 rounded-2xl border bg-card p-3 sm:flex-row sm:items-center">
        <div className="relative flex-1">
          <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
          <input
            value={q} 
            onChange={(e) => setQ(e.target.value)}
            placeholder="Rechercher un medicament..."
            className="h-10 w-full rounded-lg border bg-background pl-9 pr-4 text-sm"
          />
        </div>
        <div className="flex items-center gap-2">
          <Filter className="h-4 w-4 text-muted-foreground" />
          <select value={cat} onChange={(e) => setCat(e.target.value)} className="h-10 rounded-lg border bg-background px-3 text-sm">
            <option value="all">Toutes categories</option>
            {categories.map((c) => <option key={c.id} value={c.id}>{c.nom}</option>)}
          </select>
          <select value={stockFilter} onChange={(e) => setStockFilter(e.target.value)} className="h-10 rounded-lg border bg-background px-3 text-sm">
            <option value="all">Tous stocks</option>
            <option value="ok">En stock</option>
            <option value="faible">Stock faible</option>
            <option value="critique">Critique</option>
          </select>
        </div>
      </div>

      {/* Table */}
      <div className="overflow-hidden rounded-2xl border bg-card">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-secondary/50">
              <tr className="text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                <th className="px-4 py-3">Medicament</th>
                <th className="px-4 py-3">Categorie</th>
                <th className="px-4 py-3">Prix achat</th>
                <th className="px-4 py-3">Prix vente</th>
                <th className="px-4 py-3">Quantite</th>
                <th className="px-4 py-3">Statut</th>
                <th className="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {filtered.length === 0 ? (
                <tr>
                  <td colSpan={7} className="px-4 py-16 text-center">
                    <div className="flex flex-col items-center gap-2 text-muted-foreground">
                      <Pill className="h-10 w-10 opacity-30" />
                      <div className="text-sm">Aucun medicament trouve</div>
                    </div>
                  </td>
                </tr>
              ) : (
                filtered.map((m) => {
                  const status = stockStatus(m.quantite, m.seuil_alerte);
                  return (
                    <tr key={m.id} className="border-t text-sm hover:bg-muted/30">
                      <td className="px-4 py-3">
                        <div className="flex items-center gap-3">
                          {m.image_url ? (
                            <img src={m.image_url} alt={m.nom} className="h-11 w-11 rounded-lg object-cover" />
                          ) : (
                            <div className="h-11 w-11 rounded-lg bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center">
                              <Pill className="h-5 w-5 text-muted-foreground" />
                            </div>
                          )}
                          <div>
                            <div className="font-semibold">{m.nom}</div>
                            {m.code_barre && <div className="text-xs text-muted-foreground">{m.code_barre}</div>}
                          </div>
                        </div>
                        </td>
                      <td className="px-4 py-3">
                        <span className="rounded-md bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary">
                          {categories.find(c => c.id === m.categorie_id)?.nom || "N/A"}
                        </span>
                        </td>
                      <td className="px-4 py-3 text-muted-foreground">{formatPrice(m.prix_achat)}</td>
                      <td className="px-4 py-3 font-semibold">{formatPrice(m.prix_vente)}</td>
                      <td className="px-4 py-3">
                        <span className={`font-bold ${status === "faible" ? "text-yellow-600" : status === "critique" ? "text-red-600" : ""}`}>
                          {m.quantite}
                        </span>
                        </td>
                      <td className="px-4 py-3">
                        <div className={`inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ${
                          status === "ok" ? "bg-green-100 text-green-700" :
                          status === "faible" ? "bg-yellow-100 text-yellow-700" :
                          "bg-red-100 text-red-700"
                        }`}>
                          {status === "ok" ? "En stock" : status === "faible" ? "Stock faible" : "Stock critique"}
                        </div>
                        </td>
                      <td className="px-4 py-3 text-right">
                        <div className="flex items-center justify-end gap-1">
                          <button 
                            onClick={() => openDetailDialog(m)}
                            className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-primary"
                            title="Voir details"
                          >
                            <Eye className="h-4 w-4" />
                          </button>
                          {isAdmin && (
                            <>
                              <button 
                                onClick={() => openEditDialog(m)}
                                className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-primary"
                                title="Modifier"
                              >
                                <Edit3 className="h-4 w-4" />
                              </button>
                              <AlertDialog>
                                <AlertDialogTrigger asChild>
                                  <button className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-destructive">
                                    <Trash2 className="h-4 w-4" />
                                  </button>
                                </AlertDialogTrigger>
                                <AlertDialogContent>
                                  <AlertDialogHeader>
                                    <AlertDialogTitle>Supprimer ce medicament ?</AlertDialogTitle>
                                    <AlertDialogDescription>
                                      Cette action est irreversible. {m.nom} sera definitivement retire.
                                    </AlertDialogDescription>
                                  </AlertDialogHeader>
                                  <AlertDialogFooter>
                                    <AlertDialogCancel>Annuler</AlertDialogCancel>
                                    <AlertDialogAction onClick={() => handleDelete(m.id, m.nom)} className="bg-destructive text-destructive-foreground">
                                      Supprimer
                                    </AlertDialogAction>
                                  </AlertDialogFooter>
                                </AlertDialogContent>
                              </AlertDialog>
                            </>
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

      {/* Edit Dialog */}
      <Dialog open={editOpen} onOpenChange={setEditOpen}>
        <DialogContent className="max-w-lg max-h-[90vh] overflow-y-auto">
          <DialogHeader><DialogTitle>Modifier le medicament</DialogTitle></DialogHeader>
          <form onSubmit={handleUpdate} className="grid grid-cols-2 gap-3 py-2">
            <div className="col-span-2">
              {selectedMedicament?.image_url && !imageFile && (
                <div className="mb-2">
                  <p className="text-xs text-muted-foreground mb-1">Image actuelle :</p>
                  <img src={selectedMedicament.image_url} alt="current" className="h-20 w-20 rounded-lg object-cover" />
                </div>
              )}
              <ImagePicker 
                label="Nouvelle image (optionnel)"
                initial={null}
                hint="PNG, JPG, WEBP - max 5 Mo"
                onImageSelect={setImageFile}
              />
            </div>
            <div className="col-span-2">
              <label className="mb-1 block text-xs font-medium">Nom *</label>
              <input 
                value={formData.nom} 
                onChange={(e) => setFormData({...formData, nom: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
                required 
              />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Categorie *</label>
              <select 
                value={formData.categorie_id} 
                onChange={(e) => setFormData({...formData, categorie_id: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
                required
              >
                <option value="">Selectionner</option>
                {categories.map((c) => <option key={c.id} value={c.id}>{c.nom}</option>)}
              </select>
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Prix achat (FCFA)</label>
              <input 
                type="number" 
                value={formData.prix_achat} 
                onChange={(e) => setFormData({...formData, prix_achat: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
              />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Prix vente (FCFA) *</label>
              <input 
                type="number" 
                value={formData.prix_vente} 
                onChange={(e) => setFormData({...formData, prix_vente: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
                required 
              />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Quantite</label>
              <input 
                type="number" 
                value={formData.quantite} 
                onChange={(e) => setFormData({...formData, quantite: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
              />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Seuil alerte</label>
              <input 
                type="number" 
                value={formData.seuil_alerte} 
                onChange={(e) => setFormData({...formData, seuil_alerte: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
              />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Date expiration</label>
              <input 
                type="date" 
                value={formData.date_expiration} 
                onChange={(e) => setFormData({...formData, date_expiration: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
              />
            </div>
            <div className="col-span-2">
              <label className="mb-1 block text-xs font-medium">Code-barre</label>
              <input 
                value={formData.code_barre} 
                onChange={(e) => setFormData({...formData, code_barre: e.target.value})} 
                className="h-10 w-full rounded-lg border px-3" 
              />
            </div>
            <div className="col-span-2">
              <label className="mb-1 block text-xs font-medium">Description</label>
              <textarea 
                value={formData.description} 
                onChange={(e) => setFormData({...formData, description: e.target.value})} 
                className="min-h-20 w-full rounded-lg border p-3" 
              />
            </div>
            <div className="col-span-2">
              <label className="flex items-center gap-2 text-sm">
                <input 
                  type="checkbox" 
                  checked={formData.ordonnance} 
                  onChange={(e) => setFormData({...formData, ordonnance: e.target.checked})} 
                />
                Ordonnance requise
              </label>
            </div>
            <DialogFooter className="col-span-2">
              <button type="button" onClick={() => setEditOpen(false)} className="h-10 rounded-lg border px-4">Annuler</button>
              <button type="submit" disabled={submitting} className="h-10 rounded-lg bg-primary px-4 text-white">
                {submitting ? <Loader2 className="h-4 w-4 animate-spin" /> : "Enregistrer"}
              </button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>

      {/* Detail Dialog */}
      <Dialog open={detailOpen} onOpenChange={setDetailOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader><DialogTitle>Details du medicament</DialogTitle></DialogHeader>
          {detailMedicament && (
            <div className="space-y-3">
              {detailMedicament.image_url && (
                <div className="flex justify-center">
                  <img src={detailMedicament.image_url} alt={detailMedicament.nom} className="h-32 w-32 rounded-lg object-cover" />
                </div>
              )}
              <div className="grid grid-cols-2 gap-2 text-sm">
                <div className="font-semibold">Nom:</div>
                <div>{detailMedicament.nom}</div>
                
                <div className="font-semibold">Categorie:</div>
                <div>{categories.find(c => c.id === detailMedicament.categorie_id)?.nom || "N/A"}</div>
                
                <div className="font-semibold">Prix achat:</div>
                <div>{formatPrice(detailMedicament.prix_achat)}</div>
                
                <div className="font-semibold">Prix vente:</div>
                <div>{formatPrice(detailMedicament.prix_vente)}</div>
                
                <div className="font-semibold">Quantite:</div>
                <div>{detailMedicament.quantite}</div>
                
                <div className="font-semibold">Seuil alerte:</div>
                <div>{detailMedicament.seuil_alerte}</div>
                
                <div className="font-semibold">Date expiration:</div>
                <div>{formatDate(detailMedicament.date_expiration)}</div>
                
                {detailMedicament.code_barre && (
                  <>
                    <div className="font-semibold">Code-barre:</div>
                    <div>{detailMedicament.code_barre}</div>
                  </>
                )}
                
                <div className="font-semibold">Ordonnance:</div>
                <div>{detailMedicament.ordonnance ? "Oui" : "Non"}</div>
                
                {detailMedicament.description && (
                  <>
                    <div className="font-semibold">Description:</div>
                    <div className="col-span-2">{detailMedicament.description}</div>
                  </>
                )}
              </div>
            </div>
          )}
          <DialogFooter>
            <button onClick={() => setDetailOpen(false)} className="h-10 rounded-lg border px-4">Fermer</button>
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </AdminLayout>
  );
}
