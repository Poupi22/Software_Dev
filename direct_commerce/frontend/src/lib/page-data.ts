// Page-specific datasets

// FLOCKS
export const flocksList = [
  { id: "#121", building: "B1", breed: "Cobb 500", age: 28, count: 1850, weight: 1.42, mortality: 2.1, status: "ok" },
  { id: "#122", building: "B2", breed: "Ross 308", age: 35, count: 1720, weight: 1.84, mortality: 3.4, status: "ok" },
  { id: "#123", building: "B3", breed: "Cobb 500", age: 14, count: 1980, weight: 0.62, mortality: 1.8, status: "ok" },
  { id: "#124", building: "B4", breed: "Ross 308", age: 42, count: 1620, weight: 2.21, mortality: 4.6, status: "warn" },
  { id: "#125", building: "B5", breed: "Cobb 500", age: 21, count: 1880, weight: 0.98, mortality: 2.9, status: "ok" },
  { id: "#126", building: "B6", breed: "Hubbard", age: 7, count: 2000, weight: 0.18, mortality: 2.2, status: "ok" },
  { id: "#127", building: "B1", breed: "Ross 308", age: 30, count: 1700, weight: 1.55, mortality: 3.1, status: "ok" },
  { id: "#128", building: "B2", breed: "Cobb 500", age: 38, count: 1540, weight: 1.92, mortality: 5.2, status: "alert" },
];

export const flockGrowth = Array.from({ length: 42 }, (_, i) => {
  const day = i + 1;
  return {
    day,
    actual: +(0.04 + day * 0.052 + Math.sin(day / 6) * 0.05).toFixed(2),
    target: +(0.045 + day * 0.05).toFixed(2),
  };
});

export const flockBreedSplit = [
  { name: "Cobb 500", value: 48 },
  { name: "Ross 308", value: 36 },
  { name: "Hubbard", value: 10 },
  { name: "Local", value: 6 },
];

// HEALTH / AI
export const healthAlerts7d = [
  { d: "Lun", critical: 1, moderate: 3, info: 5 },
  { d: "Mar", critical: 0, moderate: 2, info: 7 },
  { d: "Mer", critical: 2, moderate: 4, info: 6 },
  { d: "Jeu", critical: 1, moderate: 1, info: 4 },
  { d: "Ven", critical: 0, moderate: 3, info: 8 },
  { d: "Sam", critical: 1, moderate: 2, info: 5 },
  { d: "Dim", critical: 0, moderate: 1, info: 3 },
];

export const aiPrecisionTrend = [
  { m: "S1", precision: 84, recall: 78 },
  { m: "S2", precision: 86, recall: 81 },
  { m: "S3", precision: 88, recall: 84 },
  { m: "S4", precision: 89, recall: 86 },
  { m: "S5", precision: 90, recall: 87 },
  { m: "S6", precision: 91, recall: 88 },
  { m: "S7", precision: 92, recall: 89 },
  { m: "S8", precision: 91, recall: 90 },
];

export const vaccineCoverage = [
  { vaccine: "Newcastle", coverage: 98 },
  { vaccine: "Gumboro", coverage: 95 },
  { vaccine: "Bronchite", coverage: 92 },
  { vaccine: "Marek", coverage: 88 },
  { vaccine: "Coccidiose", coverage: 84 },
];

export const bodyTempData = Array.from({ length: 24 }, (_, h) => ({
  h: `${h}h`,
  avg: +(40.5 + Math.sin(h / 3) * 0.4 + (Math.random() - 0.5) * 0.2).toFixed(2),
  max: +(41.2 + Math.cos(h / 4) * 0.3).toFixed(2),
}));

// COOPERATORS
export const coopGrowth = [
  { m: "Jan", count: 12, revenue: 8.2 },
  { m: "Fév", count: 16, revenue: 11.4 },
  { m: "Mar", count: 21, revenue: 14.8 },
  { m: "Avr", count: 26, revenue: 18.5 },
  { m: "Mai", count: 32, revenue: 22.6 },
  { m: "Juin", count: 38, revenue: 27.1 },
  { m: "Juil", count: 43, revenue: 31.8 },
  { m: "Août", count: 47, revenue: 35.4 },
  { m: "Sep", count: 52, revenue: 38.4 },
];

export const coopRegions = [
  { region: "Centre", count: 18, revenue: 14.2 },
  { region: "Littoral", count: 14, revenue: 12.8 },
  { region: "Ouest", count: 11, revenue: 8.4 },
  { region: "Nord", count: 5, revenue: 2.1 },
  { region: "Sud", count: 4, revenue: 0.9 },
];

export const coopList = [
  { name: "Mballa F.", region: "Centre", tier: "Or", flocks: 4, revenue: 4.8, score: 94 },
  { name: "Ngono J.", region: "Littoral", tier: "Or", flocks: 3, revenue: 4.2, score: 91 },
  { name: "Tchoumi P.", region: "Ouest", tier: "Argent", flocks: 3, revenue: 3.9, score: 88 },
  { name: "Essomba R.", region: "Centre", tier: "Argent", flocks: 2, revenue: 3.4, score: 85 },
  { name: "Atangana L.", region: "Centre", tier: "Argent", flocks: 2, revenue: 3.1, score: 82 },
  { name: "Kamdem S.", region: "Ouest", tier: "Bronze", flocks: 2, revenue: 2.7, score: 78 },
  { name: "Bell M.", region: "Littoral", tier: "Bronze", flocks: 1, revenue: 2.3, score: 74 },
];

// COMPOST
export const composterStatus = [
  { name: "A1", temp: 62, hum: 58, maturity: 72, status: "ok" },
  { name: "A2", temp: 68, hum: 55, maturity: 45, status: "ok" },
  { name: "A3", temp: 38, hum: 42, maturity: 95, status: "ready" },
  { name: "A4", temp: 71, hum: 41, maturity: 28, status: "warn" },
  { name: "A5", temp: 55, hum: 60, maturity: 60, status: "ok" },
  { name: "A6", temp: 48, hum: 50, maturity: 82, status: "ok" },
];

export const compostMonthly = [
  { m: "Jan", produced: 18, sold: 14 },
  { m: "Fév", produced: 22, sold: 19 },
  { m: "Mar", produced: 26, sold: 22 },
  { m: "Avr", produced: 24, sold: 23 },
  { m: "Mai", produced: 28, sold: 25 },
  { m: "Juin", produced: 32, sold: 28 },
  { m: "Juil", produced: 35, sold: 31 },
  { m: "Août", produced: 38, sold: 34 },
  { m: "Sep", produced: 41, sold: 36 },
];

export const compostNPK = [
  { metric: "N (Azote)", value: 2.8 },
  { metric: "P (Phosphore)", value: 1.6 },
  { metric: "K (Potassium)", value: 2.1 },
  { metric: "C/N Ratio", value: 18 },
  { metric: "pH", value: 7.2 },
  { metric: "M.O. %", value: 45 },
];

// FINANCE
export const monthlyPL = [
  { m: "Jan", revenue: 8.5, cost: 6.2, profit: 2.3 },
  { m: "Fév", revenue: 9.8, cost: 6.8, profit: 3.0 },
  { m: "Mar", revenue: 11.2, cost: 7.5, profit: 3.7 },
  { m: "Avr", revenue: 12.4, cost: 8.1, profit: 4.3 },
  { m: "Mai", revenue: 14.1, cost: 9.0, profit: 5.1 },
  { m: "Juin", revenue: 15.8, cost: 9.8, profit: 6.0 },
  { m: "Juil", revenue: 17.2, cost: 10.4, profit: 6.8 },
  { m: "Août", revenue: 18.5, cost: 11.1, profit: 7.4 },
  { m: "Sep", revenue: 20.1, cost: 11.9, profit: 8.2 },
];

export const costBreakdown = [
  { name: "Aliment", value: 48 },
  { name: "Main d'œuvre", value: 18 },
  { name: "Énergie", value: 12 },
  { name: "Vétérinaire", value: 8 },
  { name: "Logistique", value: 9 },
  { name: "Divers", value: 5 },
];

export const investments = [
  { item: "Bâtiments", value: 45 },
  { item: "Capteurs IoT", value: 18 },
  { item: "Caméras IA", value: 12 },
  { item: "Compostage", value: 9 },
  { item: "Logistique", value: 11 },
  { item: "R&D", value: 5 },
];

// MARKET
export const priceVsCompetitors = [
  { w: "S1", ecotec: 2850, akwa: 2700, ndogbong: 2650, mokolo: 2620 },
  { w: "S2", ecotec: 2880, akwa: 2720, ndogbong: 2680, mokolo: 2640 },
  { w: "S3", ecotec: 2900, akwa: 2750, ndogbong: 2700, mokolo: 2650 },
  { w: "S4", ecotec: 2920, akwa: 2780, ndogbong: 2720, mokolo: 2680 },
  { w: "S5", ecotec: 2950, akwa: 2810, ndogbong: 2740, mokolo: 2700 },
  { w: "S6", ecotec: 2980, akwa: 2840, ndogbong: 2760, mokolo: 2720 },
  { w: "S7", ecotec: 3010, akwa: 2860, ndogbong: 2780, mokolo: 2740 },
  { w: "S8", ecotec: 3040, akwa: 2890, ndogbong: 2810, mokolo: 2770 },
];

export const channelSales = [
  { channel: "B2C Direct", value: 35 },
  { channel: "Restaurants", value: 30 },
  { channel: "Retail", value: 20 },
  { channel: "Compost", value: 10 },
  { channel: "Pisciculture", value: 5 },
];

export const demandForecast = Array.from({ length: 12 }, (_, i) => {
  const m = i + 1;
  const base = 1200 + m * 85;
  return {
    m: `M${m}`,
    demand: Math.round(base + Math.sin(i / 2) * 100),
    supply: Math.round(base * 0.92 + Math.cos(i / 3) * 80),
  };
});

// REPORTS
export const reportsList = [
  { id: "RPT-2026-09", title: "Rapport Production Septembre", type: "Production", date: "30/09/2026", size: "2.4 MB" },
  { id: "RPT-2026-08", title: "Bilan Financier Août", type: "Finance", date: "31/08/2026", size: "1.8 MB" },
  { id: "RPT-2026-Q3", title: "Synthèse Q3 — Coopérative", type: "Coopérants", date: "30/09/2026", size: "3.1 MB" },
  { id: "RPT-2026-09H", title: "Rapport Sanitaire IA", type: "Santé", date: "28/09/2026", size: "1.2 MB" },
  { id: "RPT-2026-09C", title: "Compostage & Empreinte", type: "Compost", date: "29/09/2026", size: "0.9 MB" },
  { id: "RPT-2026-09M", title: "Étude Marché Local", type: "Marché", date: "27/09/2026", size: "1.5 MB" },
];

export const kpiHistory = [
  { m: "Jan", mortality: 4.2, fcr: 1.92, weight: 1.65 },
  { m: "Fév", mortality: 4.0, fcr: 1.89, weight: 1.68 },
  { m: "Mar", mortality: 3.8, fcr: 1.86, weight: 1.71 },
  { m: "Avr", mortality: 3.5, fcr: 1.83, weight: 1.74 },
  { m: "Mai", mortality: 3.3, fcr: 1.80, weight: 1.76 },
  { m: "Juin", mortality: 3.1, fcr: 1.78, weight: 1.78 },
  { m: "Juil", mortality: 3.0, fcr: 1.76, weight: 1.80 },
  { m: "Août", mortality: 2.9, fcr: 1.74, weight: 1.82 },
  { m: "Sep", mortality: 2.8, fcr: 1.72, weight: 1.84 },
];
