import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { useAuth, type Farm } from "@/hooks/use-auth";
import { PageShell } from "@/components/page-shell";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import {
  Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger, DialogFooter, DialogDescription,
} from "@/components/ui/dialog";
import {
  AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent,
  AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle, AlertDialogTrigger,
} from "@/components/ui/alert-dialog";
import { Plus, Building2, MapPin, User, Mail, Search, Trash2, Pencil, ShieldAlert, Power } from "lucide-react";
import { toast } from "sonner";
import { requireAuth } from "@/lib/auth-guard";

export const Route = createFileRoute("/farms")({
  beforeLoad: ({ location }) => requireAuth(location),
  component: FarmsPage,
});

function FarmsPage() {
  const { user, farms, addFarm, updateFarm, removeFarm } = useAuth();
  const [search, setSearch] = useState("");
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState<Farm | null>(null);

  const isAdmin = user?.role === "admin";
  const visible = isAdmin ? farms : farms.filter((f) => f.id === user?.farmId);
  const filtered = visible.filter((f) =>
    [f.name, f.location, f.manager].join(" ").toLowerCase().includes(search.toLowerCase()),
  );

  const total = filtered.reduce((s, f) => s + f.active, 0);
  const capacity = filtered.reduce((s, f) => s + f.capacity, 0);

  const addBtn = isAdmin ? (
    <Dialog open={open} onOpenChange={(o) => { setOpen(o); if (!o) setEditing(null); }}>
      <DialogTrigger asChild>
        <Button size="sm" variant="secondary">
          <Plus className="h-4 w-4" /> Nouvelle ferme
        </Button>
      </DialogTrigger>
      <FarmDialog
        editing={editing}
        onClose={() => { setOpen(false); setEditing(null); }}
        onSave={(data) => {
          if (editing) {
            updateFarm(editing.id, data);
            toast.success("Ferme mise à jour");
          } else {
            addFarm({ ...data, active: 0, status: "active" });
            toast.success("Ferme ajoutée");
          }
          setOpen(false);
          setEditing(null);
        }}
      />
    </Dialog>
  ) : null;

  return (
    <PageShell
      title="Gestion des fermes"
      subtitle={isAdmin ? "Ajoutez et gérez les fermes du réseau ECOTEC." : "Votre ferme assignée."}
      icon={Building2}
      actions={addBtn}
    >
          {/* Stats */}

          <div className="grid grid-cols-2 lg:grid-cols-4 gap-3">
            {[
              { l: "Fermes", v: filtered.length, c: "primary" },
              { l: "Volailles actives", v: total.toLocaleString("fr"), c: "success" },
              { l: "Capacité totale", v: capacity.toLocaleString("fr"), c: "info" },
              { l: "Taux d'occupation", v: capacity ? `${Math.round((total / capacity) * 100)}%` : "—", c: "warning" },
            ].map((s) => (
              <Card key={s.l} className="p-4">
                <p className="text-xs text-muted-foreground">{s.l}</p>
                <p className="text-2xl font-bold mt-1">{s.v}</p>
              </Card>
            ))}
          </div>

          {/* Search */}
          <div className="relative max-w-md">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input
              placeholder="Rechercher une ferme…"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="pl-9 h-11"
            />
          </div>

          {/* Grid */}
          {filtered.length === 0 ? (
            <Card className="p-12 text-center">
              <Building2 className="h-12 w-12 text-muted-foreground mx-auto mb-3" />
              <p className="font-semibold">Aucune ferme trouvée</p>
              <p className="text-sm text-muted-foreground mt-1">
                {isAdmin ? "Ajoutez votre première ferme pour commencer." : "Aucune ferme assignée."}
              </p>
            </Card>
          ) : (
            <div className="grid md:grid-cols-2 xl:grid-cols-3 gap-4">
              {filtered.map((f, i) => (
                <Card
                  key={f.id}
                  className="p-5 hover:shadow-elegant transition group animate-[fade-in_0.4s_ease-out_both]"
                  style={{ animationDelay: `${i * 60}ms` }}
                >
                  <div className="flex items-start justify-between gap-3 mb-4">
                    <div className="flex items-center gap-3 min-w-0">
                      <div className="h-11 w-11 rounded-xl gradient-primary flex items-center justify-center shrink-0 shadow-glow">
                        <Building2 className="h-5 w-5 text-primary-foreground" />
                      </div>
                      <div className="min-w-0">
                        <h3 className="font-semibold truncate">{f.name}</h3>
                        <p className="text-xs text-muted-foreground flex items-center gap-1 mt-0.5">
                          <MapPin className="h-3 w-3" /> {f.location}
                        </p>
                      </div>
                    </div>
                    <Badge
                      variant="outline"
                      className={f.status === "active"
                        ? "border-success/30 bg-success/10 text-success"
                        : "border-warning/30 bg-warning/10 text-warning"
                      }
                    >
                      {f.status === "active" ? "Active" : "En pause"}
                    </Badge>
                  </div>

                  <div className="space-y-2 text-sm border-t border-border pt-4">
                    <div className="flex items-center gap-2 text-muted-foreground">
                      <User className="h-3.5 w-3.5" />
                      <span className="truncate">{f.manager}</span>
                    </div>
                    <div className="flex items-center gap-2 text-muted-foreground">
                      <Mail className="h-3.5 w-3.5" />
                      <span className="truncate">{f.email}</span>
                    </div>
                  </div>

                  <div className="grid grid-cols-2 gap-2 mt-4 text-center">
                    <div className="p-2 rounded-lg bg-muted/50">
                      <p className="text-[10px] text-muted-foreground uppercase">Volailles</p>
                      <p className="text-lg font-bold">{f.active.toLocaleString("fr")}</p>
                    </div>
                    <div className="p-2 rounded-lg bg-muted/50">
                      <p className="text-[10px] text-muted-foreground uppercase">Capacité</p>
                      <p className="text-lg font-bold">{f.capacity.toLocaleString("fr")}</p>
                    </div>
                  </div>

                  <div className="mt-2 h-1.5 rounded-full bg-muted overflow-hidden">
                    <div
                      className="h-full gradient-primary transition-all"
                      style={{ width: `${Math.min(100, (f.active / f.capacity) * 100)}%` }}
                    />
                  </div>

                  {isAdmin && (
                    <div className="flex gap-2 mt-4 pt-4 border-t border-border">
                      <Button
                        variant="outline"
                        size="sm"
                        className="flex-1"
                        onClick={() => { setEditing(f); setOpen(true); }}
                      >
                        <Pencil className="h-3.5 w-3.5" /> Modifier
                      </Button>
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => {
                          updateFarm(f.id, { status: f.status === "active" ? "paused" : "active" });
                          toast.success(`Ferme ${f.status === "active" ? "mise en pause" : "réactivée"}`);
                        }}
                      >
                        <Power className="h-3.5 w-3.5" />
                      </Button>
                      <AlertDialog>
                        <AlertDialogTrigger asChild>
                          <Button variant="outline" size="sm" className="text-destructive hover:text-destructive">
                            <Trash2 className="h-3.5 w-3.5" />
                          </Button>
                        </AlertDialogTrigger>
                        <AlertDialogContent>
                          <AlertDialogHeader>
                            <AlertDialogTitle className="flex items-center gap-2">
                              <ShieldAlert className="h-5 w-5 text-destructive" /> Supprimer cette ferme ?
                            </AlertDialogTitle>
                            <AlertDialogDescription>
                              Cette action est irréversible. La ferme « {f.name} » sera définitivement supprimée.
                            </AlertDialogDescription>
                          </AlertDialogHeader>
                          <AlertDialogFooter>
                            <AlertDialogCancel>Annuler</AlertDialogCancel>
                            <AlertDialogAction
                              className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                              onClick={() => { removeFarm(f.id); toast.success("Ferme supprimée"); }}
                            >
                              Supprimer
                            </AlertDialogAction>
                          </AlertDialogFooter>
                        </AlertDialogContent>
                      </AlertDialog>
                    </div>
                  )}
                </Card>
              ))}
            </div>
          )}
    </PageShell>
  );
}

function FarmDialog({
  editing,
  onClose,
  onSave,
}: {
  editing: Farm | null;
  onClose: () => void;
  onSave: (data: Omit<Farm, "id" | "createdAt" | "active" | "status">) => void;
}) {
  const [name, setName] = useState(editing?.name ?? "");
  const [location, setLocation] = useState(editing?.location ?? "");
  const [manager, setManager] = useState(editing?.manager ?? "");
  const [email, setEmail] = useState(editing?.email ?? "");
  const [capacity, setCapacity] = useState(editing?.capacity ?? 1000);

  return (
    <DialogContent className="sm:max-w-lg">
      <DialogHeader>
        <DialogTitle>{editing ? "Modifier la ferme" : "Ajouter une ferme"}</DialogTitle>
        <DialogDescription>
          {editing ? "Mettez à jour les informations." : "Créez une nouvelle ferme dans le réseau."}
        </DialogDescription>
      </DialogHeader>
      <form
        onSubmit={(e) => {
          e.preventDefault();
          onSave({ name, location, manager, email, capacity: Number(capacity) });
        }}
        className="space-y-4"
      >
        <div className="space-y-1.5">
          <Label htmlFor="fname">Nom de la ferme</Label>
          <Input id="fname" required value={name} onChange={(e) => setName(e.target.value)} placeholder="Ferme Pilote Yaoundé" />
        </div>
        <div className="grid sm:grid-cols-2 gap-4">
          <div className="space-y-1.5">
            <Label htmlFor="floc">Localisation</Label>
            <Input id="floc" required value={location} onChange={(e) => setLocation(e.target.value)} placeholder="Yaoundé, Centre" />
          </div>
          <div className="space-y-1.5">
            <Label htmlFor="fcap">Capacité</Label>
            <Input id="fcap" required type="number" min={100} value={capacity} onChange={(e) => setCapacity(Number(e.target.value))} />
          </div>
        </div>
        <div className="space-y-1.5">
          <Label htmlFor="fman">Gérant</Label>
          <Input id="fman" required value={manager} onChange={(e) => setManager(e.target.value)} placeholder="Nom du gérant" />
        </div>
        <div className="space-y-1.5">
          <Label htmlFor="fmail">Email du gérant</Label>
          <Input id="fmail" required type="email" value={email} onChange={(e) => setEmail(e.target.value)} placeholder="gerant@ecotec.cm" />
        </div>
        <DialogFooter>
          <Button type="button" variant="outline" onClick={onClose}>Annuler</Button>
          <Button type="submit" className="gradient-primary text-primary-foreground">
            {editing ? "Enregistrer" : "Ajouter la ferme"}
          </Button>
        </DialogFooter>
      </form>
    </DialogContent>
  );
}
