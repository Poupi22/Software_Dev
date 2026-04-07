import { createFileRoute } from "@tanstack/react-router";
import { Mail, Trash2, Send } from "lucide-react";
import { useState, useEffect } from "react";
import { motion } from "framer-motion";
import AdminModal from "@/components/admin/AdminModal";
import NewsletterForm from "@/components/admin/NewsletterForm";
import { api } from "@/lib/api";

export const Route = createFileRoute("/admin/newsletter")({
  component: AdminNewsletter,
});

interface Subscriber {
  id: string;
  email: string;
  is_active: boolean;
  created_at: string;
}

function AdminNewsletter() {
  const [subscribers, setSubscribers] = useState<Subscriber[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [modalOpen, setModalOpen] = useState(false);
  const [sending, setSending] = useState(false);

  useEffect(() => {
    loadSubscribers();
  }, []);

  const loadSubscribers = async () => {
    try {
      setLoading(true);
      const data = await api.get('/newsletter/subscribers');
      setSubscribers(data);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (email: string) => {
    if (!confirm(`Supprimer ${email} de la newsletter ?`)) return;
    
    try {
      const token = api.getToken();
      await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:5000/api'}/newsletter/subscribers/${email}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });
      setSubscribers(subscribers.filter((s) => s.email !== email));
    } catch (err: any) {
      alert('Erreur lors de la suppression');
    }
  };

  const handleSend = async (data: { subject: string; content: string }) => {
    setSending(true);
    try {
      const token = api.getToken();
      const response = await fetch(`${import.meta.env.VITE_API_URL || 'http://localhost:5000/api'}/newsletter/send`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      
      if (!response.ok) throw new Error('Erreur envoi');
      
      alert(`Newsletter "${data.subject}" envoyée à ${subscribers.length} abonnés !`);
      setModalOpen(false);
    } catch (err: any) {
      alert('Erreur lors de l\'envoi: ' + err.message);
    } finally {
      setSending(false);
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };

  if (loading) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement des abonnés...</p>
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
          <h1 className="text-2xl font-bold text-foreground">Newsletter</h1>
          <p className="text-muted-foreground">{subscribers.length} abonnés actifs</p>
        </div>
        <div className="flex items-center gap-3">
          <div className="flex items-center gap-3 rounded-xl border border-border bg-card px-4 py-3">
            <Mail className="h-5 w-5 text-primary" />
            <div>
              <div className="text-2xl font-bold text-foreground">{subscribers.length}</div>
              <div className="text-xs text-muted-foreground">Abonnés</div>
            </div>
          </div>
          <button
            onClick={() => setModalOpen(true)}
            disabled={subscribers.length === 0}
            className="inline-flex items-center gap-2 rounded-lg bg-gradient-blue px-4 py-2.5 text-sm font-medium text-white shadow-md hover:scale-105 transition-transform disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <Send className="h-4 w-4" /> Envoyer
          </button>
        </div>
      </div>

      <div className="overflow-x-auto rounded-2xl border border-border bg-card shadow-sm">
        <table className="w-full text-sm">
          <thead>
            <tr className="border-b border-border text-left">
              <th className="px-4 py-3 font-medium text-muted-foreground">Email</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Date d'inscription</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Statut</th>
              <th className="px-4 py-3 font-medium text-muted-foreground">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-border">
            {subscribers.length === 0 ? (
              <tr>
                <td colSpan={4} className="px-4 py-8 text-center text-muted-foreground">
                  Aucun abonné pour le moment
                </td>
              </tr>
            ) : (
              subscribers.map((sub, i) => (
                <motion.tr key={sub.id} initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: i * 0.05 }}>
                  <td className="px-4 py-3 font-medium text-foreground">{sub.email}</td>
                  <td className="px-4 py-3 text-muted-foreground">{formatDate(sub.created_at)}</td>
                  <td className="px-4 py-3">
                    <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium ${sub.is_active ? 'bg-success/10 text-success' : 'bg-muted text-muted-foreground'}`}>
                      {sub.is_active ? 'Actif' : 'Inactif'}
                    </span>
                  </td>
                  <td className="px-4 py-3">
                    <button 
                      onClick={() => handleDelete(sub.email)} 
                      className="rounded-lg p-1.5 text-muted-foreground hover:bg-destructive/10 hover:text-destructive"
                    >
                      <Trash2 className="h-4 w-4" />
                    </button>
                  </td>
                </motion.tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      <AdminModal open={modalOpen} onClose={() => setModalOpen(false)} title="Envoyer une newsletter">
        <NewsletterForm onSubmit={handleSend} onCancel={() => setModalOpen(false)} sending={sending} />
      </AdminModal>
    </div>
  );
}