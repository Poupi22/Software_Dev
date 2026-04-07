import { createFileRoute } from "@tanstack/react-router";
import { motion } from "framer-motion";
import { Package, ShoppingBag, MessageCircle, TrendingUp, ArrowUpRight } from "lucide-react";
import { useState, useEffect } from "react";
import { api, type Product, type WhatsAppInquiry, type ContactMessage } from "@/lib/api";

// Variable d'environnement pour l'API (sans /api à la fin pour les images)
const API_URL = import.meta.env.VITE_API_URL || "http://localhost:5000/api";

export const Route = createFileRoute("/admin/")({
  component: AdminOverview,
});

// Fonction utilitaire pour obtenir l'URL complète des images
const getImageUrl = (imagePath: string | null): string => {
  if (!imagePath) return 'https://via.placeholder.com/40x40?text=No+Image';
  if (imagePath.startsWith('http')) return imagePath;
  const baseUrl = API_URL.replace('/api', '');
  return `${baseUrl}${imagePath}`;
};

function AdminOverview() {
  const [stats, setStats] = useState({
    totalProducts: 0,
    totalInquiries: 0,
    totalMessages: 0,
    recentInquiries: [] as WhatsAppInquiry[],
    products: [] as Product[],
    productInquiryCounts: {} as Record<string, number>
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadStats();
  }, []);

  const loadStats = async () => {
    try {
      const [products, inquiries, messages] = await Promise.all([
        api.getProducts(),
        api.getWhatsAppInquiries(),
        api.getContactMessages()
      ]);

      const productInquiryCounts: Record<string, number> = {};
      inquiries.forEach(inq => {
        if (inq.product_id) {
          productInquiryCounts[inq.product_id] = (productInquiryCounts[inq.product_id] || 0) + 1;
        }
      });

      const sortedInquiries = [...inquiries].sort((a, b) => 
        new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
      );

      setStats({
        totalProducts: products.length,
        totalInquiries: inquiries.length,
        totalMessages: messages.length,
        recentInquiries: sortedInquiries.slice(0, 5),
        products,
        productInquiryCounts
      });
    } catch (err) {
      console.error('Erreur chargement stats:', err);
    } finally {
      setLoading(false);
    }
  };

  const formatTimeAgo = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now.getTime() - date.getTime();
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return "à l'instant";
    if (diffMins < 60) return `il y a ${diffMins} min`;
    if (diffHours < 24) return `il y a ${diffHours} h`;
    return `il y a ${diffDays} j`;
  };

  const topProducts = [...stats.products]
    .map(p => ({
      ...p,
      inquiryCount: stats.productInquiryCounts[p.id] || 0
    }))
    .sort((a, b) => b.inquiryCount - a.inquiryCount)
    .slice(0, 5);

  const kpis = [
    { 
      label: "Total Produits", 
      value: stats.totalProducts.toString(), 
      change: `+${stats.totalProducts}`, 
      icon: Package 
    },
    { 
      label: "Commandes WhatsApp", 
      value: stats.totalInquiries.toString(), 
      change: `+${stats.totalInquiries}`, 
      icon: ShoppingBag 
    },
    { 
      label: "Messages contact", 
      value: stats.totalMessages.toString(), 
      change: `+${stats.totalMessages}`, 
      icon: MessageCircle 
    },
    { 
      label: "Taux conversion", 
      value: stats.totalProducts > 0 ? `${Math.round((stats.totalInquiries / stats.totalProducts) * 10)}%` : "0%", 
      change: "+0%", 
      icon: TrendingUp 
    },
  ];

  if (loading) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement du tableau de bord...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-foreground">Tableau de bord</h1>
        <p className="text-muted-foreground">Bienvenue ! Voici un aperçu de votre activité.</p>
      </div>

      <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {kpis.map((kpi, i) => (
          <motion.div
            key={kpi.label}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: i * 0.1 }}
            whileHover={{ y: -4 }}
            className="rounded-2xl border border-border bg-card p-5 shadow-sm"
          >
            <div className="flex items-center justify-between">
              <div className="rounded-lg bg-gradient-blue/10 p-2">
                <kpi.icon className="h-5 w-5 text-primary" />
              </div>
              <span className="inline-flex items-center gap-0.5 text-xs font-medium text-success">
                <ArrowUpRight className="h-3 w-3" /> {kpi.change}
              </span>
            </div>
            <div className="mt-3 text-2xl font-bold text-foreground">{kpi.value}</div>
            <div className="text-sm text-muted-foreground">{kpi.label}</div>
          </motion.div>
        ))}
      </div>

      <div className="grid gap-6 lg:grid-cols-2">
        <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
          <h3 className="mb-4 font-semibold text-foreground">Produits les plus demandés</h3>
          {topProducts.length > 0 ? (
            <div className="space-y-3">
              {topProducts.map((p, i) => (
                <motion.div
                  key={p.id}
                  initial={{ opacity: 0, x: -10 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: i * 0.08 }}
                  className="flex items-center gap-3"
                >
                  <span className="flex h-8 w-8 items-center justify-center rounded-lg bg-secondary text-xs font-bold text-muted-foreground">
                    {i + 1}
                  </span>
                  <img 
                    src={getImageUrl(p.main_image)} 
                    alt={p.name} 
                    className="h-10 w-10 rounded-lg object-cover"
                  />
                  <div className="flex-1">
                    <div className="text-sm font-medium text-foreground">{p.name}</div>
                    <div className="text-xs text-muted-foreground">{p.category_name || 'Sans catégorie'}</div>
                  </div>
                  <div className="text-sm font-semibold text-primary">
                    {p.inquiryCount} demande{p.inquiryCount > 1 ? 's' : ''}
                  </div>
                </motion.div>
              ))}
            </div>
          ) : (
            <p className="text-sm text-muted-foreground">Aucun produit</p>
          )}
        </div>

        <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
          <h3 className="mb-4 font-semibold text-foreground">Activité récente</h3>
          <div className="space-y-2">
            {stats.recentInquiries.length > 0 ? (
              stats.recentInquiries.map((inq, i) => (
                <motion.div
                  key={inq.id}
                  initial={{ opacity: 0, y: 5 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ delay: i * 0.05 }}
                  className="flex items-center justify-between border-b border-border py-2 last:border-0"
                >
                  <div>
                    <div className="font-medium text-foreground">{inq.name} {inq.surname}</div>
                    <div className="text-xs text-muted-foreground">{inq.country}, {inq.town}</div>
                  </div>
                  <div className="text-xs text-muted-foreground">{formatTimeAgo(inq.created_at)}</div>
                </motion.div>
              ))
            ) : (
              <p className="text-sm text-muted-foreground">Aucune activité récente</p>
            )}
          </div>
        </div>
      </div>

      <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
        <h3 className="mb-4 font-semibold text-foreground">Dernières commandes WhatsApp</h3>
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead>
              <tr className="border-b border-border text-left">
                <th className="pb-3 font-medium text-muted-foreground">Client</th>
                <th className="pb-3 font-medium text-muted-foreground">Contact</th>
                <th className="pb-3 font-medium text-muted-foreground">Localisation</th>
                <th className="pb-3 font-medium text-muted-foreground">Quand</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border">
              {stats.recentInquiries.map((inq) => (
                <tr key={inq.id}>
                  <td className="py-3 font-medium text-foreground">{inq.name} {inq.surname}</td>
                  <td className="py-3 text-muted-foreground">
                    <div>{inq.email}</div>
                    <div className="text-xs">{inq.country_code} {inq.phone_number}</div>
                  </td>
                  <td className="py-3 text-muted-foreground">{inq.town}, {inq.country}</td>
                  <td className="py-3 text-muted-foreground">{formatTimeAgo(inq.created_at)}</td>
                </tr>
              ))}
              {stats.recentInquiries.length === 0 && (
                <tr>
                  <td colSpan={4} className="py-8 text-center text-muted-foreground">
                    Aucune commande récente
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}