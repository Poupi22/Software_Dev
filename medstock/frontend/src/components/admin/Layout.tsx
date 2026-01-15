import { useEffect, type ReactNode } from "react";
import { useLocation, useNavigate } from "@tanstack/react-router";
import { useAuth } from "@/lib/auth";
import { Sidebar } from "./Sidebar";
import { Topbar } from "./Topbar";

// Routes accessibles aux Gérants (les autres /admin/* sont réservées ADMIN)
const GERANT_ALLOWED = new Set<string>([
  "/admin/nouvelle-vente",
  "/admin/medicaments",
  "/admin/ventes",
  "/admin/notifications",
  "/admin/parametres",
]);

export function AdminLayout({ children }: { children: ReactNode }) {
  const { user } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    if (user === null) navigate({ to: "/login" });
  }, [user, navigate]);

  // RBAC : redirige le Gérant vers Nouvelle vente s'il accède à une page Admin-only
  useEffect(() => {
    if (user?.role === "GERANT" && !GERANT_ALLOWED.has(location.pathname)) {
      navigate({ to: "/admin/nouvelle-vente" });
    }
  }, [user, location.pathname, navigate]);

  if (!user) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-background">
        <div className="h-8 w-8 animate-spin rounded-full border-2 border-primary border-t-transparent" />
      </div>
    );
  }

  return (
    <div className="flex min-h-screen bg-background">
      <div className="hidden lg:block sticky top-0 h-screen">
        <Sidebar />
      </div>
      <div className="flex min-w-0 flex-1 flex-col">
        <Topbar />
        <main className="flex-1 overflow-x-hidden p-4 md:p-6 lg:p-8">{children}</main>
      </div>
    </div>
  );
}

export function PageHeader({ title, description, actions }: { title: string; description?: string; actions?: ReactNode }) {
  return (
    <div className="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
      <div>
        <h1 className="text-2xl font-bold tracking-tight md:text-[28px]">{title}</h1>
        {description && <p className="mt-1 text-sm text-muted-foreground">{description}</p>}
      </div>
      {actions && <div className="flex items-center gap-2">{actions}</div>}
    </div>
  );
}
