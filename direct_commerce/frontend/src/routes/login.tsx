import { createFileRoute, Link, useNavigate } from "@tanstack/react-router";
import { useState, type FormEvent } from "react";
import { useAuth } from "@/hooks/use-auth";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card } from "@/components/ui/card";
import { Sparkles, Loader2, ArrowLeft, Mail, Lock, Eye, EyeOff, Copy, Check } from "lucide-react";
import { toast } from "sonner";

export const Route = createFileRoute("/login")({
  component: LoginPage,
  head: () => ({
    meta: [
      { title: "Connexion — ECOTEC Smart Poultry" },
      { name: "description", content: "Connectez-vous au tableau de bord ECOTEC Smart Poultry." },
    ],
  }),
});

function LoginPage() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPwd, setShowPwd] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [copied, setCopied] = useState<string | null>(null);

  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError(null);
    setLoading(true);
    const res = await login(email, password);
    setLoading(false);
    if (!res.ok) {
      setError(res.error ?? "Erreur de connexion");
      return;
    }
    toast.success("Connexion réussie");
    navigate({ to: "/dashboard" });
  };

  const fillDemo = (mail: string, pwd: string) => {
    setEmail(mail);
    setPassword(pwd);
  };

  const copy = async (val: string) => {
    await navigator.clipboard.writeText(val);
    setCopied(val);
    setTimeout(() => setCopied(null), 1500);
  };

  return (
    <div className="min-h-screen grid lg:grid-cols-2">
      {/* Left: visual */}
      <div className="relative hidden lg:flex gradient-hero text-primary-foreground p-12 flex-col justify-between overflow-hidden">
        <div className="absolute -top-32 -right-32 h-96 w-96 rounded-full bg-white/10 blur-3xl" />
        <div className="absolute -bottom-32 -left-32 h-96 w-96 rounded-full bg-accent/30 blur-3xl" />
        <Link to="/" className="relative z-10 inline-flex items-center gap-3 hover:opacity-90 transition">
          <div className="h-11 w-11 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center">
            <Sparkles className="h-5 w-5" />
          </div>
          <div>
            <p className="text-sm font-bold leading-tight">ECOTEC</p>
            <p className="text-xs opacity-80">Smart Poultry</p>
          </div>
        </Link>

        <div className="relative z-10 space-y-6 animate-[fade-in_0.6s_ease-out]">
          <h2 className="text-4xl font-bold leading-tight">
            Pilotez votre ferme <br /> en temps réel.
          </h2>
          <p className="text-lg opacity-90 max-w-md">
            IA, IoT, modèle coopératif. Connectez-vous pour accéder au tableau de bord intelligent.
          </p>
          <div className="grid grid-cols-3 gap-4 pt-4">
            {[
              { v: "42K+", l: "Volailles" },
              { v: "98%", l: "Précision IA" },
              { v: "52", l: "Coopérants" },
            ].map((s) => (
              <div key={s.l} className="bg-white/10 backdrop-blur rounded-xl p-4 border border-white/15">
                <p className="text-2xl font-bold">{s.v}</p>
                <p className="text-xs opacity-80">{s.l}</p>
              </div>
            ))}
          </div>
        </div>

        <p className="relative z-10 text-xs opacity-70">© 2026 ECOTEC · POESAM Orange</p>
      </div>

      {/* Right: form */}
      <div className="flex items-center justify-center p-6 lg:p-12 bg-background">
        <div className="w-full max-w-md space-y-6 animate-[fade-in_0.5s_ease-out]">
          <Link
            to="/"
            className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition"
          >
            <ArrowLeft className="h-4 w-4" /> Retour à l'accueil
          </Link>

          <div className="lg:hidden flex items-center gap-3 pb-2">
            <div className="h-10 w-10 rounded-xl gradient-primary flex items-center justify-center">
              <Sparkles className="h-5 w-5 text-primary-foreground" />
            </div>
            <div>
              <p className="text-sm font-bold">ECOTEC</p>
              <p className="text-xs text-muted-foreground">Smart Poultry</p>
            </div>
          </div>

          <div>
            <h1 className="text-3xl font-bold tracking-tight">Bon retour 👋</h1>
            <p className="text-muted-foreground mt-1">Connectez-vous pour accéder à votre dashboard.</p>
          </div>

          <Card className="p-6 shadow-elegant">
            <form onSubmit={onSubmit} className="space-y-4">
              <div className="space-y-1.5">
                <Label htmlFor="email">Email</Label>
                <div className="relative">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input
                    id="email"
                    type="email"
                    placeholder="vous@exemple.com"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="pl-9 h-11"
                    required
                    autoComplete="email"
                  />
                </div>
              </div>

              <div className="space-y-1.5">
                <Label htmlFor="password">Mot de passe</Label>
                <div className="relative">
                  <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input
                    id="password"
                    type={showPwd ? "text" : "password"}
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="pl-9 pr-10 h-11"
                    required
                    autoComplete="current-password"
                  />
                  <button
                    type="button"
                    onClick={() => setShowPwd((v) => !v)}
                    className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                  >
                    {showPwd ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                  </button>
                </div>
              </div>

              {error && (
                <div className="text-sm text-destructive bg-destructive/10 border border-destructive/20 rounded-lg px-3 py-2 animate-[fade-in_0.2s_ease-out]">
                  {error}
                </div>
              )}

              <Button type="submit" className="w-full h-11 gradient-primary text-primary-foreground" disabled={loading}>
                {loading ? (
                  <>
                    <Loader2 className="h-4 w-4 animate-spin" /> Connexion…
                  </>
                ) : (
                  "Se connecter"
                )}
              </Button>
            </form>
          </Card>

          {/* Demo accounts */}
          <div className="rounded-xl border border-border bg-muted/40 p-4 space-y-3">
            <div className="flex items-center justify-between">
              <p className="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                Comptes démo
              </p>
              <span className="text-[10px] px-2 py-0.5 rounded-full bg-success/15 text-success font-medium">
                Mode démo
              </span>
            </div>

            {[
              { role: "Admin", email: "ecotech@gmail.com", pwd: "ecotech237" },
              { role: "Gérant", email: "manager@ecotec.cm", pwd: "manager237" },
            ].map((d) => (
              <div
                key={d.email}
                className="flex items-center justify-between gap-3 rounded-lg bg-card border border-border px-3 py-2.5 hover:shadow-elegant transition"
              >
                <div className="min-w-0">
                  <div className="flex items-center gap-2">
                    <span className="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-primary/10 text-primary">
                      {d.role}
                    </span>
                    <span className="text-sm font-medium truncate">{d.email}</span>
                  </div>
                  <p className="text-xs text-muted-foreground font-mono mt-0.5">{d.pwd}</p>
                </div>
                <div className="flex items-center gap-1 shrink-0">
                  <button
                    type="button"
                    onClick={() => copy(`${d.email} / ${d.pwd}`)}
                    className="p-2 rounded-md hover:bg-muted text-muted-foreground hover:text-foreground transition"
                    title="Copier"
                  >
                    {copied === `${d.email} / ${d.pwd}` ? (
                      <Check className="h-3.5 w-3.5 text-success" />
                    ) : (
                      <Copy className="h-3.5 w-3.5" />
                    )}
                  </button>
                  <Button
                    type="button"
                    size="sm"
                    variant="outline"
                    onClick={() => fillDemo(d.email, d.pwd)}
                    className="h-8 text-xs"
                  >
                    Utiliser
                  </Button>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}
