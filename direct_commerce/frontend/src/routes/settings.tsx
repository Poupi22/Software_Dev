import { createFileRoute } from "@tanstack/react-router";
import { requireAuth } from "@/lib/auth-guard";
import { useTranslation } from "react-i18next";
import { AppSidebar } from "@/components/app-sidebar";
import { BottomBar } from "@/components/bottom-bar";
import { TopBar } from "@/components/top-bar";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { useTheme } from "@/hooks/use-theme";
import { Sun, Moon, Monitor, Globe } from "lucide-react";
import { cn } from "@/lib/utils";

export const Route = createFileRoute("/settings")({
  beforeLoad: ({ location }) => requireAuth(location),
  component: SettingsPage,
});

function SettingsPage() {
  const { t, i18n } = useTranslation();
  const { theme, setTheme } = useTheme();

  const themes = [
    { id: "light", label: t("settings.light"), icon: Sun },
    { id: "dark", label: t("settings.dark"), icon: Moon },
    { id: "system", label: t("settings.system"), icon: Monitor },
  ] as const;

  const langs = [
    { id: "fr", label: "Français", flag: "🇫🇷" },
    { id: "en", label: "English", flag: "🇬🇧" },
  ];

  return (
    <div className="min-h-screen bg-background">
      <AppSidebar />
      <div className="lg:pl-64">
        <TopBar />
        <main className="px-4 lg:px-8 py-6 pb-24 lg:pb-10 max-w-3xl space-y-6">
          <h1 className="text-2xl font-bold">{t("nav.settings")}</h1>

          <Card className="p-6 gradient-card">
            <h2 className="text-sm font-semibold mb-4 flex items-center gap-2">
              <Sun className="h-4 w-4" /> {t("settings.theme")}
            </h2>
            <div className="grid grid-cols-3 gap-3">
              {themes.map(({ id, label, icon: Icon }) => (
                <button
                  key={id}
                  onClick={() => setTheme(id)}
                  className={cn(
                    "flex flex-col items-center gap-2 p-4 rounded-xl border-2 transition-all",
                    theme === id ? "border-primary bg-primary/5 shadow-elegant" : "border-border hover:border-primary/40"
                  )}
                >
                  <Icon className="h-5 w-5" />
                  <span className="text-sm font-medium">{label}</span>
                </button>
              ))}
            </div>
          </Card>

          <Card className="p-6 gradient-card">
            <h2 className="text-sm font-semibold mb-4 flex items-center gap-2">
              <Globe className="h-4 w-4" /> {t("settings.language")}
            </h2>
            <div className="grid grid-cols-2 gap-3">
              {langs.map((l) => (
                <button
                  key={l.id}
                  onClick={() => i18n.changeLanguage(l.id)}
                  className={cn(
                    "flex items-center gap-3 p-4 rounded-xl border-2 transition-all",
                    i18n.language === l.id ? "border-primary bg-primary/5 shadow-elegant" : "border-border hover:border-primary/40"
                  )}
                >
                  <span className="text-2xl">{l.flag}</span>
                  <span className="text-sm font-medium">{l.label}</span>
                </button>
              ))}
            </div>
          </Card>
        </main>
      </div>
      <BottomBar />
    </div>
  );
}
