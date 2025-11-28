CREATE TABLE IF NOT EXISTS categories (
  id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
  name TEXT NOT NULL,
  slug TEXT NOT NULL UNIQUE,
  icon TEXT,
  position INT DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

INSERT INTO categories (name, slug, icon, position) VALUES
  ('Plomberie', 'plomberie', 'Wrench', 1),
  ('Électricité', 'electricite', 'Zap', 2),
  ('Ménage', 'menage', 'Sparkles', 3),
  ('Coiffure', 'coiffure', 'Scissors', 4),
  ('Menuiserie', 'menuiserie', 'Hammer', 5),
  ('Informatique', 'informatique', 'Laptop', 6)
ON CONFLICT (slug) DO NOTHING;