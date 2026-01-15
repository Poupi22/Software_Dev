import { createFileRoute } from "@tanstack/react-router";
import { useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { Plus, Edit3, ShieldOff, Loader2, UserX, UserCheck } from "lucide-react";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, DialogFooter } from "@/components/ui/dialog";
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger } from "@/components/ui/alert-dialog";
import { toast } from "sonner";
import api from "@/lib/api";
import { useAuth } from "@/lib/auth";

export const Route = createFileRoute("/admin/utilisateurs")({
  component: UsersPage,
});

interface Utilisateur {
  id: string;
  nom: string;
  email: string;
  telephone: string | null;
  role: string;
  statut: string;
  avatar_url: string | null;
  derniere_connexion: string | null;
  created_at: string;
}

function UsersPage() {
  const { user: currentUser } = useAuth();
  const [utilisateurs, setUtilisateurs] = useState<Utilisateur[]>([]);
  const [loading, setLoading] = useState(true);
  const [open, setOpen] = useState(false);
  const [editOpen, setEditOpen] = useState(false);
  const [selectedUser, setSelectedUser] = useState<Utilisateur | null>(null);
  const [submitting, setSubmitting] = useState(false);
  const [formData, setFormData] = useState({
    nom: "",
    email: "",
    telephone: "",
    role: "GERANT",
  });

  useEffect(() => {
    fetchUsers();
  }, []);

  const fetchUsers = async () => {
    setLoading(true);
    try {
      const res = await api.get('/utilisateurs/gerants');
      setUtilisateurs(res.data.data);
    } catch (error) {
      console.error("Error fetching users:", error);
      toast.error("Erreur lors du chargement des utilisateurs");
    } finally {
      setLoading(false);
    }
  };

  const handleCreate = async (e: React.FormEvent) => {
    e.preventDefault();
    setSubmitting(true);
    try {
      await api.post('/utilisateurs/gerants', formData);
      toast.success("Utilisateur cree avec succes");
      setOpen(false);
      resetForm();
      fetchUsers();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la creation";
      toast.error(msg);
    } finally {
      setSubmitting(false);
    }
  };

  const handleUpdate = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedUser) return;
    setSubmitting(true);
    try {
      await api.put(`/utilisateurs/gerants/${selectedUser.id}`, {
        nom: formData.nom,
        telephone: formData.telephone,
        statut: formData.role === "INACTIF" ? "INACTIF" : "ACTIF"
      });
      toast.success("Utilisateur modifie avec succes");
      setEditOpen(false);
      resetForm();
      fetchUsers();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la modification";
      toast.error(msg);
    } finally {
      setSubmitting(false);
    }
  };

  const handleDelete = async (id: string, nom: string) => {
    if (currentUser?.id === id) {
      toast.error("Vous ne pouvez pas supprimer votre propre compte");
      return;
    }
    try {
      await api.delete(`/utilisateurs/gerants/${id}`);
      toast.success(`Utilisateur "${nom}" supprime`);
      fetchUsers();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la suppression";
      toast.error(msg);
    }
  };

  const handleResetPassword = async (id: string, nom: string) => {
    try {
      await api.post(`/utilisateurs/gerants/${id}/reset-password`);
      toast.success(`Nouveau mot de passe envoye a ${nom}`);
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la reinitialisation";
      toast.error(msg);
    }
  };

  const openEditDialog = (user: Utilisateur) => {
    setSelectedUser(user);
    setFormData({
      nom: user.nom,
      email: user.email,
      telephone: user.telephone || "",
      role: user.role,
    });
    setEditOpen(true);
  };

  const resetForm = () => {
    setFormData({
      nom: "",
      email: "",
      telephone: "",
      role: "GERANT",
    });
    setSelectedUser(null);
  };

  const formatDate = (date: string | null) => {
    if (!date) return "Jamais";
    return new Date(date).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };

  const getInitials = (nom: string) => {
    return nom.split(" ").map(n => n[0]).slice(0, 2).join("").toUpperCase();
  };

  const actifs = utilisateurs.filter(u => u.statut === "ACTIF").length;

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
        title="Gestion utilisateurs"
        description={`${utilisateurs.length} comptes · ${actifs} actifs`}
        actions={
          <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
              <button className="flex h-10 items-center gap-2 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground">
                <Plus className="h-4 w-4" /> Nouvel utilisateur
              </button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader><DialogTitle>Creer un utilisateur</DialogTitle></DialogHeader>
              <form onSubmit={handleCreate} className="space-y-3 py-2">
                <div>
                  <label className="mb-1 block text-xs font-medium">Nom complet *</label>
                  <input 
                    className="h-10 w-full rounded-lg border px-3 text-sm" 
                    placeholder="Jean Kamga"
                    value={formData.nom}
                    onChange={(e) => setFormData({...formData, nom: e.target.value})}
                    required 
                  />
                </div>
                <div>
                  <label className="mb-1 block text-xs font-medium">Email *</label>
                  <input 
                    type="email"
                    className="h-10 w-full rounded-lg border px-3 text-sm" 
                    placeholder="email@pharma.com"
                    value={formData.email}
                    onChange={(e) => setFormData({...formData, email: e.target.value})}
                    required 
                  />
                </div>
                <div>
                  <label className="mb-1 block text-xs font-medium">Telephone</label>
                  <input 
                    className="h-10 w-full rounded-lg border px-3 text-sm" 
                    placeholder="690123456"
                    value={formData.telephone}
                    onChange={(e) => setFormData({...formData, telephone: e.target.value})}
                  />
                </div>
                <div>
                  <label className="mb-1 block text-xs font-medium">Role *</label>
                  <select 
                    className="h-10 w-full rounded-lg border px-3 text-sm"
                    value={formData.role}
                    onChange={(e) => setFormData({...formData, role: e.target.value})}
                    required
                  >
                    <option value="GERANT">Gerant</option>
                    <option value="ADMIN">Administrateur</option>
                  </select>
                </div>
                <DialogFooter>
                  <button type="button" onClick={() => setOpen(false)} className="h-10 rounded-lg border px-4 text-sm">Annuler</button>
                  <button type="submit" disabled={submitting} className="h-10 rounded-lg bg-primary px-4 text-sm font-semibold text-white">
                    {submitting ? <Loader2 className="h-4 w-4 animate-spin" /> : "Creer le compte"}
                  </button>
                </DialogFooter>
              </form>
            </DialogContent>
          </Dialog>
        }
      />

      <div className="overflow-hidden rounded-2xl border bg-card shadow-sm">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-secondary/50 text-left text-xs font-semibold uppercase tracking-wider text-muted-foreground">
              <tr>
                <th className="px-4 py-3">Utilisateur</th>
                <th className="px-4 py-3">Email</th>
                <th className="px-4 py-3">Telephone</th>
                <th className="px-4 py-3">Role</th>
                <th className="px-4 py-3">Statut</th>
                <th className="px-4 py-3">Derniere connexion</th>
                <th className="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              {utilisateurs.length === 0 ? (
                <tr>
                  <td colSpan={7} className="px-4 py-16 text-center">
                    <div className="flex flex-col items-center gap-2 text-muted-foreground">
                      <UserX className="h-10 w-10 opacity-30" />
                      <div className="text-sm">Aucun utilisateur</div>
                    </div>
                  </td>
                </tr>
              ) : (
                utilisateurs.map((u) => (
                  <tr key={u.id} className="border-t text-sm hover:bg-muted/30">
                    <td className="px-4 py-3">
                      <div className="flex items-center gap-3">
                        <div className="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-xs font-bold text-white">
                          {getInitials(u.nom)}
                        </div>
                        <div className="font-semibold">{u.nom}</div>
                      </div>
                    </td>
                    <td className="px-4 py-3 text-muted-foreground">{u.email}</td>
                    <td className="px-4 py-3 text-muted-foreground">{u.telephone || "-"}</td>
                    <td className="px-4 py-3">
                      <span className={u.role === "ADMIN"
                        ? "rounded-md bg-primary px-2 py-0.5 text-xs font-bold text-white"
                        : "rounded-md bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary"}>
                        {u.role}
                      </span>
                    </td>
                    <td className="px-4 py-3">
                      <span className={`rounded-full px-2 py-0.5 text-xs font-semibold ${
                        u.statut === "ACTIF" 
                          ? "bg-green-100 text-green-700" 
                          : "bg-red-100 text-red-700"
                      }`}>
                        {u.statut === "ACTIF" ? "Actif" : "Inactif"}
                      </span>
                    </td>
                    <td className="px-4 py-3 text-muted-foreground">{formatDate(u.derniere_connexion)}</td>
                    <td className="px-4 py-3 text-right">
                      <div className="flex items-center justify-end gap-1">
                        <button 
                          onClick={() => openEditDialog(u)}
                          className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-primary"
                          title="Modifier"
                        >
                          <Edit3 className="h-4 w-4" />
                        </button>
                        <button 
                          onClick={() => handleResetPassword(u.id, u.nom)}
                          className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-yellow-600"
                          title="Reinitialiser mot de passe"
                        >
                          <UserCheck className="h-4 w-4" />
                        </button>
                        {currentUser?.id !== u.id && (
                          <AlertDialog>
                            <AlertDialogTrigger asChild>
                              <button className="rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-red-600" title="Supprimer">
                                <ShieldOff className="h-4 w-4" />
                              </button>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                              <AlertDialogHeader>
                                <AlertDialogTitle>Supprimer cet utilisateur ?</AlertDialogTitle>
                                <AlertDialogDescription>
                                  Cette action est irreversible. L'utilisateur "{u.nom}" sera definitivement supprime.
                                </AlertDialogDescription>
                              </AlertDialogHeader>
                              <AlertDialogFooter>
                                <AlertDialogCancel>Annuler</AlertDialogCancel>
                                <AlertDialogAction onClick={() => handleDelete(u.id, u.nom)} className="bg-red-600 hover:bg-red-700">
                                  Supprimer
                                </AlertDialogAction>
                              </AlertDialogFooter>
                            </AlertDialogContent>
                          </AlertDialog>
                        )}
                      </div>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Edit Dialog */}
      <Dialog open={editOpen} onOpenChange={setEditOpen}>
        <DialogContent>
          <DialogHeader><DialogTitle>Modifier l'utilisateur</DialogTitle></DialogHeader>
          <form onSubmit={handleUpdate} className="space-y-3 py-2">
            <div>
              <label className="mb-1 block text-xs font-medium">Nom complet</label>
              <input 
                className="h-10 w-full rounded-lg border px-3 text-sm" 
                value={formData.nom}
                onChange={(e) => setFormData({...formData, nom: e.target.value})}
                required 
              />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Email</label>
              <input 
                type="email"
                className="h-10 w-full rounded-lg border px-3 text-sm bg-gray-50" 
                value={formData.email}
                disabled
              />
              <p className="text-xs text-muted-foreground mt-1">L'email ne peut pas etre modifie</p>
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Telephone</label>
              <input 
                className="h-10 w-full rounded-lg border px-3 text-sm" 
                placeholder="690123456"
                value={formData.telephone}
                onChange={(e) => setFormData({...formData, telephone: e.target.value})}
              />
            </div>
            <div>
              <label className="mb-1 block text-xs font-medium">Statut</label>
              <select 
                className="h-10 w-full rounded-lg border px-3 text-sm"
                value={formData.role === "ADMIN" ? "ACTIF" : selectedUser?.statut || "ACTIF"}
                onChange={(e) => setFormData({...formData, role: e.target.value === "ACTIF" ? selectedUser?.role || "GERANT" : "INACTIF"})}
              >
                <option value="ACTIF">Actif</option>
                <option value="INACTIF">Inactif</option>
              </select>
            </div>
            <DialogFooter>
              <button type="button" onClick={() => setEditOpen(false)} className="h-10 rounded-lg border px-4 text-sm">Annuler</button>
              <button type="submit" disabled={submitting} className="h-10 rounded-lg bg-primary px-4 text-sm font-semibold text-white">
                {submitting ? <Loader2 className="h-4 w-4 animate-spin" /> : "Enregistrer"}
              </button>
            </DialogFooter>
          </form>
        </DialogContent>
      </Dialog>
    </AdminLayout>
  );
}
