import { createFileRoute } from "@tanstack/react-router";
import { motion } from "framer-motion";
import { Mail, Phone, MapPin, MessageCircle, Send, Star } from "lucide-react";
import { useState } from "react";
import Footer from "@/components/layout/Footer";

export const Route = createFileRoute("/contact")({
  head: () => ({
    meta: [
      { title: "Contact — DreamRest" },
      { name: "description", content: "Contactez DreamRest pour toute question sur nos matelas." },
      { property: "og:title", content: "Contact — DreamRest" },
      { property: "og:description", content: "Notre équipe est à votre écoute." },
    ],
  }),
  component: ContactPage,
});

function ContactPage() {
  const [form, setForm] = useState({ name: "", email: "", phone: "", message: "" });
  const [sent, setSent] = useState(false);
  const [focused, setFocused] = useState<string | null>(null);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setSent(true);
  };

  const inputClass = (field: string) =>
    `w-full rounded-xl border px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground/50 bg-background/60 backdrop-blur-sm transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary/60 ${
      focused === field ? "border-primary/60 shadow-[0_0_20px_rgba(99,102,241,0.12)]" : "border-border/60"
    }`;

  const contactItems = [
    { icon: Mail, label: "Email", value: "contact@dreamrest.cm", href: "mailto:contact@dreamrest.cm", color: "from-blue-500/20 to-indigo-500/20" },
    { icon: Phone, label: "Téléphone", value: "+237 6 00 00 00 00", href: "tel:+237600000000", color: "from-sky-500/20 to-blue-500/20" },
    { icon: MessageCircle, label: "WhatsApp", value: "Discutez avec nous", href: "https://wa.me/237600000000", color: "from-emerald-500/20 to-teal-500/20" },
    { icon: MapPin, label: "Showroom", value: "Avenue Kennedy, Yaoundé, Cameroun", href: "#", color: "from-violet-500/20 to-purple-500/20" },
  ];

  return (
    <>
      {/* Ambient background */}
      <div className="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div className="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-primary/5 blur-[120px]" />
        <div className="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-blue-400/5 blur-[100px]" />
        <div className="absolute top-[40%] left-[50%] w-[300px] h-[300px] rounded-full bg-indigo-400/5 blur-[80px]" />
      </div>

      <div className="mx-auto max-w-7xl px-4 pt-28 pb-20 sm:px-6 lg:px-8">

        {/* Hero header */}
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
          className="relative mb-20"
        >
          <motion.div
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ delay: 0.2, duration: 0.5 }}
            className="mb-5 inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/5 px-4 py-1.5"
          >
            <Star className="h-3.5 w-3.5 text-primary fill-primary" />
            <span className="text-xs font-medium tracking-wide text-primary uppercase">Support premium</span>
          </motion.div>

          <h1 className="text-5xl font-bold leading-[1.1] text-foreground sm:text-6xl lg:text-7xl">
            Restons en{" "}
            <span className="relative inline-block">
              <span className="text-gradient">contact</span>
              <motion.span
                initial={{ scaleX: 0 }}
                animate={{ scaleX: 1 }}
                transition={{ delay: 0.5, duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
                className="absolute -bottom-1 left-0 right-0 h-[3px] origin-left rounded-full bg-gradient-blue opacity-60"
              />
            </span>
          </h1>
          <p className="mt-5 max-w-lg text-lg text-muted-foreground leading-relaxed">
            Une question sur nos matelas ? Notre équipe d'experts vous répond{" "}
            <span className="font-medium text-foreground">sous 24h</span>, 7j/7.
          </p>
        </motion.div>

        <div className="grid gap-10 lg:grid-cols-5 lg:gap-14">

          {/* ── Form ── */}
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.25, duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
            className="lg:col-span-3"
          >
            {sent ? (
              <motion.div
                initial={{ opacity: 0, scale: 0.95 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
                className="flex h-full flex-col items-center justify-center rounded-3xl border border-border/60 bg-card/80 backdrop-blur-sm p-14 text-center shadow-xl shadow-black/5"
              >
                <motion.div
                  initial={{ scale: 0 }}
                  animate={{ scale: 1 }}
                  transition={{ delay: 0.1, type: "spring", stiffness: 200 }}
                  className="relative mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-blue shadow-lg shadow-primary/30"
                >
                  <Send className="h-9 w-9 text-white" />
                  <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-400 shadow-md">
                    <span className="text-[10px] text-white font-bold">✓</span>
                  </span>
                </motion.div>
                <h3 className="mt-6 text-2xl font-bold text-foreground">Message envoyé !</h3>
                <p className="mt-2 text-muted-foreground">Nous vous répondrons dans les plus brefs délais.</p>
                <button
                  onClick={() => setSent(false)}
                  className="mt-8 inline-flex items-center gap-2 rounded-xl bg-gradient-blue px-7 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/20 transition-all hover:shadow-primary/40 hover:scale-[1.03] active:scale-[0.98]"
                >
                  Envoyer un autre message
                </button>
              </motion.div>
            ) : (
              <form
                onSubmit={handleSubmit}
                className="rounded-3xl border border-border/60 bg-card/80 backdrop-blur-sm p-8 shadow-xl shadow-black/5 sm:p-10"
              >
                <div className="mb-8 flex items-center gap-3">
                  <div className="h-10 w-1 rounded-full bg-gradient-blue" />
                  <div>
                    <p className="text-xs font-medium uppercase tracking-widest text-muted-foreground">Formulaire</p>
                    <h2 className="text-lg font-bold text-foreground">Envoyez-nous un message</h2>
                  </div>
                </div>

                <div className="space-y-5">
                  <div className="grid gap-5 sm:grid-cols-2">
                    <div>
                      <label className="mb-2 block text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                        Nom complet
                      </label>
                      <input
                        required
                        value={form.name}
                        onFocus={() => setFocused("name")}
                        onBlur={() => setFocused(null)}
                        onChange={(e) => setForm({ ...form, name: e.target.value })}
                        className={inputClass("name")}
                        placeholder="Jean Dupont"
                      />
                    </div>
                    <div>
                      <label className="mb-2 block text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                        Téléphone
                      </label>
                      <input
                        value={form.phone}
                        onFocus={() => setFocused("phone")}
                        onBlur={() => setFocused(null)}
                        onChange={(e) => setForm({ ...form, phone: e.target.value })}
                        className={inputClass("phone")}
                        placeholder="+237 6 00 00 00 00"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="mb-2 block text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                      Adresse email
                    </label>
                    <input
                      required
                      type="email"
                      value={form.email}
                      onFocus={() => setFocused("email")}
                      onBlur={() => setFocused(null)}
                      onChange={(e) => setForm({ ...form, email: e.target.value })}
                      className={inputClass("email")}
                      placeholder="vous@email.cm"
                    />
                  </div>

                  <div>
                    <label className="mb-2 block text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                      Message
                    </label>
                    <textarea
                      required
                      rows={5}
                      value={form.message}
                      onFocus={() => setFocused("message")}
                      onBlur={() => setFocused(null)}
                      onChange={(e) => setForm({ ...form, message: e.target.value })}
                      className={`${inputClass("message")} resize-none`}
                      placeholder="Comment pouvons-nous vous aider ?"
                    />
                  </div>

                  <motion.button
                    whileHover={{ scale: 1.02 }}
                    whileTap={{ scale: 0.98 }}
                    type="submit"
                    className="group relative w-full overflow-hidden rounded-xl bg-gradient-blue py-3.5 text-sm font-semibold text-white shadow-lg shadow-primary/25 transition-shadow hover:shadow-primary/40"
                  >
                    <span className="relative z-10 flex items-center justify-center gap-2">
                      Envoyer le message
                      <Send className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </span>
                    <span className="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/10 to-transparent transition-transform duration-700 group-hover:translate-x-full" />
                  </motion.button>
                </div>
              </form>
            )}
          </motion.div>

          {/* ── Right column ── */}
          <motion.div
            initial={{ opacity: 0, y: 24 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.4, duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
            className="lg:col-span-2 flex flex-col gap-4"
          >
            {contactItems.map((item, i) => (
              <motion.a
                key={item.label}
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ delay: 0.45 + i * 0.08, duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
                whileHover={{ x: 5, transition: { duration: 0.2 } }}
                href={item.href}
                target={item.href.startsWith("http") ? "_blank" : undefined}
                rel="noopener noreferrer"
                className="group flex items-center gap-4 rounded-2xl border border-border/60 bg-card/80 backdrop-blur-sm p-4 shadow-sm transition-all duration-300 hover:border-primary/30 hover:shadow-md hover:shadow-primary/5"
              >
                <div className={`flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br ${item.color} border border-white/10`}>
                  <item.icon className="h-5 w-5 text-primary" />
                </div>
                <div className="min-w-0 flex-1">
                  <div className="text-xs font-semibold uppercase tracking-wider text-muted-foreground">{item.label}</div>
                  <div className="mt-0.5 truncate text-sm font-medium text-foreground">{item.value}</div>
                </div>
                <div className="flex-shrink-0 opacity-0 transition-opacity group-hover:opacity-100">
                  <div className="flex h-7 w-7 items-center justify-center rounded-lg bg-primary/10">
                    <span className="text-xs text-primary">→</span>
                  </div>
                </div>
              </motion.a>
            ))}

            {/* Map placeholder */}
            <motion.div
              initial={{ opacity: 0, y: 12 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ delay: 0.75, duration: 0.5 }}
              className="relative mt-2 flex-1 overflow-hidden rounded-2xl border border-border/60"
              style={{ minHeight: "180px" }}
            >
              <div className="absolute inset-0 bg-gradient-to-br from-primary/5 via-background to-blue-400/5">
                <svg className="absolute inset-0 h-full w-full opacity-20" xmlns="http://www.w3.org/2000/svg">
                  <defs>
                    <pattern id="grid" width="28" height="28" patternUnits="userSpaceOnUse">
                      <path d="M 28 0 L 0 0 0 28" fill="none" stroke="currentColor" strokeWidth="0.5" className="text-primary" />
                    </pattern>
                  </defs>
                  <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>

                <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                  <motion.div
                    animate={{ scale: [1, 1.15, 1], opacity: [0.4, 0.15, 0.4] }}
                    transition={{ duration: 3, repeat: Infinity, ease: "easeInOut" }}
                    className="absolute -inset-10 rounded-full border border-primary/30"
                  />
                  <motion.div
                    animate={{ scale: [1, 1.2, 1], opacity: [0.3, 0.1, 0.3] }}
                    transition={{ duration: 3, repeat: Infinity, ease: "easeInOut", delay: 0.5 }}
                    className="absolute -inset-16 rounded-full border border-primary/20"
                  />
                  <div className="relative flex h-10 w-10 items-center justify-center rounded-full bg-gradient-blue shadow-lg shadow-primary/40">
                    <MapPin className="h-5 w-5 text-white" />
                  </div>
                </div>
              </div>

              <div className="absolute bottom-4 left-0 right-0 text-center">
                <p className="text-xs font-medium text-foreground">Notre showroom</p>
                <p className="text-xs text-muted-foreground">Avenue Kennedy · Yaoundé, Cameroun</p>
              </div>
            </motion.div>

            {/* Response time badge */}
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ delay: 0.9 }}
              className="flex items-center gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/5 px-4 py-3"
            >
              <span className="relative flex h-2.5 w-2.5">
                <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75" />
                <span className="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500" />
              </span>
              <p className="text-xs font-medium text-emerald-700 dark:text-emerald-400">
                Temps de réponse moyen : <span className="font-bold">moins de 2h</span>
              </p>
            </motion.div>
          </motion.div>
        </div>
      </div>
      <Footer />
    </>
  );
}