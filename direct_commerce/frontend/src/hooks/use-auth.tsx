import { createContext, useContext, useEffect, useState, type ReactNode } from "react";

export type Role = "admin" | "manager";

export interface Farm {
  id: string;
  name: string;
  location: string;
  manager: string;
  email: string;
  capacity: number;
  active: number;
  createdAt: string;
  status: "active" | "paused";
}

export interface AuthUser {
  id: string;
  email: string;
  name: string;
  role: Role;
  farmId?: string;
}

interface AuthContextValue {
  user: AuthUser | null;
  farms: Farm[];
  isAuthenticated: boolean;
  login: (email: string, password: string) => Promise<{ ok: boolean; error?: string }>;
  logout: () => void;
  addFarm: (farm: Omit<Farm, "id" | "createdAt">) => void;
  updateFarm: (id: string, patch: Partial<Farm>) => void;
  removeFarm: (id: string) => void;
}

const AuthContext = createContext<AuthContextValue | null>(null);

const STORAGE_USER = "ecotec.auth.user";
const STORAGE_FARMS = "ecotec.farms";

const DEMO_ACCOUNTS: Array<{ email: string; password: string; user: AuthUser }> = [
  {
    email: "ecotech@gmail.com",
    password: "ecotech237",
    user: {
      id: "u-admin",
      email: "ecotech@gmail.com",
      name: "Admin ECOTEC",
      role: "admin",
    },
  },
  {
    email: "manager@ecotec.cm",
    password: "manager237",
    user: {
      id: "u-manager-1",
      email: "manager@ecotec.cm",
      name: "Jean Mboma",
      role: "manager",
      farmId: "farm-1",
    },
  },
];

const SEED_FARMS: Farm[] = [
  {
    id: "farm-1",
    name: "Ferme Pilote Yaoundé",
    location: "Yaoundé, Centre",
    manager: "Jean Mboma",
    email: "manager@ecotec.cm",
    capacity: 8000,
    active: 6420,
    createdAt: "2024-11-04",
    status: "active",
  },
  {
    id: "farm-2",
    name: "Coopérative Douala-Est",
    location: "Douala, Littoral",
    manager: "Aïcha Nkomo",
    email: "aicha@ecotec.cm",
    capacity: 5000,
    active: 4200,
    createdAt: "2025-02-18",
    status: "active",
  },
  {
    id: "farm-3",
    name: "Ferme Bafoussam",
    location: "Bafoussam, Ouest",
    manager: "Paul Kamga",
    email: "paul@ecotec.cm",
    capacity: 3500,
    active: 0,
    createdAt: "2025-09-12",
    status: "paused",
  },
];

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<AuthUser | null>(null);
  const [farms, setFarms] = useState<Farm[]>([]);
  const [hydrated, setHydrated] = useState(false);

  useEffect(() => {
    try {
      const u = localStorage.getItem(STORAGE_USER);
      if (u) setUser(JSON.parse(u));
      const f = localStorage.getItem(STORAGE_FARMS);
      setFarms(f ? JSON.parse(f) : SEED_FARMS);
    } catch {
      setFarms(SEED_FARMS);
    }
    setHydrated(true);
  }, []);

  useEffect(() => {
    if (!hydrated) return;
    if (user) localStorage.setItem(STORAGE_USER, JSON.stringify(user));
    else localStorage.removeItem(STORAGE_USER);
  }, [user, hydrated]);

  useEffect(() => {
    if (!hydrated) return;
    localStorage.setItem(STORAGE_FARMS, JSON.stringify(farms));
  }, [farms, hydrated]);

  const login: AuthContextValue["login"] = async (email, password) => {
    await new Promise((r) => setTimeout(r, 600));
    const acc = DEMO_ACCOUNTS.find(
      (a) => a.email.toLowerCase() === email.trim().toLowerCase() && a.password === password,
    );
    if (!acc) return { ok: false, error: "Identifiants invalides" };
    setUser(acc.user);
    return { ok: true };
  };

  const logout = () => setUser(null);

  const addFarm: AuthContextValue["addFarm"] = (farm) => {
    setFarms((prev) => [
      ...prev,
      {
        ...farm,
        id: `farm-${Date.now()}`,
        createdAt: new Date().toISOString().slice(0, 10),
      },
    ]);
  };

  const updateFarm: AuthContextValue["updateFarm"] = (id, patch) => {
    setFarms((prev) => prev.map((f) => (f.id === id ? { ...f, ...patch } : f)));
  };

  const removeFarm: AuthContextValue["removeFarm"] = (id) => {
    setFarms((prev) => prev.filter((f) => f.id !== id));
  };

  return (
    <AuthContext.Provider
      value={{
        user,
        farms,
        isAuthenticated: !!user,
        login,
        logout,
        addFarm,
        updateFarm,
        removeFarm,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within AuthProvider");
  return ctx;
}
