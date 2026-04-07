import { createFileRoute, Outlet, Link, useLocation, useNavigate, redirect } from "@tanstack/react-router";
import { LayoutDashboard, Package, Grid3X3, ShoppingBag, MessageCircle, BarChart3, Mail, Settings, ChevronLeft, Menu, LogOut, Home } from "lucide-react";
import { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { api } from "@/lib/api";

export const Route = createFileRoute("/admin")({
  head: () => ({
    meta: [
      { title: "Administration — DreamRest" },
      { name: "description", content: "Tableau de bord administrateur DreamRest." },
    ],
  }),
  beforeLoad: async () => {
    if (typeof window === "undefined") return;
    
    if (!api.isAuthenticated()) {
      throw redirect({ to: "/login" });
    }
  },
  component: AdminLayout,
});

const navItems = [
  { to: "/admin" as const, icon: LayoutDashboard, label: "Vue d'ensemble", exact: true },
  { to: "/admin/products" as const, icon: Package, label: "Produits", exact: false },
  { to: "/admin/categories" as const, icon: Grid3X3, label: "Catégories", exact: false },
  { to: "/admin/orders" as const, icon: ShoppingBag, label: "Commandes", exact: false },
  { to: "/admin/messages" as const, icon: MessageCircle, label: "Messages", exact: false },
  { to: "/admin/analytics" as const, icon: BarChart3, label: "Statistiques", exact: false },
  { to: "/admin/newsletter" as const, icon: Mail, label: "Newsletter", exact: false },
  { to: "/admin/settings" as const, icon: Settings, label: "Paramètres", exact: false },
];

function AdminLayout() {
  const [collapsed, setCollapsed] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [checking, setChecking] = useState(true);
  const location = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    setMobileOpen(false);
  }, [location.pathname]);

  useEffect(() => {
    const checkAuth = async () => {
      if (!api.isAuthenticated()) {
        navigate({ to: "/login" });
        return;
      }
      
      try {
        await api.getProfile();
        setChecking(false);
      } catch (err) {
        api.logout();
        navigate({ to: "/login" });
      }
    };
    
    checkAuth();
  }, [navigate]);

  const logout = async () => {
    await api.logout();
    navigate({ to: "/login" });
  };

  if (checking) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Vérification...</p>
        </div>
      </div>
    );
  }

  const Sidebar = ({ isMobile = false }: { isMobile?: boolean }) => (
    <div className="flex h-full flex-col bg-card">
      <div className="flex h-16 items-center justify-between border-b border-border px-4">
        {(!collapsed || isMobile) && (
          <Link to="/" className="flex items-center gap-2">
            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-blue">
              <span className="text-xs font-bold text-white">DR</span>
            </div>
            <span className="font-bold text-foreground">Admin</span>
          </Link>
        )}
        {!isMobile && (
          <button
            onClick={() => setCollapsed(!collapsed)}
            className="hidden rounded-lg p-1.5 text-muted-foreground transition-colors hover:bg-accent lg:block"
          >
            <ChevronLeft className={`h-4 w-4 transition-transform ${collapsed ? "rotate-180" : ""}`} />
          </button>
        )}
      </div>
      <nav className="flex-1 space-y-1 overflow-y-auto p-3">
        {navItems.map((item) => {
          const showActive = item.exact 
            ? location.pathname === item.to 
            : location.pathname.startsWith(item.to + "/") || location.pathname === item.to;
          return (
            <Link
              key={item.to}
              to={item.to}
              onClick={() => setMobileOpen(false)}
              className={`group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all ${showActive ? "bg-gradient-blue text-white shadow-md shadow-primary/20" : "text-muted-foreground hover:bg-accent hover:text-foreground"}`}
              title={collapsed && !isMobile ? item.label : undefined}
            >
              <item.icon className="h-4 w-4 shrink-0" />
              {(!collapsed || isMobile) && <span>{item.label}</span>}
            </Link>
          );
        })}
      </nav>
      <div className="border-t border-border p-3 space-y-1">
        <Link
          to="/"
          className="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-foreground"
        >
          <Home className="h-4 w-4 shrink-0" />
          {(!collapsed || isMobile) && <span>Voir le site</span>}
        </Link>
        <button
          onClick={logout}
          className="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-destructive transition-colors hover:bg-destructive/10"
        >
          <LogOut className="h-4 w-4 shrink-0" />
          {(!collapsed || isMobile) && <span>Déconnexion</span>}
        </button>
      </div>
    </div>
  );

  return (
    <div className="flex min-h-screen pt-16">
      <aside className={`hidden border-r border-border transition-all duration-300 lg:block ${collapsed ? "w-16" : "w-60"}`}>
        <div className="sticky top-16 h-[calc(100vh-4rem)]">
          <Sidebar />
        </div>
      </aside>

      <AnimatePresence>
        {mobileOpen && (
          <div className="fixed inset-0 z-50 lg:hidden">
            <motion.div
              initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
              onClick={() => setMobileOpen(false)}
              className="absolute inset-0 bg-foreground/40 backdrop-blur-sm"
            />
            <motion.aside
              initial={{ x: -300 }} animate={{ x: 0 }} exit={{ x: -300 }}
              transition={{ type: "spring", damping: 25, stiffness: 200 }}
              className="absolute top-0 left-0 h-full w-64 border-r border-border pt-16 shadow-2xl"
            >
              <Sidebar isMobile />
            </motion.aside>
          </div>
        )}
      </AnimatePresence>

      <div className="flex-1 overflow-x-hidden">
        <div className="flex h-14 items-center gap-3 border-b border-border bg-card px-4 lg:hidden">
          <button onClick={() => setMobileOpen(true)} className="rounded-lg p-1.5 text-muted-foreground hover:bg-accent">
            <Menu className="h-5 w-5" />
          </button>
          <span className="text-sm font-semibold text-foreground">Administration</span>
        </div>
        <div className="p-4 sm:p-6 lg:p-8">
          <Outlet />
        </div>
      </div>
    </div>
  );
}