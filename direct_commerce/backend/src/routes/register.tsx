import { createFileRoute, Link } from "@tanstack/react-router";
import { motion } from "framer-motion";

export const Route = createFileRoute("/register")({
  head: () => ({
    meta: [
      { title: "Créer un compte — DreamRest" },
      { name: "description", content: "Créez votre compte client DreamRest." },
    ],
  }),
  component: RegisterPage,
});

function RegisterPage() {
  return (
    <div className="flex min-h-screen items-center justify-center px-4 pt-16 pb-24 md:pb-16">
      <motion.div initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} className="w-full max-w-md">
        <div className="text-center">
          <div className="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-blue shadow-lg shadow-primary/30">
            <span className="text-lg font-bold text-white">DR</span>
          </div>
          <h1 className="mt-4 text-2xl font-bold text-foreground">Créer un compte</h1>
          <p className="mt-1 text-sm text-muted-foreground">Rejoignez DreamRest</p>
        </div>

        <form className="mt-8 space-y-4 rounded-2xl border border-border bg-card p-8 shadow-lg">
          <div>
            <label className="mb-1.5 block text-sm font-medium text-foreground">Nom complet</label>
            <input type="text" className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring" placeholder="Jean Dupont" />
          </div>
          <div>
            <label className="mb-1.5 block text-sm font-medium text-foreground">Email</label>
            <input type="email" className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring" placeholder="vous@email.fr" />
          </div>
          <div>
            <label className="mb-1.5 block text-sm font-medium text-foreground">Téléphone</label>
            <input type="tel" className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring" placeholder="+33 6 00 00 00 00" />
          </div>
          <div>
            <label className="mb-1.5 block text-sm font-medium text-foreground">Mot de passe</label>
            <input type="password" className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring" placeholder="••••••••" />
          </div>
          <button type="submit" className="w-full rounded-lg bg-gradient-blue py-3 text-sm font-semibold text-white shadow-lg transition-transform hover:scale-[1.02]">
            Créer mon compte
          </button>
        </form>

        <p className="mt-6 text-center text-sm text-muted-foreground">
          Déjà un compte ? <Link to="/login" className="font-medium text-primary hover:underline">Se connecter</Link>
        </p>
      </motion.div>
    </div>
  );
}
