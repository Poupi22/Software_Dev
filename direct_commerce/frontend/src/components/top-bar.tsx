import { useTranslation } from "react-i18next";
import { Bell, Globe, Moon, Sun, Sparkles, LogOut, User as UserIcon, Building2 } from "lucide-react";
import { useNavigate } from "@tanstack/react-router";
import { useTheme } from "@/hooks/use-theme";
import { useAuth } from "@/hooks/use-auth";
import { Button } from "@/components/ui/button";
import {
  DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger,
  DropdownMenuLabel, DropdownMenuSeparator,
} from "@/components/ui/dropdown-menu";

export function TopBar() {
  const { t, i18n } = useTranslation();
  const { resolved, setTheme } = useTheme();
  const { user, logout } = useAuth();
  const navigate = useNavigate();

  const initials = (user?.name ?? "EC").split(" ").map((p) => p[0]).slice(0, 2).join("").toUpperCase();

  return (
    <header className="sticky top-0 z-20 border-b border-border bg-background/80 backdrop-blur-md">
      <div className="flex items-center justify-between px-4 lg:px-8 h-16">
        <div className="flex items-center gap-3 lg:hidden">
          <div className="h-9 w-9 rounded-xl gradient-primary flex items-center justify-center">
            <Sparkles className="h-4 w-4 text-primary-foreground" />
          </div>
          <div>
            <p className="text-sm font-bold leading-tight">ECOTEC</p>
            <p className="text-[10px] text-muted-foreground">Smart Poultry</p>
          </div>
        </div>

        <div className="hidden lg:block">
          <p className="text-xs text-muted-foreground uppercase tracking-wider">{t("common.overview")}</p>
          <h1 className="text-lg font-semibold">{t("nav.dashboard")}</h1>
        </div>

        <div className="flex items-center gap-1.5">
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" size="icon" className="rounded-full">
                <Globe className="h-4 w-4" />
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
              <DropdownMenuItem onClick={() => i18n.changeLanguage("fr")}>
                🇫🇷 Français {i18n.language === "fr" && "✓"}
              </DropdownMenuItem>
              <DropdownMenuItem onClick={() => i18n.changeLanguage("en")}>
                🇬🇧 English {i18n.language === "en" && "✓"}
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>

          <Button
            variant="ghost"
            size="icon"
            className="rounded-full"
            onClick={() => setTheme(resolved === "dark" ? "light" : "dark")}
            aria-label="Toggle theme"
          >
            {resolved === "dark" ? <Sun className="h-4 w-4" /> : <Moon className="h-4 w-4" />}
          </Button>

          <Button variant="ghost" size="icon" className="rounded-full relative">
            <Bell className="h-4 w-4" />
            <span className="absolute top-2 right-2 h-2 w-2 rounded-full bg-destructive animate-pulse" />
          </Button>

          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <button className="ml-2 h-9 w-9 rounded-full gradient-primary flex items-center justify-center text-primary-foreground text-sm font-semibold hover:opacity-90 transition">
                {initials}
              </button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end" className="w-56">
              <DropdownMenuLabel>
                <div className="flex flex-col">
                  <span className="font-semibold truncate">{user?.name ?? "Invité"}</span>
                  <span className="text-xs text-muted-foreground truncate font-normal">{user?.email}</span>
                  <span className="text-[10px] mt-1 inline-flex items-center gap-1 text-primary font-medium">
                    {user?.role === "admin" ? "🛡 Administrateur" : "👤 Gérant"}
                  </span>
                </div>
              </DropdownMenuLabel>
              <DropdownMenuSeparator />
              <DropdownMenuItem onClick={() => navigate({ to: "/farms" })}>
                <Building2 className="h-4 w-4" /> Mes fermes
              </DropdownMenuItem>
              <DropdownMenuItem onClick={() => navigate({ to: "/settings" })}>
                <UserIcon className="h-4 w-4" /> Paramètres
              </DropdownMenuItem>
              <DropdownMenuSeparator />
              <DropdownMenuItem
                className="text-destructive focus:text-destructive"
                onClick={() => { logout(); navigate({ to: "/" }); }}
              >
                <LogOut className="h-4 w-4" /> Se déconnecter
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
    </header>
  );
}
