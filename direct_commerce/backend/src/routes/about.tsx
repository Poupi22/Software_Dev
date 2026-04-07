import { createFileRoute } from "@tanstack/react-router";
import { motion, useScroll, useTransform, useInView } from "framer-motion";
import { Target, Heart, Zap, Shield, ArrowDown, Star, Users, Award, TrendingUp, ChevronRight, MessageSquare, ThumbsUp, Quote } from "lucide-react";
import Footer from "@/components/layout/Footer";
import { useLang } from "@/lib/i18n";
import { useRef, useState } from "react";

export const Route = createFileRoute("/about")({
  head: () => ({
    meta: [
      { title: "À Propos — DreamRest" },
      { name: "description", content: "Découvrez l'histoire et la mission de DreamRest." },
      { property: "og:title", content: "À Propos — DreamRest" },
      { property: "og:description", content: "Notre mission : votre sommeil." },
      { property: "og:image", content: "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1200&h=630&fit=crop" },
    ],
  }),
  component: AboutPage,
});

// ── Animated counter ──
function Counter({ to, suffix = "" }: { to: number; suffix?: string }) {
  const ref = useRef<HTMLSpanElement>(null);
  const isVisible = useInView(ref, { once: true });
  const [count, setCount] = useState(0);
  const [started, setStarted] = useState(false);

  if (isVisible && !started) {
    setStarted(true);
    let start = 0;
    const step = Math.max(1, Math.ceil(to / 60));
    const timer = setInterval(() => {
      start += step;
      if (start >= to) { setCount(to); clearInterval(timer); }
      else setCount(start);
    }, 16);
  }

  return <span ref={ref}>{count}{suffix}</span>;
}

// ── Testimonial data ──
const testimonials = [
  {
    name: "Aminata Fouda",
    city: "Yaoundé",
    avatar: "AF",
    color: "from-violet-500 to-purple-600",
    rating: 5,
    text: "Depuis que j'ai mon matelas DreamRest, mes nuits ont complètement changé. Plus de douleurs au dos, je me réveille vraiment reposée. Je recommande à toute ma famille !",
    product: "Matelas Mémoire de Forme",
  },
  {
    name: "Jean-Paul Mbarga",
    city: "Douala",
    avatar: "JM",
    color: "from-blue-500 to-indigo-600",
    rating: 5,
    text: "Service client exceptionnel, livraison rapide et le matelas est d'une qualité incroyable. DreamRest c'est vraiment le meilleur investissement que j'ai fait pour ma santé.",
    product: "Matelas Hybrid Premium",
  },
  {
    name: "Sandrine Nkomo",
    city: "Bafoussam",
    avatar: "SN",
    color: "from-rose-500 to-pink-600",
    rating: 5,
    text: "Je dormais très mal depuis des années. Après une semaine avec le matelas DreamRest, c'est comme si j'avais retrouvé le sommeil de mes 20 ans. Merci !",
    product: "Matelas Orthopédique",
  },
  {
    name: "Robert Essomba",
    city: "Kribi",
    avatar: "RE",
    color: "from-emerald-500 to-teal-600",
    rating: 4,
    text: "Très bonne qualité pour le prix. Le showroom de Yaoundé est super bien tenu, les conseillers sont patients et professionnels. Je suis très satisfait de mon achat.",
    product: "Matelas Confort Plus",
  },
  {
    name: "Cécile Atangana",
    city: "Ngaoundéré",
    avatar: "CA",
    color: "from-amber-500 to-orange-600",
    rating: 5,
    text: "Commandé en ligne, livré en 48h à Ngaoundéré. Qualité top, emballage soigné. Le matelas correspond exactement à la description. DreamRest tient ses promesses !",
    product: "Matelas Latex Naturel",
  },
  {
    name: "Michel Tchamou",
    city: "Douala",
    avatar: "MT",
    color: "from-sky-500 to-cyan-600",
    rating: 5,
    text: "J'hésitais entre plusieurs marques mais le rapport qualité-prix de DreamRest m'a convaincu. 6 mois après, aucun regret. Mon dos vous remercie !",
    product: "Matelas Hybrid Premium",
  },
];

// ── FAQ data ──
const faqs = [
  { q: "Quelle est la durée de vie d'un matelas DreamRest ?", a: "Nos matelas sont conçus pour durer entre 10 et 15 ans avec un entretien adapté. Chaque matelas est accompagné d'une garantie fabricant de 5 ans minimum." },
  { q: "Proposez-vous la livraison partout au Cameroun ?", a: "Oui ! Nous livrons dans toutes les grandes villes du Cameroun : Yaoundé, Douala, Bafoussam, Garoua, Bamenda, Kribi, Ngaoundéré et bien d'autres. Délai moyen : 24 à 72h." },
  { q: "Est-il possible d'essayer le matelas avant d'acheter ?", a: "Absolument. Notre showroom de Yaoundé vous accueille du lundi au samedi pour tester l'ensemble de notre gamme. Nos conseillers sont là pour vous guider." },
  { q: "Quels sont les modes de paiement acceptés ?", a: "Nous acceptons le paiement en espèces, par virement bancaire, Mobile Money (MTN & Orange Money) et par carte bancaire dans notre showroom." },
  { q: "Comment entretenir mon matelas DreamRest ?", a: "Retournez votre matelas tous les 3 à 6 mois, utilisez un protège-matelas respirant et aérez-le régulièrement. Évitez l'exposition directe au soleil prolongée." },
];

function AboutPage() {
  const { t, lang } = useLang();
  const heroRef = useRef(null);
  const { scrollYProgress } = useScroll({ target: heroRef, offset: ["start start", "end start"] });
  const heroY = useTransform(scrollYProgress, [0, 1], ["0%", "30%"]);
  const heroOpacity = useTransform(scrollYProgress, [0, 0.8], [1, 0]);

  const [hoveredValue, setHoveredValue] = useState<number | null>(null);
  const [activeTestimonial, setActiveTestimonial] = useState(0);
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const [likedTestimonials, setLikedTestimonials] = useState<Set<number>>(new Set());

  const toggleLike = (i: number) => {
    setLikedTestimonials(prev => {
      const next = new Set(prev);
      next.has(i) ? next.delete(i) : next.add(i);
      return next;
    });
  };

  const values = [
    { icon: Target, title: t("about.val.innovation"), desc: t("about.val.innovationDesc"), color: "from-blue-500 to-indigo-600", bg: "from-blue-500/10 to-indigo-500/10" },
    { icon: Heart,  title: t("about.val.wellness"),   desc: t("about.val.wellnessDesc"),   color: "from-rose-500 to-pink-600",   bg: "from-rose-500/10 to-pink-500/10" },
    { icon: Zap,    title: t("about.val.quality"),    desc: t("about.val.qualityDesc"),    color: "from-amber-500 to-orange-600", bg: "from-amber-500/10 to-orange-500/10" },
    { icon: Shield, title: t("about.val.warranty"),   desc: t("about.val.warrantyDesc"),   color: "from-emerald-500 to-teal-600", bg: "from-emerald-500/10 to-teal-500/10" },
  ];

  const stats = [
    { icon: Users,      value: 12000, suffix: "+",   label: "Clients satisfaits" },
    { icon: Award,      value: 8,     suffix: " ans", label: "D'expertise" },
    { icon: TrendingUp, value: 98,    suffix: "%",   label: "Taux de satisfaction" },
    { icon: Star,       value: 4,     suffix: ".9★", label: "Note moyenne" },
  ];

  const milestones = [
    { year: "2016", title: "Fondation", desc: "DreamRest naît d'une passion pour le bien-être et le sommeil de qualité." },
    { year: "2018", title: "Premier showroom", desc: "Ouverture de notre premier espace d'exposition à Yaoundé." },
    { year: "2020", title: "Expansion nationale", desc: "Déploiement dans les principales villes du Cameroun." },
    { year: "2023", title: "Innovation R&D", desc: "Lancement de notre gamme de matelas à mémoire de forme haut de gamme." },
    { year: "2025", title: "Leader du marché", desc: "DreamRest devient la référence du sommeil au Cameroun." },
  ];

  return (
    <>
      {/* ── Ambient BG ── */}
      <div className="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
        <div className="absolute top-0 left-1/4 w-[800px] h-[800px] rounded-full bg-primary/4 blur-[140px]" />
        <div className="absolute bottom-1/3 right-0 w-[600px] h-[600px] rounded-full bg-blue-400/4 blur-[120px]" />
      </div>

      {/* ══════════════════════════════════════════
          HERO
      ══════════════════════════════════════════ */}
      <section ref={heroRef} className="relative min-h-screen flex items-center overflow-hidden">
        <motion.div style={{ y: heroY }} className="absolute inset-0 -z-10">
          <img
            src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1600&h=900&fit=crop"
            alt="Atelier DreamRest"
            className="h-full w-full object-cover"
          />
          <div className="absolute inset-0 bg-gradient-to-r from-background via-background/80 to-background/30" />
          <div className="absolute inset-0 bg-gradient-to-t from-background via-transparent to-transparent" />
        </motion.div>

        <motion.div style={{ opacity: heroOpacity }} className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-32 w-full">
          <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.5 }}
            className="mb-6 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5 backdrop-blur-sm"
          >
            <Star className="h-3.5 w-3.5 text-primary fill-primary" />
            <span className="text-xs font-semibold uppercase tracking-widest text-primary">Notre histoire</span>
          </motion.div>

          <motion.h1
            initial={{ opacity: 0, y: 40 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8, ease: [0.22, 1, 0.36, 1] }}
            className="text-6xl font-black leading-[1.05] text-foreground sm:text-7xl lg:text-8xl"
          >
            {t("about.title.1")}{" "}
            <span className="relative">
              <span className="text-gradient">{t("about.title.2")}</span>
              <motion.span
                initial={{ scaleX: 0 }}
                animate={{ scaleX: 1 }}
                transition={{ delay: 0.7, duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
                className="absolute -bottom-2 left-0 right-0 h-1 origin-left rounded-full bg-gradient-blue opacity-70"
              />
            </span>
          </motion.h1>

          <motion.p
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.3, duration: 0.7 }}
            className="mt-8 max-w-xl text-xl leading-relaxed text-muted-foreground"
          >
            {t("about.intro")}
          </motion.p>

          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.5 }}
            className="mt-10"
          >
            <a href="#mission" className="group inline-flex items-center gap-2 rounded-xl bg-gradient-blue px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-primary/25 transition-all hover:shadow-primary/50 hover:scale-[1.03]">
              Découvrir notre mission
              <ChevronRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
            </a>
          </motion.div>
        </motion.div>

        <motion.div
          animate={{ y: [0, 10, 0] }}
          transition={{ duration: 2, repeat: Infinity }}
          className="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-muted-foreground/50"
        >
          <span className="text-[10px] font-medium uppercase tracking-widest">Scroll</span>
          <ArrowDown className="h-4 w-4" />
        </motion.div>
      </section>

      {/* ══════════════════════════════════════════
          STATS
      ══════════════════════════════════════════ */}
      <section className="py-20 border-y border-border/40 bg-card/40 backdrop-blur-sm">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 gap-8 lg:grid-cols-4">
            {stats.map((stat, i) => (
              <motion.div
                key={stat.label}
                initial={{ opacity: 0, y: 30 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: i * 0.1, duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
                className="group text-center"
              >
                <div className="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-primary/10 transition-all duration-300 group-hover:bg-gradient-blue group-hover:shadow-lg group-hover:shadow-primary/20">
                  <stat.icon className="h-5 w-5 text-primary transition-colors group-hover:text-white" />
                </div>
                <div className="text-4xl font-black text-foreground lg:text-5xl">
                  <Counter to={stat.value} suffix={stat.suffix} />
                </div>
                <p className="mt-1 text-sm text-muted-foreground">{stat.label}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ══════════════════════════════════════════
          MISSION + TIMELINE
      ══════════════════════════════════════════ */}
      <section id="mission" className="py-28">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div className="grid gap-16 lg:grid-cols-2 lg:items-center">
            <motion.div
              initial={{ opacity: 0, x: -40 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
            >
              <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5">
                <span className="h-1.5 w-1.5 rounded-full bg-primary" />
                <span className="text-xs font-semibold uppercase tracking-widest text-primary">Notre mission</span>
              </div>
              <h2 className="text-4xl font-black leading-tight text-foreground sm:text-5xl">{t("about.mission")}</h2>
              <p className="mt-6 text-lg leading-relaxed text-muted-foreground">{t("about.missionText")}</p>
              <div className="mt-8 space-y-3">
                {["Matériaux certifiés et durables", "Fabrication éco-responsable", "Service après-vente 7j/7"].map((item, i) => (
                  <motion.div
                    key={item}
                    initial={{ opacity: 0, x: -20 }}
                    whileInView={{ opacity: 1, x: 0 }}
                    viewport={{ once: true }}
                    transition={{ delay: 0.2 + i * 0.1 }}
                    className="flex items-center gap-3"
                  >
                    <div className="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/10">
                      <span className="text-xs text-emerald-500">✓</span>
                    </div>
                    <span className="text-sm font-medium text-foreground">{item}</span>
                  </motion.div>
                ))}
              </div>
            </motion.div>

            <motion.div
              initial={{ opacity: 0, x: 40 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
              className="relative"
            >
              <div className="absolute left-[22px] top-0 bottom-0 w-px bg-gradient-to-b from-primary/60 via-primary/20 to-transparent" />
              <div className="space-y-8">
                {milestones.map((m, i) => (
                  <motion.div
                    key={m.year}
                    initial={{ opacity: 0, x: 20 }}
                    whileInView={{ opacity: 1, x: 0 }}
                    viewport={{ once: true }}
                    transition={{ delay: i * 0.1, duration: 0.5 }}
                    className="group relative flex gap-6 pl-12"
                  >
                    <div className="absolute left-0 top-1 flex h-11 w-11 items-center justify-center">
                      <motion.div
                        whileHover={{ scale: 1.2 }}
                        className="h-4 w-4 rounded-full border-2 border-primary bg-background shadow-[0_0_12px_rgba(99,102,241,0.4)] transition-all group-hover:bg-primary"
                      />
                    </div>
                    <div className="rounded-2xl border border-border/60 bg-card/60 p-5 backdrop-blur-sm transition-all duration-300 group-hover:border-primary/30 group-hover:shadow-lg group-hover:shadow-primary/5 flex-1">
                      <span className="text-xs font-bold uppercase tracking-widest text-primary">{m.year}</span>
                      <h4 className="mt-0.5 font-bold text-foreground">{m.title}</h4>
                      <p className="mt-1 text-sm text-muted-foreground">{m.desc}</p>
                    </div>
                  </motion.div>
                ))}
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* ══════════════════════════════════════════
          VALUES
      ══════════════════════════════════════════ */}
      <section className="py-28 bg-secondary/50">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="mb-16 text-center"
          >
            <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5">
              <span className="h-1.5 w-1.5 rounded-full bg-primary" />
              <span className="text-xs font-semibold uppercase tracking-widest text-primary">Ce qui nous définit</span>
            </div>
            <h2 className="text-4xl font-black text-foreground sm:text-5xl">{t("about.values")}</h2>
          </motion.div>

          <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {values.map((v, i) => (
              <motion.div
                key={v.title}
                initial={{ opacity: 0, y: 40 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: i * 0.1, duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
                onHoverStart={() => setHoveredValue(i)}
                onHoverEnd={() => setHoveredValue(null)}
                whileHover={{ y: -10, transition: { duration: 0.3 } }}
                className="group relative overflow-hidden rounded-3xl border border-border/60 bg-card p-7 shadow-sm cursor-pointer"
              >
                <motion.div
                  animate={{ opacity: hoveredValue === i ? 1 : 0 }}
                  transition={{ duration: 0.3 }}
                  className={`absolute inset-0 bg-gradient-to-br ${v.bg}`}
                />
                <motion.div
                  animate={{ scale: hoveredValue === i ? 1 : 0, opacity: hoveredValue === i ? 0.15 : 0 }}
                  transition={{ duration: 0.4 }}
                  className={`absolute -top-8 -right-8 h-32 w-32 rounded-full bg-gradient-to-br ${v.color}`}
                />
                <div className="relative z-10">
                  <motion.div
                    animate={{ scale: hoveredValue === i ? 1.1 : 1 }}
                    transition={{ duration: 0.3 }}
                    className={`mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br ${v.bg} border border-white/10`}
                  >
                    <v.icon className="h-7 w-7 text-primary" />
                  </motion.div>
                  <h3 className="font-bold text-foreground text-lg">{v.title}</h3>
                  <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{v.desc}</p>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ══════════════════════════════════════════
          TESTIMONIALS — interactive carousel
      ══════════════════════════════════════════ */}
      <section className="py-28 overflow-hidden">
        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="mb-16 text-center"
          >
            <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5">
              <MessageSquare className="h-3.5 w-3.5 text-primary" />
              <span className="text-xs font-semibold uppercase tracking-widest text-primary">Ils nous font confiance</span>
            </div>
            <h2 className="text-4xl font-black text-foreground sm:text-5xl">Ce que disent nos clients</h2>
            <p className="mt-4 text-lg text-muted-foreground">Des Camerounais qui ont transformé leur sommeil</p>
          </motion.div>

          {/* Featured testimonial */}
          <div className="relative">
            <motion.div
              key={activeTestimonial}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -20 }}
              transition={{ duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
              className="mx-auto max-w-3xl"
            >
              <div className="relative rounded-3xl border border-border/60 bg-card/80 backdrop-blur-sm p-10 shadow-xl shadow-black/5 text-center">
                {/* Quote icon */}
                <div className="absolute -top-5 left-1/2 -translate-x-1/2 flex h-10 w-10 items-center justify-center rounded-full bg-gradient-blue shadow-lg shadow-primary/30">
                  <Quote className="h-4 w-4 text-white" />
                </div>

                {/* Stars */}
                <div className="mb-6 flex justify-center gap-1">
                  {Array.from({ length: testimonials[activeTestimonial].rating }).map((_, i) => (
                    <motion.div
                      key={i}
                      initial={{ opacity: 0, scale: 0 }}
                      animate={{ opacity: 1, scale: 1 }}
                      transition={{ delay: i * 0.08 }}
                    >
                      <Star className="h-5 w-5 fill-amber-400 text-amber-400" />
                    </motion.div>
                  ))}
                </div>

                <p className="text-xl leading-relaxed text-foreground font-medium">
                  "{testimonials[activeTestimonial].text}"
                </p>

                <div className="mt-8 flex items-center justify-center gap-4">
                  <div className={`flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br ${testimonials[activeTestimonial].color} text-white text-sm font-bold shadow-lg`}>
                    {testimonials[activeTestimonial].avatar}
                  </div>
                  <div className="text-left">
                    <div className="font-bold text-foreground">{testimonials[activeTestimonial].name}</div>
                    <div className="text-sm text-muted-foreground">{testimonials[activeTestimonial].city} · {testimonials[activeTestimonial].product}</div>
                  </div>
                  <button
                    onClick={() => toggleLike(activeTestimonial)}
                    className={`ml-4 flex items-center gap-1.5 rounded-xl border px-3 py-1.5 text-xs font-semibold transition-all duration-200 ${
                      likedTestimonials.has(activeTestimonial)
                        ? "border-rose-300 bg-rose-50 text-rose-500 dark:bg-rose-500/10 dark:border-rose-500/30"
                        : "border-border bg-background text-muted-foreground hover:border-rose-300 hover:text-rose-400"
                    }`}
                  >
                    <ThumbsUp className={`h-3.5 w-3.5 ${likedTestimonials.has(activeTestimonial) ? "fill-rose-500 text-rose-500" : ""}`} />
                    Utile
                  </button>
                </div>
              </div>
            </motion.div>

            {/* Thumbnail nav */}
            <div className="mt-8 flex flex-wrap justify-center gap-3">
              {testimonials.map((t, i) => (
                <motion.button
                  key={t.name}
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  onClick={() => setActiveTestimonial(i)}
                  className={`flex items-center gap-2.5 rounded-2xl border px-4 py-2.5 text-sm font-medium transition-all duration-300 ${
                    activeTestimonial === i
                      ? "border-primary/40 bg-primary/10 text-primary shadow-md shadow-primary/10"
                      : "border-border/60 bg-card/60 text-muted-foreground hover:border-primary/20 hover:text-foreground"
                  }`}
                >
                  <div className={`h-7 w-7 flex items-center justify-center rounded-full bg-gradient-to-br ${t.color} text-white text-xs font-bold`}>
                    {t.avatar}
                  </div>
                  <span>{t.name.split(" ")[0]}</span>
                  {likedTestimonials.has(i) && (
                    <ThumbsUp className="h-3 w-3 fill-rose-400 text-rose-400" />
                  )}
                </motion.button>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* ══════════════════════════════════════════
          FAQ — interactive accordion
      ══════════════════════════════════════════ */}
      <section className="py-28 bg-secondary/50">
        <div className="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="mb-16 text-center"
          >
            <div className="mb-4 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5">
              <span className="h-1.5 w-1.5 rounded-full bg-primary" />
              <span className="text-xs font-semibold uppercase tracking-widest text-primary">Questions fréquentes</span>
            </div>
            <h2 className="text-4xl font-black text-foreground sm:text-5xl">On répond à tout</h2>
            <p className="mt-4 text-muted-foreground">Tout ce que vous voulez savoir sur DreamRest</p>
          </motion.div>

          <div className="space-y-3">
            {faqs.map((faq, i) => (
              <motion.div
                key={i}
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ delay: i * 0.07 }}
                className={`overflow-hidden rounded-2xl border transition-all duration-300 ${
                  openFaq === i ? "border-primary/30 shadow-lg shadow-primary/5" : "border-border/60"
                } bg-card/80 backdrop-blur-sm`}
              >
                <button
                  onClick={() => setOpenFaq(openFaq === i ? null : i)}
                  className="flex w-full items-center justify-between gap-4 px-6 py-5 text-left"
                >
                  <span className="font-semibold text-foreground">{faq.q}</span>
                  <motion.div
                    animate={{ rotate: openFaq === i ? 45 : 0 }}
                    transition={{ duration: 0.25 }}
                    className={`flex-shrink-0 flex h-7 w-7 items-center justify-center rounded-full transition-colors ${
                      openFaq === i ? "bg-primary text-white" : "bg-primary/10 text-primary"
                    }`}
                  >
                    <span className="text-lg font-light leading-none">+</span>
                  </motion.div>
                </button>

                <motion.div
                  initial={false}
                  animate={{ height: openFaq === i ? "auto" : 0, opacity: openFaq === i ? 1 : 0 }}
                  transition={{ duration: 0.35, ease: [0.22, 1, 0.36, 1] }}
                  style={{ overflow: "hidden" }}
                >
                  <div className="px-6 pb-5">
                    <div className="h-px w-full bg-border/60 mb-4" />
                    <p className="text-sm leading-relaxed text-muted-foreground">{faq.a}</p>
                  </div>
                </motion.div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      {/* ══════════════════════════════════════════
          CTA
      ══════════════════════════════════════════ */}
      <section className="py-20">
        <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.7 }}
            className="relative overflow-hidden rounded-3xl bg-gradient-blue p-12 text-center shadow-2xl shadow-primary/25"
          >
            <div className="absolute -top-12 -right-12 h-40 w-40 rounded-full bg-white/10 blur-2xl" />
            <div className="absolute -bottom-8 -left-8 h-32 w-32 rounded-full bg-white/10 blur-xl" />
            <div className="relative z-10">
              <h2 className="text-3xl font-black text-white sm:text-4xl">Prêt à mieux dormir ?</h2>
              <p className="mt-4 text-lg text-white/80">Découvrez notre gamme de matelas premium conçus pour le Cameroun.</p>
              <div className="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">
                <motion.a
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.97 }}
                  href="/products"
                  className="group inline-flex items-center gap-2 rounded-xl bg-white px-8 py-3.5 text-sm font-bold text-primary shadow-lg"
                >
                  Voir nos matelas
                  <ChevronRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                </motion.a>
                <motion.a
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.97 }}
                  href="/contact"
                  className="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-8 py-3.5 text-sm font-bold text-white backdrop-blur-sm hover:bg-white/20"
                >
                  Nous contacter
                </motion.a>
              </div>
            </div>
          </motion.div>
        </div>
      </section>

      <Footer />
    </>
  );
}