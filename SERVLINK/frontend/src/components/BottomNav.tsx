import { Link, useRouterState } from "@tanstack/react-router";
import { Home, Search, LayoutDashboard, CalendarCheck, User } from "lucide-react";

const tabs = [
  { to: "/", label: "Accueil", icon: Home, exact: true },
  { to: "/search", label: "Explorer", icon: Search },
  { to: "/dashboard", label: "Espace", icon: LayoutDashboard },
  { to: "/bookings", label: "Résa.", icon: CalendarCheck },
  { to: "/profile", label: "Profil", icon: User },
];

export function BottomNav() {
  const path = useRouterState({ select: (r) => r.location.pathname });
  return (
    <nav className="md:hidden fixed bottom-0 inset-x-0 z-40 bg-background border-t border-border pb-[env(safe-area-inset-bottom)]">
      <ul className="flex justify-around items-stretch h-16">
        {tabs.map((t) => {
          const active = t.exact ? path === t.to : path.startsWith(t.to);
          const Icon = t.icon;
          return (
            <li key={t.to} className="flex-1">
              <Link
                to={t.to}
                className={`flex flex-col items-center justify-center gap-0.5 h-full text-[11px] font-medium transition-colors ${
                  active ? "text-primary" : "text-muted-foreground"
                }`}
              >
                <Icon className={`h-5 w-5 ${active ? "scale-110" : ""} transition-transform`} />
                {t.label}
                {active && <span className="absolute top-0 h-0.5 w-8 bg-primary rounded-b" />}
              </Link>
            </li>
          );
        })}
      </ul>
    </nav>
  );
}
