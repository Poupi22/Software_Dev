import { createFileRoute, useNavigate } from "@tanstack/react-router";
import { useEffect, useState, type FormEvent } from "react";
import { Stethoscope, Mail, Lock, ArrowRight, Loader2 } from "lucide-react";
import { useAuth } from "@/lib/auth";
import { toast } from "sonner";

export const Route = createFileRoute("/login")({
  component: LoginPage,
});

function LoginPage() {
  const { user, login, loading: authLoading } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    if (user && !authLoading) {
      navigate({ to: "/admin" });
    }
  }, [user, authLoading, navigate]);

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      const u = await login(email, password);
      toast.success(`Bienvenue ${u.nom.split(" ")[0]}`);
      navigate({ to: "/admin" });
    } catch (err: any) {
      const msg = err.response?.data?.message || err.message || "Erreur de connexion";
      setError(msg);
      toast.error(msg);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="grid min-h-screen lg:grid-cols-2">
      <div className="relative hidden overflow-hidden bg-gradient-to-br from-blue-900 to-indigo-900 lg:block">
        <div className="relative z-10 flex h-full flex-col justify-between p-12 text-white">
          <div className="flex items-center gap-3">
            <Stethoscope className="h-8 w-8" />
            <div className="text-xl font-bold">PharmaCare</div>
          </div>
          <div>
            <h1 className="text-4xl font-bold mb-4">Gestion de stock pharmaceutique</h1>
            <p className="text-white/80">Suivez vos médicaments, ventes et alertes en temps réel.</p>
          </div>
          <div className="text-sm text-white/50">© 2024 PharmaCare - Tous droits réservés</div>
        </div>
      </div>

      <div className="flex items-center justify-center p-8">
        <div className="w-full max-w-md">
          <div className="mb-8 text-center lg:hidden">
            <Stethoscope className="mx-auto h-10 w-10 text-primary" />
            <h2 className="mt-2 text-2xl font-bold">PharmaCare</h2>
          </div>
          <h2 className="text-3xl font-bold text-center lg:text-left">Connexion</h2>
          <p className="mt-2 text-muted-foreground text-center lg:text-left">
            Connectez-vous à votre compte
          </p>

          <form onSubmit={handleSubmit} className="mt-8 space-y-5">
            <div>
              <label className="mb-1.5 block text-sm font-medium">Email</label>
              <div className="relative">
                <Mail className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                  placeholder="exemple@pharmacie.com"
                  className="h-11 w-full rounded-lg border border-input bg-background pl-10 pr-4 text-sm"
                />
              </div>
            </div>

            <div>
              <label className="mb-1.5 block text-sm font-medium">Mot de passe</label>
              <div className="relative">
                <Lock className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <input
                  type="password"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  placeholder="••••••••"
                  className="h-11 w-full rounded-lg border border-input bg-background pl-10 pr-4 text-sm"
                />
              </div>
            </div>

            {error && (
              <div className="rounded-lg bg-red-50 p-3 text-sm text-red-600">
                {error}
              </div>
            )}

            <button
              type="submit"
              disabled={loading}
              className="flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-primary text-sm font-semibold text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
            >
              {loading ? <Loader2 className="h-4 w-4 animate-spin" /> : <>Se connecter <ArrowRight className="h-4 w-4" /></>}
            </button>
          </form>

          <div className="mt-8 text-center text-xs text-muted-foreground">
            <p>Contactez l'administrateur pour obtenir vos identifiants</p>
          </div>
        </div>
      </div>
    </div>
  );
}
