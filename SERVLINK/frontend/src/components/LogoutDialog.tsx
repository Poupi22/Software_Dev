import { useState, type ReactNode } from "react";
import { useNavigate } from "@tanstack/react-router";
import { LogOut } from "lucide-react";
import { clearSession } from "@/lib/auth";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/components/ui/alert-dialog";

type Props = {
  children: ReactNode;
  redirectTo?: string;
  userLabel?: string;
};

export function LogoutDialog({ children, redirectTo = "/login", userLabel }: Props) {
  const navigate = useNavigate();
  const [open, setOpen] = useState(false);
  return (
    <AlertDialog open={open} onOpenChange={setOpen}>
      <AlertDialogTrigger asChild>{children}</AlertDialogTrigger>
      <AlertDialogContent>
        <AlertDialogHeader>
          <div className="mx-auto h-12 w-12 rounded-full bg-destructive/10 flex items-center justify-center mb-2">
            <LogOut className="h-6 w-6 text-destructive" />
          </div>
          <AlertDialogTitle className="text-center">Se déconnecter ?</AlertDialogTitle>
          <AlertDialogDescription className="text-center">
            {userLabel
              ? `Vous êtes sur le point de quitter la session de ${userLabel}.`
              : "Vous allez quitter votre session actuelle."}{" "}
            Vous devrez vous reconnecter pour accéder à votre espace.
          </AlertDialogDescription>
        </AlertDialogHeader>
        <AlertDialogFooter className="sm:justify-center gap-2">
          <AlertDialogCancel>Annuler</AlertDialogCancel>
          <AlertDialogAction
            onClick={() => {
              clearSession();
              setOpen(false);
              navigate({ to: redirectTo });
            }}
            className="bg-destructive text-destructive-foreground hover:bg-destructive/90"
          >
            Oui, me déconnecter
          </AlertDialogAction>
        </AlertDialogFooter>
      </AlertDialogContent>
    </AlertDialog>
  );
}
