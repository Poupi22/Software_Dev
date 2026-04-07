// Realistic mock data for ECOTEC Smart Poultry dashboard
import type { TFunction } from "i18next";

export const productionData = (t: TFunction) => [
  { m: t("months.jan"), broilers: 32, eggs: 145 },
  { m: t("months.feb"), broilers: 38, eggs: 152 },
  { m: t("months.mar"), broilers: 42, eggs: 161 },
  { m: t("months.apr"), broilers: 40, eggs: 168 },
  { m: t("months.may"), broilers: 45, eggs: 172 },
  { m: t("months.jun"), broilers: 48, eggs: 178 },
  { m: t("months.jul"), broilers: 52, eggs: 184 },
  { m: t("months.aug"), broilers: 50, eggs: 188 },
  { m: t("months.sep"), broilers: 55, eggs: 192 },
  { m: t("months.oct"), broilers: 58, eggs: 198 },
  { m: t("months.nov"), broilers: 62, eggs: 205 },
  { m: t("months.dec"), broilers: 65, eggs: 212 },
];

export const mortalityData = [
  { flock: "#121", rate: 2.1, target: 4 },
  { flock: "#122", rate: 3.4, target: 4 },
  { flock: "#123", rate: 1.8, target: 4 },
  { flock: "#124", rate: 4.6, target: 4 },
  { flock: "#125", rate: 2.9, target: 4 },
  { flock: "#126", rate: 2.2, target: 4 },
  { flock: "#127", rate: 3.1, target: 4 },
  { flock: "#128", rate: 5.2, target: 4 },
];

export const feedWaterData = (t: TFunction) => [
  { d: "L", feed: 1240, water: 2480 },
  { d: "M", feed: 1280, water: 2510 },
  { d: "M", feed: 1310, water: 2560 },
  { d: "J", feed: 1295, water: 2540 },
  { d: "V", feed: 1340, water: 2620 },
  { d: "S", feed: 1380, water: 2700 },
  { d: "D", feed: 1410, water: 2750 },
];

export const revenueSplit = [
  { name: "B2C", value: 35 },
  { name: "B2B Resto", value: 30 },
  { name: "B2B Retail", value: 20 },
  { name: "Compost", value: 10 },
  { name: "Pisciculture", value: 5 },
];

export const compostCycle = [
  { day: 1, temp: 35, humidity: 68, maturity: 5 },
  { day: 5, temp: 52, humidity: 65, maturity: 12 },
  { day: 10, temp: 65, humidity: 60, maturity: 25 },
  { day: 15, temp: 68, humidity: 55, maturity: 38 },
  { day: 20, temp: 62, humidity: 52, maturity: 52 },
  { day: 25, temp: 55, humidity: 50, maturity: 68 },
  { day: 30, temp: 48, humidity: 48, maturity: 80 },
  { day: 40, temp: 38, humidity: 45, maturity: 95 },
];

export const diseaseDetection = (t: TFunction) => [
  { name: t("diseases.healthy"), value: 94.2 },
  { name: t("diseases.coccidiosis"), value: 2.8 },
  { name: t("diseases.newcastle"), value: 1.2 },
  { name: t("diseases.gumboro"), value: 1.0 },
  { name: t("diseases.bronchitis"), value: 0.8 },
];

export const cooperatorTiers = (t: TFunction) => [
  { tier: t("tiers.bronze"), count: 28, fill: "var(--color-chart-2)" },
  { tier: t("tiers.silver"), count: 14, fill: "var(--color-chart-3)" },
  { tier: t("tiers.gold"), count: 7, fill: "var(--color-chart-1)" },
  { tier: t("tiers.platinum"), count: 3, fill: "var(--color-chart-5)" },
];

export const cashflow = Array.from({ length: 36 }, (_, i) => {
  const month = i + 1;
  const revenue = 8 + month * 0.9 + Math.sin(i / 3) * 2;
  const cost = 6 + month * 0.55 + Math.cos(i / 4) * 1.2;
  return {
    m: `M${month}`,
    revenue: +revenue.toFixed(2),
    cost: +cost.toFixed(2),
    profit: +(revenue - cost).toFixed(2),
  };
});

export const marketPrice = [
  { w: "S1", ecotec: 2850, market: 2600 },
  { w: "S2", ecotec: 2880, market: 2620 },
  { w: "S3", ecotec: 2900, market: 2650 },
  { w: "S4", ecotec: 2920, market: 2680 },
  { w: "S5", ecotec: 2950, market: 2700 },
  { w: "S6", ecotec: 2980, market: 2720 },
  { w: "S7", ecotec: 3010, market: 2750 },
  { w: "S8", ecotec: 3040, market: 2780 },
];

export const radarPerf = [
  { axis: "Bio-sécurité", A: 92 },
  { axis: "IA Vision", A: 91 },
  { axis: "IoT", A: 88 },
  { axis: "Coopérative", A: 85 },
  { axis: "Compost", A: 90 },
  { axis: "Finance", A: 78 },
];

// Histogramme — distribution des poids des poulets (g) à J35
export const weightHistogram = [
  { bucket: "1.2-1.4", count: 42 },
  { bucket: "1.4-1.6", count: 128 },
  { bucket: "1.6-1.8", count: 384 },
  { bucket: "1.8-2.0", count: 612 },
  { bucket: "2.0-2.2", count: 498 },
  { bucket: "2.2-2.4", count: 226 },
  { bucket: "2.4-2.6", count: 84 },
  { bucket: "2.6-2.8", count: 26 },
];

// Histogramme — répartition de la mortalité par tranche d'âge (jours)
export const mortalityAgeHistogram = [
  { bucket: "0-7j", count: 38 },
  { bucket: "8-14j", count: 24 },
  { bucket: "15-21j", count: 18 },
  { bucket: "22-28j", count: 14 },
  { bucket: "29-35j", count: 22 },
  { bucket: "36-42j", count: 31 },
];

// Barres groupées — production hebdomadaire par bâtiment
export const buildingProduction = [
  { b: "B1", broilers: 1240, eggs: 820 },
  { b: "B2", broilers: 1380, eggs: 940 },
  { b: "B3", broilers: 980, eggs: 760 },
  { b: "B4", broilers: 1520, eggs: 1080 },
  { b: "B5", broilers: 1180, eggs: 880 },
  { b: "B6", broilers: 1340, eggs: 920 },
];

// Barres horizontales — top coopérants (FCFA)
export const topCooperators = [
  { name: "Mballa F.", revenue: 4.8 },
  { name: "Ngono J.", revenue: 4.2 },
  { name: "Tchoumi P.", revenue: 3.9 },
  { name: "Essomba R.", revenue: 3.4 },
  { name: "Atangana L.", revenue: 3.1 },
  { name: "Kamdem S.", revenue: 2.7 },
  { name: "Bell M.", revenue: 2.3 },
];

// Barres empilées — coûts opérationnels mensuels
export const opCosts = (t: TFunction) => [
  { m: t("months.jan"), feed: 4.2, labor: 1.8, energy: 0.9, vet: 0.6 },
  { m: t("months.feb"), feed: 4.4, labor: 1.8, energy: 0.95, vet: 0.5 },
  { m: t("months.mar"), feed: 4.6, labor: 1.9, energy: 1.0, vet: 0.7 },
  { m: t("months.apr"), feed: 4.8, labor: 1.9, energy: 1.05, vet: 0.6 },
  { m: t("months.may"), feed: 5.0, labor: 2.0, energy: 1.1, vet: 0.55 },
  { m: t("months.jun"), feed: 5.1, labor: 2.0, energy: 1.15, vet: 0.65 },
  { m: t("months.jul"), feed: 5.3, labor: 2.1, energy: 1.2, vet: 0.7 },
  { m: t("months.aug"), feed: 5.4, labor: 2.1, energy: 1.25, vet: 0.6 },
];

