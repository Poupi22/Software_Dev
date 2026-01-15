import { useRef, useState, useEffect } from "react";
import { Upload, X, ImageIcon, Loader2 } from "lucide-react";

type Props = {
  label?: string;
  shape?: "square" | "circle";
  initial?: string | null;
  required?: boolean;
  hint?: string;
  onImageSelect?: (file: File | null) => void;
};

export function ImagePicker({ 
  label = "Image", 
  shape = "square", 
  initial, 
  required, 
  hint, 
  onImageSelect 
}: Props) {
  const [preview, setPreview] = useState<string | null>(initial ?? null);
  const [loading, setLoading] = useState(false);
  const [fileName, setFileName] = useState<string>("");
  const inputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (initial !== undefined && initial !== null) {
      setPreview(initial);
    }
  }, [initial]);

  const onPick = async (file?: File | null) => {
    if (!file) return;
    if (!file.type.startsWith("image/")) {
      alert("Veuillez sélectionner une image valide");
      return;
    }
    
    setLoading(true);
    setFileName(file.name);
    const url = URL.createObjectURL(file);
    setPreview(url);
    setLoading(false);
    
    if (onImageSelect) onImageSelect(file);
  };

  const handleRemove = () => {
    setPreview(null);
    setFileName("");
    if (inputRef.current) inputRef.current.value = "";
    if (onImageSelect) onImageSelect(null);
  };

  const radius = shape === "circle" ? "rounded-full" : "rounded-xl";

  return (
    <div className="space-y-2">
      {label && (
        <label className="mb-1 block text-xs font-medium text-muted-foreground">
          {label}{required && <span className="text-destructive"> *</span>}
        </label>
      )}
      <div className="flex items-center gap-3">
        <div className={`relative flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden border border-dashed border-input bg-muted/40 ${radius}`}>
          {loading ? (
            <Loader2 className="h-6 w-6 animate-spin text-muted-foreground" />
          ) : preview ? (
            <>
              <img src={preview} alt="apercu" className="h-full w-full object-cover" />
              <button
                type="button"
                onClick={handleRemove}
                className="absolute right-0.5 top-0.5 rounded-full bg-black/60 p-0.5 text-white hover:bg-black/80"
                aria-label="Retirer l'image"
              >
                <X className="h-3 w-3" />
              </button>
            </>
          ) : (
            <ImageIcon className="h-6 w-6 text-muted-foreground/60" />
          )}
        </div>
        <div className="flex-1">
          <button
            type="button"
            onClick={() => inputRef.current?.click()}
            className="flex h-10 w-full items-center justify-center gap-2 rounded-lg border border-input bg-background px-3 text-sm font-medium hover:bg-muted"
          >
            <Upload className="h-4 w-4" /> {preview ? "Changer l'image" : "Choisir depuis la galerie"}
          </button>
          {fileName && <p className="mt-1 text-xs text-muted-foreground">{fileName}</p>}
          {hint && !fileName && <p className="mt-1 text-[11px] text-muted-foreground">{hint}</p>}
        </div>
        <input
          ref={inputRef}
          type="file"
          accept="image/png,image/jpeg,image/webp"
          className="hidden"
          required={required && !preview}
          onChange={(e) => onPick(e.target.files?.[0])}
        />
      </div>
    </div>
  );
}
