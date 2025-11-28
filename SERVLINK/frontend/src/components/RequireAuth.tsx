import { useEffect, type ReactNode } from "react";
import { useNavigate } from "@tanstack/react-router";
import { useSession } from "@/lib/auth";
import type { Role } from "@/lib/auth";

type Props = { children: ReactNode; roles?: Role[]; redirectTo?: string };

export function RequireAuth({ children, roles, redirectTo = "/login" }: Props) {
  const session = useSession();
  const navigate = useNavigate();

  useEffect(() => {
    if (session === null) {
      // session non encore hydratée (SSR): on attend le prochain tick
      const t = setTimeout(() => {
        const s = JSON.parse(window.localStorage.getItem("servlink_session") || "null");
        if (!s) navigate({ to: redirectTo });
        else if (roles && !roles.includes(s.role)) navigate({ to: redirectTo });
      }, 0);
      return () => clearTimeout(t);
    }
    if (roles && !roles.includes(session.role)) {
      navigate({ to: redirectTo });
    }
  }, [session, roles, navigate, redirectTo]);

  if (typeof window !== "undefined") {
    const raw = window.localStorage.getItem("servlink_session");
    if (!raw) {
      return (
        <div className="container mx-auto px-4 py-20 text-center">
          <p className="text-muted-foreground">Redirection vers la connexion…</p>
        </div>
      );
    }
  }
  return <>{children}</>;
}
