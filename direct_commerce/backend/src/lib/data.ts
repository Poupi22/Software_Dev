export interface Product {
  id: string;
  name: string;
  nameEn?: string;
  description: string;
  descriptionEn?: string;
  price: number; // FCFA
  salePrice?: number; // FCFA — prix de solde optionnel
  category: string;
  image: string;        // image principale
  images?: string[];    // sous-images (galerie)
  badge?: string;
  badgeEn?: string;
  available: boolean;
  features: string[];
  featuresEn?: string[];
  specs: Record<string, string>;
}

export interface Category {
  id: string;
  name: string;
  nameEn: string;
  image: string;
  count?: number; // calculé dynamiquement
}

export const categories: Category[] = [
  { id: "memoire",      name: "Mémoire de Forme",   nameEn: "Memory Foam",      image: "https://images.unsplash.com/photo-1631049552240-59c37f38802b?w=800&h=600&fit=crop", count: 6 },
  { id: "ressorts",     name: "Ressorts Ensachés",  nameEn: "Pocket Springs",   image: "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&h=600&fit=crop", count: 5 },
  { id: "latex",        name: "Latex Naturel",      nameEn: "Natural Latex",    image: "https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800&h=600&fit=crop", count: 4 },
  { id: "hybride",      name: "Hybride Premium",    nameEn: "Premium Hybrid",   image: "https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&h=600&fit=crop", count: 5 },
  { id: "orthopedique", name: "Orthopédique",       nameEn: "Orthopedic",       image: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&h=600&fit=crop", count: 4 },
  { id: "enfant",       name: "Enfant & Bébé",      nameEn: "Kids & Baby",      image: "https://images.unsplash.com/photo-1567016526105-22da7c13161a?w=800&h=600&fit=crop", count: 3 },
];

export const products: Product[] = [
  {
    id: "mat-001",
    name: "CloudRest Mémoire 24cm",
    nameEn: "CloudRest Memory 24cm",
    description: "Matelas en mousse à mémoire de forme thermosensible avec accueil moelleux et soutien ferme.",
    descriptionEn: "Thermosensitive memory foam mattress with soft welcome and firm support.",
    price: 250000,
    category: "memoire",
    image: "https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&h=600&fit=crop",
    badge: "Best-Seller", badgeEn: "Best-Seller",
    available: true,
    features: ["Mémoire de forme", "Hypoallergénique", "Housse déhoussable", "Garantie 10 ans"],
    featuresEn: ["Memory foam", "Hypoallergenic", "Removable cover", "10-year warranty"],
    specs: { "Épaisseur": "24 cm", "Densité": "55 kg/m³", "Fermeté": "Medium", "Tailles": "90 à 200 cm" },
  },
  {
    id: "mat-002",
    name: "OrthoSpring Premium",
    nameEn: "OrthoSpring Premium",
    description: "Matelas à ressorts ensachés indépendants pour un soutien ciblé et une indépendance de couchage parfaite.",
    descriptionEn: "Independent pocket spring mattress for targeted support and perfect motion isolation.",
    price: 350000,
    category: "ressorts",
    image: "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&h=600&fit=crop",
    badge: "Nouveau", badgeEn: "New",
    available: true,
    features: ["1 200 ressorts", "7 zones de confort", "Anti-acariens", "Coutil tencel"],
    featuresEn: ["1,200 springs", "7 comfort zones", "Anti-mite", "Tencel cover"],
    specs: { "Épaisseur": "28 cm", "Ressorts": "1 200", "Fermeté": "Ferme", "Tailles": "140 à 200 cm" },
  },
  {
    id: "mat-003",
    name: "BioLatex Nature",
    nameEn: "BioLatex Nature",
    description: "Matelas 100% latex naturel certifié, parfait pour les personnes recherchant une literie écologique.",
    descriptionEn: "100% certified natural latex mattress, perfect for eco-conscious sleepers.",
    price: 425000,
    category: "latex",
    image: "https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800&h=600&fit=crop",
    available: true,
    features: ["100% Latex naturel", "Bio & Eco-Tex", "Respirant", "Anti-bactérien"],
    featuresEn: ["100% Natural latex", "Bio & Eco-Tex", "Breathable", "Antibacterial"],
    specs: { "Épaisseur": "22 cm", "Densité": "85 kg/m³", "Fermeté": "Medium-Ferme", "Certif.": "GOLS / Oeko-Tex" },
  },
  {
    id: "mat-004",
    name: "HybridLuxe Royal",
    nameEn: "HybridLuxe Royal",
    description: "Matelas hybride combinant ressorts ensachés et mémoire de forme pour un confort haut de gamme.",
    descriptionEn: "Hybrid mattress combining pocket springs and memory foam for premium comfort.",
    price: 650000,
    category: "hybride",
    image: "https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=800&h=600&fit=crop",
    badge: "Premium", badgeEn: "Premium",
    available: true,
    features: ["Hybride 7 zones", "Mémoire gel", "Bord renforcé", "Coutil cachemire"],
    featuresEn: ["7-zone hybrid", "Gel memory", "Reinforced edge", "Cashmere cover"],
    specs: { "Épaisseur": "32 cm", "Ressorts": "1 800", "Fermeté": "Medium", "Garantie": "15 ans" },
  },
  {
    id: "mat-005",
    name: "DorsiCare Ortho",
    nameEn: "DorsiCare Ortho",
    description: "Matelas orthopédique recommandé pour les douleurs dorsales avec soutien ferme et zones différenciées.",
    descriptionEn: "Orthopedic mattress recommended for back pain with firm support and zoned comfort.",
    price: 325000,
    category: "orthopedique",
    image: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=800&h=600&fit=crop",
    available: true,
    features: ["Soutien orthopédique", "5 zones", "Anti-douleur dos", "Recommandé kiné"],
    featuresEn: ["Orthopedic support", "5 zones", "Back pain relief", "Physio-recommended"],
    specs: { "Épaisseur": "26 cm", "Densité": "60 kg/m³", "Fermeté": "Très ferme", "Tailles": "80 à 180 cm" },
  },
  {
    id: "mat-006",
    name: "BabyDream Confort",
    nameEn: "BabyDream Comfort",
    description: "Matelas bébé en mousse haute résilience, déhoussable et lavable, traité anti-acariens.",
    descriptionEn: "High-resilience baby foam mattress, removable, washable, anti-mite treated.",
    price: 95000,
    category: "enfant",
    image: "https://images.unsplash.com/photo-1567016526105-22da7c13161a?w=800&h=600&fit=crop",
    badge: "Bébé", badgeEn: "Baby",
    available: true,
    features: ["Mousse HR", "Déhoussable", "Anti-acariens", "Tailles berceau"],
    featuresEn: ["HR foam", "Removable", "Anti-mite", "Crib sizes"],
    specs: { "Épaisseur": "12 cm", "Densité": "25 kg/m³", "Fermeté": "Ferme", "Tailles": "60x120 / 70x140" },
  },
  {
    id: "mat-007",
    name: "ZenMemory Plus",
    nameEn: "ZenMemory Plus",
    description: "Matelas mémoire de forme avec gel rafraîchissant pour un sommeil tempéré toute l'année.",
    descriptionEn: "Memory foam mattress with cooling gel for tempered sleep all year round.",
    price: 295000,
    category: "memoire",
    image: "https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&h=600&fit=crop",
    available: true,
    features: ["Gel thermorégulant", "Mémoire visco", "Housse 4 saisons", "Sans CFC"],
    featuresEn: ["Thermo-regulating gel", "Visco memory", "4-season cover", "CFC-free"],
    specs: { "Épaisseur": "25 cm", "Densité": "60 kg/m³", "Fermeté": "Medium", "Tailles": "Toutes" },
  },
  {
    id: "mat-008",
    name: "PocketSpring Élite",
    nameEn: "PocketSpring Elite",
    description: "Matelas à ressorts ensachés haut de gamme avec coutil naturel et bord renforcé.",
    descriptionEn: "High-end pocket spring mattress with natural cover and reinforced edge.",
    price: 475000,
    category: "ressorts",
    image: "https://images.unsplash.com/photo-1617325247661-675ab4b64ae2?w=800&h=600&fit=crop",
    badge: "Tendance", badgeEn: "Trending",
    available: true,
    features: ["1 500 ressorts", "Coutil lin", "Bord renforcé", "5 zones"],
    featuresEn: ["1,500 springs", "Linen cover", "Reinforced edge", "5 zones"],
    specs: { "Épaisseur": "30 cm", "Ressorts": "1 500", "Fermeté": "Medium-Ferme", "Garantie": "12 ans" },
  },
  {
    id: "mat-009",
    name: "HybridFlex Pro",
    nameEn: "HybridFlex Pro",
    description: "Combinaison parfaite de mousse à mémoire et ressorts pour un soutien dynamique.",
    descriptionEn: "Perfect blend of memory foam and springs for dynamic support.",
    price: 545000,
    category: "hybride",
    image: "https://images.unsplash.com/photo-1631049552240-59c37f38802b?w=800&h=600&fit=crop",
    available: true,
    features: ["Hybride 5 zones", "Mémoire 5cm", "Anti-transpiration", "Déhoussable"],
    featuresEn: ["5-zone hybrid", "5cm memory", "Anti-perspiration", "Removable"],
    specs: { "Épaisseur": "29 cm", "Ressorts": "1 400", "Fermeté": "Medium", "Tailles": "Toutes" },
  },
  {
    id: "mat-010",
    name: "JuniorSleep Évolutif",
    nameEn: "JuniorSleep Evolutive",
    description: "Matelas enfant évolutif pour lit junior, ferme et confortable, idéal de 3 à 12 ans.",
    descriptionEn: "Evolutive junior mattress, firm and comfortable, ideal from 3 to 12 years.",
    price: 125000,
    category: "enfant",
    image: "https://images.unsplash.com/photo-1631679706909-1844bbd07221?w=800&h=600&fit=crop",
    available: true,
    features: ["Mousse HR", "Anti-acariens", "Hypoallergénique", "Déhoussable"],
    featuresEn: ["HR foam", "Anti-mite", "Hypoallergenic", "Removable"],
    specs: { "Épaisseur": "16 cm", "Densité": "30 kg/m³", "Fermeté": "Ferme", "Tailles": "90x190 / 90x200" },
  },
  {
    id: "mat-011",
    name: "LatexComfort Pure",
    nameEn: "LatexComfort Pure",
    description: "Matelas latex 100% naturel avec 7 zones de confort, idéal pour les dormeurs sensibles.",
    descriptionEn: "100% natural latex mattress with 7 comfort zones, ideal for sensitive sleepers.",
    price: 575000,
    category: "latex",
    image: "https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=800&h=600&fit=crop",
    badge: "Eco", badgeEn: "Eco",
    available: true,
    features: ["Latex 100% naturel", "7 zones", "Respirant", "Bio"],
    featuresEn: ["100% natural latex", "7 zones", "Breathable", "Organic"],
    specs: { "Épaisseur": "24 cm", "Densité": "80 kg/m³", "Fermeté": "Medium", "Garantie": "15 ans" },
  },
  {
    id: "mat-012",
    name: "ChiroSupport Plus",
    nameEn: "ChiroSupport Plus",
    description: "Matelas orthopédique extra-ferme conçu avec des kinésithérapeutes pour soulager le dos.",
    descriptionEn: "Extra-firm orthopedic mattress designed with physiotherapists to relieve back pain.",
    price: 495000,
    category: "orthopedique",
    image: "https://images.unsplash.com/photo-1604147495798-57beb5d6af73?w=800&h=600&fit=crop",
    available: false,
    features: ["Extra-ferme", "Soutien lombaire", "Anti-affaissement", "Recommandé kiné"],
    featuresEn: ["Extra-firm", "Lumbar support", "Anti-sagging", "Physio-recommended"],
    specs: { "Épaisseur": "28 cm", "Densité": "65 kg/m³", "Fermeté": "Extra-ferme", "Tailles": "Toutes" },
  },
];

export const teamMembers = [
  { name: "Sophie Martin",   role: "PDG & Fondatrice",      roleEn: "CEO & Founder",          description: "15 ans d'expertise dans la literie haut de gamme et le bien-être.", descriptionEn: "15 years of expertise in premium bedding and wellness.", photo: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop" },
  { name: "Julien Durand",   role: "Directeur Production",  roleEn: "Production Director",    description: "Spécialiste des mousses et ressorts. Ancien Bultex.",                descriptionEn: "Foam and spring specialist. Ex-Bultex.", photo: "https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400&h=400&fit=crop" },
  { name: "Aïcha Bensaïd",   role: "Responsable Qualité",   roleEn: "Quality Manager",        description: "Garante des certifications Oeko-Tex et écologiques.",               descriptionEn: "Guardian of Oeko-Tex and eco certifications.", photo: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&h=400&fit=crop" },
  { name: "David Rodriguez", role: "Directeur Commercial",  roleEn: "Sales Director",         description: "Plus de 50M€ générés dans la literie premium.",                    descriptionEn: "Over €50M generated in premium bedding.", photo: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop" },
];

export const stats = [
  { label: "Clients Satisfaits", labelEn: "Happy Clients",   value: 25000, suffix: "+" },
  { label: "Modèles de Matelas", labelEn: "Mattress Models", value: 27,    suffix: "" },
  { label: "Note Moyenne",       labelEn: "Average Rating",  value: 4.9,   suffix: "/5" },
  { label: "Livraison Express",  labelEn: "Express Delivery",value: 48,    suffix: "h" },
];

export const trustBadges = [
  { fr: "Oeko-Tex Certifié",  en: "Oeko-Tex Certified" },
  { fr: "Made in France",      en: "Made in France" },
  { fr: "100 Nuits d'Essai",  en: "100-Night Trial" },
  { fr: "Livraison Offerte",  en: "Free Delivery" },
  { fr: "Garantie 10 ans",     en: "10-Year Warranty" },
  { fr: "Paiement Sécurisé",  en: "Secure Payment" },
];

/** Format a FCFA price with thin spaces. */
export function formatFCFA(value: number): string {
  return `${value.toLocaleString("fr-FR").replace(/,/g, " ")} FCFA`;
}
