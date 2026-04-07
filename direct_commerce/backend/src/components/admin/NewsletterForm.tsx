import { useState } from "react";

interface NewsletterFormProps {
  onSubmit: (data: { subject: string; content: string }) => void;
  onCancel: () => void;
  sending?: boolean;
}

export default function NewsletterForm({ onSubmit, onCancel, sending = false }: NewsletterFormProps) {
  const [data, setData] = useState({ subject: "", content: "" });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!data.subject.trim() || !data.content.trim()) {
      alert("Veuillez remplir tous les champs");
      return;
    }
    onSubmit(data);
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">
          Sujet <span className="text-destructive">*</span>
        </label>
        <input
          required
          value={data.subject}
          onChange={(e) => setData({ ...data, subject: e.target.value })}
          disabled={sending}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-50 disabled:cursor-not-allowed"
          placeholder="Ex: Nouvelle collection printemps"
        />
      </div>
      <div>
        <label className="mb-1.5 block text-sm font-medium text-foreground">
          Contenu <span className="text-destructive">*</span>
        </label>
        <textarea
          required
          rows={6}
          value={data.content}
          onChange={(e) => setData({ ...data, content: e.target.value })}
          disabled={sending}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-50 disabled:cursor-not-allowed"
          placeholder="Votre message..."
        />
        <p className="mt-1 text-xs text-muted-foreground">
          Vous pouvez utiliser du HTML simple (p, strong, br, a)
        </p>
      </div>
      <div className="flex gap-3 pt-2">
        <button 
          type="button" 
          onClick={onCancel} 
          disabled={sending}
          className="flex-1 rounded-lg border border-border bg-card px-4 py-2.5 text-sm font-medium text-foreground hover:bg-accent disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Annuler
        </button>
        <button 
          type="submit" 
          disabled={sending}
          className="flex-1 rounded-lg bg-gradient-blue px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:scale-[1.02] transition-transform disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {sending ? (
            <span className="flex items-center justify-center gap-2">
              <svg className="h-4 w-4 animate-spin" viewBox="0 0 24 24">
                <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none" />
                <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
              </svg>
              Envoi...
            </span>
          ) : (
            "Envoyer"
          )}
        </button>
      </div>
    </form>
  );
}