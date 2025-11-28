// Mini auth front-end basé sur localStorage (démo)
export type Role = "admin" | "client" | "provider";
export type Session = { role: Role; email: string; name: string };

const KEY = "servlink_session";

export function getSession(): Session | null {
  if (typeof window === "undefined") return null;
  try {
    const raw = window.localStorage.getItem(KEY);
    return raw ? (JSON.parse(raw) as Session) : null;
  } catch {
    return null;
  }
}

export function setSession(s: Session) {
  window.localStorage.setItem(KEY, JSON.stringify(s));
  window.dispatchEvent(new Event("servlink-auth"));
}

export function clearSession() {
  window.localStorage.removeItem(KEY);
  window.dispatchEvent(new Event("servlink-auth"));
}

// Hook React pour réagir aux changements de session
import { useEffect, useState } from "react";
export function useSession() {
  const [session, setS] = useState<Session | null>(null);
  useEffect(() => {
    setS(getSession());
    const h = () => setS(getSession());
    window.addEventListener("servlink-auth", h);
    window.addEventListener("storage", h);
    return () => {
      window.removeEventListener("servlink-auth", h);
      window.removeEventListener("storage", h);
    };
  }, []);
  return session;
}
