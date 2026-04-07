import { useRef, useState } from "react";
import { Plus, X } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

interface MultiImagePickerProps {
  label: string;
  values: string[];
  onChange: (urls: string[]) => void;
  maxSizeMB?: number;
  max?: number;
}

/**
 * Multiple-image picker for product galleries (sub-images).
 * Lets the user add several files from local gallery, with live previews.
 */
export default function MultiImagePicker({ label, values, onChange, maxSizeMB = 5, max = 8 }: MultiImagePickerProps) {
  const inputRef = useRef<HTMLInputElement>(null);
  const [error, setError] = useState("");

  const handleFiles = (files: FileList | null) => {
    setError("");
    if (!files || !files.length) return;
    const remaining = max - values.length;
    if (remaining <= 0) { setError(`Maximum ${max} images.`); return; }
    const list = Array.from(files).slice(0, remaining);
    Promise.all(list.map((file) => new Promise<string>((resolve, reject) => {
      if (!file.type.startsWith("image/")) return reject(new Error("Fichier image requis."));
      if (file.size > maxSizeMB * 1024 * 1024) return reject(new Error(`Image > ${maxSizeMB}MB.`));
      const r = new FileReader();
      r.onload = () => resolve(String(r.result ?? ""));
      r.onerror = () => reject(new Error("Erreur lecture."));
      r.readAsDataURL(file);
    })))
      .then((urls) => onChange([...values, ...urls]))
      .catch((e: Error) => setError(e.message));
  };

  const removeAt = (i: number) => onChange(values.filter((_, idx) => idx !== i));

  return (
    <div>
      <div className="mb-1.5 flex items-baseline justify-between">
        <label className="block text-sm font-medium text-foreground">{label}</label>
        <span className="text-xs text-muted-foreground">{values.length}/{max}</span>
      </div>

      <div className="grid grid-cols-3 gap-2 sm:grid-cols-4">
        <AnimatePresence initial={false}>
          {values.map((src, i) => (
            <motion.div
              key={src.slice(0, 32) + i}
              layout
              initial={{ opacity: 0, scale: 0.85 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.85 }}
              className="group relative aspect-square overflow-hidden rounded-lg border border-border"
            >
              <img src={src} alt={`Aperçu ${i + 1}`} className="h-full w-full object-cover" />
              <button
                type="button"
                onClick={() => removeAt(i)}
                className="absolute right-1 top-1 rounded-full bg-foreground/70 p-1 text-background opacity-0 transition-opacity group-hover:opacity-100"
                aria-label="Retirer"
              >
                <X className="h-3 w-3" />
              </button>
            </motion.div>
          ))}
        </AnimatePresence>

        {values.length < max && (
          <button
            type="button"
            onClick={() => inputRef.current?.click()}
            className="flex aspect-square flex-col items-center justify-center gap-1 rounded-lg border-2 border-dashed border-border bg-background text-muted-foreground transition-colors hover:border-primary/50 hover:bg-accent/30"
          >
            <Plus className="h-4 w-4" />
            <span className="text-[10px] font-medium">Ajouter</span>
          </button>
        )}
      </div>

      <input ref={inputRef} type="file" accept="image/*" multiple className="hidden" onChange={(e) => handleFiles(e.target.files)} />
      {error && <p className="mt-1.5 text-xs text-destructive">{error}</p>}
    </div>
  );
}
