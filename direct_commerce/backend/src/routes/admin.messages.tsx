import { createFileRoute } from "@tanstack/react-router";
import { MessageCircle, Trash2, ExternalLink, Check } from "lucide-react";
import { motion } from "framer-motion";
import { useState, useEffect } from "react";
import { api, type ContactMessage } from "@/lib/api";

export const Route = createFileRoute("/admin/messages")({
  component: AdminMessages,
});

interface Message {
  id: string;
  client: string;
  email: string;
  phone: string;
  message: string;
  time: string;
  read: boolean;
}

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

function AdminMessages() {
  const [messages, setMessages] = useState<Message[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [filter, setFilter] = useState<"all" | "unread">("all");

  useEffect(() => {
    loadMessages();
  }, []);

  const loadMessages = async () => {
    try {
      setLoading(true);
      const data = await api.getContactMessages();
      
      const formattedMessages: Message[] = data.map(msg => ({
        id: msg.id,
        client: `${msg.name} ${msg.surname}`,
        email: msg.email,
        phone: msg.phone_number,
        message: msg.message,
        time: formatDate(msg.created_at),
        read: false
      }));
      
      setMessages(formattedMessages);
    } catch (err: any) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const filtered = messages.filter((m) => filter === "all" || !m.read);
  const unread = messages.filter((m) => !m.read).length;

  const toggleRead = (id: string) => {
    setMessages(messages.map((m) => m.id === id ? { ...m, read: !m.read } : m));
  };

  const remove = async (id: string) => {
    if (!confirm("Supprimer ce message ?")) return;
    
    try {
      await api.deleteContactMessage(id);
      setMessages(messages.filter((m) => m.id !== id));
    } catch (err: any) {
      alert(err.message);
    }
  };

  const directLink = (m: Message) => {
    const phone = m.phone.replace(/\D/g, "");
    return `https://wa.me/${phone}?text=${encodeURIComponent(`Bonjour ${m.client}, nous avons bien reçu votre message: "${m.message}"`)}`;
  };

  if (loading) {
    return (
      <div className="flex h-64 items-center justify-center">
        <div className="text-center">
          <div className="mx-auto h-10 w-10 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
          <p className="mt-4 text-muted-foreground">Chargement des messages...</p>
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
      <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <h1 className="text-2xl font-bold text-foreground">Messages clients</h1>
          <p className="text-muted-foreground">{messages.length} demandes · <span className="font-medium text-primary">{unread} non lues</span></p>
        </div>
        <div className="inline-flex rounded-lg border border-border bg-card p-0.5 text-xs">
          <button onClick={() => setFilter("all")} className={`rounded-md px-3 py-1.5 font-medium transition-colors ${filter === "all" ? "bg-gradient-blue text-white shadow-sm" : "text-muted-foreground"}`}>Tous</button>
          <button onClick={() => setFilter("unread")} className={`rounded-md px-3 py-1.5 font-medium transition-colors ${filter === "unread" ? "bg-gradient-blue text-white shadow-sm" : "text-muted-foreground"}`}>Non lus ({unread})</button>
        </div>
      </div>

      <div className="space-y-3">
        {filtered.map((msg, i) => (
          <motion.article
            key={msg.id}
            initial={{ opacity: 0, y: 8 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: Math.min(i * 0.05, 0.3) }}
            className={`relative overflow-hidden rounded-2xl border bg-card shadow-sm transition-all ${msg.read ? "border-border" : "border-primary/30 ring-1 ring-primary/10"}`}
          >
            <span className="absolute left-0 top-0 h-full w-1 bg-[#25D366]" aria-hidden />
            <div className="flex flex-col gap-4 p-5 pl-6 sm:flex-row sm:items-start sm:justify-between">
              <div className="flex-1 min-w-0">
                <div className="flex flex-wrap items-center gap-2">
                  <span className="font-semibold text-foreground">{msg.client}</span>
                  {!msg.read && <span className="rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-primary">Nouveau</span>}
                  <span className="inline-flex items-center gap-1 rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">
                    <MessageCircle className="h-3 w-3" /> Contact
                  </span>
                </div>
                <div className="mt-1 flex flex-wrap gap-x-3 text-xs text-muted-foreground">
                  <span>{msg.email}</span>
                  <span>·</span>
                  <span>{msg.phone}</span>
                </div>
                <blockquote className="mt-3 rounded-lg border-l-2 border-border bg-secondary/40 px-3 py-2 text-sm text-foreground">
                  {msg.message}
                </blockquote>
                <div className="mt-2 text-xs text-muted-foreground">
                  <span>{msg.time}</span>
                </div>
              </div>
              <div className="flex shrink-0 flex-row gap-2 sm:flex-col">
                <a
                  href={directLink(msg)}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#25D366] px-3 py-2 text-xs font-semibold text-white shadow-md transition-transform hover:scale-105"
                >
                  <ExternalLink className="h-3.5 w-3.5" />
                  Ouvrir WhatsApp
                </a>
                <button
                  onClick={() => toggleRead(msg.id)}
                  className="inline-flex items-center justify-center gap-1 rounded-lg border border-border bg-background px-3 py-2 text-xs font-medium text-foreground hover:bg-accent"
                  title={msg.read ? "Marquer non lu" : "Marquer lu"}
                >
                  <Check className="h-3.5 w-3.5" /> {msg.read ? "Non lu" : "Lu"}
                </button>
                <button
                  onClick={() => remove(msg.id)}
                  className="inline-flex items-center justify-center rounded-lg p-2 text-muted-foreground hover:bg-destructive/10 hover:text-destructive"
                  title="Supprimer"
                >
                  <Trash2 className="h-4 w-4" />
                </button>
              </div>
            </div>
          </motion.article>
        ))}
        {filtered.length === 0 && (
          <div className="rounded-2xl border border-dashed border-border bg-card/50 p-12 text-center text-sm text-muted-foreground">
            Aucun message {filter === "unread" ? "non lu" : ""}.
          </div>
        )}
      </div>
    </div>
  );
}