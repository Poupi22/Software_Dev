import { createContext, useContext, useEffect, useState, type ReactNode } from "react";

export type Lang = "fr" | "en";

type Dict = Record<string, { fr: string; en: string }>;

const dict: Dict = {
  // Nav
  "nav.home":        { fr: "Accueil",     en: "Home" },
  "nav.shop":        { fr: "Boutique",    en: "Shop" },
  "nav.categories":  { fr: "Catégories",  en: "Categories" },
  "nav.about":       { fr: "À Propos",    en: "About" },
  "nav.contact":     { fr: "Contact",     en: "Contact" },
  "nav.login":       { fr: "Connexion",   en: "Sign in" },
  "nav.account":     { fr: "Compte",      en: "Account" },
  "nav.search.placeholder": { fr: "Rechercher un matelas...", en: "Search a mattress..." },

  // Hero
  "hero.badge":      { fr: "Nouvelle collection 2026", en: "New 2026 collection" },
  "hero.title.1":    { fr: "Le sommeil que vous",      en: "The sleep you" },
  "hero.title.2":    { fr: "méritez",                   en: "deserve" },
  "hero.subtitle":   { fr: "Découvrez nos matelas premium fabriqués avec soin. Mémoire de forme, ressorts ensachés, latex naturel — votre confort, notre obsession.", en: "Discover our premium mattresses crafted with care. Memory foam, pocket springs, natural latex — your comfort, our obsession." },
  "hero.rating":     { fr: "clients",                   en: "clients" },
  "hero.cta.shop":   { fr: "Voir nos matelas",         en: "Browse mattresses" },
  "hero.cta.explore":{ fr: "Explorer",                  en: "Explore" },
  "hero.delivery":   { fr: "Livraison express",        en: "Express delivery" },
  "hero.trial":      { fr: "Essai gratuit",             en: "Free trial" },
  "hero.nights":     { fr: "100 nuits",                 en: "100 nights" },
  "hero.everywhere": { fr: "Partout en Afrique",       en: "Across Africa" },

  // Sections
  "feat.title":      { fr: "Matelas Vedettes",          en: "Featured Mattresses" },
  "feat.subtitle":   { fr: "Nos modèles les plus appréciés", en: "Our most loved models" },
  "feat.viewAll":    { fr: "Voir tout",                  en: "View all" },
  "cat.title":       { fr: "Trouvez votre matelas idéal", en: "Find your ideal mattress" },
  "cat.subtitle":    { fr: "Explorez nos différentes gammes", en: "Explore our ranges" },
  "cat.models":      { fr: "modèles",                    en: "models" },

  // Shop
  "shop.title":      { fr: "Nos Matelas",               en: "Our Mattresses" },
  "shop.subtitle":   { fr: "Trouvez le matelas parfait pour vos nuits", en: "Find the perfect mattress for your nights" },
  "shop.empty":      { fr: "Aucun matelas ne correspond à votre recherche.", en: "No mattress matches your search." },
  "shop.filters":    { fr: "Filtres",                    en: "Filters" },
  "shop.category":   { fr: "Catégorie",                  en: "Category" },
  "shop.allCategories": { fr: "Toutes les catégories",  en: "All categories" },
  "shop.availability":{ fr: "Disponibilité",            en: "Availability" },
  "shop.onlyAvailable":{ fr: "Uniquement disponibles",  en: "Available only" },

  // Product
  "prod.inStock":    { fr: "En stock",                   en: "In stock" },
  "prod.soon":       { fr: "Bientôt disponible",        en: "Coming soon" },
  "prod.features":   { fr: "Caractéristiques",          en: "Features" },
  "prod.specs":      { fr: "Spécifications techniques", en: "Technical specs" },
  "prod.back":       { fr: "Retour à la boutique",      en: "Back to shop" },
  "prod.notFound":   { fr: "Matelas introuvable",       en: "Mattress not found" },

  // About
  "about.title.1":   { fr: "L'art du",                   en: "The art of" },
  "about.title.2":   { fr: "sommeil parfait",           en: "perfect sleep" },
  "about.intro":     { fr: "Fondée en 2015, DreamRest est née d'une conviction simple : un matelas de qualité change une vie. Nous concevons des matelas premium accessibles à tous.", en: "Founded in 2015, DreamRest was born from a simple conviction: a quality mattress changes a life. We craft premium mattresses accessible to all." },
  "about.mission":   { fr: "Notre Mission",              en: "Our Mission" },
  "about.missionText":{ fr: "Démocratiser l'excellence du sommeil en proposant des matelas haut de gamme, fabriqués avec des matériaux nobles, à des prix justes.", en: "Democratize sleep excellence by offering premium mattresses, made with noble materials, at fair prices." },
  "about.values":    { fr: "Nos Valeurs",                en: "Our Values" },
  "about.team":      { fr: "Notre Équipe",              en: "Our Team" },
  "about.teamSubtitle":{ fr: "Les artisans de votre sommeil", en: "The craftsmen of your sleep" },
  "about.val.innovation":{ fr: "Innovation",            en: "Innovation" },
  "about.val.innovationDesc":{ fr: "Des technologies de pointe au service de votre sommeil.", en: "Cutting-edge technology for your sleep." },
  "about.val.wellness":{ fr: "Bien-être",                en: "Wellness" },
  "about.val.wellnessDesc":{ fr: "Votre confort est notre priorité absolue.", en: "Your comfort is our absolute priority." },
  "about.val.quality":{ fr: "Qualité",                  en: "Quality" },
  "about.val.qualityDesc":{ fr: "Fabrication soignée, matériaux premium.", en: "Careful manufacturing, premium materials." },
  "about.val.warranty":{ fr: "Garantie",                en: "Warranty" },
  "about.val.warrantyDesc":{ fr: "10 ans de garantie, 100 nuits d'essai.", en: "10-year warranty, 100-night trial." },

  // Contact
  "contact.title.1": { fr: "Restons en",                 en: "Let's stay in" },
  "contact.title.2": { fr: "contact",                    en: "touch" },
  "contact.intro":   { fr: "Notre équipe vous répond sous 24h.", en: "Our team replies within 24h." },
  "contact.name":    { fr: "Nom",                        en: "Name" },
  "contact.email":   { fr: "Email",                      en: "Email" },
  "contact.phone":   { fr: "Téléphone",                  en: "Phone" },
  "contact.message": { fr: "Message",                    en: "Message" },
  "contact.send":    { fr: "Envoyer le message",        en: "Send message" },
  "contact.sent":    { fr: "Message envoyé !",          en: "Message sent!" },
  "contact.sentDesc":{ fr: "Nous vous répondrons sous 24h.", en: "We'll reply within 24h." },
  "contact.another": { fr: "Envoyer un autre",           en: "Send another" },
  "contact.help":    { fr: "Comment pouvons-nous vous aider ?", en: "How can we help you?" },
  "contact.showroom":{ fr: "Notre showroom",             en: "Our showroom" },
  "contact.whatsapp":{ fr: "Discutez avec nous",         en: "Chat with us" },

  // Footer
  "footer.tagline":  { fr: "Le sommeil que vous méritez. Des matelas premium pour toute la famille, livrés rapidement.", en: "The sleep you deserve. Premium mattresses for the whole family, quickly delivered." },
  "footer.mattresses":{ fr: "Matelas",                   en: "Mattresses" },
  "footer.company":  { fr: "Entreprise",                  en: "Company" },
  "footer.careers":  { fr: "Carrières",                   en: "Careers" },
  "footer.blog":     { fr: "Blog",                        en: "Blog" },
  "footer.newsletter":{ fr: "Newsletter",                en: "Newsletter" },
  "footer.newsletterDesc":{ fr: "Restez informés de nos offres et nouveautés.", en: "Stay informed about our offers and news." },
  "footer.subscribed":{ fr: "Merci pour votre inscription !", en: "Thank you for subscribing!" },
  "footer.rights":   { fr: "Tous droits réservés.",      en: "All rights reserved." },

  // Auth
  "auth.welcome":    { fr: "Bienvenue",                  en: "Welcome" },
  "auth.loginSubtitle":{ fr: "Connectez-vous à votre compte DreamRest", en: "Sign in to your DreamRest account" },
  "auth.password":   { fr: "Mot de passe",               en: "Password" },
  "auth.signin":     { fr: "Se connecter",               en: "Sign in" },
  "auth.demoAdmin":  { fr: "Utiliser les identifiants admin démo", en: "Use demo admin credentials" },
  "auth.noAccount":  { fr: "Pas encore de compte ?",    en: "No account yet?" },
  "auth.create":     { fr: "Créer un compte client",    en: "Create a client account" },
  "auth.createTitle":{ fr: "Créer un compte",            en: "Create an account" },
  "auth.fullName":   { fr: "Nom complet",                en: "Full name" },
  "auth.signup":     { fr: "Créer mon compte",          en: "Create my account" },
  "auth.haveAccount":{ fr: "Déjà un compte ?",          en: "Already have an account?" },

  // Common
  "common.viewMore": { fr: "Voir →",                     en: "View →" },
};

interface Ctx {
  lang: Lang;
  setLang: (l: Lang) => void;
  t: (key: string) => string;
}

const LanguageContext = createContext<Ctx>({ lang: "fr", setLang: () => {}, t: (k) => k });

export function LanguageProvider({ children }: { children: ReactNode }) {
  const [lang, setLangState] = useState<Lang>("fr");

  useEffect(() => {
    const saved = (typeof window !== "undefined" && localStorage.getItem("dr_lang")) as Lang | null;
    if (saved === "fr" || saved === "en") setLangState(saved);
  }, []);

  const setLang = (l: Lang) => {
    setLangState(l);
    if (typeof window !== "undefined") localStorage.setItem("dr_lang", l);
  };

  const t = (key: string) => dict[key]?.[lang] ?? key;

  return (
    <LanguageContext.Provider value={{ lang, setLang, t }}>
      {children}
    </LanguageContext.Provider>
  );
}

export const useLang = () => useContext(LanguageContext);
