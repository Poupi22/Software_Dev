import { createFileRoute } from "@tanstack/react-router";

export const Route = createFileRoute("/provider/messages")({
  component: () => (
    <div className="space-y-4">
      <h1 className="font-display text-2xl font-bold">Messagerie</h1>
      <p className="text-muted-foreground text-sm">Échangez directement avec vos clients depuis cet espace.</p>
      <div className="bg-card border border-border rounded-2xl p-12 text-center text-muted-foreground">
        Votre boîte de réception apparaîtra ici.
      </div>
    </div>
  ),
});
