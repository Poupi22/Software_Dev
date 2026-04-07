import { createFileRoute, Link } from "@tanstack/react-router";
import {
  Sparkles, ArrowRight, Brain, Cpu, Recycle, Users, ShieldCheck, BarChart3,
  Mail, Phone, MapPin, Send, Quote, Award, Leaf, Zap, CheckCircle2,
} from "lucide-react";
import { useState, type FormEvent } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Label } from "@/components/ui/label";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { toast } from "sonner";
import heroImg from "@/assets/hero-poultry.jpg";
import coopImg from "@/assets/farm-coop.jpg";
import iotImg from "@/assets/tech-iot.jpg";
import eggsImg from "@/assets/eggs.jpg";
import compostImg from "@/assets/compost.jpg";
import teamImg from "@/assets/team.jpg";

export const Route = createFileRoute("/")({
  component: LandingPage,
  head: () => ({
    meta: [
      { title: "ECOTEC Smart Poultry — Ferme avicole intelligente & durable" },
      {
        name: "description",
        content:
          "ECOTEC Smart Poultry : la plateforme camerounaise qui combine IA, IoT et modèle coopératif pour transformer l'aviculture.",
      },
      { property: "og:title", content: "ECOTEC Smart Poultry" },
      { property: "og:description", content: "IA + IoT + Coopérative pour une aviculture intelligente." },
    ],
  }),
});

function LandingPage() {
  return (
    <div className="min-h-screen bg-background text-foreground">
      <Navbar />
      <Hero />
      <Stats />
      <About />
      <Features />
      <Tech />
      <Coop />
      <Testimonials />
      <Team />
      <Contact />
      <Footer />
    </div>
  );
}

function Navbar() {
  const [open, setOpen] = useState(false);
  return (
    <header className="sticky top-0 z-50 backdrop-blur-lg bg-background/75 border-b border-border/60">
      <div className="max-w-7xl mx-auto flex items-center justify-between px-4 lg:px-8 h-16">
        <Link to="/" className="flex items-center gap-2.5 hover-scale">
          <div className="h-9 w-9 rounded-xl gradient-primary flex items-center justify-center shadow-glow">
            <Sparkles className="h-4 w-4 text-primary-foreground" />
          </div>
          <div>
            <p className="text-sm font-bold leading-tight">ECOTEC</p>
            <p className="text-[10px] text-muted-foreground -mt-0.5">Smart Poultry</p>
          </div>
        </Link>

        <nav className="hidden md:flex items-center gap-7 text-sm font-medium">
          <a href="#about" className="story-link text-muted-foreground hover:text-foreground">À propos</a>
          <a href="#features" className="story-link text-muted-foreground hover:text-foreground">Fonctionnalités</a>
          <a href="#tech" className="story-link text-muted-foreground hover:text-foreground">Technologie</a>
          <a href="#coop" className="story-link text-muted-foreground hover:text-foreground">Coopérative</a>
          <a href="#contact" className="story-link text-muted-foreground hover:text-foreground">Contact</a>
        </nav>

        <Link to="/login">
          <Button className="gradient-primary text-primary-foreground shadow-elegant hover:opacity-95">
            Connect <ArrowRight className="h-4 w-4" />
          </Button>
        </Link>
      </div>
    </header>
  );
}

function Hero() {
  return (
    <section className="relative overflow-hidden">
      <div className="absolute inset-0">
        <img src={heroImg} alt="" className="h-full w-full object-cover" width={1600} height={1024} />
        <div className="absolute inset-0 bg-gradient-to-r from-background via-background/85 to-background/30" />
      </div>

      <div className="relative max-w-7xl mx-auto px-4 lg:px-8 py-20 lg:py-32 grid lg:grid-cols-2 gap-12 items-center">
        <div className="space-y-6 animate-[fade-in_0.7s_ease-out]">
          <Badge variant="outline" className="border-primary/30 bg-primary/5 text-primary px-3 py-1">
            <Award className="h-3.5 w-3.5 mr-1.5" /> POESAM Orange 2026
          </Badge>
          <h1 className="text-4xl lg:text-6xl font-bold leading-[1.05] tracking-tight">
            L'aviculture <span className="text-gradient">intelligente</span>,
            <br /> coopérative et durable.
          </h1>
          <p className="text-lg text-muted-foreground max-w-xl">
            ECOTEC Smart Poultry combine <b>IA</b>, <b>IoT</b> et <b>modèle coopératif</b> pour offrir aux
            éleveurs camerounais un pilotage temps réel, une détection précoce des maladies et un revenu
            stable.
          </p>
          <div className="flex flex-wrap gap-3 pt-2">
            <Link to="/login">
              <Button size="lg" className="gradient-primary text-primary-foreground shadow-elegant h-12 px-6">
                Tester le dashboard <ArrowRight className="h-4 w-4" />
              </Button>
            </Link>
            <a href="#features">
              <Button size="lg" variant="outline" className="h-12 px-6">
                Découvrir
              </Button>
            </a>
          </div>

          <div className="flex items-center gap-4 pt-4 text-sm text-muted-foreground">
            <div className="flex -space-x-2">
              {[1, 2, 3, 4].map((i) => (
                <div
                  key={i}
                  className="h-8 w-8 rounded-full border-2 border-background gradient-primary flex items-center justify-center text-[10px] font-bold text-primary-foreground"
                >
                  {String.fromCharCode(64 + i)}
                </div>
              ))}
            </div>
            <span><b className="text-foreground">52 coopérants</b> actifs nous font confiance</span>
          </div>
        </div>

        <div className="relative animate-[scale-in_0.6s_ease-out_0.2s_both]">
          <div className="absolute -inset-4 gradient-hero opacity-20 blur-3xl rounded-3xl" />
          <Card className="relative overflow-hidden p-0 shadow-elegant border-2">
            <img src={heroImg} alt="Ferme avicole moderne ECOTEC" className="w-full h-72 lg:h-[420px] object-cover" width={1600} height={1024} />
            <div className="absolute top-4 left-4 flex items-center gap-2 bg-background/90 backdrop-blur rounded-full px-3 py-1.5 text-xs font-medium border border-border">
              <span className="h-2 w-2 rounded-full bg-success animate-pulse" />
              IA active · 24/7
            </div>
            <div className="absolute bottom-4 right-4 bg-background/95 backdrop-blur rounded-xl border border-border p-3 shadow-elegant">
              <p className="text-[10px] uppercase tracking-wider text-muted-foreground">Mortalité détectée</p>
              <p className="text-2xl font-bold text-success">-37%</p>
            </div>
          </Card>
        </div>
      </div>
    </section>
  );
}

function Stats() {
  const stats = [
    { v: "42 580", l: "Volailles suivies", icon: Sparkles },
    { v: "98%", l: "Précision IA YOLO v8", icon: Brain },
    { v: "52", l: "Coopérants partenaires", icon: Users },
    { v: "248 T", l: "Compost produit / an", icon: Recycle },
  ];
  return (
    <section className="border-y border-border bg-muted/30">
      <div className="max-w-7xl mx-auto px-4 lg:px-8 py-10 grid grid-cols-2 md:grid-cols-4 gap-6">
        {stats.map((s, i) => (
          <div
            key={s.l}
            className="text-center animate-[fade-in_0.5s_ease-out_both]"
            style={{ animationDelay: `${i * 100}ms` }}
          >
            <div className="inline-flex h-10 w-10 items-center justify-center rounded-xl gradient-primary text-primary-foreground mb-2">
              <s.icon className="h-5 w-5" />
            </div>
            <p className="text-3xl font-bold">{s.v}</p>
            <p className="text-xs text-muted-foreground mt-1">{s.l}</p>
          </div>
        ))}
      </div>
    </section>
  );
}

function About() {
  return (
    <section id="about" className="py-20 lg:py-28">
      <div className="max-w-7xl mx-auto px-4 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <div className="relative">
          <img
            src={coopImg}
            alt="Coopérants"
            className="w-full h-96 object-cover rounded-2xl shadow-elegant"
            loading="lazy"
            width={1200}
            height={800}
          />
          <Card className="absolute -bottom-6 -right-4 lg:right-8 p-5 max-w-xs shadow-elegant border-2 hidden md:block">
            <Leaf className="h-6 w-6 text-success mb-2" />
            <p className="text-sm font-semibold">100% agroécologique</p>
            <p className="text-xs text-muted-foreground mt-1">
              Compostage circulaire, zéro déchet, alimentation locale.
            </p>
          </Card>
        </div>

        <div className="space-y-5">
          <Badge variant="outline" className="border-success/30 bg-success/5 text-success">
            À propos
          </Badge>
          <h2 className="text-3xl lg:text-4xl font-bold tracking-tight">
            Une ferme intelligente <br /> pensée pour le <span className="text-gradient">Cameroun</span>.
          </h2>
          <p className="text-muted-foreground leading-relaxed">
            ECOTEC Smart Poultry est née du constat que l'aviculture africaine perd jusqu'à 30% de sa
            production faute de suivi. Notre plateforme connecte les éleveurs, applique l'IA au quotidien
            et redistribue la valeur via un modèle coopératif.
          </p>
          <ul className="space-y-3">
            {[
              "Détection précoce des maladies via vision par ordinateur",
              "Capteurs IoT pour température, humidité, ammoniac",
              "Compostage automatisé et revente du compost",
              "Marketplace pour les coopérants",
            ].map((p) => (
              <li key={p} className="flex items-start gap-3">
                <CheckCircle2 className="h-5 w-5 text-success shrink-0 mt-0.5" />
                <span className="text-sm">{p}</span>
              </li>
            ))}
          </ul>
        </div>
      </div>
    </section>
  );
}

function Features() {
  const items = [
    {
      icon: Brain,
      title: "IA temps réel",
      desc: "Caméras YOLO v8 qui détectent maladies, comportements anormaux et stress thermique 24/7.",
      color: "from-blue-500/15 to-cyan-500/15",
    },
    {
      icon: Cpu,
      title: "IoT connecté",
      desc: "Capteurs de température, humidité, NH₃ et eau. Alertes instantanées sur votre téléphone.",
      color: "from-emerald-500/15 to-teal-500/15",
    },
    {
      icon: BarChart3,
      title: "Dashboard avancé",
      desc: "Plus de 20 graphiques précis : production, FCR, cashflow, prix marché, performance par bande.",
      color: "from-violet-500/15 to-fuchsia-500/15",
    },
    {
      icon: Users,
      title: "Modèle coopératif",
      desc: "Les coopérants gèrent leur ferme via un compte dédié et bénéficient d'un revenu stable.",
      color: "from-amber-500/15 to-orange-500/15",
    },
    {
      icon: Recycle,
      title: "Compost circulaire",
      desc: "Les fientes deviennent du compost premium revendu aux maraîchers — économie zéro déchet.",
      color: "from-green-500/15 to-lime-500/15",
    },
    {
      icon: ShieldCheck,
      title: "Traçabilité",
      desc: "De la bande au consommateur : numéro de lot, vaccinations, alimentation, abattage.",
      color: "from-sky-500/15 to-indigo-500/15",
    },
  ];
  return (
    <section id="features" className="py-20 lg:py-28 bg-muted/30 border-y border-border">
      <div className="max-w-7xl mx-auto px-4 lg:px-8">
        <div className="text-center max-w-2xl mx-auto mb-14 space-y-3">
          <Badge variant="outline" className="border-primary/30 bg-primary/5 text-primary">
            Fonctionnalités
          </Badge>
          <h2 className="text-3xl lg:text-4xl font-bold tracking-tight">
            Tout ce qu'il faut pour <span className="text-gradient">piloter une ferme moderne</span>.
          </h2>
          <p className="text-muted-foreground">
            Un outil pensé pour les éleveurs, simple à utiliser, puissant en coulisses.
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
          {items.map((it, i) => (
            <Card
              key={it.title}
              className="group p-6 hover-scale hover:shadow-elegant transition-all duration-300 border-border/60 animate-[fade-in_0.5s_ease-out_both] cursor-default relative overflow-hidden"
              style={{ animationDelay: `${i * 80}ms` }}
            >
              <div className={`absolute inset-0 bg-gradient-to-br ${it.color} opacity-0 group-hover:opacity-100 transition-opacity duration-500`} />
              <div className="relative">
                <div className="h-12 w-12 rounded-xl gradient-primary flex items-center justify-center mb-4 shadow-glow">
                  <it.icon className="h-5 w-5 text-primary-foreground" />
                </div>
                <h3 className="text-lg font-semibold mb-2">{it.title}</h3>
                <p className="text-sm text-muted-foreground leading-relaxed">{it.desc}</p>
              </div>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
}

function Tech() {
  return (
    <section id="tech" className="py-20 lg:py-28">
      <div className="max-w-7xl mx-auto px-4 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <div className="space-y-5 order-2 lg:order-1">
          <Badge variant="outline" className="border-info/30 bg-info/5 text-info">
            Technologie
          </Badge>
          <h2 className="text-3xl lg:text-4xl font-bold tracking-tight">
            <span className="text-gradient">YOLO v8</span> + IoT + Edge AI
          </h2>
          <p className="text-muted-foreground">
            Notre stack technique combine vision par ordinateur, capteurs connectés et inférence locale
            pour fonctionner même avec une connexion intermittente.
          </p>
          <div className="grid sm:grid-cols-2 gap-3 pt-2">
            {[
              { i: Brain, t: "YOLO v8", s: "Détection volailles & maladies" },
              { i: Cpu, t: "ESP32 + LoRa", s: "Capteurs longue portée" },
              { i: Zap, t: "Edge inference", s: "Fonctionne hors-ligne" },
              { i: ShieldCheck, t: "Cloud sécurisé", s: "Données chiffrées" },
            ].map((t) => (
              <div key={t.t} className="flex items-start gap-3 p-3 rounded-xl border border-border bg-card hover:shadow-elegant transition">
                <div className="h-9 w-9 rounded-lg bg-info/10 flex items-center justify-center text-info shrink-0">
                  <t.i className="h-4 w-4" />
                </div>
                <div>
                  <p className="text-sm font-semibold">{t.t}</p>
                  <p className="text-xs text-muted-foreground">{t.s}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
        <div className="relative order-1 lg:order-2">
          <div className="absolute -inset-6 gradient-blue opacity-15 blur-3xl rounded-3xl" />
          <img
            src={iotImg}
            alt="Technologie IoT"
            className="relative w-full h-96 object-cover rounded-2xl shadow-elegant"
            loading="lazy"
            width={1200}
            height={800}
          />
        </div>
      </div>
    </section>
  );
}

function Coop() {
  return (
    <section id="coop" className="py-20 lg:py-28 bg-muted/30 border-y border-border">
      <div className="max-w-7xl mx-auto px-4 lg:px-8">
        <div className="text-center max-w-2xl mx-auto mb-12 space-y-3">
          <Badge variant="outline" className="border-success/30 bg-success/5 text-success">
            Modèle coopératif
          </Badge>
          <h2 className="text-3xl lg:text-4xl font-bold tracking-tight">
            Une <span className="text-gradient">valeur partagée</span> avec les éleveurs.
          </h2>
        </div>

        <div className="grid md:grid-cols-3 gap-5">
          {[
            { img: eggsImg, t: "Production garantie", d: "ECOTEC fournit poussins, aliment et suivi vétérinaire." },
            { img: coopImg, t: "Revenu stable", d: "Achat à prix fixe + bonus performance trimestriel." },
            { img: compostImg, t: "Économie circulaire", d: "Compost revendu, profit redistribué aux coopérants." },
          ].map((c, i) => (
            <Card key={c.t} className="overflow-hidden hover-scale hover:shadow-elegant transition-all duration-300 animate-[fade-in_0.5s_ease-out_both]" style={{ animationDelay: `${i * 100}ms` }}>
              <img src={c.img} alt={c.t} className="w-full h-48 object-cover" loading="lazy" width={1200} height={800} />
              <div className="p-5">
                <h3 className="font-semibold mb-1.5">{c.t}</h3>
                <p className="text-sm text-muted-foreground">{c.d}</p>
              </div>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
}

function Testimonials() {
  const items = [
    { n: "Jean Mboma", r: "Coopérant, Yaoundé", q: "Depuis ECOTEC, ma mortalité a chuté de 40%. Le dashboard m'avertit avant que les problèmes n'arrivent." },
    { n: "Aïcha Nkomo", r: "Gérante, Douala-Est", q: "Le compost rapporte autant que les œufs. Je n'aurais jamais imaginé valoriser les fientes ainsi." },
    { n: "Paul Kamga", r: "Coopérant, Bafoussam", q: "Le suivi temps réel a changé ma vie. Je gère 3500 sujets depuis mon téléphone." },
  ];
  return (
    <section className="py-20 lg:py-28">
      <div className="max-w-7xl mx-auto px-4 lg:px-8">
        <div className="text-center max-w-2xl mx-auto mb-12 space-y-3">
          <Badge variant="outline">Témoignages</Badge>
          <h2 className="text-3xl lg:text-4xl font-bold tracking-tight">Ils nous font confiance.</h2>
        </div>
        <div className="grid md:grid-cols-3 gap-5">
          {items.map((it, i) => (
            <Card key={it.n} className="p-6 relative hover:shadow-elegant transition animate-[fade-in_0.5s_ease-out_both]" style={{ animationDelay: `${i * 100}ms` }}>
              <Quote className="absolute top-4 right-4 h-8 w-8 text-primary/15" />
              <p className="text-sm leading-relaxed text-muted-foreground italic">"{it.q}"</p>
              <div className="flex items-center gap-3 mt-5 pt-5 border-t border-border">
                <div className="h-10 w-10 rounded-full gradient-primary flex items-center justify-center text-primary-foreground font-semibold text-sm">
                  {it.n.split(" ").map((p) => p[0]).join("")}
                </div>
                <div>
                  <p className="text-sm font-semibold">{it.n}</p>
                  <p className="text-xs text-muted-foreground">{it.r}</p>
                </div>
              </div>
            </Card>
          ))}
        </div>
      </div>
    </section>
  );
}

function Team() {
  return (
    <section className="py-20 lg:py-28 bg-muted/30 border-y border-border">
      <div className="max-w-7xl mx-auto px-4 lg:px-8 grid lg:grid-cols-2 gap-12 items-center">
        <div className="space-y-5">
          <Badge variant="outline" className="border-warning/30 bg-warning/5 text-warning">L'équipe</Badge>
          <h2 className="text-3xl lg:text-4xl font-bold tracking-tight">
            Des <span className="text-gradient">passionnés</span> de tech & d'élevage.
          </h2>
          <p className="text-muted-foreground">
            Une équipe pluridisciplinaire : ingénieurs IA, vétérinaires, agronomes et entrepreneurs sociaux,
            tous engagés pour transformer l'aviculture africaine.
          </p>
          <div className="grid grid-cols-3 gap-3 pt-2">
            {[{ v: "12", l: "Membres" }, { v: "5", l: "Pays" }, { v: "8 ans", l: "Expérience" }].map((s) => (
              <Card key={s.l} className="p-4 text-center">
                <p className="text-2xl font-bold text-gradient">{s.v}</p>
                <p className="text-xs text-muted-foreground">{s.l}</p>
              </Card>
            ))}
          </div>
        </div>
        <img src={teamImg} alt="Équipe ECOTEC" className="w-full h-96 object-cover rounded-2xl shadow-elegant" loading="lazy" width={1200} height={800} />
      </div>
    </section>
  );
}

function Contact() {
  const [sending, setSending] = useState(false);
  const onSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setSending(true);
    await new Promise((r) => setTimeout(r, 800));
    setSending(false);
    toast.success("Message envoyé ! Nous vous répondrons sous 24h.");
    (e.target as HTMLFormElement).reset();
  };
  return (
    <section id="contact" className="py-20 lg:py-28">
      <div className="max-w-6xl mx-auto px-4 lg:px-8 grid lg:grid-cols-5 gap-10">
        <div className="lg:col-span-2 space-y-5">
          <Badge variant="outline" className="border-primary/30 bg-primary/5 text-primary">Contact</Badge>
          <h2 className="text-3xl lg:text-4xl font-bold tracking-tight">
            Une question ? <br /> <span className="text-gradient">Parlons-en.</span>
          </h2>
          <p className="text-muted-foreground">
            Vous êtes éleveur, investisseur ou simplement curieux ? Écrivez-nous.
          </p>
          <div className="space-y-3 pt-2">
            {[
              { i: Mail, t: "ecotech@gmail.com" },
              { i: Phone, t: "+237 6 99 88 77 66" },
              { i: MapPin, t: "Yaoundé, Cameroun" },
            ].map((c) => (
              <div key={c.t} className="flex items-center gap-3 text-sm">
                <div className="h-9 w-9 rounded-lg gradient-primary flex items-center justify-center text-primary-foreground">
                  <c.i className="h-4 w-4" />
                </div>
                <span>{c.t}</span>
              </div>
            ))}
          </div>
        </div>

        <Card className="lg:col-span-3 p-6 lg:p-8 shadow-elegant">
          <form onSubmit={onSubmit} className="space-y-4">
            <div className="grid sm:grid-cols-2 gap-4">
              <div className="space-y-1.5">
                <Label htmlFor="name">Nom complet</Label>
                <Input id="name" required placeholder="Jean Dupont" className="h-11" />
              </div>
              <div className="space-y-1.5">
                <Label htmlFor="cmail">Email</Label>
                <Input id="cmail" type="email" required placeholder="vous@exemple.com" className="h-11" />
              </div>
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="subject">Sujet</Label>
              <Input id="subject" required placeholder="Devenir coopérant" className="h-11" />
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="msg">Message</Label>
              <Textarea id="msg" required rows={5} placeholder="Décrivez votre projet ou question…" />
            </div>
            <Button type="submit" disabled={sending} className="gradient-primary text-primary-foreground h-11 w-full sm:w-auto">
              {sending ? "Envoi…" : (<>Envoyer <Send className="h-4 w-4" /></>)}
            </Button>
          </form>
        </Card>
      </div>
    </section>
  );
}

function Footer() {
  return (
    <footer className="border-t border-border bg-muted/40">
      <div className="max-w-7xl mx-auto px-4 lg:px-8 py-10 grid md:grid-cols-4 gap-8 text-sm">
        <div className="md:col-span-2 space-y-3">
          <div className="flex items-center gap-2">
            <div className="h-9 w-9 rounded-xl gradient-primary flex items-center justify-center">
              <Sparkles className="h-4 w-4 text-primary-foreground" />
            </div>
            <div>
              <p className="font-bold">ECOTEC Smart Poultry</p>
              <p className="text-xs text-muted-foreground">Aviculture intelligente · Cameroun</p>
            </div>
          </div>
          <p className="text-muted-foreground max-w-md">
            Plateforme camerounaise d'aviculture intelligente. IA, IoT, modèle coopératif. Lauréate POESAM Orange 2026.
          </p>
        </div>
        <div>
          <p className="font-semibold mb-3">Navigation</p>
          <ul className="space-y-2 text-muted-foreground">
            <li><a href="#about" className="hover:text-foreground">À propos</a></li>
            <li><a href="#features" className="hover:text-foreground">Fonctionnalités</a></li>
            <li><a href="#tech" className="hover:text-foreground">Technologie</a></li>
            <li><a href="#contact" className="hover:text-foreground">Contact</a></li>
          </ul>
        </div>
        <div>
          <p className="font-semibold mb-3">Espace</p>
          <ul className="space-y-2 text-muted-foreground">
            <li><Link to="/login" className="hover:text-foreground">Connexion</Link></li>
            <li><Link to="/dashboard" className="hover:text-foreground">Dashboard</Link></li>
          </ul>
        </div>
      </div>
      <div className="border-t border-border py-5 text-center text-xs text-muted-foreground">
        © 2026 ECOTEC Smart Poultry · Tous droits réservés
      </div>
    </footer>
  );
}
