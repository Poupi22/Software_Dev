import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { useAuth } from "@/lib/auth";
import { User, Loader2 } from "lucide-react";
import { toast } from "sonner";
import api from "@/lib/api";

export const Route = createFileRoute("/admin/parametres")({
  component: ParametresPage,
});

const fieldCls = "h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30";

function ParametresPage() {
  const { user } = useAuth();
  const [loading, setLoading] = useState(false);
  const [formData, setFormData] = useState({
    nom: user?.nom || "",
    telephone: user?.telephone || "",
    old_password: "",
    new_password: "",
    confirm_password: "",
  });

  const handleUpdateProfile = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      await api.put('/utilisateurs/profile', {
        nom: formData.nom,
        telephone: formData.telephone || null,
      });
      toast.success("Profil mis à jour avec succès");
      
      // Mettre à jour l'utilisateur dans le localStorage
      if (user) {
        const updatedUser = { ...user, nom: formData.nom, telephone: formData.telephone };
        localStorage.setItem('pharma_user', JSON.stringify(updatedUser));
        // Recharger la page pour mettre à jour le contexte
        window.location.reload();
      }
    } catch (error: any) {
      toast.error(error.response?.data?.message || "Erreur lors de la mise à jour");
    } finally {
      setLoading(false);
    }
  };

  const handleChangePassword = async (e: React.FormEvent) => {
    e.preventDefault();
    if (formData.new_password !== formData.confirm_password) {
      toast.error("Les mots de passe ne correspondent pas");
      return;
    }
    if (formData.new_password.length < 6) {
      toast.error("Le mot de passe doit contenir au moins 6 caractères");
      return;
    }
    if (!formData.old_password) {
      toast.error("Veuillez entrer votre mot de passe actuel");
      return;
    }
    setLoading(true);
    try {
      await api.post('/auth/change-password', {
        old_password: formData.old_password,
        new_password: formData.new_password,
      });
      toast.success("Mot de passe changé avec succès");
      setFormData({
        ...formData,
        old_password: "",
        new_password: "",
        confirm_password: "",
      });
    } catch (error: any) {
      toast.error(error.response?.data?.message || "Erreur lors du changement de mot de passe");
    } finally {
      setLoading(false);
    }
  };

  const getInitials = (nom: string) => {
    return nom.split(" ").map((n) => n[0]).slice(0, 2).join("").toUpperCase();
  };

  return (
    <AdminLayout>
      <PageHeader 
        title="Mon profil" 
        description="Gérez vos informations personnelles et votre mot de passe" 
      />

      <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {/* Informations personnelles */}
        <div className="rounded-2xl border bg-card shadow-sm overflow-hidden">
          <div className="border-b px-5 py-4">
            <div className="flex items-center gap-2">
              <User className="h-5 w-5 text-primary" />
              <h3 className="font-semibold">Informations personnelles</h3>
            </div>
          </div>
          
          <div className="p-5">
            <div className="mb-6 flex items-center gap-4">
              <div className="flex h-16 w-16 items-center justify-center rounded-full bg-primary text-lg font-bold text-primary-foreground">
                {getInitials(user?.nom || "User")}
              </div>
              <div>
                <div className="font-semibold text-lg">{user?.nom}</div>
                <div className="text-sm text-muted-foreground">{user?.email}</div>
                <div className="mt-1 inline-block rounded-md bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary">
                  {user?.role === "ADMIN" ? "Administrateur" : "Gérant"}
                </div>
              </div>
            </div>

            <form onSubmit={handleUpdateProfile} className="space-y-4">
              <div>
                <label className="mb-1 block text-sm font-medium">Nom complet</label>
                <input 
                  type="text"
                  className={fieldCls}
                  value={formData.nom}
                  onChange={(e) => setFormData({ ...formData, nom: e.target.value })}
                  required
                />
              </div>
              <div>
                <label className="mb-1 block text-sm font-medium">Email</label>
                <input 
                  type="email"
                  className={`${fieldCls} bg-muted cursor-not-allowed`}
                  value={user?.email || ""}
                  disabled
                />
                <p className="text-xs text-muted-foreground mt-1">L'email ne peut pas être modifié</p>
              </div>
              <div>
                <label className="mb-1 block text-sm font-medium">Téléphone</label>
                <input 
                  type="tel"
                  className={fieldCls}
                  value={formData.telephone}
                  onChange={(e) => setFormData({ ...formData, telephone: e.target.value })}
                  placeholder="Optionnel"
                />
              </div>
              <button 
                type="submit" 
                disabled={loading}
                className="h-10 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
              >
                {loading ? <Loader2 className="h-4 w-4 animate-spin mx-auto" /> : "Mettre à jour le profil"}
              </button>
            </form>
          </div>
        </div>

        {/* Changer le mot de passe */}
        <div className="rounded-2xl border bg-card shadow-sm overflow-hidden">
          <div className="border-b px-5 py-4">
            <h3 className="font-semibold">Changer le mot de passe</h3>
          </div>
          
          <div className="p-5">
            <form onSubmit={handleChangePassword} className="space-y-4">
              <div>
                <label className="mb-1 block text-sm font-medium">Mot de passe actuel</label>
                <input 
                  type="password"
                  className={fieldCls}
                  value={formData.old_password}
                  onChange={(e) => setFormData({ ...formData, old_password: e.target.value })}
                  required
                  placeholder="••••••••"
                />
              </div>
              <div>
                <label className="mb-1 block text-sm font-medium">Nouveau mot de passe</label>
                <input 
                  type="password"
                  className={fieldCls}
                  value={formData.new_password}
                  onChange={(e) => setFormData({ ...formData, new_password: e.target.value })}
                  required
                  placeholder="••••••••"
                />
                <p className="text-xs text-muted-foreground mt-1">Minimum 6 caractères</p>
              </div>
              <div>
                <label className="mb-1 block text-sm font-medium">Confirmer le mot de passe</label>
                <input 
                  type="password"
                  className={fieldCls}
                  value={formData.confirm_password}
                  onChange={(e) => setFormData({ ...formData, confirm_password: e.target.value })}
                  required
                  placeholder="••••••••"
                />
              </div>
              <button 
                type="submit" 
                disabled={loading}
                className="h-10 rounded-lg bg-primary px-4 text-sm font-semibold text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
              >
                {loading ? <Loader2 className="h-4 w-4 animate-spin mx-auto" /> : "Changer le mot de passe"}
              </button>
            </form>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}