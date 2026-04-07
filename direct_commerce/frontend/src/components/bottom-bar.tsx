import { Link, useRouterState } from "@tanstack/react-router";
import { LayoutDashboard, Building2, HeartPulse, Wallet } from "lucide-react";
import { cn } from "@/lib/utils";

const items = [
  { to: "/dashboard", label: "Dashboard", icon: LayoutDashboard },
  { to: "/farms", label: "Fermes", icon: Building2 },
  { to: "/health", label: "Santé", icon: HeartPulse },
  { to: "/finance", label: "Finance", icon: Wallet },
] as const;

export function BottomBar() {
  const path = useRouterState({ select: (s) => s.location.pathname });

  return (
    <nav className="lg:hidden fixed bottom-0 inset-x-0 z-40 border-t border-border bg-card/95 backdrop-blur-md">
      <div className="grid grid-cols-4 h-16 safe-bottom">
        {items.map((item) => {
          const active = path === item.to;
          const Icon = item.icon;
          return (
            <Link
              key={item.to}
              to={item.to}
              className={cn(
                "flex flex-col items-center justify-center gap-1 transition-colors relative",
                active ? "text-primary" : "text-muted-foreground"
              )}
            >
              {active && (
                <span className="absolute top-0 h-1 w-10 rounded-b-full gradient-primary" />
              )}
              <Icon className="h-5 w-5" />
              <span className="text-[11px] font-medium">{item.label}</span>
            </Link>
          );
        })}
      </div>
    </nav>
  );
}
