import { Outlet, Link, createRootRoute } from "@tanstack/react-router";
import Navbar from "../components/layout/Navbar";
import MobileNav from "../components/layout/MobileNav";
import FloatingButtons from "../components/layout/FloatingButtons";
import { useTheme } from "../lib/theme";
import { LanguageProvider } from "../lib/i18n";

function NotFoundComponent() {
  return (
    <div className="flex min-h-screen items-center justify-center bg-background px-4">
      <div className="max-w-md text-center">
        <h1 className="text-7xl font-bold text-gradient">404</h1>
        <h2 className="mt-4 text-xl font-semibold text-foreground">Page introuvable</h2>
        <p className="mt-2 text-sm text-muted-foreground">
          Cette page n'existe pas ou a été déplacée.
        </p>
        <div className="mt-6">
          <Link
            to="/"
            className="inline-flex items-center justify-center rounded-md bg-gradient-blue px-4 py-2 text-sm font-medium text-white transition-transform hover:scale-105"
          >
            Retour à l'accueil
          </Link>
        </div>
      </div>
    </div>
  );
}

export const Route = createRootRoute({
  component: RootComponent,
  notFoundComponent: NotFoundComponent,
});

function RootComponent() {
  const { isDark, toggle } = useTheme();

  return (
    <LanguageProvider>
      <Navbar isDark={isDark} toggleTheme={toggle} />
      <main className="min-h-screen">
        <Outlet />
      </main>
      <MobileNav />
      <FloatingButtons />
    </LanguageProvider>
  );
}