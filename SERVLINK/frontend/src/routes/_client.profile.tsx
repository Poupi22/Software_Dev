import { createFileRoute, Link } from "@tanstack/react-router";
import { useState } from "react";
import {
  User, CreditCard, Bell, Shield, Settings, LogOut, Heart, Star, CalendarCheck,
  Camera, Mail, Phone, MapPin, Lock, Eye, EyeOff, Check, LayoutDashboard,
} from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { Switch } from "@/components/ui/switch";
import { LogoutDialog } from "@/components/LogoutDialog";
import { toast } from "sonner";
import { RequireAuth } from "@/components/RequireAuth";

export const Route = createFileRoute("/_client/profile")({
  head: () => ({ meta: [{ title: "Mon profil — SERVLINK" }] }),
  component: () => <RequireAuth roles={["client"]}><ProfilePage /></RequireAuth>,
});

function ProfilePage() {
  const [name, setName] = useState("Adèle Kouam");
  const [email, setEmail] = useState("adele.kouam@example.com");
  const [phone, setPhone] = useState("+237 6 99 12 34 56");
  const [city, setCity] = useState("Douala");
  const [avatar, setAvatar] = useState<string>("https://i.pravatar.cc/200?img=47");
  const [pwd, setPwd] = useState({ current: "", next: "", confirm: "" });
  const [showPwd, setShowPwd] = useState(false);
  const [notif, setNotif] = useState({ email: true, sms: false, push: true, promo: false });

  const onPickAvatar = (e: React.ChangeEvent<HTMLInputElement>) => {
    const f = e.target.files?.[0];
    if (f) {
      setAvatar(URL.createObjectURL(f));
      toast.success("Photo de profil mise à jour");
    }
  };

  const saveInfo = (e: React.FormEvent) => {
    e.preventDefault();
    toast.success("Informations enregistrées avec succès");
  };

  const savePwd = (e: React.FormEvent) => {
    e.preventDefault();
    if (!pwd.current || !pwd.next) return toast.error("Veuillez remplir tous les champs");
    if (pwd.next !== pwd.confirm) return toast.error("Les mots de passe ne correspondent pas");
    if (pwd.next.length < 8) return toast.error("Minimum 8 caractères");
    setPwd({ current: "", next: "", confirm: "" });
    toast.success("Mot de passe modifié");
  };

  return (
    <div className="container mx-auto px-4 py-8 max-w-5xl space-y-6">
      {/* Identity card */}
      <div className="bg-card border border-border rounded-2xl p-6 flex flex-col sm:flex-row items-center sm:items-end gap-5">
        <div className="relative">
          <img src={avatar} alt="" className="h-24 w-24 rounded-2xl object-cover border-4 border-background shadow-lg" />
          <label className="absolute -bottom-2 -right-2 h-9 w-9 rounded-full bg-primary text-primary-foreground flex items-center justify-center cursor-pointer hover:bg-primary-dark transition shadow-md">
            <Camera className="h-4 w-4" />
            <input type="file" accept="image/*" onChange={onPickAvatar} className="hidden" />
          </label>
        </div>
        <div className="flex-1 text-center sm:text-left">
          <h1 className="font-display text-2xl font-bold">{name}</h1>
          <p className="text-sm text-muted-foreground">{email}</p>
          <div className="flex items-center justify-center sm:justify-start gap-3 mt-2 text-xs">
            <span className="flex items-center gap-1 text-gold-foreground bg-gold/30 px-2 py-0.5 rounded-full"><Star className="h-3 w-3 fill-current" /> 4.9 client</span>
            <span className="text-muted-foreground">Membre depuis fév. 2025</span>
          </div>
        </div>
        <div className="flex gap-2">
          <Link to="/dashboard">
            <Button variant="outline" size="sm"><LayoutDashboard className="h-4 w-4 mr-1" /> Dashboard</Button>
          </Link>
          <LogoutDialog userLabel={name}>
            <Button variant="outline" size="sm" className="text-destructive hover:text-destructive border-destructive/40 hover:bg-destructive/5">
              <LogOut className="h-4 w-4 mr-1" /> Déconnexion
            </Button>
          </LogoutDialog>
        </div>
      </div>

      {/* Quick stats */}
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
        {[
          { k: "12", l: "Réservations", icon: CreditCard },
          { k: "8", l: "Favoris", icon: Heart },
          { k: "5", l: "Avis donnés", icon: Star },
          { k: "3", l: "À venir", icon: CalendarCheck },
        ].map((s) => (
          <div key={s.l} className="bg-card border border-border rounded-xl p-4">
            <s.icon className="h-4 w-4 text-primary mb-1" />
            <div className="font-display text-xl font-bold">{s.k}</div>
            <div className="text-xs text-muted-foreground">{s.l}</div>
          </div>
        ))}
      </div>

      {/* Settings tabs */}
      <Tabs defaultValue="info" className="space-y-4">
        <TabsList className="grid grid-cols-2 md:grid-cols-4 w-full md:w-auto">
          <TabsTrigger value="info"><User className="h-4 w-4 mr-1.5" /> Profil</TabsTrigger>
          <TabsTrigger value="security"><Shield className="h-4 w-4 mr-1.5" /> Sécurité</TabsTrigger>
          <TabsTrigger value="notifications"><Bell className="h-4 w-4 mr-1.5" /> Notifications</TabsTrigger>
          <TabsTrigger value="preferences"><Settings className="h-4 w-4 mr-1.5" /> Préférences</TabsTrigger>
        </TabsList>

        {/* INFO */}
        <TabsContent value="info">
          <form onSubmit={saveInfo} className="bg-card border border-border rounded-2xl p-6 space-y-5">
            <div>
              <h3 className="font-display font-bold">Informations personnelles</h3>
              <p className="text-xs text-muted-foreground">Ces informations seront affichées aux prestataires lors d'une réservation.</p>
            </div>
            <div className="grid md:grid-cols-2 gap-4">
              <div>
                <Label htmlFor="name">Nom complet</Label>
                <div className="relative mt-1">
                  <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input id="name" value={name} onChange={(e) => setName(e.target.value)} className="pl-9 h-11" />
                </div>
              </div>
              <div>
                <Label htmlFor="email">Email</Label>
                <div className="relative mt-1">
                  <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input id="email" type="email" value={email} onChange={(e) => setEmail(e.target.value)} className="pl-9 h-11" />
                </div>
              </div>
              <div>
                <Label htmlFor="phone">Téléphone</Label>
                <div className="relative mt-1">
                  <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input id="phone" value={phone} onChange={(e) => setPhone(e.target.value)} className="pl-9 h-11" />
                </div>
              </div>
              <div>
                <Label htmlFor="city">Ville</Label>
                <div className="relative mt-1">
                  <MapPin className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                  <Input id="city" value={city} onChange={(e) => setCity(e.target.value)} className="pl-9 h-11" />
                </div>
              </div>
            </div>
            <div className="flex justify-end gap-2 pt-2">
              <Button type="button" variant="outline">Annuler</Button>
              <Button type="submit"><Check className="h-4 w-4 mr-1" /> Enregistrer</Button>
            </div>
          </form>
        </TabsContent>

        {/* SECURITY */}
        <TabsContent value="security">
          <form onSubmit={savePwd} className="bg-card border border-border rounded-2xl p-6 space-y-5">
            <div>
              <h3 className="font-display font-bold">Changer de mot de passe</h3>
              <p className="text-xs text-muted-foreground">Minimum 8 caractères, avec lettres et chiffres.</p>
            </div>
            <div className="space-y-4 max-w-md">
              {[
                { id: "current", label: "Mot de passe actuel" },
                { id: "next", label: "Nouveau mot de passe" },
                { id: "confirm", label: "Confirmer le nouveau mot de passe" },
              ].map((f) => (
                <div key={f.id}>
                  <Label htmlFor={f.id}>{f.label}</Label>
                  <div className="relative mt-1">
                    <Lock className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                    <Input
                      id={f.id}
                      type={showPwd ? "text" : "password"}
                      value={pwd[f.id as keyof typeof pwd]}
                      onChange={(e) => setPwd({ ...pwd, [f.id]: e.target.value })}
                      className="pl-9 pr-9 h-11"
                      placeholder="••••••••"
                    />
                    <button type="button" onClick={() => setShowPwd(!showPwd)} className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground">
                      {showPwd ? <EyeOff className="h-4 w-4" /> : <Eye className="h-4 w-4" />}
                    </button>
                  </div>
                </div>
              ))}
            </div>
            <div className="flex justify-end gap-2 pt-2">
              <Button type="submit">Mettre à jour</Button>
            </div>
          </form>

          <div className="bg-card border border-border rounded-2xl p-6 mt-4 space-y-4">
            <h3 className="font-display font-bold">Sessions actives</h3>
            {[
              { device: "iPhone 15 — Safari", loc: "Douala, CM", time: "Maintenant", current: true },
              { device: "MacBook Pro — Chrome", loc: "Douala, CM", time: "Il y a 3h", current: false },
            ].map((s, i) => (
              <div key={i} className="flex items-center justify-between p-3 rounded-lg border border-border">
                <div>
                  <div className="text-sm font-medium">{s.device} {s.current && <span className="ml-2 text-[10px] px-2 py-0.5 rounded-full bg-success/15 text-success font-semibold">Actuelle</span>}</div>
                  <div className="text-xs text-muted-foreground">{s.loc} · {s.time}</div>
                </div>
                {!s.current && <Button variant="outline" size="sm">Révoquer</Button>}
              </div>
            ))}
          </div>
        </TabsContent>

        {/* NOTIFICATIONS */}
        <TabsContent value="notifications">
          <div className="bg-card border border-border rounded-2xl p-6 space-y-1">
            <h3 className="font-display font-bold mb-4">Préférences de notification</h3>
            {[
              { k: "email", l: "Notifications par email", d: "Confirmations de réservation, reçus, messages." },
              { k: "push", l: "Notifications push", d: "Alertes en temps réel sur votre appareil." },
              { k: "sms", l: "Notifications SMS", d: "Rappels de rendez-vous par SMS." },
              { k: "promo", l: "Offres et promotions", d: "Recevoir les nouveautés et bons plans SERVLINK." },
            ].map((n) => (
              <div key={n.k} className="flex items-center justify-between py-3 border-b border-border last:border-0">
                <div>
                  <div className="text-sm font-medium">{n.l}</div>
                  <div className="text-xs text-muted-foreground">{n.d}</div>
                </div>
                <Switch
                  checked={notif[n.k as keyof typeof notif]}
                  onCheckedChange={(v) => setNotif({ ...notif, [n.k]: v })}
                />
              </div>
            ))}
          </div>
        </TabsContent>

        {/* PREFERENCES */}
        <TabsContent value="preferences">
          <div className="bg-card border border-border rounded-2xl p-6 space-y-5">
            <h3 className="font-display font-bold">Préférences générales</h3>
            <div className="grid md:grid-cols-2 gap-4">
              <div>
                <Label>Langue</Label>
                <select className="mt-1 w-full h-11 rounded-md border border-input bg-transparent px-3 text-sm">
                  <option>Français</option>
                  <option>English</option>
                </select>
              </div>
              <div>
                <Label>Devise</Label>
                <select className="mt-1 w-full h-11 rounded-md border border-input bg-transparent px-3 text-sm">
                  <option>FCFA (XAF)</option>
                  <option>EUR €</option>
                  <option>USD $</option>
                </select>
              </div>
            </div>
            <div className="pt-4 border-t border-border">
              <h4 className="font-semibold text-sm text-destructive mb-1">Zone dangereuse</h4>
              <p className="text-xs text-muted-foreground mb-3">La suppression de votre compte est définitive et irréversible.</p>
              <Button variant="outline" className="text-destructive border-destructive/40 hover:bg-destructive/5">Supprimer mon compte</Button>
            </div>
          </div>
        </TabsContent>
      </Tabs>
    </div>
  );
}
