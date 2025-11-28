import { createFileRoute } from "@tanstack/react-router";
import { useState } from "react";
import { Camera, User, Mail, Phone, MapPin } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { toast } from "sonner";

export const Route = createFileRoute("/provider/profile")({
  component: ProviderProfile,
});

function ProviderProfile() {
  const [name, setName] = useState("Awa Nkomo");
  const [email, setEmail] = useState("provider@servlink.cm");
  const [phone, setPhone] = useState("+237 6 77 88 99 00");
  const [city, setCity] = useState("Douala");
  const [bio, setBio] = useState("Plombière professionnelle avec 8 ans d'expérience. Interventions rapides et matériel garanti.");
  const [avatar, setAvatar] = useState("https://i.pravatar.cc/200?img=12");

  const onPick = (e: React.ChangeEvent<HTMLInputElement>) => {
    const f = e.target.files?.[0];
    if (f) { setAvatar(URL.createObjectURL(f)); toast.success("Photo mise à jour"); }
  };

  return (
    <div className="space-y-6 max-w-3xl">
      <div>
        <h1 className="font-display text-2xl font-bold">Mon profil prestataire</h1>
        <p className="text-muted-foreground text-sm">Ces informations sont visibles par vos clients.</p>
      </div>

      <div className="bg-card border border-border rounded-2xl p-6 flex flex-col sm:flex-row items-center sm:items-end gap-5">
        <div className="relative">
          <img src={avatar} alt="" className="h-24 w-24 rounded-2xl object-cover border-4 border-background shadow-lg" />
          <label className="absolute -bottom-2 -right-2 h-9 w-9 rounded-full bg-primary text-primary-foreground flex items-center justify-center cursor-pointer hover:opacity-90 transition shadow-md">
            <Camera className="h-4 w-4" />
            <input type="file" accept="image/*" onChange={onPick} className="hidden" />
          </label>
        </div>
        <div className="flex-1 text-center sm:text-left">
          <h2 className="font-display text-2xl font-bold">{name}</h2>
          <p className="text-sm text-muted-foreground">Plomberie · {city}</p>
        </div>
      </div>

      <form onSubmit={(e) => { e.preventDefault(); toast.success("Profil enregistré"); }} className="bg-card border border-border rounded-2xl p-6 space-y-5">
        <div className="grid md:grid-cols-2 gap-4">
          <div>
            <Label>Nom complet</Label>
            <div className="relative mt-1">
              <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input value={name} onChange={(e) => setName(e.target.value)} className="pl-9 h-11" />
            </div>
          </div>
          <div>
            <Label>Email</Label>
            <div className="relative mt-1">
              <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input type="email" value={email} onChange={(e) => setEmail(e.target.value)} className="pl-9 h-11" />
            </div>
          </div>
          <div>
            <Label>Téléphone</Label>
            <div className="relative mt-1">
              <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input value={phone} onChange={(e) => setPhone(e.target.value)} className="pl-9 h-11" />
            </div>
          </div>
          <div>
            <Label>Ville</Label>
            <div className="relative mt-1">
              <MapPin className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input value={city} onChange={(e) => setCity(e.target.value)} className="pl-9 h-11" />
            </div>
          </div>
        </div>
        <div>
          <Label>Bio professionnelle</Label>
          <textarea
            value={bio}
            onChange={(e) => setBio(e.target.value)}
            rows={4}
            className="mt-1 w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
          />
        </div>
        <div className="flex justify-end">
          <Button type="submit">Enregistrer</Button>
        </div>
      </form>
    </div>
  );
}
