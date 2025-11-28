import { createFileRoute } from "@tanstack/react-router";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Switch } from "@/components/ui/switch";

export const Route = createFileRoute("/provider/settings")({
  component: () => (
    <div className="space-y-6 max-w-2xl">
      <div>
        <h1 className="font-display text-2xl font-bold">Paramètres</h1>
        <p className="text-muted-foreground text-sm">Disponibilité, paiements et notifications.</p>
      </div>
      <div className="bg-card border border-border rounded-2xl p-6 space-y-4">
        <h2 className="font-display font-bold">Disponibilité</h2>
        {["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"].map((d, i) => (
          <div key={d} className="flex items-center justify-between">
            <span className="text-sm">{d}</span>
            <Switch defaultChecked={i < 6} />
          </div>
        ))}
      </div>
      <div className="bg-card border border-border rounded-2xl p-6 space-y-4">
        <h2 className="font-display font-bold">Paiements</h2>
        <div>
          <Label>Numéro MTN MoMo</Label>
          <Input defaultValue="+237 6 77 88 99 00" className="mt-1 h-11" />
        </div>
        <div>
          <Label>Numéro Orange Money</Label>
          <Input placeholder="Optionnel" className="mt-1 h-11" />
        </div>
        <Button>Enregistrer</Button>
      </div>
    </div>
  ),
});
