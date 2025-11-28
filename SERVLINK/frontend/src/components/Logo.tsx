export function Logo({ variant = "default" }: { variant?: "default" | "light" }) {
  const text = variant === "light" ? "text-white" : "text-secondary";
  return (
    <div className="flex items-center gap-2">
      <div className="relative h-9 w-9 rounded-lg gradient-hero flex items-center justify-center shadow-md">
        <svg viewBox="0 0 24 24" className="h-5 w-5 text-white" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
          <path d="M4 12c0-4.4 3.6-8 8-8s8 3.6 8 8" />
          <path d="M8 12c2.5 2.5 5.5 2.5 8 0" />
          <circle cx="12" cy="18" r="1.5" fill="currentColor" />
        </svg>
        <span className="absolute -top-1 -right-1 h-2.5 w-2.5 rounded-full bg-gold border-2 border-background" />
      </div>
      <span className={`font-display font-bold text-lg tracking-tight ${text}`}>
        SERV<span className="text-primary">LINK</span>
      </span>
    </div>
  );
}
