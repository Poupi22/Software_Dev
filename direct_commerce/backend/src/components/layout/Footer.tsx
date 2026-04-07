import { Link } from "@tanstack/react-router";
import { Mail, Phone, MapPin, Loader2, CheckCircle2 } from "lucide-react";
import { useState } from "react";
import { useLang } from "@/lib/i18n";
import logoEtcg from "@/assets/logo-etcg.png";

const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

export default function Footer() {
  const { t } = useLang();
  const [email, setEmail] = useState("");
  const [loading, setLoading] = useState(false);
  const [status, setStatus] = useState<"idle" | "success" | "error">("idle");
  const [message, setMessage] = useState("");

  const handleSubscribe = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!email) return;
    
    setLoading(true);
    setStatus("idle");
    setMessage("");
    
    try {
      const response = await fetch(`${API_URL}/newsletter/subscribe`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ email }),
      });
      
      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.message || "Erreur lors de l'inscription");
      }
      
      setStatus("success");
      setMessage(data.message || "Inscription réussie ! Vérifiez votre email pour confirmer.");
      setEmail("");
    } catch (err: any) {
      setStatus("error");
      setMessage(err.message || "Une erreur est survenue");
    } finally {
      setLoading(false);
    }
  };

  return (
    <footer className="border-t border-border bg-card pb-24 md:pb-0">
      <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div className="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
          <div>
            <div className="flex items-center gap-2.5">
              <img src={logoEtcg} alt="ETCG" className="h-10 w-10 object-contain" />
              <span className="text-lg font-bold text-foreground">E.T.C.G</span>
            </div>
            <p className="mt-4 text-sm leading-relaxed text-muted-foreground">{t("footer.tagline")}</p>
            <div className="mt-6 space-y-3">
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <Mail className="h-4 w-4" />
                <span>nounkouaalexsedard@gmail.com</span>
              </div>
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <Phone className="h-4 w-4" />
                <span>+237 674435332</span>
              </div>
              <div className="flex items-center gap-2 text-sm text-muted-foreground">
                <MapPin className="h-4 w-4" />
                <span>Doual, Cameroun</span>
              </div>
            </div>
          </div>

          <div>
            <h3 className="text-sm font-semibold text-foreground">{t("footer.mattresses")}</h3>
            <ul className="mt-4 space-y-3">
              {["Mémoire de forme", "Ressorts ensachés", "Latex naturel", "Hybride premium"].map((item) => (
                <li key={item}>
                  <Link to="/shop" className="text-sm text-muted-foreground transition-colors hover:text-primary">{item}</Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="text-sm font-semibold text-foreground">{t("footer.company")}</h3>
            <ul className="mt-4 space-y-3">
              {[
                { label: t("nav.about"), to: "/about" as const },
                { label: t("nav.contact"), to: "/contact" as const },
                { label: t("footer.careers"), to: "/about" as const },
                { label: t("footer.blog"), to: "/about" as const },
              ].map((item) => (
                <li key={item.label}>
                  <Link to={item.to} className="text-sm text-muted-foreground transition-colors hover:text-primary">{item.label}</Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="text-sm font-semibold text-foreground">{t("footer.newsletter")}</h3>
            <p className="mt-4 text-sm text-muted-foreground">{t("footer.newsletterDesc")}</p>
            <form onSubmit={handleSubscribe} className="mt-4">
              {status === "success" ? (
                <div className="flex items-center gap-2 rounded-lg bg-success/10 p-3">
                  <CheckCircle2 className="h-5 w-5 text-success" />
                  <div>
                    <p className="text-sm font-medium text-success">{t("footer.subscribed")}</p>
                    <p className="text-xs text-muted-foreground">{message}</p>
                  </div>
                </div>
              ) : (
                <>
                  <div className="flex gap-2">
                    <input
                      type="email"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      placeholder="email@example.com"
                      className="flex-1 rounded-lg border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
                      required
                      disabled={loading}
                    />
                    <button 
                      type="submit" 
                      disabled={loading}
                      className="rounded-lg bg-gradient-brand px-4 py-2 text-sm font-medium text-white transition-transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      {loading ? <Loader2 className="h-4 w-4 animate-spin" /> : "OK"}
                    </button>
                  </div>
                  {status === "error" && (
                    <p className="mt-2 text-xs text-destructive">{message}</p>
                  )}
                </>
              )}
            </form>
          </div>
        </div>

        <div className="mt-12 border-t border-border pt-8 text-center text-sm text-muted-foreground">
          © 2026 E.T.C.G — {t("footer.rights")}
        </div>
      </div>
    </footer>
  );
}