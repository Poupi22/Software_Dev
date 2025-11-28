import { createFileRoute } from "@tanstack/react-router";
import { Search, Filter, MoreHorizontal, UserPlus, Download } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { adminUsers } from "@/lib/mock-data";

export const Route = createFileRoute("/admin/users")({
  component: UsersPage,
});

function UsersPage() {
  return (
    <div className="space-y-5">
      <div className="flex items-center justify-between flex-wrap gap-3">
        <div>
          <h1 className="font-display text-2xl font-bold">Utilisateurs</h1>
          <p className="text-sm text-muted-foreground">Gérez les comptes clients et prestataires.</p>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" size="sm"><Download className="h-4 w-4 mr-2" /> Exporter</Button>
          <Button size="sm"><UserPlus className="h-4 w-4 mr-2" /> Inviter</Button>
        </div>
      </div>

      <div className="bg-card border border-border rounded-xl">
        <div className="p-4 border-b border-border flex flex-wrap items-center gap-2">
          <div className="relative flex-1 min-w-[200px]">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input placeholder="Rechercher par nom, email…" className="pl-9 h-9" />
          </div>
          <Button variant="outline" size="sm"><Filter className="h-4 w-4 mr-2" /> Rôle</Button>
          <Button variant="outline" size="sm"><Filter className="h-4 w-4 mr-2" /> Statut</Button>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="text-xs text-muted-foreground uppercase border-b border-border bg-muted/40">
              <tr>
                <th className="text-left py-3 px-4"><input type="checkbox" className="accent-primary" /></th>
                <th className="text-left py-3 px-4">Utilisateur</th>
                <th className="text-left py-3 px-4">Rôle</th>
                <th className="text-left py-3 px-4">Statut</th>
                <th className="text-left py-3 px-4">Inscription</th>
                <th className="text-right py-3 px-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              {adminUsers.map((u) => (
                <tr key={u.id} className="border-b border-border last:border-0 hover:bg-muted/40">
                  <td className="py-3 px-4"><input type="checkbox" className="accent-primary" /></td>
                  <td className="py-3 px-4">
                    <div className="flex items-center gap-3">
                      <img src={u.avatar} alt={u.name} className="h-9 w-9 rounded-full object-cover" />
                      <div>
                        <div className="font-semibold">{u.name}</div>
                        <div className="text-xs text-muted-foreground">{u.email}</div>
                      </div>
                    </div>
                  </td>
                  <td className="py-3 px-4">
                    <span className={`text-xs px-2 py-0.5 rounded-full font-semibold ${u.role === "Prestataire" ? "bg-secondary/15 text-secondary" : "bg-accent text-accent-foreground"}`}>{u.role}</span>
                  </td>
                  <td className="py-3 px-4">
                    <span className={`inline-flex items-center gap-1 text-xs font-semibold ${u.status === "Actif" ? "text-success" : "text-destructive"}`}>
                      <span className={`h-1.5 w-1.5 rounded-full ${u.status === "Actif" ? "bg-success" : "bg-destructive"}`} />
                      {u.status}
                    </span>
                  </td>
                  <td className="py-3 px-4 text-muted-foreground">{u.joined}</td>
                  <td className="py-3 px-4 text-right">
                    <Button variant="ghost" size="icon"><MoreHorizontal className="h-4 w-4" /></Button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        <div className="p-3 border-t border-border flex items-center justify-between text-sm">
          <span className="text-muted-foreground text-xs">12 sur 12 480</span>
          <div className="flex gap-1">
            <Button variant="outline" size="sm">Précédent</Button>
            <Button variant="outline" size="sm">Suivant</Button>
          </div>
        </div>
      </div>
    </div>
  );
}
