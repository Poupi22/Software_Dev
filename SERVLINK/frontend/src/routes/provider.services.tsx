import { createFileRoute } from "@tanstack/react-router";
import { Plus, Edit2, Trash2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { formatXAF } from "@/lib/mock-data";

export const Route = createFileRoute("/provider/services")({
  component: ProviderServices,
});

const services = [
  { id: "s1", name: "Réparation de fuite",        cat: "Plomberie", price: 15000, duration: "1-2h", active: true, bookings: 32 },
  { id: "s2", name: "Installation robinetterie",  cat: "Plomberie", price: 8000,  duration: "45min", active: true, bookings: 18 },
  { id: "s3", name: "Débouchage canalisation",    cat: "Plomberie", price: 12000, duration: "1h",   active: true, bookings: 24 },
  { id: "s4", name: "Devis sur site",             cat: "Plomberie", price: 5000,  duration: "30min", active: true, bookings: 11 },
  { id: "s5", name: "Remplacement chauffe-eau",   cat: "Plomberie", price: 45000, duration: "3h",   active: false, bookings: 4 },
];

function ProviderServices() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="font-display text-2xl font-bold">Mes services</h1>
          <p className="text-muted-foreground text-sm">Gérez votre catalogue de prestations.</p>
        </div>
        <Button><Plus className="h-4 w-4 mr-1" /> Nouveau service</Button>
      </div>
      <div className="grid md:grid-cols-2 gap-4">
        {services.map((s) => (
          <div key={s.id} className="bg-card border border-border rounded-2xl p-5">
            <div className="flex items-start justify-between gap-3">
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2">
                  <span className="text-xs px-2 py-0.5 rounded-md bg-accent text-primary font-semibold">{s.cat}</span>
                  {s.active ? (
                    <span className="text-xs px-2 py-0.5 rounded-md bg-success/15 text-success font-semibold">Actif</span>
                  ) : (
                    <span className="text-xs px-2 py-0.5 rounded-md bg-muted text-muted-foreground font-semibold">Inactif</span>
                  )}
                </div>
                <h3 className="font-display font-bold text-lg mt-2">{s.name}</h3>
                <div className="flex items-center gap-4 mt-2 text-sm text-muted-foreground">
                  <span>Durée : <span className="text-foreground font-medium">{s.duration}</span></span>
                  <span>·</span>
                  <span>{s.bookings} réservations</span>
                </div>
              </div>
              <div className="text-right">
                <div className="text-[11px] text-muted-foreground uppercase">À partir de</div>
                <div className="font-display text-xl font-bold text-primary">{formatXAF(s.price)}</div>
              </div>
            </div>
            <div className="flex gap-2 mt-4 pt-4 border-t border-border">
              <Button variant="outline" size="sm" className="flex-1"><Edit2 className="h-3.5 w-3.5 mr-1" /> Modifier</Button>
              <Button variant="outline" size="sm" className="text-destructive border-destructive/30"><Trash2 className="h-3.5 w-3.5" /></Button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
