import { AppSidebar } from "@/components/app-sidebar";
import { BottomBar } from "@/components/bottom-bar";
import { TopBar } from "@/components/top-bar";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Button } from "@/components/ui/button";
import type { LucideIcon } from "lucide-react";

interface PageShellProps {
  title: string;
  subtitle?: string;
  icon?: LucideIcon;
  actions?: React.ReactNode;
  children: React.ReactNode;
}

export function PageShell({ title, subtitle, icon: Icon, actions, children }: PageShellProps) {
  return (
    <div className="min-h-screen bg-background">
      <AppSidebar />
      <div className="lg:pl-64">
        <TopBar />
        <main className="px-4 lg:px-8 py-6 pb-24 lg:pb-10 space-y-6">
          <Card className="relative overflow-hidden border-0 gradient-hero text-primary-foreground p-6 lg:p-7">
            <div className="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
              <div className="flex items-center gap-4">
                {Icon && (
                  <div className="h-12 w-12 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <Icon className="h-6 w-6" />
                  </div>
                )}
                <div>
                  <h1 className="text-2xl lg:text-3xl font-bold">{title}</h1>
                  {subtitle && <p className="text-sm opacity-90 mt-1 max-w-2xl">{subtitle}</p>}
                </div>
              </div>
              <div className="flex items-center gap-3">
                <Badge className="bg-white/20 hover:bg-white/25 text-white border-0 backdrop-blur">
                  <span className="h-2 w-2 rounded-full bg-success mr-2 animate-pulse" />
                  En direct
                </Badge>
                {actions ?? <Button variant="secondary" size="sm">Exporter</Button>}
              </div>
            </div>
            <div className="absolute -right-20 -bottom-20 h-72 w-72 rounded-full bg-primary-glow/30 blur-3xl" />
          </Card>
          {children}
        </main>
      </div>
      <BottomBar />
    </div>
  );
}

export const tooltipStyle = {
  background: "var(--color-popover)",
  border: "1px solid var(--color-border)",
  borderRadius: "0.75rem",
  fontSize: "12px",
  color: "var(--color-popover-foreground)",
  boxShadow: "var(--shadow-elegant)",
};

export const CHART_COLORS = [
  "var(--color-chart-1)",
  "var(--color-chart-2)",
  "var(--color-chart-3)",
  "var(--color-chart-4)",
  "var(--color-chart-5)",
  "var(--color-chart-6)",
];
