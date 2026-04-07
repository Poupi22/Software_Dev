import { motion } from "framer-motion";
import { Link } from "@tanstack/react-router";
import { ArrowRight, Sparkles, Star } from "lucide-react";
import { useLang } from "@/lib/i18n";

export default function HeroSection() {
  const { t } = useLang();
  return (
    <section className="relative overflow-hidden bg-background pt-24 pb-16 lg:pt-32 lg:pb-24">
      <div className="pointer-events-none absolute inset-0 overflow-hidden">
        <div className="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-primary opacity-[0.12] blur-3xl animate-blob" />
        <div className="absolute top-1/3 -right-32 h-96 w-96 rounded-full bg-chart-2 opacity-[0.15] blur-3xl animate-blob" style={{ animationDelay: "5s" }} />
        <div className="absolute -bottom-32 left-1/3 h-80 w-80 rounded-full bg-chart-4 opacity-[0.10] blur-3xl animate-blob" style={{ animationDelay: "10s" }} />
      </div>

      <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div className="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7 }}
          >
            <motion.div
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ delay: 0.2 }}
              className="mb-6 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5 text-sm text-primary"
            >
              <Sparkles className="h-3.5 w-3.5" />
              {t("hero.badge")}
            </motion.div>

            <h1 className="text-4xl font-bold leading-[1.1] tracking-tight text-foreground sm:text-5xl lg:text-6xl">
              {t("hero.title.1")}{" "}<span className="text-gradient">{t("hero.title.2")}</span>
            </h1>

            <p className="mt-6 max-w-lg text-lg leading-relaxed text-muted-foreground">{t("hero.subtitle")}</p>

            <div className="mt-6 flex items-center gap-4">
              <div className="flex">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className="h-4 w-4 fill-warning text-warning" />
                ))}
              </div>
              <span className="text-sm text-muted-foreground"><strong className="text-foreground">4.9/5</strong> · 25 000 {t("hero.rating")}</span>
            </div>

            <div className="mt-8 flex flex-wrap gap-4">
              <Link
                to="/shop"
                className="inline-flex items-center gap-2 rounded-xl bg-gradient-blue px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-primary/30 transition-all hover:scale-105"
              >
                {t("hero.cta.shop")} <ArrowRight className="h-4 w-4" />
              </Link>
              <Link
                to="/categories"
                className="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-6 py-3 text-sm font-semibold text-foreground shadow-sm transition-all hover:bg-accent"
              >
                <Sparkles className="h-4 w-4" /> {t("hero.cta.explore")}
              </Link>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.8, delay: 0.3 }}
            className="relative"
          >
            <motion.div
              animate={{ y: [0, -15, 0] }}
              transition={{ duration: 6, repeat: Infinity, ease: "easeInOut" }}
              className="overflow-hidden rounded-3xl shadow-2xl shadow-primary/20"
            >
              <img
                src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=1200&h=900&fit=crop"
                alt="Matelas premium DreamRest"
                width={1200}
                height={900}
                className="h-auto w-full object-cover"
              />
            </motion.div>
            <motion.div
              animate={{ y: [0, -8, 0] }}
              transition={{ duration: 3, repeat: Infinity, ease: "easeInOut" }}
              className="absolute -bottom-4 -left-4 rounded-2xl border border-border bg-card p-4 shadow-xl"
            >
              <div className="text-xs font-medium text-muted-foreground">{t("hero.delivery")}</div>
              <div className="text-2xl font-bold text-foreground">48h</div>
              <div className="text-xs text-success">↑ {t("hero.everywhere")}</div>
            </motion.div>
            <motion.div
              animate={{ y: [0, 10, 0] }}
              transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
              className="absolute -top-4 -right-4 rounded-2xl border border-border bg-card p-4 shadow-xl"
            >
              <div className="text-xs font-medium text-muted-foreground">{t("hero.trial")}</div>
              <div className="text-2xl font-bold text-primary">{t("hero.nights")}</div>
            </motion.div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
