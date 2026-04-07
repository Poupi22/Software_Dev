import { createFileRoute } from "@tanstack/react-router";
import HeroSection from "@/components/home/HeroSection";
import FeaturedProducts from "@/components/home/FeaturedProducts";
import CategoryCards from "@/components/home/CategoryCards";
import StatsCounter from "@/components/home/StatsCounter";
import Footer from "@/components/layout/Footer";

export const Route = createFileRoute("/")({
  head: () => ({
    meta: [
      { title: "DreamRest — Matelas Premium Made in France" },
      { name: "description", content: "Matelas mémoire de forme, ressorts, latex et hybride. 100 nuits d'essai, livraison 48h." },
      { property: "og:title", content: "DreamRest — Matelas Premium" },
      { property: "og:description", content: "Le sommeil que vous méritez." },
    ],
  }),
  component: Index,
});

function Index() {
  return (
    <>
      <HeroSection />
      <FeaturedProducts />
      <CategoryCards />
      <StatsCounter />
      <Footer />
    </>
  );
}
