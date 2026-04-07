import { useState, useMemo } from "react";
import { motion } from "framer-motion";
import { z } from "zod";
import { useLang } from "@/lib/i18n";
import type { Product } from "@/lib/api";
import { COUNTRIES, findCountry } from "@/lib/countries";
import { api } from "@/lib/api";

const WHATSAPP_NUMBER = "237674435332";

function WhatsAppIcon({ className }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" fill="currentColor" className={className} aria-hidden="true">
      <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.198-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c0-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
    </svg>
  );
}

const formatFCFA = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XAF',
    maximumFractionDigits: 0
  }).format(price);
};

const schema = z.object({
  firstName: z.string().trim().min(1, "Prénom requis").max(60),
  lastName: z.string().trim().min(1, "Nom requis").max(60),
  country: z.string().trim().min(1, "Pays requis"),
  town: z.string().trim().min(1, "Ville requise").max(100),
  address: z.string().trim().min(5, "Adresse requise").max(200),
  phone: z.string().trim().min(4, "Téléphone requis").max(25).regex(/^[\d\s().-]+$/, "Format invalide"),
  email: z.string().trim().email("Email invalide").max(120),
});

interface Props {
  product: Product;
}

export default function ProductInquiryForm({ product }: Props) {
  const { lang } = useLang();
  const [form, setForm] = useState({
    firstName: "",
    lastName: "",
    country: "Cameroun",
    town: "",
    address: "",
    phone: "",
    email: ""
  });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [loading, setLoading] = useState(false);

  // ✅ CHANGEMENT 1 : Récupérer l'URL de l'API depuis les variables d'environnement
  const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:5000/api';

  const dial = useMemo(() => findCountry(form.country)?.dial ?? "+237", [form.country]);

  const update = (k: keyof typeof form) => (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setForm((f) => ({ ...f, [k]: e.target.value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    const parsed = schema.safeParse(form);
    if (!parsed.success) {
      const errs: Record<string, string> = {};
      parsed.error.issues.forEach((i) => { errs[String(i.path[0])] = i.message; });
      setErrors(errs);
      return;
    }
    
    setErrors({});
    setLoading(true);

    try {
      // 1. Sauvegarder la commande dans la base de données
      // Note: api.createWhatsAppInquiry utilise déjà la variable d'environnement
      // car la fonction api est configurée avec VITE_API_URL
      const response = await api.createWhatsAppInquiry({
        name: parsed.data.firstName,
        surname: parsed.data.lastName,
        email: parsed.data.email,
        phone_number: parsed.data.phone,
        country_code: dial,
        country: parsed.data.country,
        town: parsed.data.town,
        address: parsed.data.address,
        product_id: product.id
      });

      console.log('Commande sauvegardée:', response.inquiry);

      // 2. Préparer le message WhatsApp
      const productName = product.name;
      const fullPhone = `${dial} ${parsed.data.phone}`.trim();
      
      const msg = `🛒 *NOUVELLE COMMANDE*\n\n` +
        `*Produit:* ${productName}\n` +
        `*Réf:* ${product.id.slice(0, 8)}\n` +
        `*Prix:* ${formatFCFA(product.sold_price || product.price)}\n\n` +
        `*Client:* ${parsed.data.firstName} ${parsed.data.lastName}\n` +
        `*Email:* ${parsed.data.email}\n` +
        `*Téléphone:* ${fullPhone}\n` +
        `*Pays:* ${parsed.data.country}\n` +
        `*Ville:* ${parsed.data.town}\n` +
        `*Adresse:* ${parsed.data.address}`;

      // 3. Ouvrir WhatsApp
      window.open(`https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(msg)}`, "_blank", "noopener,noreferrer");
      
    } catch (err: any) {
      console.error('Erreur sauvegarde commande:', err);
      alert('Erreur lors de l\'enregistrement de la commande. Veuillez réessayer.');
    } finally {
      setLoading(false);
    }
  };

  const labels = lang === "fr"
    ? { 
        title: "Commander ce produit", 
        sub: "Remplissez vos coordonnées — nous vous contactons sur WhatsApp.", 
        first: "Prénom", 
        last: "Nom", 
        country: "Pays",
        town: "Ville",
        address: "Adresse complète",
        phone: "Numéro", 
        email: "Email", 
        send: "Commander sur WhatsApp" 
      }
    : { 
        title: "Order this product", 
        sub: "Fill your details — we'll reach out on WhatsApp.", 
        first: "First name", 
        last: "Last name", 
        country: "Country",
        town: "City",
        address: "Full address",
        phone: "Number", 
        email: "Email", 
        send: "Order on WhatsApp" 
      };

  const inputCls = "w-full rounded-lg border border-input bg-background px-3 py-2.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring transition-colors";

  return (
    <motion.form
      onSubmit={handleSubmit}
      initial={{ opacity: 0, y: 16 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="rounded-2xl border border-border bg-card p-5 shadow-sm"
    >
      <h3 className="text-base font-semibold text-foreground">{labels.title}</h3>
      <p className="mt-1 text-xs text-muted-foreground">{labels.sub}</p>

      <div className="mt-4 grid gap-3 sm:grid-cols-2">
        <div>
          <input className={inputCls} placeholder={labels.first} value={form.firstName} onChange={update("firstName")} disabled={loading} />
          {errors.firstName && <p className="mt-1 text-xs text-destructive">{errors.firstName}</p>}
        </div>
        <div>
          <input className={inputCls} placeholder={labels.last} value={form.lastName} onChange={update("lastName")} disabled={loading} />
          {errors.lastName && <p className="mt-1 text-xs text-destructive">{errors.lastName}</p>}
        </div>
        <div>
          <select className={inputCls} value={form.country} onChange={update("country")} disabled={loading}>
            {COUNTRIES.map((c) => (
              <option key={c.code} value={c.name}>{c.flag} {c.name} ({c.dial})</option>
            ))}
          </select>
        </div>
        <div>
          <input className={inputCls} placeholder={labels.town} value={form.town} onChange={update("town")} disabled={loading} />
          {errors.town && <p className="mt-1 text-xs text-destructive">{errors.town}</p>}
        </div>
        <div className="sm:col-span-2">
          <input className={inputCls} placeholder={labels.address} value={form.address} onChange={update("address")} disabled={loading} />
          {errors.address && <p className="mt-1 text-xs text-destructive">{errors.address}</p>}
        </div>
        <div>
          <div className="flex">
            <span className="inline-flex select-none items-center rounded-l-lg border border-r-0 border-input bg-secondary/60 px-3 text-sm font-medium text-foreground">
              {dial}
            </span>
            <input
              className={`${inputCls} rounded-l-none`}
              placeholder={labels.phone}
              value={form.phone}
              onChange={update("phone")}
              inputMode="tel"
              disabled={loading}
            />
          </div>
          {errors.phone && <p className="mt-1 text-xs text-destructive">{errors.phone}</p>}
        </div>
        <div>
          <input className={inputCls} placeholder={labels.email} value={form.email} onChange={update("email")} type="email" disabled={loading} />
          {errors.email && <p className="mt-1 text-xs text-destructive">{errors.email}</p>}
        </div>
      </div>

      <motion.button
        type="submit"
        whileHover={{ scale: loading ? 1 : 1.02 }}
        whileTap={{ scale: loading ? 1 : 0.98 }}
        disabled={loading}
        className="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#25D366] py-3 text-sm font-semibold text-white shadow-lg shadow-[#25D366]/25 transition-shadow hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
      >
        {loading ? (
          <>
            <svg className="h-5 w-5 animate-spin" viewBox="0 0 24 24">
              <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
              <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            Enregistrement...
          </>
        ) : (
          <>
            <WhatsAppIcon className="h-5 w-5" />
            {labels.send}
          </>
        )}
      </motion.button>

      <p className="mt-3 text-center text-[11px] text-muted-foreground">
        🔒 {lang === "fr" ? "Vos informations restent confidentielles." : "Your information stays confidential."}
      </p>
    </motion.form>
  );
}