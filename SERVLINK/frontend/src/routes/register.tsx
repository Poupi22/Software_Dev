import { createFileRoute, Link } from "@tanstack/react-router";
import { useState } from "react";
import { Mail, Lock, User, Phone, Briefcase, ShoppingBag } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Logo } from "@/components/Logo";

export const Route = createFileRoute("/register")({
  head: () => ({ meta: [{ title: "Créer un compte — SERVLINK" }] }),
  component: RegisterPage,
});

function RegisterPage() {
  const [role, setRole] = useState<"client" | "provider">("client");
  return (
    <div className="min-h-screen flex items-center justify-center p-6 bg-muted/40">
      <div className="w-full max-w-xl bg-card border border-border rounded-2xl p-8 shadow-sm">
        <Link to="/"><Logo /></Link>
        <h1 className="font-display text-2xl font-bold mt-6">Créer votre compte</h1>
        <p className="text-sm text-muted-foreground">Rejoignez la communauté SERVLINK en quelques secondes.</p>

        <div className="grid grid-cols-2 gap-3 mt-6">
          {[
            { v: "client", icon: ShoppingBag, t: "Je suis client", s: "Trouver et réserver des prestataires" },
            { v: "provider", icon: Briefcase, t: "Je suis prestataire", s: "Proposer et vendre mes services" },
          ].map((r) => (
            <button key={r.v} onClick={() => setRole(r.v as typeof role)} className={`p-4 rounded-xl border-2 text-left transition-all ${role === r.v ? "border-primary bg-accent" : "border-border hover:border-primary/40"}`}>
              <r.icon className={`h-5 w-5 mb-2 ${role === r.v ? "text-primary" : "text-muted-foreground"}`} />
              <div className="font-semibold text-sm">{r.t}</div>
              <div className="text-xs text-muted-foreground mt-0.5">{r.s}</div>
            </button>
          ))}
        </div>

        <form className="mt-6 space-y-4">
          <div className="grid sm:grid-cols-2 gap-3">
            <div>
              <label className="text-sm font-medium">Nom complet</label>
              <div className="relative mt-1"><User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" /><Input placeholder="Awa Nkomo" className="pl-9 h-11" /></div>
            </div>
            <div>
              <label className="text-sm font-medium">Téléphone</label>
              <div className="relative mt-1"><Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" /><Input placeholder="+237 6 …" className="pl-9 h-11" /></div>
            </div>
          </div>
          <div>
            <label className="text-sm font-medium">Email</label>
            <div className="relative mt-1"><Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" /><Input type="email" placeholder="vous@email.com" className="pl-9 h-11" /></div>
          </div>
          <div>
            <label className="text-sm font-medium">Mot de passe</label>
            <div className="relative mt-1"><Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" /><Input type="password" placeholder="Min. 8 caractères" className="pl-9 h-11" /></div>
          </div>

          {role === "provider" && (
            <div className="p-4 rounded-xl bg-accent/60 border border-primary/20">
              <h3 className="font-semibold text-sm mb-2">Informations professionnelles</h3>
              <div className="grid sm:grid-cols-2 gap-3">
                <Input placeholder="Métier (ex. Plombier)" className="h-10" />
                <Input placeholder="Ville d'intervention" className="h-10" />
              </div>
              <p className="text-xs text-muted-foreground mt-2">Une vérification d'identité sera demandée après inscription.</p>
            </div>
          )}

          <label className="flex items-start gap-2 text-xs text-muted-foreground">
            <input type="checkbox" className="accent-primary mt-0.5" /> J'accepte les <a className="text-primary hover:underline" href="#">conditions générales</a> et la <a className="text-primary hover:underline" href="#">politique de confidentialité</a>.
          </label>

          <Link to="/"><Button type="button" size="lg" className="w-full">Créer mon compte</Button></Link>
        </form>

        <p className="text-center text-sm text-muted-foreground mt-6">
          Déjà inscrit ? <Link to="/login" className="text-primary font-semibold hover:underline">Se connecter</Link>
        </p>
      </div>
    </div>
  );
}
