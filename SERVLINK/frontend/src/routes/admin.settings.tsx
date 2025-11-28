import { createFileRoute } from "@tanstack/react-router";
import { Save, Plus, Trash2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

export const Route = createFileRoute("/admin/settings")({
  component: SettingsPage,
});

function SettingsPage() {
  return (
    <div className="space-y-5 max-w-4xl">
      <div>
        <h1 className="font-display text-2xl font-bold">Paramètres de la plateforme</h1>
        <p className="text-sm text-muted-foreground">Configuration globale, politiques et accès administrateurs.</p>
      </div>

      <Section title="Catégories de services">
        <div className="flex flex-wrap gap-2">
          {["Plomberie", "Électricité", "Ménage", "Coiffure", "Menuiserie", "Informatique", "Cours", "Événementiel"].map((c) => (
            <span key={c} className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent text-accent-foreground text-sm">
              {c}
              <button className="text-destructive hover:opacity-70"><Trash2 className="h-3 w-3" /></button>
            </span>
          ))}
          <Button size="sm" variant="outline"><Plus className="h-4 w-4 mr-1" /> Ajouter</Button>
        </div>
      </Section>

      <Section title="Commission de la plateforme">
        <div className="grid sm:grid-cols-3 gap-3">
          <Field label="Commission standard (%)" value="10" />
          <Field label="Commission prestataire vérifié (%)" value="8" />
          <Field label="TVA applicable (%)" value="19.25" />
        </div>
      </Section>

      <Section title="Politique d'annulation & remboursement">
        <div className="space-y-3">
          <label className="flex items-center gap-2 text-sm"><input type="checkbox" defaultChecked className="accent-primary" /> Annulation gratuite jusqu'à 24h avant la prestation</label>
          <label className="flex items-center gap-2 text-sm"><input type="checkbox" defaultChecked className="accent-primary" /> Remboursement automatique en cas d'annulation par le prestataire</label>
          <label className="flex items-center gap-2 text-sm"><input type="checkbox" className="accent-primary" /> Pénalité de 10% en cas d'annulation tardive</label>
        </div>
      </Section>

      <Section title="Emails transactionnels">
        <div className="space-y-2">
          {["Confirmation d'inscription", "Validation de réservation", "Reçu de paiement", "Demande d'avis", "Notification de litige"].map((e) => (
            <div key={e} className="flex items-center justify-between p-3 rounded-lg border border-border">
              <span className="text-sm">{e}</span>
              <Button variant="outline" size="sm">Éditer le template</Button>
            </div>
          ))}
        </div>
      </Section>

      <Section title="Accès administrateurs">
        <div className="space-y-2">
          {[
            { n: "Ymdra (Super Admin)", e: "ymdra@servlink.cm", r: "Super-admin" },
            { n: "Sophie Mvogo", e: "sophie@servlink.cm", r: "Modérateur" },
            { n: "Karim Diallo", e: "karim@servlink.cm", r: "Support" },
          ].map((a) => (
            <div key={a.e} className="flex items-center justify-between p-3 rounded-lg border border-border">
              <div>
                <div className="text-sm font-semibold">{a.n}</div>
                <div className="text-xs text-muted-foreground">{a.e}</div>
              </div>
              <div className="flex items-center gap-3">
                <span className="text-xs px-2 py-0.5 rounded-full bg-secondary/15 text-secondary font-semibold">{a.r}</span>
                <Button variant="ghost" size="sm">Permissions</Button>
              </div>
            </div>
          ))}
          <Button variant="outline" size="sm" className="mt-2"><Plus className="h-4 w-4 mr-1" /> Inviter un admin</Button>
        </div>
      </Section>

      <div className="flex justify-end gap-2">
        <Button variant="outline">Annuler</Button>
        <Button><Save className="h-4 w-4 mr-2" /> Enregistrer les modifications</Button>
      </div>
    </div>
  );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="bg-card border border-border rounded-xl p-5">
      <h2 className="font-display font-bold mb-4">{title}</h2>
      {children}
    </div>
  );
}

function Field({ label, value }: { label: string; value: string }) {
  return (
    <div>
      <label className="text-xs font-medium text-muted-foreground">{label}</label>
      <Input defaultValue={value} className="mt-1 h-10" />
    </div>
  );
}
