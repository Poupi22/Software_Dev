// Mock data réalistes pour pharmacie

export type Medicament = {
  id: string;
  nom: string;
  categorie: string;
  prixAchat: number;
  prixVente: number;
  quantite: number;
  dateExpiration: string;
  codeBarre: string;
  description: string;
  image: string;
  derniereVente?: string;
};

export type CategorieInfo = { nom: string; image: string; couleur: string };

export type Vente = {
  id: string;
  numero: string;
  date: string;
  utilisateur: string;
  items: { medicament: string; quantite: number; prix: number }[];
  total: number;
};

export type EntreeStock = {
  id: string;
  medicament: string;
  quantite: number;
  dateEntree: string;
  dateExpiration: string;
  numeroLot: string;
  fournisseur: string;
};

export type Utilisateur = {
  id: string;
  nom: string;
  email: string;
  role: "ADMIN" | "GERANT";
  statut: "actif" | "inactif";
  derniereConnexion: string;
};

export type ActiviteSysteme = {
  id: string;
  utilisateur: string;
  action: string;
  cible: string;
  date: string;
  type: "vente" | "stock" | "user" | "auth" | "medicament";
};

export const categoriesInfo: CategorieInfo[] = [
  { nom: "Antibiotique", image: "https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600&q=80&auto=format&fit=crop", couleur: "from-blue-500 to-cyan-500" },
  { nom: "Antalgique", image: "https://images.unsplash.com/photo-1550572017-edd951b55104?w=600&q=80&auto=format&fit=crop", couleur: "from-emerald-500 to-teal-500" },
  { nom: "Anti-inflammatoire", image: "https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=600&q=80&auto=format&fit=crop", couleur: "from-orange-500 to-red-500" },
  { nom: "Antihistaminique", image: "https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=600&q=80&auto=format&fit=crop", couleur: "from-purple-500 to-pink-500" },
  { nom: "Cardiovasculaire", image: "https://images.unsplash.com/photo-1628771065518-0d82f1938462?w=600&q=80&auto=format&fit=crop", couleur: "from-rose-500 to-red-600" },
  { nom: "Vitamine", image: "https://images.unsplash.com/photo-1577460551100-d3f84b6e4f22?w=600&q=80&auto=format&fit=crop", couleur: "from-amber-500 to-orange-500" },
  { nom: "Dermatologique", image: "https://images.unsplash.com/photo-1556228720-195a672e8a03?w=600&q=80&auto=format&fit=crop", couleur: "from-pink-500 to-rose-500" },
  { nom: "Digestif", image: "https://images.unsplash.com/photo-1626716493137-b67fe9501e76?w=600&q=80&auto=format&fit=crop", couleur: "from-lime-500 to-green-500" },
  { nom: "Respiratoire", image: "https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=600&q=80&auto=format&fit=crop", couleur: "from-sky-500 to-blue-500" },
];

export const categories = categoriesInfo.map((c) => c.nom);

const today = new Date();
const daysFromNow = (d: number) => {
  const dt = new Date(today);
  dt.setDate(dt.getDate() + d);
  return dt.toISOString().split("T")[0];
};

export const medicaments: Medicament[] = [
  { id: "m1", nom: "Amoxicilline 500mg", categorie: "Antibiotique", prixAchat: 1200, prixVente: 1800, quantite: 245, dateExpiration: daysFromNow(420), codeBarre: "3401597862451", description: "Antibiotique β-lactamine", image: "https://images.unsplash.com/photo-1587854692152-cbe660dbde88?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-1) },
  { id: "m2", nom: "Paracétamol 1g", categorie: "Antalgique", prixAchat: 350, prixVente: 600, quantite: 8, dateExpiration: daysFromNow(180), codeBarre: "3401597862468", description: "Antalgique antipyrétique", image: "https://images.unsplash.com/photo-1550572017-edd951b55104?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(0) },
  { id: "m3", nom: "Ibuprofène 400mg", categorie: "Anti-inflammatoire", prixAchat: 800, prixVente: 1300, quantite: 150, dateExpiration: daysFromNow(70), codeBarre: "3401597862475", description: "AINS non stéroïdien", image: "https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-2) },
  { id: "m4", nom: "Cetirizine 10mg", categorie: "Antihistaminique", prixAchat: 600, prixVente: 950, quantite: 12, dateExpiration: daysFromNow(45), codeBarre: "3401597862482", description: "Antihistaminique H1", image: "https://images.unsplash.com/photo-1471864190281-a93a3070b6de?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-1) },
  { id: "m5", nom: "Aspirine 500mg", categorie: "Antalgique", prixAchat: 300, prixVente: 500, quantite: 320, dateExpiration: daysFromNow(540), codeBarre: "3401597862499", description: "Acide acétylsalicylique", image: "https://images.unsplash.com/photo-1626716493137-b67fe9501e76?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(0) },
  { id: "m6", nom: "Oméprazole 20mg", categorie: "Digestif", prixAchat: 1500, prixVente: 2400, quantite: 6, dateExpiration: daysFromNow(60), codeBarre: "3401597862505", description: "Inhibiteur pompe à protons", image: "https://images.unsplash.com/photo-1631549916768-4119b2e5f926?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-5) },
  { id: "m7", nom: "Atorvastatine 20mg", categorie: "Cardiovasculaire", prixAchat: 2800, prixVente: 4200, quantite: 95, dateExpiration: daysFromNow(360), codeBarre: "3401597862512", description: "Statine hypocholestérolémiante", image: "https://images.unsplash.com/photo-1628771065518-0d82f1938462?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-1) },
  { id: "m8", nom: "Vitamine C 1g", categorie: "Vitamine", prixAchat: 400, prixVente: 750, quantite: 480, dateExpiration: daysFromNow(720), codeBarre: "3401597862529", description: "Acide ascorbique", image: "https://images.unsplash.com/photo-1577460551100-d3f84b6e4f22?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(0) },
  { id: "m9", nom: "Salbutamol Inhalateur", categorie: "Respiratoire", prixAchat: 3200, prixVente: 5000, quantite: 22, dateExpiration: daysFromNow(15), codeBarre: "3401597862536", description: "Bronchodilatateur β2", image: "https://images.unsplash.com/photo-1584017911766-d451b3d0e843?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-3) },
  { id: "m10", nom: "Hydrocortisone 1%", categorie: "Dermatologique", prixAchat: 900, prixVente: 1500, quantite: 4, dateExpiration: daysFromNow(200), codeBarre: "3401597862543", description: "Corticostéroïde topique", image: "https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-90) },
  { id: "m11", nom: "Métronidazole 500mg", categorie: "Antibiotique", prixAchat: 1100, prixVente: 1700, quantite: 78, dateExpiration: daysFromNow(-10), codeBarre: "3401597862550", description: "Antibactérien antiparasitaire", image: "https://images.unsplash.com/photo-1585435557343-3b092031a831?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-45) },
  { id: "m12", nom: "Lisinopril 10mg", categorie: "Cardiovasculaire", prixAchat: 1800, prixVente: 2700, quantite: 130, dateExpiration: daysFromNow(450), codeBarre: "3401597862567", description: "IEC antihypertenseur", image: "https://images.unsplash.com/photo-1576602976047-174e57a47881?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-2) },
  { id: "m13", nom: "Loratadine 10mg", categorie: "Antihistaminique", prixAchat: 550, prixVente: 900, quantite: 200, dateExpiration: daysFromNow(80), codeBarre: "3401597862574", description: "Antihistaminique non sédatif", image: "https://images.unsplash.com/photo-1631549919535-be3c0a4a90b2?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(0) },
  { id: "m14", nom: "Diclofénac Gel", categorie: "Anti-inflammatoire", prixAchat: 1200, prixVente: 2000, quantite: 3, dateExpiration: daysFromNow(300), codeBarre: "3401597862581", description: "AINS topique", image: "https://images.unsplash.com/photo-1607619056574-7b8d3ee536b2?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-120) },
  { id: "m15", nom: "Vitamine D3 1000UI", categorie: "Vitamine", prixAchat: 700, prixVente: 1200, quantite: 165, dateExpiration: daysFromNow(600), codeBarre: "3401597862598", description: "Cholécalciférol", image: "https://images.unsplash.com/photo-1559757175-5700dde675bc?w=400&q=80&auto=format&fit=crop", derniereVente: daysFromNow(-1) },
];

export const ventes: Vente[] = [
  { id: "v1", numero: "F-2024-0341", date: daysFromNow(0), utilisateur: "Marie K.", total: 12400, items: [{ medicament: "Paracétamol 1g", quantite: 2, prix: 600 }, { medicament: "Vitamine C 1g", quantite: 3, prix: 750 }, { medicament: "Amoxicilline 500mg", quantite: 5, prix: 1800 }] },
  { id: "v2", numero: "F-2024-0340", date: daysFromNow(0), utilisateur: "Jean B.", total: 8500, items: [{ medicament: "Ibuprofène 400mg", quantite: 4, prix: 1300 }] },
  { id: "v3", numero: "F-2024-0339", date: daysFromNow(0), utilisateur: "Marie K.", total: 15600, items: [{ medicament: "Atorvastatine 20mg", quantite: 2, prix: 4200 }, { medicament: "Lisinopril 10mg", quantite: 3, prix: 2700 }] },
  { id: "v4", numero: "F-2024-0338", date: daysFromNow(-1), utilisateur: "Sophie L.", total: 6750, items: [{ medicament: "Vitamine D3 1000UI", quantite: 3, prix: 1200 }, { medicament: "Vitamine C 1g", quantite: 4, prix: 750 }] },
  { id: "v5", numero: "F-2024-0337", date: daysFromNow(-1), utilisateur: "Jean B.", total: 22000, items: [{ medicament: "Salbutamol Inhalateur", quantite: 2, prix: 5000 }, { medicament: "Atorvastatine 20mg", quantite: 3, prix: 4200 }] },
  { id: "v6", numero: "F-2024-0336", date: daysFromNow(-2), utilisateur: "Marie K.", total: 4200, items: [{ medicament: "Aspirine 500mg", quantite: 4, prix: 500 }, { medicament: "Loratadine 10mg", quantite: 2, prix: 900 }] },
];

export const entreesStock: EntreeStock[] = [
  { id: "e1", medicament: "Amoxicilline 500mg", quantite: 100, dateEntree: daysFromNow(-2), dateExpiration: daysFromNow(420), numeroLot: "LOT-2024-A451", fournisseur: "Sanofi" },
  { id: "e2", medicament: "Vitamine C 1g", quantite: 200, dateEntree: daysFromNow(-3), dateExpiration: daysFromNow(720), numeroLot: "LOT-2024-V128", fournisseur: "Bayer" },
  { id: "e3", medicament: "Atorvastatine 20mg", quantite: 50, dateEntree: daysFromNow(-5), dateExpiration: daysFromNow(360), numeroLot: "LOT-2024-A892", fournisseur: "Pfizer" },
  { id: "e4", medicament: "Paracétamol 1g", quantite: 150, dateEntree: daysFromNow(-7), dateExpiration: daysFromNow(180), numeroLot: "LOT-2024-P772", fournisseur: "UPSA" },
  { id: "e5", medicament: "Lisinopril 10mg", quantite: 80, dateEntree: daysFromNow(-10), dateExpiration: daysFromNow(450), numeroLot: "LOT-2024-L334", fournisseur: "Sandoz" },
];

export const utilisateurs: Utilisateur[] = [
  { id: "u1", nom: "Dr. Aïcha Mensah", email: "aicha.mensah@pharma.com", role: "ADMIN", statut: "actif", derniereConnexion: daysFromNow(0) },
  { id: "u2", nom: "Marie Koffi", email: "marie.koffi@pharma.com", role: "GERANT", statut: "actif", derniereConnexion: daysFromNow(0) },
  { id: "u3", nom: "Jean Baptiste", email: "jean.b@pharma.com", role: "GERANT", statut: "actif", derniereConnexion: daysFromNow(0) },
  { id: "u4", nom: "Sophie Lawson", email: "sophie.l@pharma.com", role: "GERANT", statut: "actif", derniereConnexion: daysFromNow(-1) },
  { id: "u5", nom: "Pierre N'Doye", email: "pierre.n@pharma.com", role: "GERANT", statut: "inactif", derniereConnexion: daysFromNow(-30) },
];

export const activitesSysteme: ActiviteSysteme[] = [
  { id: "a1", utilisateur: "Marie K.", action: "Vente effectuée", cible: "F-2024-0341 — 12 400 FCFA", date: new Date().toISOString(), type: "vente" },
  { id: "a2", utilisateur: "Jean B.", action: "Vente effectuée", cible: "F-2024-0340 — 8 500 FCFA", date: new Date(Date.now() - 5 * 60000).toISOString(), type: "vente" },
  { id: "a3", utilisateur: "Dr. Aïcha M.", action: "Médicament modifié", cible: "Paracétamol 1g", date: new Date(Date.now() - 12 * 60000).toISOString(), type: "medicament" },
  { id: "a4", utilisateur: "Marie K.", action: "Connexion système", cible: "Session ouverte", date: new Date(Date.now() - 25 * 60000).toISOString(), type: "auth" },
  { id: "a5", utilisateur: "Dr. Aïcha M.", action: "Entrée stock", cible: "Amoxicilline +100", date: new Date(Date.now() - 45 * 60000).toISOString(), type: "stock" },
  { id: "a6", utilisateur: "Sophie L.", action: "Connexion système", cible: "Session ouverte", date: new Date(Date.now() - 90 * 60000).toISOString(), type: "auth" },
  { id: "a7", utilisateur: "Dr. Aïcha M.", action: "Utilisateur créé", cible: "Pierre N'Doye", date: new Date(Date.now() - 180 * 60000).toISOString(), type: "user" },
];

// Charts data
export const ventesQuotidiennes = [
  { jour: "Lun", ventes: 28, revenu: 145000 },
  { jour: "Mar", ventes: 35, revenu: 182000 },
  { jour: "Mer", ventes: 42, revenu: 215000 },
  { jour: "Jeu", ventes: 31, revenu: 168000 },
  { jour: "Ven", ventes: 48, revenu: 248000 },
  { jour: "Sam", ventes: 52, revenu: 287000 },
  { jour: "Dim", ventes: 22, revenu: 112000 },
];

export const revenusMensuels = [
  { mois: "Jan", revenu: 4200000 },
  { mois: "Fév", revenu: 3850000 },
  { mois: "Mar", revenu: 4650000 },
  { mois: "Avr", revenu: 5100000 },
  { mois: "Mai", revenu: 4920000 },
  { mois: "Jun", revenu: 5430000 },
  { mois: "Jul", revenu: 5870000 },
  { mois: "Aoû", revenu: 5210000 },
  { mois: "Sep", revenu: 5680000 },
  { mois: "Oct", revenu: 6120000 },
  { mois: "Nov", revenu: 5890000 },
  { mois: "Déc", revenu: 6450000 },
];

export const topMedicaments = [
  { nom: "Paracétamol 1g", ventes: 245 },
  { nom: "Amoxicilline 500mg", ventes: 198 },
  { nom: "Vitamine C 1g", ventes: 176 },
  { nom: "Ibuprofène 400mg", ventes: 152 },
  { nom: "Aspirine 500mg", ventes: 134 },
];

export const formatFCFA = (n: number) => new Intl.NumberFormat("fr-FR").format(n) + " FCFA";

export const stockStatus = (q: number): "critique" | "faible" | "ok" => {
  if (q <= 5) return "critique";
  if (q <= 15) return "faible";
  return "ok";
};

export const expirationStatus = (date: string): "expire" | "proche" | "ok" => {
  const diff = (new Date(date).getTime() - Date.now()) / (1000 * 60 * 60 * 24);
  if (diff < 0) return "expire";
  if (diff < 90) return "proche";
  return "ok";
};
