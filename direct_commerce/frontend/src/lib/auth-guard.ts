import { redirect } from "@tanstack/react-router";

const STORAGE_USER = "ecotec.auth.user";

export function requireAuth(_location: { href: string }) {
  if (typeof window === "undefined") return;
  const raw = localStorage.getItem(STORAGE_USER);
  if (!raw) {
    throw redirect({ to: "/login" });
  }
}

export function redirectIfAuthed() {
  if (typeof window === "undefined") return;
  const raw = localStorage.getItem(STORAGE_USER);
  if (raw) {
    throw redirect({ to: "/dashboard" });
  }
}
