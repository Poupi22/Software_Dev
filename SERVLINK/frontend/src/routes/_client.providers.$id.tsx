import { createFileRoute, Link, notFound } from "@tanstack/react-router";
import { Star, MapPin, BadgeCheck, Clock, Calendar, MessageCircle, Heart, Share2, Shield, Award, CheckCircle2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { providers, formatXAF, reviews } from "@/lib/mock-data";

export const Route = createFileRoute("/_client/providers/$id")({
  loader: ({ params }) => {
    const provider = providers.find((p) => p.id === params.id);
    if (!provider) throw notFound();
    return { provider };
  },
  head: ({ loaderData }) => ({
    meta: [
      { title: `${loaderData?.provider.name} — ${loaderData?.provider.category} | SERVLINK` },
      { name: "description", content: loaderData?.provider.bio },
      { property: "og:image", content: loaderData?.provider.cover },
    ],
  }),
  component: ProviderPage,
  notFoundComponent: () => (
    <div className="container mx-auto px-4 py-20 text-center">
      <h1 className="font-display text-2xl">Prestataire introuvable</h1>
      <Link to="/search" className="text-primary underline mt-2 inline-block">Retour à la recherche</Link>
    </div>
  ),
});

function ProviderPage() {
  const { provider } = Route.useLoaderData();
  const p = provider;
  const gallery = [p.cover, "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=800", "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800", "https://images.unsplash.com/photo-1562322140-8baeececf3df?w=800"];

  return (
    <>
      {/* Cover */}
      <div className="relative h-56 md:h-80 bg-muted overflow-hidden">
        <img src={p.cover} alt={p.name} className="w-full h-full object-cover" />
        <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
      </div>

      <div className="container mx-auto px-4 -mt-16 relative">
        <div className="bg-card border border-border rounded-2xl p-6 shadow-lg">
          <div className="flex flex-col md:flex-row items-start gap-6">
            <img src={p.avatar} alt={p.name} className="h-24 w-24 rounded-2xl object-cover ring-4 ring-background shadow-md -mt-12 md:mt-0" />
            <div className="flex-1">
              <div className="flex items-center gap-2 flex-wrap">
                <h1 className="font-display text-2xl md:text-3xl font-bold">{p.name}</h1>
                {p.verified && <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-semibold"><BadgeCheck className="h-3.5 w-3.5" /> Vérifié</span>}
                {p.featured && <span className="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gold/15 text-gold-foreground text-xs font-semibold"><Award className="h-3.5 w-3.5 text-gold" /> À la une</span>}
              </div>
              <p className="text-muted-foreground mt-1">{p.category}</p>
              <div className="flex flex-wrap items-center gap-4 mt-3 text-sm">
                <span className="flex items-center gap-1"><Star className="h-4 w-4 fill-gold text-gold" /><span className="font-semibold">{p.rating}</span> <span className="text-muted-foreground">({p.reviews} avis)</span></span>
                <span className="flex items-center gap-1 text-muted-foreground"><MapPin className="h-4 w-4" />{p.city}</span>
                <span className="flex items-center gap-1 text-muted-foreground"><Clock className="h-4 w-4" />Répond {p.responseTime}</span>
                <span className="flex items-center gap-1 text-muted-foreground"><CheckCircle2 className="h-4 w-4 text-primary" />{p.completed} missions</span>
              </div>
            </div>
            <div className="flex gap-2 md:flex-col w-full md:w-auto">
              <Button size="lg" className="flex-1"><Calendar className="mr-2 h-4 w-4" /> Réserver</Button>
              <Link to="/messages" className="flex-1"><Button size="lg" variant="outline" className="w-full"><MessageCircle className="mr-2 h-4 w-4" /> Message</Button></Link>
              <Button size="lg" variant="ghost" className="px-3"><Heart className="h-4 w-4" /></Button>
              <Button size="lg" variant="ghost" className="px-3"><Share2 className="h-4 w-4" /></Button>
            </div>
          </div>
        </div>

        <div className="grid lg:grid-cols-3 gap-6 mt-6">
          <div className="lg:col-span-2 space-y-6">
            <section className="bg-card border border-border rounded-2xl p-6">
              <h2 className="font-display text-xl font-bold mb-3">À propos</h2>
              <p className="text-muted-foreground leading-relaxed">{p.bio}</p>
              <div className="flex flex-wrap gap-2 mt-4">
                {p.tags.map((t: string) => <span key={t} className="px-3 py-1 rounded-full bg-accent text-accent-foreground text-xs font-medium">{t}</span>)}
              </div>
            </section>

            <section className="bg-card border border-border rounded-2xl p-6">
              <h2 className="font-display text-xl font-bold mb-4">Portfolio</h2>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                {gallery.map((src, i) => (
                  <div key={i} className="aspect-square rounded-lg overflow-hidden bg-muted">
                    <img src={src} alt="" className="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer" />
                  </div>
                ))}
              </div>
            </section>

            <section className="bg-card border border-border rounded-2xl p-6">
              <h2 className="font-display text-xl font-bold mb-4">Services proposés</h2>
              <div className="space-y-3">
                {["Intervention standard", "Intervention express", "Forfait 4 heures"].map((s, i) => (
                  <div key={s} className="flex items-center justify-between p-4 rounded-xl border border-border hover:border-primary transition-colors">
                    <div>
                      <h3 className="font-semibold">{s}</h3>
                      <p className="text-xs text-muted-foreground">Délai estimé : {["2h", "1h", "4h"][i]}</p>
                    </div>
                    <div className="text-right">
                      <div className="font-display font-bold text-primary">{formatXAF(p.priceFrom * (i + 1))}</div>
                      <Button size="sm" variant="outline" className="mt-1">Réserver</Button>
                    </div>
                  </div>
                ))}
              </div>
            </section>

            <section className="bg-card border border-border rounded-2xl p-6">
              <div className="flex items-center justify-between mb-4">
                <h2 className="font-display text-xl font-bold">Avis ({p.reviews})</h2>
                <div className="flex items-center gap-1"><Star className="h-5 w-5 fill-gold text-gold" /><span className="font-display font-bold text-lg">{p.rating}</span></div>
              </div>
              <div className="space-y-4">
                {reviews.map((r) => (
                  <div key={r.id} className="border-b border-border last:border-0 pb-4 last:pb-0">
                    <div className="flex items-center gap-3 mb-2">
                      <img src={r.avatar} alt={r.author} className="h-9 w-9 rounded-full object-cover" />
                      <div className="flex-1">
                        <div className="text-sm font-semibold">{r.author}</div>
                        <div className="text-xs text-muted-foreground">{r.date}</div>
                      </div>
                      <div className="flex gap-0.5">
                        {Array.from({ length: 5 }).map((_, i) => (
                          <Star key={i} className={`h-3.5 w-3.5 ${i < r.rating ? "fill-gold text-gold" : "text-muted"}`} />
                        ))}
                      </div>
                    </div>
                    <p className="text-sm">{r.comment}</p>
                  </div>
                ))}
              </div>
            </section>
          </div>

          <aside className="space-y-4">
            <div className="bg-card border border-border rounded-2xl p-6 lg:sticky lg:top-20">
              <div className="text-xs uppercase text-muted-foreground font-semibold">À partir de</div>
              <div className="font-display text-3xl font-bold text-primary">{formatXAF(p.priceFrom)}</div>
              <Button size="lg" className="w-full mt-4"><Calendar className="mr-2 h-4 w-4" /> Réserver un créneau</Button>
              <Link to="/messages"><Button variant="outline" size="lg" className="w-full mt-2"><MessageCircle className="mr-2 h-4 w-4" /> Envoyer un message</Button></Link>

              <div className="mt-6 pt-6 border-t border-border space-y-3 text-sm">
                <div className="flex items-start gap-3"><Shield className="h-5 w-5 text-primary shrink-0" /><div><div className="font-semibold">Paiement sécurisé</div><div className="text-xs text-muted-foreground">Funds protégés jusqu'à la fin de la prestation.</div></div></div>
                <div className="flex items-start gap-3"><CheckCircle2 className="h-5 w-5 text-primary shrink-0" /><div><div className="font-semibold">Annulation gratuite</div><div className="text-xs text-muted-foreground">Jusqu'à 24h avant l'intervention.</div></div></div>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </>
  );
}
