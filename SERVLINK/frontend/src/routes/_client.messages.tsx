import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { Search, Send, Paperclip, Smile, Phone, Video, MoreVertical, CheckCheck } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { threads } from "@/lib/mock-data";
import { RequireAuth } from "@/components/RequireAuth";

export const Route = createFileRoute("/_client/messages")({
  head: () => ({ meta: [{ title: "Messages — SERVLINK" }] }),
  component: () => <RequireAuth roles={["client"]}><MessagesPage /></RequireAuth>,
});

const messages = [
  { id: 1, from: "them", text: "Bonjour ! Merci pour votre demande de réservation.", time: "09:30" },
  { id: 2, from: "me", text: "Bonjour Awa, à quelle heure pouvez-vous passer demain ?", time: "09:32" },
  { id: 3, from: "them", text: "Je peux être chez vous à 10h pile. Ça vous convient ?", time: "09:38" },
  { id: 4, from: "me", text: "Parfait, c'est noté. À demain !", time: "09:40" },
  { id: 5, from: "them", text: "Je serai chez vous à 10h pile.", time: "09:42" },
];

function MessagesPage() {
  const [active, setActive] = useState(threads[0].id);
  const current = threads.find((t) => t.id === active)!;

  return (
    <div className="container mx-auto px-0 md:px-4 py-0 md:py-6">
      <div className="bg-card md:border md:border-border md:rounded-2xl overflow-hidden h-[calc(100dvh-9rem)] md:h-[78vh] grid md:grid-cols-[320px_1fr]">
        {/* Threads list */}
        <aside className="border-r border-border flex flex-col">
          <div className="p-4 border-b border-border">
            <h1 className="font-display font-bold text-xl mb-3">Messages</h1>
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input placeholder="Rechercher…" className="pl-9 h-9" />
            </div>
          </div>
          <div className="flex-1 overflow-y-auto">
            {threads.map((t) => (
              <button
                key={t.id}
                onClick={() => setActive(t.id)}
                className={`w-full flex items-center gap-3 p-3 text-left hover:bg-muted/60 transition-colors ${active === t.id ? "bg-accent" : ""}`}
              >
                <div className="relative">
                  <img src={t.avatar} alt={t.provider} className="h-12 w-12 rounded-full object-cover" />
                  {t.online && <span className="absolute bottom-0 right-0 h-3 w-3 rounded-full bg-success ring-2 ring-background" />}
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex items-center justify-between">
                    <span className="font-semibold text-sm truncate">{t.provider}</span>
                    <span className="text-[10px] text-muted-foreground shrink-0">{t.time}</span>
                  </div>
                  <div className="flex items-center justify-between gap-2 mt-0.5">
                    <span className="text-xs text-muted-foreground truncate">{t.lastMessage}</span>
                    {t.unread > 0 && <span className="bg-primary text-primary-foreground text-[10px] font-bold h-5 min-w-5 px-1.5 rounded-full flex items-center justify-center">{t.unread}</span>}
                  </div>
                </div>
              </button>
            ))}
          </div>
        </aside>

        {/* Chat */}
        <section className="flex flex-col bg-muted/30">
          <header className="px-4 py-3 border-b border-border bg-background flex items-center gap-3">
            <img src={current.avatar} alt={current.provider} className="h-10 w-10 rounded-full object-cover" />
            <div className="flex-1">
              <div className="font-semibold text-sm">{current.provider}</div>
              <div className="text-xs text-success">{current.online ? "En ligne" : "Vu récemment"}</div>
            </div>
            <Button variant="ghost" size="icon"><Phone className="h-4 w-4" /></Button>
            <Button variant="ghost" size="icon"><Video className="h-4 w-4" /></Button>
            <Button variant="ghost" size="icon"><MoreVertical className="h-4 w-4" /></Button>
          </header>

          <div className="flex-1 overflow-y-auto p-4 space-y-3">
            <div className="text-center"><span className="text-xs text-muted-foreground bg-background px-3 py-1 rounded-full">Aujourd'hui</span></div>
            {messages.map((m) => (
              <div key={m.id} className={`flex ${m.from === "me" ? "justify-end" : "justify-start"}`}>
                <div className={`max-w-[75%] rounded-2xl px-4 py-2 ${m.from === "me" ? "bg-primary text-primary-foreground rounded-br-sm" : "bg-background border border-border rounded-bl-sm"}`}>
                  <p className="text-sm">{m.text}</p>
                  <div className={`flex items-center gap-1 justify-end mt-1 text-[10px] ${m.from === "me" ? "text-white/70" : "text-muted-foreground"}`}>
                    {m.time} {m.from === "me" && <CheckCheck className="h-3 w-3" />}
                  </div>
                </div>
              </div>
            ))}
          </div>

          <form className="p-3 border-t border-border bg-background flex items-center gap-2">
            <Button type="button" variant="ghost" size="icon"><Paperclip className="h-4 w-4" /></Button>
            <Input placeholder="Écrivez un message…" className="flex-1" />
            <Button type="button" variant="ghost" size="icon"><Smile className="h-4 w-4" /></Button>
            <Button type="button" size="icon"><Send className="h-4 w-4" /></Button>
          </form>
        </section>
      </div>
    </div>
  );
}
