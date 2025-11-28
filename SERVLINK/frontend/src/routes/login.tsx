import { createFileRoute, Link, useNavigate } from "@tanstack/react-router";
import { Mail, Lock, Eye, ShieldCheck, User, Briefcase } from "lucide-react";
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Logo } from "@/components/Logo";
import { setSession, type Role } from "@/lib/auth";

export const Route = createFileRoute("/login")({
  head: () => ({ meta: [{ title: "Connexion — SERVLINK" }] }),
  component: LoginPage,
});

const DEMO: Record<"admin" | "client" | "provider", { email: string; password: string; redirect: string; role: Role; name: string }> = {
  admin:    { email: "admin@servlink.cm",    password: "admin123",    redirect: "/admin",     role: "admin",    name: "Admin SERVLINK" },
  client:   { email: "client@servlink.cm",   password: "client123",   redirect: "/dashboard", role: "client",   name: "Adèle Kouam" },
  provider: { email: "provider@servlink.cm", password: "provider123", redirect: "/provider",  role: "provider", name: "Awa Nkomo" },
};

function LoginPage() {
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);

  const fill = (k: keyof typeof DEMO) => {
    setEmail(DEMO[k].email);
    setPassword(DEMO[k].password);
    setError(null);
  };

  const submit = (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    for (const k of Object.keys(DEMO) as (keyof typeof DEMO)[]) {
      const d = DEMO[k];
      if (email === d.email && password === d.password) {
        setSession({ role: d.role, email: d.email, name: d.name });
        navigate({ to: d.redirect });
        return;
      }
    }
    setError("Identifiants incorrects. Utilisez un compte de démonstration ci-dessous.");
  };

  return (
    <div className="min-h-screen grid lg:grid-cols-2">
      <div className="hidden lg:flex relative gradient-hero text-white p-12 flex-col justify-between">
        <Link to="/"><Logo variant="light" /></Link>
        <div>
          <h1 className="font-display text-4xl font-bold leading-tight">Bienvenue<br />sur SERVLINK.</h1>
          <p className="mt-3 text-white/80 max-w-md">Connectez-vous pour réserver, échanger et gérer vos prestations en toute simplicité.</p>
        </div>
        <div className="text-xs text-white/60">© 2026 SERVLINK</div>
      </div>

      <div className="flex items-center justify-center p-8">
        <div className="w-full max-w-md">
          <div className="lg:hidden mb-8"><Link to="/"><Logo /></Link></div>
          <h2 className="font-display text-2xl font-bold">Connexion</h2>
          <p className="text-sm text-muted-foreground mt-1">Heureux de vous revoir.</p>

          {/* Demo accounts */}
          <div className="mt-6 rounded-xl border border-primary/30 bg-primary/5 p-4">
            <div className="text-xs font-semibold text-primary uppercase tracking-wider mb-2">Comptes de démonstration</div>
            <div className="grid grid-cols-3 gap-2">
              {([
                { k: "admin",    icon: ShieldCheck, label: "Admin" },
                { k: "client",   icon: User,        label: "Client" },
                { k: "provider", icon: Briefcase,   label: "Prestataire" },
              ] as const).map(({ k, icon: Icon, label }) => (
                <button
                  key={k}
                  type="button"
                  onClick={() => fill(k)}
                  className="text-left rounded-lg bg-card border border-border hover:border-primary p-3 transition"
                >
                  <div className="flex items-center gap-1.5 text-sm font-semibold"><Icon className="h-4 w-4 text-primary" /> {label}</div>
                  <div className="text-[10px] text-muted-foreground mt-1 font-mono leading-tight break-all">
                    {DEMO[k].email}<br />{DEMO[k].password}
                  </div>
                </button>
              ))}
            </div>
            <p className="text-[11px] text-muted-foreground mt-2">Cliquez sur une carte pour pré-remplir, puis « Se connecter ».</p>
          </div>

          <form className="space-y-4 mt-6" onSubmit={submit}>
            <div>
              <label className="text-sm font-medium">Email</label>
              <div className="relative mt-1">
                <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input type="email" value={email} onChange={(e) => setEmail(e.target.value)} placeholder="vous@email.com" className="pl-9 h-11" />
              </div>
            </div>
            <div>
              <div className="flex justify-between items-center">
                <label className="text-sm font-medium">Mot de passe</label>
                <a href="#" className="text-xs text-primary hover:underline">Oublié ?</a>
              </div>
              <div className="relative mt-1">
                <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                <Input type="password" value={password} onChange={(e) => setPassword(e.target.value)} placeholder="••••••••" className="pl-9 h-11 pr-9" />
                <Eye className="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground cursor-pointer" />
              </div>
            </div>
            {error && <div className="text-xs text-destructive bg-destructive/10 border border-destructive/30 rounded-md p-2">{error}</div>}
            <label className="flex items-center gap-2 text-sm text-muted-foreground">
              <input type="checkbox" className="accent-primary" /> Se souvenir de moi
            </label>
            <Button type="submit" size="lg" className="w-full">Se connecter</Button>
          </form>

          <p className="text-center text-sm text-muted-foreground mt-6">
            Pas encore de compte ? <Link to="/register" className="text-primary font-semibold hover:underline">Créer un compte</Link>
          </p>
        </div>
      </div>
    </div>
  );
}
