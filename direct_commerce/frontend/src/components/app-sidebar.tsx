import { Link, useRouterState } from "@tanstack/react-router";
import { useTranslation } from "react-i18next";
import {
  LayoutDashboard, Bird, HeartPulse, Users, Recycle,
  Wallet, Store, FileText, Settings, Sparkles, Building2,
} from "lucide-react";
import { cn } from "@/lib/utils";
import { useAuth } from "@/hooks/use-auth";

export const navItems = [
  { to: "/dashboard", key: "dashboard", icon: LayoutDashboard, roles: ["admin", "manager"] },
  { to: "/farms", key: "farms", icon: Building2, roles: ["admin", "manager"] },
  { to: "/flocks", key: "flocks", icon: Bird, roles: ["admin", "manager"] },
  { to: "/health", key: "health", icon: HeartPulse, roles: ["admin", "manager"] },
  { to: "/cooperators", key: "cooperators", icon: Users, roles: ["admin"] },
  { to: "/compost", key: "compost", icon: Recycle, roles: ["admin", "manager"] },
  { to: "/finance", key: "finance", icon: Wallet, roles: ["admin", "manager"] },
  { to: "/market", key: "market", icon: Store, roles: ["admin"] },
  { to: "/reports", key: "reports", icon: FileText, roles: ["admin", "manager"] },
  { to: "/settings", key: "settings", icon: Settings, roles: ["admin", "manager"] },
] as const;

export function AppSidebar() {
  const { t } = useTranslation();
  const { user } = useAuth();
  const path = useRouterState({ select: (s) => s.location.pathname });
  const role = user?.role ?? "manager";
  const items = navItems.filter((it) => (it.roles as readonly string[]).includes(role));

  return (
    <aside className="hidden lg:flex fixed inset-y-0 left-0 z-30 w-64 flex-col border-r border-sidebar-border bg-sidebar">
      <div className="flex items-center gap-3 px-6 py-5 border-b border-sidebar-border">
        <div className="h-10 w-10 rounded-xl gradient-primary flex items-center justify-center shadow-glow">
          <Sparkles className="h-5 w-5 text-primary-foreground" />
        </div>
        <div>
          <p className="text-sm font-bold text-sidebar-foreground leading-tight">ECOTEC</p>
          <p className="text-xs text-muted-foreground">Smart Poultry</p>
        </div>
      </div>

      <nav className="flex-1 overflow-y-auto p-3 space-y-1">
        {items.map((item) => {
          const active = path === item.to;
          const Icon = item.icon;
          return (
            <Link
              key={item.to}
              to={item.to}
              className={cn(
                "flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all",
                active
                  ? "bg-sidebar-primary text-sidebar-primary-foreground shadow-elegant"
                  : "text-sidebar-foreground hover:bg-sidebar-accent"
              )}
            >
              <Icon className="h-4 w-4 shrink-0" />
              <span>{t(`nav.${item.key}`)}</span>
            </Link>
          );
        })}
      </nav>

      <div className="m-3 rounded-xl gradient-hero p-4 text-primary-foreground">
        <p className="text-xs uppercase tracking-wider opacity-80">POESAM 2026</p>
        <p className="text-sm font-semibold mt-1">Orange • Concours</p>
        <p className="text-[11px] mt-2 opacity-80">IA · Coopérative · Agroécologie</p>
      </div>
    </aside>
  );
}
