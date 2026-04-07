import { createFileRoute } from "@tanstack/react-router";
import { Save } from "lucide-react";
import { useState } from "react";

export const Route = createFileRoute("/admin/settings")({
  component: AdminSettings,
});

function AdminSettings() {
  const [saved, setSaved] = useState(false);
  const handleSave = (e: React.FormEvent) => {
    e.preventDefault();
    setSaved(true);
    setTimeout(() => setSaved(false), 2500);
  };

  return (
    <form onSubmit={handleSave} className="space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-foreground">Paramètres</h1>
        <p className="text-muted-foreground">Configurez votre boutique DreamRest</p>
      </div>

      <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
        <h3 className="mb-4 font-semibold text-foreground">Informations entreprise</h3>
        <div className="grid gap-4 sm:grid-cols-2">
          <div>
            <label className="mb-1 block text-xs text-muted-foreground">Nom de l'entreprise</label>
            <input defaultValue="DreamRest" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
          </div>
          <div>
            <label className="mb-1 block text-xs text-muted-foreground">Email</label>
            <input defaultValue="contact@dreamrest.fr" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
          </div>
          <div>
            <label className="mb-1 block text-xs text-muted-foreground">Téléphone</label>
            <input defaultValue="+33 1 23 45 67 89" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
          </div>
          <div>
            <label className="mb-1 block text-xs text-muted-foreground">WhatsApp</label>
            <input defaultValue="+33600000000" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
          </div>
        </div>
      </div>

      <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
        <h3 className="mb-4 font-semibold text-foreground">Préférences de notification</h3>
        <div className="space-y-3">
          {[
            "Notifications email pour nouvelles demandes",
            "Notifications WhatsApp pour demandes urgentes",
            "Résumé quotidien par email",
            "Alertes inscription nouveaux clients",
          ].map((item) => (
            <label key={item} className="flex items-center gap-3 text-sm text-foreground">
              <input type="checkbox" defaultChecked className="rounded accent-primary" />
              {item}
            </label>
          ))}
        </div>
      </div>

      <div className="flex items-center gap-4">
        <button type="submit" className="inline-flex items-center gap-2 rounded-lg bg-gradient-blue px-6 py-2.5 text-sm font-medium text-white shadow-md hover:scale-105 transition-transform">
          <Save className="h-4 w-4" /> Enregistrer
        </button>
        {saved && <span className="text-sm font-medium text-success">✓ Paramètres enregistrés</span>}
      </div>
    </form>
  );
}
