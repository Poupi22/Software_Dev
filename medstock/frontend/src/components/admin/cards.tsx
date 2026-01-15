import type { ReactNode } from "react";
import { cn } from "@/lib/utils";
import { TrendingUp, TrendingDown } from "lucide-react";

export function StatCard({
  title, value, icon: Icon, trend, trendValue, accent = "primary", description,
}: {
  title: string; value: string | number; icon: React.ElementType;
  trend?: "up" | "down"; trendValue?: string; description?: string;
  accent?: "primary" | "success" | "warning" | "destructive" | "info";
}) {
  const accentMap = {
    primary: "bg-primary/10 text-primary",
    success: "bg-success/10 text-success",
    warning: "bg-warning/15 text-warning",
    destructive: "bg-destructive/10 text-destructive",
    info: "bg-info/10 text-info",
  };
  return (
    <div className="group relative overflow-hidden rounded-2xl border border-border bg-card p-5 shadow-sm transition hover:shadow-md">
      <div className="flex items-start justify-between">
        <div>
          <div className="text-xs font-medium uppercase tracking-wider text-muted-foreground">{title}</div>
          <div className="mt-2 text-3xl font-bold tracking-tight">{value}</div>
          {description && <div className="mt-1 text-xs text-muted-foreground">{description}</div>}
        </div>
        <div className={cn("flex h-11 w-11 items-center justify-center rounded-xl", accentMap[accent])}>
          <Icon className="h-5 w-5" />
        </div>
      </div>
      {trend && trendValue && (
        <div className="mt-3 flex items-center gap-1.5 text-xs">
          {trend === "up" ? (
            <span className="flex items-center gap-1 rounded-full bg-success/10 px-2 py-0.5 font-medium text-success">
              <TrendingUp className="h-3 w-3" />{trendValue}
            </span>
          ) : (
            <span className="flex items-center gap-1 rounded-full bg-destructive/10 px-2 py-0.5 font-medium text-destructive">
              <TrendingDown className="h-3 w-3" />{trendValue}
            </span>
          )}
          <span className="text-muted-foreground">vs mois dernier</span>
        </div>
      )}
    </div>
  );
}

export function PanelCard({ title, action, children, className }: { title: string; action?: ReactNode; children: ReactNode; className?: string }) {
  return (
    <div className={cn("rounded-2xl border border-border bg-card shadow-sm", className)}>
      <div className="flex items-center justify-between border-b border-border px-5 py-4">
        <h3 className="font-semibold tracking-tight">{title}</h3>
        {action}
      </div>
      <div className="p-5">{children}</div>
    </div>
  );
}

export function StatusBadge({ status }: { status: "ok" | "faible" | "critique" | "expire" | "proche" | "actif" | "inactif" }) {
  const map: Record<string, { label: string; cls: string }> = {
    ok: { label: "En stock", cls: "bg-success/10 text-success border-success/20" },
    faible: { label: "Stock faible", cls: "bg-warning/15 text-warning border-warning/30" },
    critique: { label: "Critique", cls: "bg-destructive/10 text-destructive border-destructive/20" },
    expire: { label: "Expiré", cls: "bg-destructive/10 text-destructive border-destructive/20" },
    proche: { label: "Expire bientôt", cls: "bg-warning/15 text-warning border-warning/30" },
    actif: { label: "Actif", cls: "bg-success/10 text-success border-success/20" },
    inactif: { label: "Inactif", cls: "bg-muted text-muted-foreground border-border" },
  };
  const { label, cls } = map[status];
  return (
    <span className={cn("inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[11px] font-semibold", cls)}>
      <span className="h-1.5 w-1.5 rounded-full bg-current" />
      {label}
    </span>
  );
}
