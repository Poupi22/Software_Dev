-- Activer l'extension UUID (nécessaire une fois)
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Table profiles avec UUID
CREATE TABLE IF NOT EXISTS profiles (
  id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  full_name     TEXT NOT NULL,
  email         TEXT NOT NULL UNIQUE,
  phone         TEXT,
  avatar_url    TEXT,
  city          TEXT,
  bio           TEXT,
  created_at    TIMESTAMPTZ NOT NULL DEFAULT NOW(),
  updated_at    TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- Message de confirmation
DO $$
BEGIN
    RAISE NOTICE '✅ Table profiles créée avec UUID';
END $$;