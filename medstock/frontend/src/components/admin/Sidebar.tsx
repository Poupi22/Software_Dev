import { Link, useLocation, useNavigate } from "@tanstack/react-router";
import {
  LayoutDashboard, Pill, FolderTree, PackageMinus,
  Receipt, AlertTriangle, Archive, Users, FileBarChart2, Bell, Activity, Settings, LogOut, Stethoscope, ShoppingCart,
} from "lucide-react";
import { useAuth } from "@/lib/auth";
import { cn } from "@/lib/utils";

type NavItem = { to: string; label: string; icon: typeof LayoutDashboard; exact?: boolean; highlight?: boolean; roles?: Array<"ADMIN" | "GERANT"> };
const allItems: NavItem[] = [
  { to: "/admin", label: "Tableau de bord", icon: LayoutDashboard, exact: true, roles: ["ADMIN"] },
  { to: "/admin/nouvelle-vente", label: "Nouvelle vente", icon: ShoppingCart, highlight: true, roles: ["ADMIN", "GERANT"] },
  { to: "/admin/medicaments", label: "Médicaments", icon: Pill, roles: ["ADMIN", "GERANT"] },
  { to: "/admin/categories", label: "Catégories", icon: FolderTree, roles: ["ADMIN"] },
  { to: "/admin/sorties", label: "Sorties de stock", icon: PackageMinus, roles: ["ADMIN"] },
  { to: "/admin/ventes", label: "Historique ventes", icon: Receipt, roles: ["ADMIN", "GERANT"] },
  { to: "/admin/alertes", label: "Alertes expiration", icon: AlertTriangle, roles: ["ADMIN"] },
  { to: "/admin/immobiles", label: "Produits immobiles", icon: Archive, roles: ["ADMIN"] },
  { to: "/admin/utilisateurs", label: "Gestion utilisateurs", icon: Users, roles: ["ADMIN"] },
  { to: "/admin/rapports", label: "Rapports", icon: FileBarChart2, roles: ["ADMIN"] },
  { to: "/admin/notifications", label: "Notifications", icon: Bell, roles: ["ADMIN", "GERANT"] },
  { to: "/admin/activite", label: "Activité système", icon: Activity, roles: ["ADMIN"] },
  { to: "/admin/parametres", label: "Paramètres", icon: Settings, roles: ["ADMIN", "GERANT"] },
];

export function Sidebar({ onNavigate }: { onNavigate?: () => void }) {
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout } = useAuth();
  const items = allItems.filter((i) => !i.roles || (user && i.roles.includes(user.role)));

  const isActive = (to: string, exact?: boolean) =>
    exact ? location.pathname === to : location.pathname === to || location.pathname.startsWith(to + "/");

  return (
    <aside className="flex h-screen w-64 flex-col bg-gradient-sidebar text-sidebar-foreground">
      <div className="flex items-center gap-3 px-5 py-5 border-b border-sidebar-border">
        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-primary shadow-glow">
          <Stethoscope className="h-5 w-5 text-primary-foreground" />
        </div>
        <div>
          <div className="text-base font-semibold tracking-tight">PharmaCare</div>
          <div className="text-[11px] uppercase tracking-wider text-sidebar-foreground/60">
            {user?.role === "GERANT" ? "Espace Gérant" : "Stock médical"}
          </div>
        </div>
      </div>

      <nav className="flex-1 overflow-y-auto scrollbar-thin px-3 py-4 space-y-0.5">
        {items.map(({ to, label, icon: Icon, exact, highlight }) => {
          const active = isActive(to, exact);
          return (
            <Link
              key={to}
              to={to}
              onClick={onNavigate}
              className={cn(
                "group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all",
                active
                  ? "bg-sidebar-primary text-sidebar-primary-foreground shadow-glow"
                  : highlight
                    ? "bg-sidebar-accent/40 text-sidebar-foreground ring-1 ring-sidebar-primary/40 hover:bg-sidebar-accent"
                    : "text-sidebar-foreground/75 hover:bg-sidebar-accent hover:text-sidebar-foreground"
              )}
            >
              <Icon className={cn("h-4 w-4 shrink-0", active && "text-sidebar-primary-foreground", !active && highlight && "text-sidebar-primary")} />
              <span className="truncate">{label}</span>
              {active && <span className="ml-auto h-1.5 w-1.5 rounded-full bg-sidebar-primary-foreground" />}
              {!active && highlight && <span className="ml-auto rounded-full bg-sidebar-primary px-1.5 py-0.5 text-[9px] font-bold text-sidebar-primary-foreground">VENTE</span>}
            </Link>
          );
        })}
      </nav>

      <div className="border-t border-sidebar-border p-4">
        <div className="flex items-center gap-3 rounded-lg bg-sidebar-accent/50 p-3">
          {user?.avatar ? (
            <img src={user.avatar} alt={user.nom} className="h-9 w-9 rounded-full object-cover ring-2 ring-sidebar-primary/40" />
          ) : (
            <div className="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-primary text-sm font-semibold text-primary-foreground">
              {user?.nom.split(" ").map((n) => n[0]).slice(0, 2).join("")}
            </div>
          )}
          <div className="min-w-0 flex-1">
            <div className="truncate text-sm font-medium">{user?.nom}</div>
            <div className="truncate text-[11px] text-sidebar-foreground/60">{user?.role}</div>
          </div>
          <button
            onClick={() => { logout(); navigate({ to: "/login" }); }}
            className="rounded-md p-1.5 text-sidebar-foreground/60 hover:bg-sidebar-accent hover:text-sidebar-foreground transition-colors"
            aria-label="Déconnexion"
          >
            <LogOut className="h-4 w-4" />
          </button>
        </div>
      </div>
    </aside>
  );
}
