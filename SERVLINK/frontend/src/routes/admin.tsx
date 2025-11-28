import { createFileRoute, Link, Outlet, useRouterState } from "@tanstack/react-router";
import { LayoutDashboard, Users, Briefcase, CreditCard, AlertTriangle, MessageSquareWarning, Settings, Bell, Search, LogOut, Menu } from "lucide-react";
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Logo } from "@/components/Logo";
import { LogoutDialog } from "@/components/LogoutDialog";
import { RequireAuth } from "@/components/RequireAuth";

export const Route = createFileRoute("/admin")({
  head: () => ({ meta: [{ title: "Administration — SERVLINK" }] }),
  component: () => <RequireAuth roles={["admin"]}><AdminLayout /></RequireAuth>,
});

const nav = [
  { to: "/admin", label: "Tableau de bord", icon: LayoutDashboard, exact: true },
  { to: "/admin/users", label: "Utilisateurs", icon: Users },
  { to: "/admin/providers", label: "Prestataires", icon: Briefcase },
  { to: "/admin/transactions", label: "Transactions", icon: CreditCard },
  { to: "/admin/disputes", label: "Litiges", icon: AlertTriangle },
  { to: "/admin/reviews", label: "Avis signalés", icon: MessageSquareWarning },
  { to: "/admin/settings", label: "Paramètres", icon: Settings },
];

function AdminLayout() {
  const path = useRouterState({ select: (r) => r.location.pathname });
  const [open, setOpen] = useState(false);
  return (
    <div className="h-screen flex bg-muted/40 overflow-hidden">
      {/* Sidebar */}
      <aside className={`fixed lg:static inset-y-0 left-0 z-50 w-64 h-screen bg-sidebar text-sidebar-foreground flex flex-col transition-transform shrink-0 ${open ? "translate-x-0" : "-translate-x-full lg:translate-x-0"}`}>
        <div className="h-16 px-5 flex items-center border-b border-sidebar-border shrink-0">
          <Logo variant="light" />
        </div>
        <nav className="flex-1 py-4 px-3 space-y-0.5 overflow-y-auto min-h-0">
          <div className="px-3 py-2 text-[10px] uppercase font-semibold text-white/40 tracking-wider">Pilotage</div>
          {nav.map((n) => {
            const active = n.exact ? path === n.to : path.startsWith(n.to);
            const Icon = n.icon;
            return (
              <Link key={n.to} to={n.to} onClick={() => setOpen(false)} className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ${active ? "bg-sidebar-primary text-sidebar-primary-foreground shadow" : "text-white/75 hover:bg-sidebar-accent hover:text-white"}`}>
                <Icon className="h-4 w-4" />
                {n.label}
              </Link>
            );
          })}
        </nav>
        <div className="p-3 border-t border-sidebar-border shrink-0">
          <div className="flex items-center gap-3 px-2 py-2">
            <div className="h-9 w-9 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-bold text-sm">AD</div>
            <div className="flex-1 min-w-0">
              <div className="text-sm font-semibold truncate">Admin SERVLINK</div>
              <div className="text-xs text-white/50">Super-administrateur</div>
            </div>
            <LogoutDialog userLabel="Admin SERVLINK" redirectTo="/login">
              <button className="text-white/60 hover:text-white" aria-label="Déconnexion">
                <LogOut className="h-4 w-4" />
              </button>
            </LogoutDialog>
          </div>
        </div>
      </aside>

      {open && <div className="fixed inset-0 bg-black/40 z-40 lg:hidden" onClick={() => setOpen(false)} />}

      {/* Main */}
      <div className="flex-1 flex flex-col min-w-0 h-screen">
        <header className="h-16 bg-background border-b border-border flex items-center px-4 lg:px-6 gap-3 shrink-0">
          <Button variant="ghost" size="icon" className="lg:hidden" onClick={() => setOpen(true)}><Menu className="h-5 w-5" /></Button>
          <div className="relative flex-1 max-w-md">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
            <Input placeholder="Rechercher utilisateurs, transactions…" className="pl-9 h-10" />
          </div>
          <Button variant="ghost" size="icon" className="relative">
            <Bell className="h-5 w-5" />
            <span className="absolute top-2 right-2 h-2 w-2 rounded-full bg-destructive" />
          </Button>
          <Link to="/"><Button variant="outline" size="sm">Voir le site</Button></Link>
          <LogoutDialog userLabel="Admin SERVLINK" redirectTo="/login">
            <Button variant="ghost" size="icon" className="text-destructive" aria-label="Déconnexion">
              <LogOut className="h-5 w-5" />
            </Button>
          </LogoutDialog>
        </header>
        <main className="flex-1 overflow-y-auto overflow-x-hidden p-4 lg:p-6">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
