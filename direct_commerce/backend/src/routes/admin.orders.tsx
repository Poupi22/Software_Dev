import { createFileRoute } from "@tanstack/react-router";
import { motion } from "framer-motion";
import { Eye, Search } from "lucide-react";
import { useState, useEffect } from "react";
import { api, type WhatsAppInquiry } from "@/lib/api";

interface Order {
  id: string;
  client: string;
  email: string;
  phone: string;
  country: string;
  town: string;
  address: string;
  product: string;
  productId: string;
  productPrice: number;
  amount: number;
  via: "WhatsApp";
  date: string;
}

const formatFCFA = (price: number) => {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'XAF',
    maximumFractionDigits: 0
  }).format(price);
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

function AdminOrdersPage() {
  const [inquiries, setInquiries] = useState<WhatsAppInquiry[]>([]);
  const [products, setProducts] = useState<Record<string, any>>({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [search, setSearch] = useState("");

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      setLoading(true);
      const [inquiriesData, productsData] = await Promise.all([
        api.getWhatsAppInquiries(),
        api.getProducts()
      ]);
      
      setInquiries(inquiriesData);
      
      const productsMap: Record<string, any> = {};
      productsData.forEach(p => {
        productsMap[p.id] = p;
      });
      setProducts(productsMap);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const orders: Order[] = inquiries.map(inquiry => {
    const product = products[inquiry.product_id];
    return {
      id: inquiry.id.slice(0, 8).toUpperCase(),
      client: `${inquiry.name} ${inquiry.surname}`,
      email: inquiry.email,
      phone: `${inquiry.country_code} ${inquiry.phone_number}`,
      country: inquiry.country,
      town: inquiry.town,
      address: inquiry.address,
      product: product?.name || 'Produit inconnu',
      productId: inquiry.product_id,
      productPrice: product?.price || 0,
      amount: product?.price || 0,
      via: "WhatsApp",
      date: formatDate(inquiry.created_at)
    };
  });

  const filtered = orders.filter((o) =>
    o.client.toLowerCase().includes(search.toLowerCase()) ||
    o.id.toLowerCase().includes(search.toLowerCase()) ||
    o.product.toLowerCase().includes(search.toLowerCase())
  );

  const total = orders.reduce((s, o) => s + o.amount, 0);

  if (loading) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement des commandes...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center text-destructive">
          <p>Erreur lors du chargement</p>
          <p className="text-sm text-muted-foreground">{error}</p>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-foreground">Commandes WhatsApp</h1>
          <p className="text-muted-foreground">{orders.length} commandes — {formatFCFA(total)} générés</p>
        </div>
      </div>

      <div className="relative">
        <Search className="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
        <input
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Rechercher par client, n° de commande, produit…"
          className="w-full rounded-lg border border-input bg-card py-2.5 pl-10 pr-4 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
        />
      </div>

      <div className="overflow-x-auto rounded-2xl border border-border bg-card shadow-sm">
        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-border text-left">
              <th className="px-4 py-3 font-medium text-muted-foreground">N°</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Client</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Produit</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Montant</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Canal</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Date</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-border">
            {filtered.map((o, i) => (
              <motion.tr
                key={o.id}
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ delay: Math.min(i * 0.03, 0.4) }}
              >
                <td className="px-4 py-3 font-mono text-xs text-foreground">#{o.id}</td>
                <td className="px-4 py-3">
                  <div className="font-medium text-foreground">{o.client}</div>
                  <div className="text-xs text-muted-foreground">{o.country}, {o.town}</div>
                  <div className="text-xs text-muted-foreground">{o.phone}</div>
                </td>
                <td className="px-4 py-3 text-muted-foreground">{o.product}</td>
                <td className="px-4 py-3 font-semibold text-foreground">{formatFCFA(o.amount)}</td>
                <td className="px-4 py-3">
                  <span className="rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">
                    {o.via}
                  </span>
                </td>
                <td className="px-4 py-3 text-xs text-muted-foreground">{o.date}</td>
              </motion.tr>
            ))}
            {filtered.length === 0 && (
              <tr><td colSpan={6} className="px-4 py-8 text-center text-sm text-muted-foreground">
                <Eye className="mx-auto mb-2 h-5 w-5 opacity-50" />
                Aucune commande trouvée
              </td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export const Route = createFileRoute("/admin/orders")({
  component: AdminOrdersPage,
});