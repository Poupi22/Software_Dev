import { Outlet, createRootRoute, HeadContent, Scripts } from "@tanstack/react-router";
import { Suspense } from "react";
import appCss from "../styles.css?url";
import { ThemeProvider } from "@/hooks/use-theme";
import { AuthProvider } from "@/hooks/use-auth";
import { Toaster } from "@/components/ui/sonner";
import "@/lib/i18n";

export const Route = createRootRoute({
  head: () => ({
    meta: [
      { charSet: "utf-8" },
      { name: "viewport", content: "width=device-width, initial-scale=1, viewport-fit=cover" },
      { title: "ECOTEC Smart Poultry — Ferme Avicole Intelligente" },
      { name: "description", content: "ECOTEC Smart Poultry: ferme avicole intelligente, coopérative, IA & IoT. Tableau de bord temps réel, détection précoce des maladies, compostage optimisé." },
      { name: "theme-color", content: "#1f6b3f" },
      { property: "og:title", content: "ECOTEC Smart Poultry" },
      { property: "og:description", content: "Smart & sustainable poultry farm — AI, IoT, cooperative model." },
      { property: "og:type", content: "website" },
    ],
    links: [{ rel: "stylesheet", href: appCss }],
  }),
  shellComponent: RootShell,
  component: () => (
    <ThemeProvider>
      <AuthProvider>
        <Suspense fallback={null}>
          <Outlet />
        </Suspense>
        <Toaster />
      </AuthProvider>
    </ThemeProvider>
  ),
  notFoundComponent: () => (
    <div className="flex min-h-screen items-center justify-center">
      <p className="text-muted-foreground">404 — Page not found</p>
    </div>
  ),
});

function RootShell({ children }: { children: React.ReactNode }) {
  return (
    <html lang="fr" suppressHydrationWarning>
      <head>
        <HeadContent />
      </head>
      <body>
        {children}
        <Scripts />
      </body>
    </html>
  );
}
