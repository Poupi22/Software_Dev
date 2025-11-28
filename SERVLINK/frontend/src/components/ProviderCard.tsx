import { Link } from "@tanstack/react-router";
import { Star, MapPin, BadgeCheck, Clock } from "lucide-react";
import type { Provider } from "@/lib/mock-data";
import { formatXAF } from "@/lib/mock-data";

export function ProviderCard({ p }: { p: Provider }) {
  return (
    <Link to="/providers/$id" params={{ id: p.id }} className="card-provider block">
      {p.featured && <div className="ribbon-featured">À la une</div>}
      <div className="aspect-[16/10] overflow-hidden bg-muted">
        <img src={p.cover} alt={p.category} className="w-full h-full object-cover" loading="lazy" />
      </div>
      <div className="p-4">
        <div className="flex items-start gap-3">
          <img src={p.avatar} alt={p.name} className="h-12 w-12 rounded-full object-cover ring-2 ring-background -mt-10 shadow" />
          <div className="flex-1 min-w-0">
            <div className="flex items-center gap-1.5">
              <h3 className="font-display font-semibold truncate">{p.name}</h3>
              {p.verified && <BadgeCheck className="h-4 w-4 text-primary shrink-0" />}
            </div>
            <p className="text-xs text-muted-foreground truncate">{p.category}</p>
          </div>
        </div>

        <div className="flex items-center gap-3 mt-3 text-xs text-muted-foreground">
          <span className="flex items-center gap-1"><Star className="h-3.5 w-3.5 fill-gold text-gold" /><span className="text-foreground font-semibold">{p.rating}</span> ({p.reviews})</span>
          <span className="flex items-center gap-1"><MapPin className="h-3.5 w-3.5" />{p.city}</span>
          <span className="flex items-center gap-1"><Clock className="h-3.5 w-3.5" />{p.responseTime}</span>
        </div>

        <div className="flex items-end justify-between mt-3 pt-3 border-t border-border">
          <div>
            <div className="text-[10px] uppercase tracking-wide text-muted-foreground">À partir de</div>
            <div className="font-display font-bold text-primary">{formatXAF(p.priceFrom)}</div>
          </div>
          <span className="text-xs px-2 py-1 rounded-full bg-accent text-accent-foreground font-medium">{p.completed} missions</span>
        </div>
      </div>
    </Link>
  );
}
