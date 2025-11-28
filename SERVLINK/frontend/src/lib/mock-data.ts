export type Category = { slug: string; name: string; icon: string; count: number };

export const categories: Category[] = [
  { slug: "plomberie", name: "Plomberie", icon: "Wrench", count: 124 },
  { slug: "electricite", name: "Électricité", icon: "Zap", count: 98 },
  { slug: "menage", name: "Ménage", icon: "Sparkles", count: 212 },
  { slug: "coiffure", name: "Coiffure & Beauté", icon: "Scissors", count: 187 },
  { slug: "menuiserie", name: "Menuiserie", icon: "Hammer", count: 76 },
  { slug: "informatique", name: "Informatique", icon: "Laptop", count: 143 },
  { slug: "cours", name: "Cours & Formation", icon: "GraduationCap", count: 165 },
  { slug: "evenementiel", name: "Événementiel", icon: "PartyPopper", count: 89 },
  { slug: "transport", name: "Transport", icon: "Truck", count: 54 },
  { slug: "jardinage", name: "Jardinage", icon: "Trees", count: 41 },
  { slug: "couture", name: "Couture", icon: "Shirt", count: 67 },
  { slug: "photo", name: "Photo & Vidéo", icon: "Camera", count: 102 },
];

export type Provider = {
  id: string;
  name: string;
  avatar: string;
  cover: string;
  category: string;
  categorySlug: string;
  city: string;
  rating: number;
  reviews: number;
  priceFrom: number;
  verified: boolean;
  featured: boolean;
  bio: string;
  tags: string[];
  responseTime: string;
  completed: number;
};

const avatars = [
  "https://i.pravatar.cc/200?img=12",
  "https://i.pravatar.cc/200?img=23",
  "https://i.pravatar.cc/200?img=33",
  "https://i.pravatar.cc/200?img=47",
  "https://i.pravatar.cc/200?img=15",
  "https://i.pravatar.cc/200?img=51",
  "https://i.pravatar.cc/200?img=8",
  "https://i.pravatar.cc/200?img=27",
  "https://i.pravatar.cc/200?img=60",
  "https://i.pravatar.cc/200?img=39",
];

const covers = [
  "https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=800",
  "https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800",
  "https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800",
  "https://images.unsplash.com/photo-1562322140-8baeececf3df?w=800",
  "https://images.unsplash.com/photo-1504148455328-c376907d081c?w=800",
  "https://images.unsplash.com/photo-1518770660439-4636190af475?w=800",
  "https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800",
  "https://images.unsplash.com/photo-1542038784456-1ea8e935640e?w=800",
];

const cities = ["Douala", "Yaoundé", "Bafoussam", "Kribi", "Garoua", "Bamenda"];

export const providers: Provider[] = Array.from({ length: 18 }).map((_, i) => {
  const cat = categories[i % categories.length];
  const names = [
    "Awa Nkomo", "Patrick Mbarga", "Sandrine Etoa", "Jean-Pierre Fouda",
    "Marie Tchamba", "Christian Edou", "Brenda Nguepi", "Yves Talla",
    "Linda Mvondo", "Serge Atangana", "Pauline Biya", "Hervé Kameni",
    "Esther Manga", "Olivier Essomba", "Diane Owono", "Nadège Tchana",
    "Rodrigue Mbida", "Sylvie Ngono",
  ];
  return {
    id: `p${i + 1}`,
    name: names[i],
    avatar: avatars[i % avatars.length],
    cover: covers[i % covers.length],
    category: cat.name,
    categorySlug: cat.slug,
    city: cities[i % cities.length],
    rating: Math.round((4 + Math.random()) * 10) / 10,
    reviews: Math.floor(20 + Math.random() * 200),
    priceFrom: [5000, 8000, 12000, 15000, 25000, 35000][i % 6],
    verified: i % 3 !== 0,
    featured: i % 5 === 0,
    bio: "Professionnel expérimenté, ponctuel et soigneux. Plus de cinq ans d'expérience au service des particuliers et des entreprises.",
    tags: ["Réactif", "Tarifs clairs", "Matériel fourni"].slice(0, 2 + (i % 2)),
    responseTime: ["< 1h", "< 2h", "< 4h", "Même jour"][i % 4],
    completed: Math.floor(30 + Math.random() * 400),
  };
});

export type Booking = {
  id: string;
  provider: string;
  service: string;
  date: string;
  status: "pending" | "confirmed" | "completed" | "cancelled";
  amount: number;
};

export const bookings: Booking[] = [
  { id: "BK-1042", provider: "Awa Nkomo", service: "Réparation fuite cuisine", date: "2026-05-24 10:00", status: "confirmed", amount: 15000 },
  { id: "BK-1041", provider: "Patrick Mbarga", service: "Installation tableau", date: "2026-05-22 14:00", status: "pending", amount: 35000 },
  { id: "BK-1038", provider: "Sandrine Etoa", service: "Ménage hebdo 4h", date: "2026-05-18 09:00", status: "completed", amount: 12000 },
  { id: "BK-1035", provider: "Marie Tchamba", service: "Coiffure à domicile", date: "2026-05-12 16:30", status: "completed", amount: 8000 },
  { id: "BK-1029", provider: "Yves Talla", service: "Dépannage PC", date: "2026-05-08 11:00", status: "cancelled", amount: 10000 },
];

export type ChatThread = {
  id: string;
  provider: string;
  avatar: string;
  lastMessage: string;
  time: string;
  unread: number;
  online: boolean;
};

export const threads: ChatThread[] = [
  { id: "t1", provider: "Awa Nkomo", avatar: avatars[0], lastMessage: "Je serai chez vous à 10h pile.", time: "09:42", unread: 2, online: true },
  { id: "t2", provider: "Patrick Mbarga", avatar: avatars[1], lastMessage: "Pouvez-vous m'envoyer une photo du tableau ?", time: "Hier", unread: 0, online: false },
  { id: "t3", provider: "Sandrine Etoa", avatar: avatars[2], lastMessage: "Merci pour votre note ⭐", time: "Lun", unread: 0, online: true },
  { id: "t4", provider: "Marie Tchamba", avatar: avatars[4], lastMessage: "Disponible samedi matin ?", time: "12 mai", unread: 1, online: false },
];

export const reviews = [
  { id: "r1", author: "Claire D.", avatar: avatars[3], rating: 5, comment: "Service impeccable, je recommande à 100% !", provider: "Awa Nkomo", date: "Il y a 2 jours" },
  { id: "r2", author: "Marc T.", avatar: avatars[5], rating: 5, comment: "Ponctuel et très professionnel. Travail propre.", provider: "Patrick Mbarga", date: "Il y a 5 jours" },
  { id: "r3", author: "Léa K.", avatar: avatars[6], rating: 4, comment: "Très bien, juste un petit retard mais bon travail.", provider: "Sandrine Etoa", date: "La semaine dernière" },
];

/* ===== Admin ===== */

export const adminUsers = Array.from({ length: 12 }).map((_, i) => ({
  id: `U-${1000 + i}`,
  name: ["Awa Nkomo","Patrick Mbarga","Sandrine Etoa","Jean Fouda","Marie Tchamba","Christian Edou","Brenda Nguepi","Yves Talla","Linda Mvondo","Serge Atangana","Pauline Biya","Hervé Kameni"][i],
  email: `user${i + 1}@servlink.cm`,
  role: i % 4 === 0 ? "Prestataire" : "Client",
  status: i % 5 === 0 ? "Suspendu" : "Actif",
  joined: `2026-0${(i % 5) + 1}-1${i}`,
  avatar: avatars[i % avatars.length],
}));

export const adminTransactions = Array.from({ length: 10 }).map((_, i) => ({
  id: `TX-20${i}`,
  client: ["Claire D.","Marc T.","Léa K.","Paul N.","Sylvie M.","Aïcha B."][i % 6],
  provider: ["Awa Nkomo","Patrick Mbarga","Sandrine Etoa","Marie Tchamba"][i % 4],
  amount: [5000,12000,15000,8000,35000,25000][i % 6],
  method: ["MTN MoMo","Orange Money","Carte VISA"][i % 3],
  status: ["Réussie","Réussie","En attente","Réussie","Remboursée"][i % 5],
  date: `2026-05-${10 + i}`,
}));

export const adminDisputes = [
  { id: "D-301", client: "Claire D.", provider: "Awa Nkomo", reason: "Prestation incomplète", status: "Ouvert", priority: "Haute", opened: "2026-05-19" },
  { id: "D-300", client: "Marc T.", provider: "Patrick Mbarga", reason: "Retard et matériel non fourni", status: "En arbitrage", priority: "Moyenne", opened: "2026-05-17" },
  { id: "D-298", client: "Léa K.", provider: "Sandrine Etoa", reason: "Désaccord sur tarif", status: "Résolu", priority: "Basse", opened: "2026-05-12" },
  { id: "D-295", client: "Paul N.", provider: "Yves Talla", reason: "Service non rendu", status: "Ouvert", priority: "Haute", opened: "2026-05-10" },
];

export const adminFlaggedReviews = [
  { id: "AV-77", author: "Anonymous", provider: "Patrick Mbarga", rating: 1, comment: "Texte signalé comme abusif par le prestataire.", flagged: "Insultes", date: "2026-05-18" },
  { id: "AV-75", author: "User_22", provider: "Awa Nkomo", rating: 2, comment: "Avis jugé diffamatoire — sous modération.", flagged: "Diffamation", date: "2026-05-15" },
];

export const revenueSeries = [
  { m: "Jan", v: 420 }, { m: "Fév", v: 510 }, { m: "Mar", v: 680 }, { m: "Avr", v: 740 },
  { m: "Mai", v: 920 }, { m: "Juin", v: 1040 }, { m: "Juil", v: 1180 },
];

export const cityDistribution = [
  { name: "Douala", value: 42 },
  { name: "Yaoundé", value: 31 },
  { name: "Bafoussam", value: 12 },
  { name: "Kribi", value: 8 },
  { name: "Autres", value: 7 },
];

export const formatXAF = (n: number) =>
  new Intl.NumberFormat("fr-FR").format(n) + " FCFA";
