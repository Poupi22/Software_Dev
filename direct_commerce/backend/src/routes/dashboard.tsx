import { createFileRoute, useNavigate } from "@tanstack/react-router";
import { User, MessageCircle, Settings, LogOut } from "lucide-react";

export const Route = createFileRoute("/dashboard")({
  head: () => ({
    meta: [
      { title: "Mon Compte — DreamRest" },
      { name: "description", content: "Votre espace client DreamRest." },
    ],
  }),
  component: DashboardPage,
});

const inquiries = [
  { product: "CloudRest Mémoire 24cm", date: "15/04/2026", status: "Répondu", via: "WhatsApp" },
  { product: "OrthoSpring Premium", date: "12/04/2026", status: "En attente", via: "Email" },
  { product: "BioLatex Nature", date: "08/04/2026", status: "Répondu", via: "WhatsApp" },
];

function DashboardPage() {
  const navigate = useNavigate();
  const logout = () => {
    if (typeof window !== "undefined") localStorage.removeItem("dr_role");
    navigate({ to: "/" });
  };

  return (
    <div className="mx-auto max-w-7xl px-4 pt-24 pb-24 sm:px-6 lg:px-8">
      <div className="grid gap-8 lg:grid-cols-[240px_1fr]">
        <nav className="hidden space-y-1 lg:block">
          {[
            { icon: User, label: "Profil", active: true, action: () => {} },
            { icon: MessageCircle, label: "Demandes", active: false, action: () => {} },
            { icon: Settings, label: "Paramètres", active: false, action: () => {} },
            { icon: LogOut, label: "Déconnexion", active: false, action: logout },
          ].map((item) => (
            <button
              key={item.label}
              onClick={item.action}
              className={`flex w-full items-center gap-3 rounded-lg px-4 py-2.5 text-sm font-medium transition-colors ${item.active ? "bg-gradient-blue text-white" : "text-muted-foreground hover:bg-accent"}`}
            >
              <item.icon className="h-4 w-4" /> {item.label}
            </button>
          ))}
        </nav>

        <div className="space-y-8">
          <div>
            <h1 className="text-2xl font-bold text-foreground">ace Client</h1>
            <p className="text-muted-foreground">Gérez votre profil et vos demandes</p>
          </div>

          <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
            <h2 className="mb-4 text-lg font-semibold text-foreground">Informations personnelles</h2>
            <div className="grid gap-4 sm:grid-cols-2">
              <div>
                <label className="mb-1 block text-xs text-muted-foreground">Nom</label>
                <input defaultValue="Client" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring" />
              </div>
              <div>
                <label className="mb-1 block text-xs text-muted-foreground">Email</label>
                <input defaultValue="client@email.fr" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring" />
              </div>
              <div>
                <label className="mb-1 block text-xs text-muted-foreground">Téléphone</label>
                <input defaultValue="+33 6 00 00 00 00" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring" />
              </div>
            </div>
            <button className="mt-4 rounded-lg bg-gradient-blue px-6 py-2 text-sm font-medium text-white">Enregistrer</button>
          </div>

          <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
            <h2 className="mb-4 text-lg font-semibold text-foreground">Historique des demandes</h2>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="border-b border-border text-left">
                    <th className="pb-3 font-medium text-muted-foreground">Matelas</th>
                    <th className="pb-3 font-medium text-muted-foreground">Date</th>
                    <th className="pb-3 font-medium text-muted-foreground">Canal</th>
                    <th className="pb-3 font-medium text-muted-foreground">Statut</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-border">
                  {inquiries.map((inq, i) => (
                    <tr key={i}>
                      <td className="py-3 font-medium text-foreground">{inq.product}</td>
                      <td className="py-3 text-muted-foreground">{inq.date}</td>
                      <td className="py-3 text-muted-foreground">{inq.via}</td>
                      <td className="py-3">
                        <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium ${inq.status === "Répondu" ? "bg-success/10 text-success" : "bg-warning/10 text-warning"}`}>
                          {inq.status}
                        </span>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
