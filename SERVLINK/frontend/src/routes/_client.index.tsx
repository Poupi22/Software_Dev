import { createFileRoute, Link, useNavigate } from "@tanstack/react-router";
import { useState } from "react";
import { Search, MapPin, ArrowRight, Shield, CreditCard, MessageCircle, CheckCircle2, Star, Sparkles, Zap, Wrench, Scissors, Hammer, Laptop, GraduationCap, PartyPopper, Truck, Trees, Shirt, Camera } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { ProviderCard } from "@/components/ProviderCard";
import { categories, providers, reviews } from "@/lib/mock-data";

const iconMap: Record<string, React.ComponentType<{ className?: string }>> = {
  Wrench, Zap, Sparkles, Scissors, Hammer, Laptop, GraduationCap, PartyPopper, Truck, Trees, Shirt, Camera,
};

export const Route = createFileRoute("/_client/")({
  head: () => ({
    meta: [
      { title: "SERVLINK — Trouvez le bon prestataire, en toute confiance" },
      { name: "description", content: "Plomberie, électricité, ménage, cours, événementiel… Réservez et payez en ligne via Mobile Money ou carte bancaire." },
    ],
  }),
  component: HomePage,
});

function HomePage() {
  const navigate = useNavigate();
  const [q, setQ] = useState("");
  const featured = providers.filter((p) => p.featured).slice(0, 4);
  const top = providers.slice(0, 8);

  return (
    <>
      {/* Hero */}
      <section className="relative gradient-hero text-white overflow-hidden">
        <div className="absolute inset-0 opacity-10" style={{ backgroundImage: "radial-gradient(circle at 20% 20%, white 1px, transparent 1px), radial-gradient(circle at 80% 60%, white 1px, transparent 1px)", backgroundSize: "60px 60px" }} />
        <div className="container mx-auto px-4 py-16 md:py-24 relative">
          <div className="max-w-3xl">
            <span className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur text-xs font-medium mb-4">
              <Sparkles className="h-3.5 w-3.5 text-gold" /> +1 200 prestataires vérifiés
            </span>
            <h1 className="font-display text-4xl md:text-6xl font-bold leading-tight">
              Le bon prestataire,<br />
              <span className="text-gold">au bon moment.</span>
            </h1>
            <p className="mt-4 text-lg text-white/85 max-w-xl">
              Plomberie, ménage, cours, événementiel… réservez en quelques clics et payez en toute sécurité via Mobile Money ou carte bancaire.
            </p>

            <form
              onSubmit={(e) => { e.preventDefault(); navigate({ to: "/search", search: { q } as never }); }}
              className="mt-8 bg-background text-foreground rounded-2xl shadow-2xl p-2 flex flex-col md:flex-row gap-2"
            >
              <div className="flex-1 flex items-center gap-2 px-3">
                <Search className="h-5 w-5 text-muted-foreground" />
                <Input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Que cherchez-vous ?" className="border-0 focus-visible:ring-0 shadow-none h-12 px-0" />
              </div>
              <div className="hidden md:flex items-center gap-2 px-3 border-l border-border">
                <MapPin className="h-5 w-5 text-muted-foreground" />
                <Input placeholder="Douala" className="border-0 focus-visible:ring-0 shadow-none h-12 w-32 px-0" />
              </div>
              <Button type="submit" size="lg" className="h-12 md:px-8">
                Rechercher <ArrowRight className="ml-2 h-4 w-4" />
              </Button>
            </form>

            <div className="mt-6 flex flex-wrap gap-2 text-sm">
              <span className="text-white/70 mr-1">Populaire :</span>
              {["Plombier", "Coiffeuse", "Prof maths", "DJ mariage", "Femme de ménage"].map((t) => (
                <button key={t} onClick={() => navigate({ to: "/search", search: { q: t } as never })} className="px-3 py-1 rounded-full bg-white/10 hover:bg-white/20 transition-colors">
                  {t}
                </button>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Trust band */}
      <section className="border-b border-border">
        <div className="container mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">
          {[
            { icon: Shield, t: "Prestataires vérifiés", s: "Pièce d'identité contrôlée" },
            { icon: CreditCard, t: "Paiement sécurisé", s: "Mobile Money & VISA" },
            { icon: MessageCircle, t: "Messagerie intégrée", s: "Discutez avant de réserver" },
            { icon: CheckCircle2, t: "Satisfait ou remboursé", s: "Litiges arbitrés sous 48h" },
          ].map(({ icon: Icon, t, s }) => (
            <div key={t} className="flex items-start gap-3">
              <div className="h-10 w-10 rounded-lg bg-accent flex items-center justify-center shrink-0">
                <Icon className="h-5 w-5 text-primary" />
              </div>
              <div>
                <div className="font-semibold">{t}</div>
                <div className="text-muted-foreground text-xs">{s}</div>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* Categories */}
      <section className="container mx-auto px-4 py-14">
        <div className="flex items-end justify-between mb-8">
          <div>
            <h2 className="font-display text-3xl font-bold">Catégories populaires</h2>
            <p className="text-muted-foreground mt-1">Explorez les services les plus demandés.</p>
          </div>
          <Link to="/search" className="text-sm font-medium text-primary hover:underline hidden md:flex items-center gap-1">
            Voir tout <ArrowRight className="h-4 w-4" />
          </Link>
        </div>
        <div className="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-3">
          {categories.map((c) => {
            const Icon = iconMap[c.icon] ?? Sparkles;
            return (
              <Link key={c.slug} to="/search" search={{ cat: c.slug } as never} className="group flex flex-col items-center text-center gap-2 p-4 rounded-xl border border-border bg-card hover:border-primary hover:shadow-md transition-all">
                <div className="h-12 w-12 rounded-xl bg-accent group-hover:bg-primary group-hover:text-white flex items-center justify-center transition-colors">
                  <Icon className="h-6 w-6 text-primary group-hover:text-white" />
                </div>
                <span className="text-sm font-medium">{c.name}</span>
                <span className="text-xs text-muted-foreground">{c.count} pros</span>
              </Link>
            );
          })}
        </div>
      </section>

      {/* Featured */}
      <section className="bg-muted/40 border-y border-border py-14">
        <div className="container mx-auto px-4">
          <div className="flex items-end justify-between mb-8">
            <div>
              <span className="inline-flex items-center gap-1 text-xs font-semibold text-gold uppercase tracking-wider">
                <Star className="h-3.5 w-3.5 fill-gold" /> Mis en avant
              </span>
              <h2 className="font-display text-3xl font-bold">Prestataires à la une</h2>
            </div>
            <Link to="/search" className="text-sm font-medium text-primary hover:underline hidden md:block">Voir tout</Link>
          </div>
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {featured.map((p) => <ProviderCard key={p.id} p={p} />)}
          </div>
        </div>
      </section>

      {/* How it works */}
      <section className="container mx-auto px-4 py-16">
        <h2 className="font-display text-3xl font-bold text-center">Comment ça marche</h2>
        <p className="text-center text-muted-foreground mt-2 mb-12">Trois étapes pour réserver le prestataire idéal.</p>
        <div className="grid md:grid-cols-3 gap-8">
          {[
            { n: "01", t: "Cherchez", s: "Filtrez par catégorie, ville, prix et notes pour trouver le bon prestataire." },
            { n: "02", t: "Réservez", s: "Choisissez un créneau, échangez par chat, puis confirmez votre réservation." },
            { n: "03", t: "Payez & évaluez", s: "Réglez via Mobile Money ou carte. Notez la prestation pour aider la communauté." },
          ].map((s, i) => (
            <div key={s.n} className="relative p-6 rounded-2xl bg-card border border-border">
              <div className="absolute -top-4 left-6 px-3 py-1 rounded-full gradient-gold text-gold-foreground text-xs font-bold font-display">
                ÉTAPE {s.n}
              </div>
              <h3 className="font-display text-xl font-bold mt-2">{s.t}</h3>
              <p className="text-muted-foreground mt-2 text-sm">{s.s}</p>
              {i < 2 && <ArrowRight className="hidden md:block absolute -right-6 top-1/2 -translate-y-1/2 h-6 w-6 text-primary/30 z-10" />}
            </div>
          ))}
        </div>
      </section>

      {/* Top providers */}
      <section className="container mx-auto px-4 pb-14">
        <div className="flex items-end justify-between mb-8">
          <h2 className="font-display text-3xl font-bold">Les mieux notés près de chez vous</h2>
          <Link to="/search" className="text-sm font-medium text-primary hover:underline hidden md:block">Voir tout</Link>
        </div>
        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {top.map((p) => <ProviderCard key={p.id} p={p} />)}
        </div>
      </section>

      {/* Reviews */}
      <section className="bg-muted/40 border-y border-border py-14">
        <div className="container mx-auto px-4">
          <h2 className="font-display text-3xl font-bold mb-8">Derniers avis clients</h2>
          <div className="grid md:grid-cols-3 gap-6">
            {reviews.map((r) => (
              <div key={r.id} className="bg-card rounded-xl border border-border p-6">
                <div className="flex gap-0.5 mb-3">
                  {Array.from({ length: 5 }).map((_, i) => (
                    <Star key={i} className={`h-4 w-4 ${i < r.rating ? "fill-gold text-gold" : "text-muted"}`} />
                  ))}
                </div>
                <p className="text-sm leading-relaxed">"{r.comment}"</p>
                <div className="flex items-center gap-3 mt-4 pt-4 border-t border-border">
                  <img src={r.avatar} alt={r.author} className="h-9 w-9 rounded-full object-cover" />
                  <div>
                    <div className="text-sm font-semibold">{r.author}</div>
                    <div className="text-xs text-muted-foreground">à propos de {r.provider} · {r.date}</div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="container mx-auto px-4 py-16">
        <div className="rounded-3xl gradient-hero text-white p-10 md:p-14 relative overflow-hidden">
          <div className="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-gold/30 blur-3xl" />
          <div className="relative max-w-2xl">
            <h2 className="font-display text-3xl md:text-4xl font-bold">Vous êtes prestataire ?</h2>
            <p className="mt-3 text-white/85">Rejoignez SERVLINK, recevez des demandes qualifiées et développez votre activité en toute sérénité.</p>
            <Link to="/register">
              <Button size="lg" className="mt-6 bg-gold text-gold-foreground hover:bg-gold/90">
                Devenir prestataire <ArrowRight className="ml-2 h-4 w-4" />
              </Button>
            </Link>
          </div>
        </div>
      </section>
    </>
  );
}
