import { useMemo, useRef, useState, useEffect } from "react";
import { Bell, Search, Menu, AlertTriangle, Package, ShoppingCart, UserPlus, Pill, Receipt, UsersIcon, X } from "lucide-react";
import { useAuth } from "@/lib/auth";
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet";
import { Sidebar } from "./Sidebar";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from "@/components/ui/dropdown-menu";
import { Link, useNavigate } from "@tanstack/react-router";
import { formatFCFA } from "@/lib/mock-data";
import api from "@/lib/api";

interface Notification {
  id: string;
  titre: string;
  message: string;
  lien: string | null;
  lue: boolean;
  created_at: string;
}

export function Topbar() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const [notifications, setNotifications] = useState<Notification[]>([]);
  const [loadingNotifs, setLoadingNotifs] = useState(false);
  const [query, setQuery] = useState("");
  const [open, setOpen] = useState(false);
  const [searchResults, setSearchResults] = useState<any[]>([]);
  const [searching, setSearching] = useState(false);
  const wrapRef = useRef<HTMLDivElement>(null);

  // Charger les notifications non lues
  useEffect(() => {
    fetchNotifications();
    const interval = setInterval(fetchNotifications, 30000); // Rafraîchir toutes les 30s
    return () => clearInterval(interval);
  }, []);

  const fetchNotifications = async () => {
    try {
      const res = await api.get('/notifications/non-lues');
      setNotifications(res.data.data);
    } catch (error) {
      console.error("Erreur chargement notifications:", error);
    }
  };

  const marquerCommeLue = async (id: string) => {
    try {
      await api.put(`/notifications/${id}/lue`);
      setNotifications(prev => prev.filter(n => n.id !== id));
    } catch (error) {
      console.error("Erreur marquage notification:", error);
    }
  };

  const getIconForNotification = (titre: string) => {
    if (titre.includes("Stock") || titre.includes("critique")) return AlertTriangle;
    if (titre.includes("expire")) return Package;
    if (titre.includes("vente")) return ShoppingCart;
    if (titre.includes("Utilisateur") || titre.includes("créé")) return UserPlus;
    return Bell;
  };

  const getColorForNotification = (titre: string) => {
    if (titre.includes("Stock") || titre.includes("critique")) return "text-destructive";
    if (titre.includes("expire")) return "text-warning";
    if (titre.includes("vente")) return "text-primary";
    if (titre.includes("Utilisateur") || titre.includes("créé")) return "text-info";
    return "text-muted-foreground";
  };

  const getRelativeTime = (date: string) => {
    const now = new Date();
    const notifDate = new Date(date);
    const diffMs = now.getTime() - notifDate.getTime();
    const diffMins = Math.floor(diffMs / (1000 * 60));
    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

    if (diffMins < 1) return "à l'instant";
    if (diffMins < 60) return `il y a ${diffMins} min`;
    if (diffHours < 24) return `il y a ${diffHours} h`;
    if (diffDays === 1) return "hier";
    return `il y a ${diffDays} jours`;
  };

  // Recherche
  useEffect(() => {
    const delayDebounce = setTimeout(() => {
      if (query.trim().length >= 2) {
        performSearch();
      } else {
        setSearchResults([]);
      }
    }, 300);
    return () => clearTimeout(delayDebounce);
  }, [query]);

  const performSearch = async () => {
    setSearching(true);
    try {
      const [medicamentsRes, ventesRes] = await Promise.all([
        api.get(`/medicaments/search?q=${query}`),
        api.get('/ventes')
      ]);
      
      const meds = medicamentsRes.data.data.slice(0, 5).map((m: any) => ({
        type: "med",
        id: m.id,
        title: m.nom,
        sub: `${m.categorie_nom || "N/A"} · ${formatFCFA(m.prix_vente)} · ${m.quantite} en stock`,
        to: `/admin/medicaments`
      }));
      
      const ventes = ventesRes.data.data
        .filter((v: any) => v.numero.toLowerCase().includes(query.toLowerCase()))
        .slice(0, 3)
        .map((v: any) => ({
          type: "vente",
          id: v.id,
          title: v.numero,
          sub: `${formatFCFA(v.total)}`,
          to: `/admin/ventes`
        }));
      
      setSearchResults([...meds, ...ventes]);
    } catch (error) {
      console.error("Erreur recherche:", error);
    } finally {
      setSearching(false);
    }
  };

  useEffect(() => {
    const handler = (e: MouseEvent) => {
      if (wrapRef.current && !wrapRef.current.contains(e.target as Node)) setOpen(false);
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  const iconFor = (t: string) => (t === "med" ? Pill : t === "vente" ? Receipt : UsersIcon);
  const nonLuesCount = notifications.length;

  return (
    <header className="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-border bg-background/80 px-4 backdrop-blur-md md:px-6">
      <Sheet>
        <SheetTrigger asChild>
          <button className="rounded-lg p-2 hover:bg-muted lg:hidden" aria-label="Menu">
            <Menu className="h-5 w-5" />
          </button>
        </SheetTrigger>
        <SheetContent side="left" className="w-64 p-0 border-r-0">
          <Sidebar />
        </SheetContent>
      </Sheet>

      {/* Barre de recherche */}
      <div ref={wrapRef} className="relative flex-1 max-w-md mr-auto">
        <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <input
          type="search"
          value={query}
          onChange={(e) => { setQuery(e.target.value); setOpen(true); }}
          onFocus={() => setOpen(true)}
          placeholder="Rechercher médicament, vente..."
          className="h-10 w-full rounded-lg border border-input bg-secondary/50 pl-9 pr-9 text-sm placeholder:text-muted-foreground focus:bg-background focus:outline-none focus:ring-2 focus:ring-ring/30"
        />
        {query && (
          <button
            onClick={() => { setQuery(""); setOpen(false); }}
            className="absolute right-2 top-1/2 -translate-y-1/2 rounded-md p-1 text-muted-foreground hover:bg-muted"
          >
            <X className="h-3.5 w-3.5" />
          </button>
        )}
        {open && query && (
          <div className="absolute left-0 right-0 top-full z-40 mt-2 overflow-hidden rounded-xl border border-border bg-popover shadow-lg">
            {searching ? (
              <div className="px-4 py-6 text-center text-sm text-muted-foreground">Recherche...</div>
            ) : searchResults.length === 0 ? (
              <div className="px-4 py-6 text-center text-sm text-muted-foreground">Aucun résultat pour "{query}"</div>
            ) : (
              <ul className="max-h-96 overflow-y-auto scrollbar-thin py-1">
                {searchResults.map((r) => {
                  const Ic = iconFor(r.type);
                  return (
                    <li key={`${r.type}-${r.id}`}>
                      <button
                        onClick={() => { navigate({ to: r.to }); setOpen(false); setQuery(""); }}
                        className="flex w-full items-center gap-3 px-3 py-2.5 text-left hover:bg-muted/60"
                      >
                        <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-primary/10 text-primary">
                          <Ic className="h-4 w-4" />
                        </div>
                        <div className="min-w-0 flex-1">
                          <div className="truncate text-sm font-medium">{r.title}</div>
                          <div className="truncate text-[11px] text-muted-foreground">{r.sub}</div>
                        </div>
                      </button>
                    </li>
                  );
                })}
              </ul>
            )}
          </div>
        )}
      </div>

      {/* Bouton notifications avec API réelle */}
      <Popover>
        <PopoverTrigger asChild>
          <button className="relative rounded-lg p-2.5 hover:bg-muted transition" aria-label="Notifications">
            <Bell className="h-5 w-5" />
            {nonLuesCount > 0 && (
              <span className="absolute right-1.5 top-1.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-destructive px-1 text-[10px] font-semibold text-destructive-foreground animate-pulse-ring">
                {nonLuesCount > 99 ? "99+" : nonLuesCount}
              </span>
            )}
          </button>
        </PopoverTrigger>
        <PopoverContent align="end" className="w-96 p-0">
          <div className="border-b border-border px-4 py-3">
            <div className="flex items-center justify-between">
              <h3 className="font-semibold">Notifications</h3>
              <span className="text-xs text-muted-foreground">{nonLuesCount} non lue{nonLuesCount > 1 ? "s" : ""}</span>
            </div>
          </div>
          <div className="max-h-96 overflow-y-auto scrollbar-thin">
            {notifications.length === 0 ? (
              <div className="px-4 py-8 text-center text-sm text-muted-foreground">
                Aucune notification non lue
              </div>
            ) : (
              notifications.map((notif) => {
                const Icon = getIconForNotification(notif.titre);
                const color = getColorForNotification(notif.titre);
                return (
                  <div
                    key={notif.id}
                    className="flex gap-3 border-b border-border px-4 py-3 hover:bg-muted/50 transition cursor-pointer last:border-0"
                    onClick={() => {
                      marquerCommeLue(notif.id);
                      if (notif.lien) navigate({ to: notif.lien as any });
                    }}
                  >
                    <div className={`flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-muted ${color}`}>
                      <Icon className="h-4 w-4" />
                    </div>
                    <div className="min-w-0 flex-1">
                      <div className="text-sm font-medium">{notif.titre}</div>
                      <div className="text-xs text-muted-foreground line-clamp-2">{notif.message}</div>
                      <div className="mt-1 text-[11px] text-muted-foreground">{getRelativeTime(notif.created_at)}</div>
                    </div>
                  </div>
                );
              })
            )}
          </div>
          <Link to="/admin/notifications" className="block border-t border-border px-4 py-2.5 text-center text-sm font-medium text-primary hover:bg-muted/50">
            Voir toutes les notifications
          </Link>
        </PopoverContent>
      </Popover>

      <div className="h-8 w-px bg-border" />

      {/* Avatar utilisateur */}
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <button className="ml-auto flex items-center gap-2.5 rounded-full p-1 pl-1 pr-3 hover:bg-muted transition ring-1 ring-transparent hover:ring-border">
            <div className="relative">
              <div className="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-xs font-semibold text-primary-foreground">
                {user?.nom.split(" ").map((n) => n[0]).slice(0, 2).join("")}
              </div>
              <span className="absolute bottom-0 right-0 h-2.5 w-2.5 rounded-full bg-green-500 ring-2 ring-background" />
            </div>
            <div className="hidden text-left md:block">
              <div className="text-sm font-semibold leading-tight">{user?.nom.split(" ").slice(0, 2).join(" ")}</div>
              <div className="text-[11px] font-medium text-primary">{user?.role}</div>
            </div>
          </button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" className="w-64">
          <div className="flex items-center gap-3 px-2 py-2.5">
            <div className="flex h-12 w-12 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">
              {user?.nom.split(" ").map((n) => n[0]).slice(0, 2).join("")}
            </div>
            <div className="min-w-0">
              <div className="truncate text-sm font-semibold">{user?.nom}</div>
              <div className="truncate text-xs text-muted-foreground">{user?.email}</div>
              <div className="mt-0.5 inline-block rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold text-primary">{user?.role}</div>
            </div>
          </div>
          <DropdownMenuSeparator />
          <DropdownMenuLabel>Mon compte</DropdownMenuLabel>
          <DropdownMenuItem onClick={() => navigate({ to: "/admin/parametres" })}>Profil</DropdownMenuItem>
          <DropdownMenuItem onClick={() => navigate({ to: "/admin/parametres" })}>Paramètres</DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem onClick={() => { logout(); navigate({ to: "/login" }); }} className="text-destructive">
            Déconnexion
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </header>
  );
}