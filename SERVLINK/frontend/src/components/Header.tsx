import { Link, useRouterState } from "@tanstack/react-router";
import { Search, Bell, MessageCircle, Menu, LayoutDashboard, LogOut } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Logo } from "./Logo";
import { useSession } from "@/lib/auth";
import { LogoutDialog } from "./LogoutDialog";

const publicLinks = [
  { to: "/", label: "Accueil" },
  { to: "/search", label: "Explorer" },
];

const clientLinks = [
  { to: "/dashboard", label: "Mon espace" },
  { to: "/bookings", label: "Réservations" },
  { to: "/messages", label: "Messages" },
];

export function Header() {
  const path = useRouterState({ select: (r) => r.location.pathname });
  const session = useSession();
  const isClient = session?.role === "client";

  const links = [...publicLinks, ...(isClient ? clientLinks : [])];

  return (
    <header className="sticky top-0 z-40 w-full border-b border-border bg-background/85 backdrop-blur">
      <div className="container mx-auto flex h-16 items-center justify-between gap-4 px-4">
        <Link to="/" className="flex items-center gap-2">
          <Logo />
        </Link>

        <nav className="hidden md:flex items-center gap-1">
          {links.map((l) => {
            const active = l.to === "/" ? path === "/" : path.startsWith(l.to);
            return (
              <Link
                key={l.to}
                to={l.to}
                className={`px-3 py-2 rounded-md text-sm font-medium transition-colors ${
                  active
                    ? "text-primary bg-accent"
                    : "text-foreground/70 hover:text-foreground hover:bg-muted"
                }`}
              >
                {l.label}
              </Link>
            );
          })}
        </nav>

        <div className="flex items-center gap-1">
          <Link to="/search" className="md:hidden">
            <Button variant="ghost" size="icon"><Search className="h-5 w-5" /></Button>
          </Link>

          {session ? (
            <>
              {isClient && (
                <>
                  <Button variant="ghost" size="icon" className="relative hidden sm:inline-flex">
                    <Bell className="h-5 w-5" />
                    <span className="absolute top-2 right-2 h-2 w-2 rounded-full bg-destructive" />
                  </Button>
                  <Link to="/messages" className="hidden sm:block">
                    <Button variant="ghost" size="icon"><MessageCircle className="h-5 w-5" /></Button>
                  </Link>
                  <Link to="/dashboard" className="hidden md:block">
                    <Button variant="outline" size="sm" className="ml-2">
                      <LayoutDashboard className="h-4 w-4 mr-1.5" /> Mon espace
                    </Button>
                  </Link>
                </>
              )}
              {session.role === "admin" && (
                <Link to="/admin" className="hidden md:block">
                  <Button size="sm" className="ml-2">Administration</Button>
                </Link>
              )}
              {session.role === "provider" && (
                <Link to="/provider" className="hidden md:block">
                  <Button size="sm" className="ml-2">Espace prestataire</Button>
                </Link>
              )}
              <Link to={isClient ? "/profile" : session.role === "admin" ? "/admin" : "/provider"} className="hidden md:block">
                <span className="ml-2 inline-flex h-9 w-9 rounded-full bg-primary text-primary-foreground items-center justify-center text-xs font-bold">
                  {session.name.split(" ").map((n) => n[0]).slice(0, 2).join("")}
                </span>
              </Link>
              <LogoutDialog userLabel={session.name}>
                <Button variant="ghost" size="icon" className="text-destructive" aria-label="Déconnexion">
                  <LogOut className="h-5 w-5" />
                </Button>
              </LogoutDialog>
            </>
          ) : (
            <Link to="/login" className="hidden md:block">
              <Button size="sm" className="ml-2">Connexion</Button>
            </Link>
          )}

          <Button variant="ghost" size="icon" className="md:hidden"><Menu className="h-5 w-5" /></Button>
        </div>
      </div>
    </header>
  );
}
