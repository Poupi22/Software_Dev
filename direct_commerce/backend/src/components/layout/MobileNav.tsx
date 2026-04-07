import { Link } from "@tanstack/react-router";
import { Home, ShoppingBag, Grid3X3, MessageCircle } from "lucide-react";
import { useLang } from "@/lib/i18n";

export default function MobileNav() {
  const { t } = useLang();
  const tabs = [
    { to: "/" as const, icon: Home, label: t("nav.home") },
    { to: "/shop" as const, icon: ShoppingBag, label: t("nav.shop") },
    { to: "/categories" as const, icon: Grid3X3, label: t("nav.categories") },
    { to: "/contact" as const, icon: MessageCircle, label: t("nav.contact") },
  ];
  return (
    <nav className="fixed bottom-0 left-0 right-0 z-50 border-t border-border bg-background/95 backdrop-blur pb-[env(safe-area-inset-bottom)] md:hidden">
      <div className="flex items-center justify-around py-2">
        {tabs.map((tab) => (
          <Link
            key={tab.to}
            to={tab.to}
            activeProps={{ className: "text-primary" }}
            inactiveProps={{ className: "text-muted-foreground" }}
            className="flex flex-col items-center gap-0.5 px-3 py-1 text-xs transition-all hover:scale-110"
          >
            <tab.icon className="h-5 w-5" />
            <span className="font-medium">{tab.label}</span>
          </Link>
        ))}
      </div>
    </nav>
  );
}