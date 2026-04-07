import { createFileRoute } from "@tanstack/react-router";
import { motion } from "framer-motion";
import { useState, useEffect } from "react";
import { api } from "@/lib/api";

export const Route = createFileRoute("/admin/analytics")({
  component: AdminAnalytics,
});

function AdminAnalytics() {
  const [stats, setStats] = useState({
    totalProducts: 0,
    totalCategories: 0,
    totalInquiries: 0,
    totalMessages: 0,
    totalRevenue: 0,
    recentInquiries: [] as any[],
    productsByCategory: {} as Record<string, number>
  });
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadStats();
  }, []);

  const loadStats = async () => {
    try {
      const [products, categories, inquiries, messages] = await Promise.all([
        api.getProducts(),
        api.getCategories(),
        api.getWhatsAppInquiries(),
        api.getContactMessages()
      ]);

      const productsByCategory: Record<string, number> = {};
      products.forEach(p => {
        if (p.category_name) {
          productsByCategory[p.category_name] = (productsByCategory[p.category_name] || 0) + 1;
        }
      });

      const totalRevenue = inquiries.reduce((sum, i) => {
        const product = products.find(p => p.id === i.product_id);
        return sum + (product?.price || 0);
      }, 0);

      setStats({
        totalProducts: products.length,
        totalCategories: categories.length,
        totalInquiries: inquiries.length,
        totalMessages: messages.length,
        totalRevenue,
        recentInquiries: inquiries.slice(-5).reverse(),
        productsByCategory
      });
    } catch (err) {
      console.error('Erreur chargement stats:', err);
    } finally {
      setLoading(false);
    }
  };

  const formatFCFA = (price: number) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'XAF',
      maximumFractionDigits: 0
    }).format(price);
  };

  if (loading) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement des statistiques...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-8">
      <div>
        <h1 className="text-2xl font-bold text-foreground">Tableau de bord</h1>
        <p className="text-muted-foreground">Vue d'ensemble de votre activité</p>
      </div>

      <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          className="rounded-2xl border border-border bg-card p-6 shadow-sm"
        >
          <div className="text-sm text-muted-foreground">Total Produits</div>
          <div className="mt-2 text-3xl font-bold text-foreground">{stats.totalProducts}</div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.1 }}
          className="rounded-2xl border border-border bg-card p-6 shadow-sm"
        >
          <div className="text-sm text-muted-foreground">Catégories</div>
          <div className="mt-2 text-3xl font-bold text-foreground">{stats.totalCategories}</div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.2 }}
          className="rounded-2xl border border-border bg-card p-6 shadow-sm"
        >
          <div className="text-sm text-muted-foreground">Commandes WhatsApp</div>
          <div className="mt-2 text-3xl font-bold text-foreground">{stats.totalInquiries}</div>
        </motion.div>

        <motion.div
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ delay: 0.3 }}
          className="rounded-2xl border border-border bg-card p-6 shadow-sm"
        >
          <div className="text-sm text-muted-foreground">Chiffre d'affaires</div>
          <div className="mt-2 text-3xl font-bold text-primary">{formatFCFA(stats.totalRevenue)}</div>
        </motion.div>
      </div>

      <div className="grid gap-6 lg:grid-cols-2">
        <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
          <h3 className="mb-4 font-semibold text-foreground">Produits par catégorie</h3>
          {Object.keys(stats.productsByCategory).length > 0 ? (
            <div className="space-y-3">
              {Object.entries(stats.productsByCategory).map(([category, count], i) => (
                <div key={category}>
                  <div className="mb-1 flex items-center justify-between text-sm">
                    <span className="text-foreground">{category}</span>
                    <span className="font-medium text-primary">{count} produit{count > 1 ? 's' : ''}</span>
                  </div>
                  <div className="h-2 overflow-hidden rounded-full bg-secondary">
                    <motion.div
                      initial={{ width: 0 }}
                      animate={{ width: `${(count / stats.totalProducts) * 100}%` }}
                      transition={{ delay: i * 0.1, duration: 0.6 }}
                      className="h-full rounded-full bg-gradient-blue"
                    />
                  </div>
                </div>
              ))}
            </div>
          ) : (
            <p className="text-sm text-muted-foreground">Aucun produit</p>
          )}
        </div>

        <div className="rounded-2xl border border-border bg-card p-6 shadow-sm">
          <h3 className="mb-4 font-semibold text-foreground">Dernières commandes</h3>
          {stats.recentInquiries.length > 0 ? (
            <div className="space-y-3">
              {stats.recentInquiries.map((inquiry, i) => (
                <motion.div
                  key={inquiry.id}
                  initial={{ opacity: 0, x: -20 }}
                  animate={{ opacity: 1, x: 0 }}
                  transition={{ delay: i * 0.1 }}
                  className="flex items-center justify-between border-b border-border pb-2 last:border-0"
                >
                  <div>
                    <div className="font-medium text-foreground">{inquiry.name} {inquiry.surname}</div>
                    <div className="text-xs text-muted-foreground">{inquiry.country}, {inquiry.town}</div>
                  </div>
                  <div className="text-sm text-muted-foreground">
                    {new Date(inquiry.created_at).toLocaleDateString('fr-FR')}
                  </div>
                </motion.div>
              ))}
            </div>
          ) : (
            <p className="text-sm text-muted-foreground">Aucune commande récente</p>
          )}
        </div>
      </div>
    </div>
  );
}