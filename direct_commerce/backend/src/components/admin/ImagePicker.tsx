import { useRef, useState } from "react";
import { Upload, X, ImageIcon, Link2 } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

interface ImagePickerProps {
  label: string;
  value: string;
  onChange: (url: string) => void;
  /** rough max size in MB (default 5) */
  maxSizeMB?: number;
}

/**
 * Single-image picker. Lets the user either:
 *  - Pick a file from the local gallery (converted to a data URL preview)
 *  - Paste an image URL
 * Always shows a live preview before saving.
 */
export default function ImagePicker({ label, value, onChange, maxSizeMB = 5 }: ImagePickerProps) {
  const inputRef = useRef<HTMLInputElement>(null);
  const [mode, setMode] = useState<"upload" | "url">(value && value.startsWith("http") ? "url" : "upload");
  const [error, setError] = useState<string>("");

  const handleFile = (file?: File | null) => {
    setError("");
    if (!file) return;
    if (!file.type.startsWith("image/")) { setError("Fichier image requis."); return; }
    if (file.size > maxSizeMB * 1024 * 1024) { setError(`Image > ${maxSizeMB}MB.`); return; }
    const reader = new FileReader();
    reader.onload = () => onChange(String(reader.result ?? ""));
    reader.readAsDataURL(file);
  };

  return (
    <div>
      <label className="mb-1.5 block text-sm font-medium text-foreground">{label}</label>

      <div className="mb-2 inline-flex rounded-lg border border-border bg-secondary/50 p-0.5 text-xs">
        <button type="button" onClick={() => setMode("upload")} className={`inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 font-medium transition-colors ${mode === "upload" ? "bg-card text-foreground shadow-sm" : "text-muted-foreground"}`}>
          <Upload className="h-3.5 w-3.5" /> Galerie
        </button>
        <button type="button" onClick={() => setMode("url")} className={`inline-flex items-center gap-1.5 rounded-md px-3 py-1.5 font-medium transition-colors ${mode === "url" ? "bg-card text-foreground shadow-sm" : "text-muted-foreground"}`}>
          <Link2 className="h-3.5 w-3.5" /> URL
        </button>
      </div>

      {mode === "upload" ? (
        <div
          onClick={() => inputRef.current?.click()}
          onDragOver={(e) => e.preventDefault()}
          onDrop={(e) => { e.preventDefault(); handleFile(e.dataTransfer.files?.[0]); }}
          className="flex cursor-pointer flex-col items-center justify-center gap-1.5 rounded-xl border-2 border-dashed border-border bg-background px-4 py-6 text-center transition-colors hover:border-primary/50 hover:bg-accent/30"
        >
          <ImageIcon className="h-6 w-6 text-muted-foreground" />
          <p className="text-sm font-medium text-foreground">Cliquer ou glisser une image</p>
          <p className="text-xs text-muted-foreground">PNG, JPG, WEBP — max {maxSizeMB}MB</p>
          <input ref={inputRef} type="file" accept="image/*" className="hidden" onChange={(e) => handleFile(e.target.files?.[0])} />
        </div>
      ) : (
        <input
          value={value.startsWith("data:") ? "" : value}
          onChange={(e) => onChange(e.target.value)}
          className="w-full rounded-lg border border-input bg-background px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-ring"
          placeholder="https://…"
        />
      )}

      {error && <p className="mt-1.5 text-xs text-destructive">{error}</p>}

      <AnimatePresence>
        {value && (
          <motion.div
            initial={{ opacity: 0, scale: 0.96 }}
            animate={{ opacity: 1, scale: 1 }}
            exit={{ opacity: 0, scale: 0.96 }}
            className="relative mt-3 overflow-hidden rounded-xl border border-border"
          >
            <img src={value} alt="Aperçu" className="h-40 w-full object-cover" />
            <button
              type="button"
              onClick={() => onChange("")}
              className="absolute right-2 top-2 rounded-full bg-foreground/70 p-1.5 text-background hover:bg-foreground"
              aria-label="Retirer l'image"
            >
              <X className="h-3.5 w-3.5" />
            </button>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
}
