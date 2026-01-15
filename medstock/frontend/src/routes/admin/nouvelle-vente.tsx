import { createFileRoute } from "@tanstack/react-router";
import { useMemo, useState, useEffect } from "react";
import { AdminLayout, PageHeader } from "@/components/admin/Layout";
import { useAuth } from "@/lib/auth";
import { Search, Plus, Minus, Trash2, ShoppingCart, Printer, CheckCircle2, Pill, Package, Send, User, Phone } from "lucide-react";
import { toast } from "sonner";
import { Dialog, DialogContent } from "@/components/ui/dialog";
import { medicamentsApi, type Medicament } from "@/lib/medicaments";
import { ventesApi } from "@/lib/ventes";

export const Route = createFileRoute("/admin/nouvelle-vente")({
  component: NouvelleVentePage,
});

type CartItem = { med: Medicament; qty: number };

function NouvelleVentePage() {
  const { user } = useAuth();
  const [medicaments, setMedicaments] = useState<Medicament[]>([]);
  const [loading, setLoading] = useState(true);
  const [query, setQuery] = useState("");
  const [cart, setCart] = useState<CartItem[]>([]);
  const [clientNom, setClientNom] = useState("");
  const [clientWhatsapp, setClientWhatsapp] = useState("");
  const [clientTelephone, setClientTelephone] = useState("");
  const [invoice, setInvoice] = useState<{ numero: string; date: string; items: CartItem[]; total: number; clientNom: string; clientWhatsapp: string } | null>(null);

  useEffect(() => {
    fetchMedicaments();
  }, []);

  const fetchMedicaments = async () => {
    setLoading(true);
    try {
      const res = await medicamentsApi.getAll();
      setMedicaments(res.data.data);
    } catch (error) {
      console.error("Error fetching medicaments:", error);
      toast.error("Erreur lors du chargement des medicaments");
    } finally {
      setLoading(false);
    }
  };

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase();
    if (!q) return medicaments.slice(0, 8);
    return medicaments.filter(
      (m) =>
        m.nom.toLowerCase().includes(q) ||
        (m.code_barre && m.code_barre.includes(q))
    ).slice(0, 12);
  }, [query, medicaments]);

  const total = cart.reduce((s, c) => s + c.med.prix_vente * c.qty, 0);

  const addToCart = (med: Medicament) => {
    if (med.quantite <= 0) {
      toast.error("Stock epuise", { description: med.nom });
      return;
    }
    setCart((prev) => {
      const existing = prev.find((c) => c.med.id === med.id);
      if (existing) {
        if (existing.qty + 1 > med.quantite) {
          toast.warning("Stock insuffisant", { description: `Reste ${med.quantite} en stock` });
          return prev;
        }
        return prev.map((c) => (c.med.id === med.id ? { ...c, qty: c.qty + 1 } : c));
      }
      return [...prev, { med, qty: 1 }];
    });
    toast.success("Ajoute au panier", { description: med.nom });
  };

  const updateQty = (id: string, delta: number) => {
    setCart((prev) =>
      prev
        .map((c) => {
          if (c.med.id !== id) return c;
          const next = c.qty + delta;
          if (next > c.med.quantite) {
            toast.warning("Stock insuffisant");
            return c;
          }
          return { ...c, qty: next };
        })
        .filter((c) => c.qty > 0)
    );
  };

  const removeItem = (id: string) => setCart((prev) => prev.filter((c) => c.med.id !== id));

  const validateSale = async () => {
    if (cart.length === 0) {
      toast.error("Panier vide");
      return;
    }

    try {
      const response = await ventesApi.create({
        client_nom: clientNom || undefined,
        client_telephone: clientTelephone || undefined,
        client_whatsapp: clientWhatsapp || undefined,
        items: cart.map(item => ({
          medicament_id: item.med.id,
          quantite: item.qty,
          prix_unitaire: item.med.prix_vente
        })),
        mode_paiement: "ESPECES"
      });

      const vente = response.data.data;
      
      setInvoice({
        numero: vente.numero,
        date: new Date().toISOString(),
        items: [...cart],
        total,
        clientNom: clientNom,
        clientWhatsapp: clientWhatsapp
      });
      
      toast.success("Vente validee", { description: `${total.toLocaleString()} FCFA encaisse` });
      setCart([]);
      setClientNom("");
      setClientWhatsapp("");
      setClientTelephone("");
      fetchMedicaments();
    } catch (error: any) {
      const msg = error.response?.data?.message || "Erreur lors de la validation";
      toast.error(msg);
    }
  };

  const sendWhatsApp = () => {
    if (!invoice || !invoice.clientWhatsapp) {
      toast.error("Numero WhatsApp non renseigne");
      return;
    }
    
    const message = `?? *PHARMACARE - FACTURE* ??\n\n` +
      `?? N°: ${invoice.numero}\n` +
      `?? Date: ${new Date(invoice.date).toLocaleString('fr-FR')}\n` +
      `?? Client: ${invoice.clientNom || "Non renseigne"}\n` +
      `????????????????????\n` +
      `*DETAILS DES PRODUITS:*\n` +
      invoice.items.map(item => 
        `• ${item.med.nom}\n  ${item.qty} x ${item.med.prix_vente.toLocaleString()} FCFA = ${(item.med.prix_vente * item.qty).toLocaleString()} FCFA`
      ).join('\n') +
      `\n????????????????????\n` +
      `?? *TOTAL: ${invoice.total.toLocaleString()} FCFA*\n` +
      `????????????????????\n` +
      `? Vente validee le ${new Date().toLocaleDateString('fr-FR')}\n` +
      `?? Merci pour votre confiance !\n` +
      `?? PharmaCare - Votre sante notre priorite`;
    
    const whatsappUrl = `https://wa.me/${invoice.clientWhatsapp.replace(/\D/g, '')}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
  };

  const printInvoice = () => window.print();

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
  };

  if (loading) {
    return (
      <AdminLayout>
        <div className="flex h-96 items-center justify-center">
          <div className="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent" />
        </div>
      </AdminLayout>
    );
  }

  return (
    <AdminLayout>
      <PageHeader
        title="Nouvelle vente"
        description="Recherchez, composez le panier et encaissez votre client."
      />

      <div className="grid gap-6 lg:grid-cols-[1fr_420px]">
        {/* LEFT — Search & products */}
        <div className="space-y-4">
          <div className="relative">
            <Search className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
            <input
              autoFocus
              type="search"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              placeholder="Rechercher par nom ou code-barre..."
              className="h-14 w-full rounded-2xl border bg-card pl-12 pr-4 text-base shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/30"
            />
          </div>

          {filtered.length === 0 ? (
            <div className="rounded-2xl border border-dashed bg-card p-12 text-center">
              <Package className="mx-auto h-12 w-12 text-muted-foreground opacity-30" />
              <div className="mt-3 text-sm text-muted-foreground">Aucun medicament trouve pour "{query}"</div>
            </div>
          ) : (
            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
              {filtered.map((m) => {
                const out = m.quantite <= 0;
                const low = m.quantite > 0 && m.quantite <= m.seuil_alerte;
                return (
                  <button
                    key={m.id}
                    onClick={() => addToCart(m)}
                    disabled={out}
                    className="group flex flex-col overflow-hidden rounded-2xl border bg-card text-left shadow-sm transition hover:-translate-y-0.5 hover:border-primary hover:shadow-md disabled:cursor-not-allowed disabled:opacity-50"
                  >
                    <div className="relative h-28 overflow-hidden bg-muted">
                      {m.image_url ? (
                        <img src={m.image_url} alt={m.nom} loading="lazy" className="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                      ) : (
                        <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-primary/20 to-primary/5">
                          <Pill className="h-8 w-8 text-primary/40" />
                        </div>
                      )}
                      <span
                        className={`absolute right-2 top-2 rounded-full px-2 py-0.5 text-[10px] font-semibold backdrop-blur ${
                          out
                            ? "bg-red-500/90 text-white"
                            : low
                              ? "bg-yellow-500/90 text-white"
                              : "bg-green-500/90 text-white"
                        }`}
                      >
                        {out ? "Rupture" : `${m.quantite} en stock`}
                      </span>
                    </div>
                    <div className="flex flex-1 flex-col gap-1 p-3">
                      <div className="line-clamp-1 text-sm font-semibold">{m.nom}</div>
                      <div className="mt-auto flex items-center justify-between pt-2">
                        <span className="text-base font-bold text-primary">{formatPrice(m.prix_vente)}</span>
                        <span className="rounded-lg bg-primary/10 p-1.5 text-primary opacity-0 transition group-hover:opacity-100">
                          <Plus className="h-3.5 w-3.5" />
                        </span>
                      </div>
                    </div>
                  </button>
                );
              })}
            </div>
          )}
        </div>

        {/* RIGHT — Cart & Client Info */}
        <aside className="lg:sticky lg:top-20 lg:h-[calc(100vh-7rem)]">
          <div className="flex h-full flex-col overflow-hidden rounded-2xl border bg-card shadow-sm">
            <div className="flex items-center justify-between border-b px-5 py-4">
              <div className="flex items-center gap-2">
                <ShoppingCart className="h-5 w-5 text-primary" />
                <h3 className="font-semibold">Panier</h3>
              </div>
              <span className="rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                {cart.length} article{cart.length > 1 ? "s" : ""}
              </span>
            </div>

            <div className="flex-1 overflow-y-auto scrollbar-thin px-3 py-2">
              {cart.length === 0 ? (
                <div className="flex h-full flex-col items-center justify-center px-6 text-center">
                  <ShoppingCart className="h-10 w-10 text-muted-foreground opacity-30" />
                  <div className="mt-3 text-sm font-medium text-muted-foreground">Panier vide</div>
                  <div className="mt-1 text-xs text-muted-foreground">
                    Cliquez sur un medicament pour l'ajouter
                  </div>
                </div>
              ) : (
                <ul className="space-y-2">
                  {cart.map((c) => (
                    <li key={c.med.id} className="rounded-xl border bg-background p-3">
                      <div className="flex items-start justify-between gap-2">
                        <div className="min-w-0 flex-1">
                          <div className="line-clamp-1 text-sm font-medium">{c.med.nom}</div>
                          <div className="text-[11px] text-muted-foreground">{formatPrice(c.med.prix_vente)} / unite</div>
                        </div>
                        <button
                          onClick={() => removeItem(c.med.id)}
                          className="rounded-md p-1 text-muted-foreground hover:bg-red-100 hover:text-red-600"
                        >
                          <Trash2 className="h-3.5 w-3.5" />
                        </button>
                      </div>
                      <div className="mt-2 flex items-center justify-between">
                        <div className="flex items-center gap-1.5 rounded-lg border">
                          <button onClick={() => updateQty(c.med.id, -1)} className="rounded-l-md p-1.5 hover:bg-muted">
                            <Minus className="h-3 w-3" />
                          </button>
                          <span className="min-w-7 text-center text-sm font-semibold">{c.qty}</span>
                          <button onClick={() => updateQty(c.med.id, 1)} className="rounded-r-md p-1.5 hover:bg-muted">
                            <Plus className="h-3 w-3" />
                          </button>
                        </div>
                        <span className="text-sm font-bold">{formatPrice(c.med.prix_vente * c.qty)}</span>
                      </div>
                    </li>
                  ))}
                </ul>
              )}
            </div>

            {/* Client Information */}
            <div className="border-t p-4 space-y-3">
              <div className="space-y-2">
                <div className="relative">
                  <User className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                  <input
                    type="text"
                    value={clientNom}
                    onChange={(e) => setClientNom(e.target.value)}
                    placeholder="Nom du client (optionnel)"
                    className="h-10 w-full rounded-lg border bg-background pl-9 pr-3 text-sm"
                  />
                </div>
                <div className="relative">
                  <Phone className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                  <input
                    type="tel"
                    value={clientTelephone}
                    onChange={(e) => setClientTelephone(e.target.value)}
                    placeholder="Telephone (optionnel)"
                    className="h-10 w-full rounded-lg border bg-background pl-9 pr-3 text-sm"
                  />
                </div>
                <div className="relative">
                  <Send className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                  <input
                    type="tel"
                    value={clientWhatsapp}
                    onChange={(e) => setClientWhatsapp(e.target.value)}
                    placeholder="WhatsApp pour envoi facture (optionnel)"
                    className="h-10 w-full rounded-lg border bg-background pl-9 pr-3 text-sm"
                  />
                </div>
              </div>

              <div className="flex items-center justify-between border-t pt-3">
                <span className="text-sm font-semibold">Total a payer</span>
                <span className="text-2xl font-bold text-primary">{formatPrice(total)}</span>
              </div>
              <button
                onClick={validateSale}
                disabled={cart.length === 0}
                className="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-primary text-sm font-semibold text-primary-foreground transition hover:bg-primary/90 disabled:opacity-50"
              >
                <CheckCircle2 className="h-4 w-4" />
                Valider la vente
              </button>
            </div>
          </div>
        </aside>
      </div>

      {/* Invoice dialog */}
      <Dialog open={!!invoice} onOpenChange={(o) => !o && setInvoice(null)}>
        <DialogContent className="max-w-md p-0 print:max-w-full print:shadow-none">
          {invoice && (
            <div className="print-area">
              <div className="bg-primary p-6 text-primary-foreground print:bg-primary">
                <div className="flex items-center justify-between">
                  <div>
                    <div className="text-xs uppercase tracking-widest opacity-80">PharmaCare</div>
                    <div className="text-lg font-bold">Facture de vente</div>
                  </div>
                  <CheckCircle2 className="h-10 w-10 opacity-90" />
                </div>
              </div>
              <div className="space-y-4 p-6">
                <div className="grid grid-cols-2 gap-3 text-sm">
                  <div>
                    <div className="text-xs text-muted-foreground">N° Facture</div>
                    <div className="font-mono font-semibold text-primary">{invoice.numero}</div>
                  </div>
                  <div className="text-right">
                    <div className="text-xs text-muted-foreground">Date</div>
                    <div className="font-medium">{new Date(invoice.date).toLocaleString("fr-FR")}</div>
                  </div>
                  <div className="col-span-2">
                    <div className="text-xs text-muted-foreground">Vendeur</div>
                    <div className="font-medium">{user?.nom}</div>
                  </div>
                  {invoice.clientNom && (
                    <div className="col-span-2">
                      <div className="text-xs text-muted-foreground">Client</div>
                      <div className="font-medium">{invoice.clientNom}</div>
                    </div>
                  )}
                </div>

                <div className="rounded-xl border">
                  <table className="w-full text-sm">
                    <thead className="border-b text-left text-xs uppercase tracking-wider text-muted-foreground">
                      <tr>
                        <th className="px-3 py-2">Article</th>
                        <th className="px-3 py-2 text-center">Qte</th>
                        <th className="px-3 py-2 text-right">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                      {invoice.items.map((it) => (
                        <tr key={it.med.id} className="border-b last:border-0">
                          <td className="px-3 py-2">{it.med.nom}</td>
                          <td className="px-3 py-2 text-center">{it.qty}</td>
                          <td className="px-3 py-2 text-right font-medium">{formatPrice(it.med.prix_vente * it.qty)}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>

                <div className="flex items-center justify-between rounded-xl bg-primary/10 px-4 py-3">
                  <span className="text-sm font-semibold">Total paye</span>
                  <span className="text-xl font-bold text-primary">{formatPrice(invoice.total)}</span>
                </div>

                <div className="text-center text-[11px] text-muted-foreground">
                  Merci pour votre confiance — PharmaCare © {new Date().getFullYear()}
                </div>

                <div className="flex gap-2 print:hidden">
                  <button
                    onClick={() => setInvoice(null)}
                    className="flex-1 rounded-lg border bg-background px-4 py-2.5 text-sm font-medium hover:bg-muted"
                  >
                    Fermer
                  </button>
                  <button
                    onClick={printInvoice}
                    className="flex flex-1 items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-foreground hover:bg-primary/90"
                  >
                    <Printer className="h-4 w-4" />
                    Imprimer
                  </button>
                  {invoice.clientWhatsapp && (
                    <button
                      onClick={sendWhatsApp}
                      className="flex flex-1 items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700"
                    >
                      <Send className="h-4 w-4" />
                      WhatsApp
                    </button>
                  )}
                </div>
              </div>
            </div>
          )}
        </DialogContent>
      </Dialog>
    </AdminLayout>
  );
}
