import { Link } from "@tanstack/react-router";
import { Facebook, Instagram, Twitter, Mail } from "lucide-react";
import { Logo } from "./Logo";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

export function Footer() {
  return (
    <footer className="bg-secondary text-secondary-foreground mt-20 pb-20 md:pb-0">
      <div className="container mx-auto px-4 py-12 grid gap-10 md:grid-cols-4">
        <div className="space-y-4">
          <Logo variant="light" />
          <p className="text-sm text-white/70">
            La plateforme de mise en relation des clients et prestataires de confiance au Cameroun et en Afrique subsaharienne.
          </p>
          <div className="flex gap-2">
            <Button variant="ghost" size="icon" className="text-white hover:bg-white/10"><Facebook className="h-4 w-4" /></Button>
            <Button variant="ghost" size="icon" className="text-white hover:bg-white/10"><Instagram className="h-4 w-4" /></Button>
            <Button variant="ghost" size="icon" className="text-white hover:bg-white/10"><Twitter className="h-4 w-4" /></Button>
          </div>
        </div>

        <div>
          <h4 className="font-display font-semibold mb-3">Plateforme</h4>
          <ul className="space-y-2 text-sm text-white/75">
            <li><Link to="/search">Explorer les services</Link></li>
            <li><Link to="/register">Devenir prestataire</Link></li>
            <li><a href="#">Comment ça marche</a></li>
            <li><a href="#">Tarifs et commissions</a></li>
          </ul>
        </div>

        <div>
          <h4 className="font-display font-semibold mb-3">Aide</h4>
          <ul className="space-y-2 text-sm text-white/75">
            <li><a href="#">Centre d'aide</a></li>
            <li><a href="#">Sécurité des paiements</a></li>
            <li><a href="#">Conditions générales</a></li>
            <li><a href="#">Politique RGPD</a></li>
          </ul>
        </div>

        <div>
          <h4 className="font-display font-semibold mb-3">Newsletter</h4>
          <p className="text-sm text-white/70 mb-3">Les meilleurs prestataires, chaque semaine.</p>
          <form className="flex gap-2">
            <Input placeholder="Votre email" className="bg-white/10 border-white/20 placeholder:text-white/50 text-white" />
            <Button type="button" size="icon" className="bg-gold text-gold-foreground hover:bg-gold/90"><Mail className="h-4 w-4" /></Button>
          </form>
        </div>
      </div>
      <div className="border-t border-white/10">
        <div className="container mx-auto px-4 py-5 text-xs text-white/60 flex flex-wrap justify-between gap-2">
          <span>© 2026 SERVLINK. Tous droits réservés.</span>
          <span>Made with care in Cameroon 🇨🇲</span>
        </div>
      </div>
    </footer>
  );
}
