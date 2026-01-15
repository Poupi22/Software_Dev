-- =========================================================================
-- PharmaCare — Schéma PostgreSQL (tous les identifiants en UUID)
-- Version idempotente - peut être exécutée plusieurs fois sans erreur
-- =========================================================================

-- Extensions
CREATE EXTENSION IF NOT EXISTS "pgcrypto";
CREATE EXTENSION IF NOT EXISTS "citext";

-- =========================================================================
-- ENUMS (avec vérification d'existence)
-- =========================================================================
DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_role') THEN
        CREATE TYPE user_role AS ENUM ('ADMIN', 'GERANT');
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_statut') THEN
        CREATE TYPE user_statut AS ENUM ('ACTIF', 'INACTIF', 'SUSPENDU');
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'vente_statut') THEN
        CREATE TYPE vente_statut AS ENUM ('PAYEE', 'EN_ATTENTE', 'ANNULEE', 'REMBOURSEE');
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'paiement_mode') THEN
        CREATE TYPE paiement_mode AS ENUM ('ESPECES', 'CARTE', 'MOBILE_MONEY', 'VIREMENT', 'CHEQUE');
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'alerte_type') THEN
        CREATE TYPE alerte_type AS ENUM ('STOCK_BAS', 'STOCK_CRITIQUE', 'EXPIRATION_PROCHE', 'EXPIRE', 'AUTRE');
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'alerte_statut') THEN
        CREATE TYPE alerte_statut AS ENUM ('NOUVELLE', 'LUE', 'TRAITEE', 'IGNOREE');
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'mouvement_type') THEN
        CREATE TYPE mouvement_type AS ENUM ('ENTREE', 'SORTIE', 'AJUSTEMENT', 'PERTE');
    END IF;
END $$;

-- =========================================================================
-- TRIGGER GÉNÉRIQUE : updated_at
-- =========================================================================
CREATE OR REPLACE FUNCTION set_updated_at()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = NOW();
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- =========================================================================
-- TABLE UTILISATEURS
-- =========================================================================
CREATE TABLE IF NOT EXISTS utilisateurs (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nom             VARCHAR(150) NOT NULL,
    email           CITEXT UNIQUE NOT NULL,
    telephone       VARCHAR(30),
    mot_de_passe    VARCHAR(255) NOT NULL,
    role            user_role NOT NULL DEFAULT 'GERANT',
    statut          user_statut NOT NULL DEFAULT 'ACTIF',
    avatar_url      TEXT,
    derniere_connexion TIMESTAMPTZ,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DROP INDEX IF EXISTS idx_utilisateurs_role;
DROP INDEX IF EXISTS idx_utilisateurs_statut;
CREATE INDEX idx_utilisateurs_role ON utilisateurs(role);
CREATE INDEX idx_utilisateurs_statut ON utilisateurs(statut);

DROP TRIGGER IF EXISTS trg_utilisateurs_updated ON utilisateurs;
CREATE TRIGGER trg_utilisateurs_updated BEFORE UPDATE ON utilisateurs
    FOR EACH ROW EXECUTE FUNCTION set_updated_at();

-- =========================================================================
-- TABLE CATEGORIES
-- =========================================================================
CREATE TABLE IF NOT EXISTS categories (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nom         VARCHAR(120) UNIQUE NOT NULL,
    description TEXT,
    image_url   TEXT,
    couleur     VARCHAR(20),
    created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DROP TRIGGER IF EXISTS trg_categories_updated ON categories;
CREATE TRIGGER trg_categories_updated BEFORE UPDATE ON categories
    FOR EACH ROW EXECUTE FUNCTION set_updated_at();

-- =========================================================================
-- TABLE FOURNISSEURS
-- =========================================================================
CREATE TABLE IF NOT EXISTS fournisseurs (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nom         VARCHAR(150) NOT NULL,
    contact     VARCHAR(150),
    telephone   VARCHAR(30),
    email       CITEXT,
    adresse     TEXT,
    created_at  TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at  TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DROP TRIGGER IF EXISTS trg_fournisseurs_updated ON fournisseurs;
CREATE TRIGGER trg_fournisseurs_updated BEFORE UPDATE ON fournisseurs
    FOR EACH ROW EXECUTE FUNCTION set_updated_at();

-- =========================================================================
-- TABLE MEDICAMENTS
-- =========================================================================
CREATE TABLE IF NOT EXISTS medicaments (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code_barre      VARCHAR(64) UNIQUE,
    nom             VARCHAR(200) NOT NULL,
    description     TEXT,
    image_url       TEXT,
    categorie_id    UUID NOT NULL,
    fournisseur_id  UUID,
    dosage          VARCHAR(100),
    forme           VARCHAR(80),
    prix_achat      NUMERIC(12,2) NOT NULL DEFAULT 0 CHECK (prix_achat >= 0),
    prix_vente      NUMERIC(12,2) NOT NULL CHECK (prix_vente >= 0),
    quantite        INTEGER NOT NULL DEFAULT 0 CHECK (quantite >= 0),
    seuil_alerte    INTEGER NOT NULL DEFAULT 10 CHECK (seuil_alerte >= 0),
    date_expiration DATE,
    ordonnance      BOOLEAN NOT NULL DEFAULT FALSE,
    actif           BOOLEAN NOT NULL DEFAULT TRUE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- Ajout des contraintes de clé étrangère (avec vérification)
DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'medicaments_categorie_id_fkey') THEN
        ALTER TABLE medicaments ADD CONSTRAINT medicaments_categorie_id_fkey 
        FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE RESTRICT;
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'medicaments_fournisseur_id_fkey') THEN
        ALTER TABLE medicaments ADD CONSTRAINT medicaments_fournisseur_id_fkey 
        FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id) ON DELETE SET NULL;
    END IF;
END $$;

DROP INDEX IF EXISTS idx_medicaments_categorie;
DROP INDEX IF EXISTS idx_medicaments_fournisseur;
DROP INDEX IF EXISTS idx_medicaments_expiration;
CREATE INDEX idx_medicaments_categorie ON medicaments(categorie_id);
CREATE INDEX idx_medicaments_fournisseur ON medicaments(fournisseur_id);
CREATE INDEX idx_medicaments_expiration ON medicaments(date_expiration);

DROP TRIGGER IF EXISTS trg_medicaments_updated ON medicaments;
CREATE TRIGGER trg_medicaments_updated BEFORE UPDATE ON medicaments
    FOR EACH ROW EXECUTE FUNCTION set_updated_at();

-- =========================================================================
-- TABLE MOUVEMENTS STOCK
-- =========================================================================
CREATE TABLE IF NOT EXISTS mouvements_stock (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    medicament_id   UUID NOT NULL,
    utilisateur_id  UUID,
    type            mouvement_type NOT NULL,
    quantite        INTEGER NOT NULL CHECK (quantite <> 0),
    motif           TEXT,
    reference       VARCHAR(80),
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'mouvements_stock_medicament_id_fkey') THEN
        ALTER TABLE mouvements_stock ADD CONSTRAINT mouvements_stock_medicament_id_fkey 
        FOREIGN KEY (medicament_id) REFERENCES medicaments(id) ON DELETE CASCADE;
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'mouvements_stock_utilisateur_id_fkey') THEN
        ALTER TABLE mouvements_stock ADD CONSTRAINT mouvements_stock_utilisateur_id_fkey 
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL;
    END IF;
END $$;

DROP INDEX IF EXISTS idx_mouvements_medicament;
DROP INDEX IF EXISTS idx_mouvements_date;
CREATE INDEX idx_mouvements_medicament ON mouvements_stock(medicament_id);
CREATE INDEX idx_mouvements_date ON mouvements_stock(created_at);

-- =========================================================================
-- TABLE VENTES
-- =========================================================================
CREATE TABLE IF NOT EXISTS ventes (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    numero          VARCHAR(40) UNIQUE NOT NULL,
    utilisateur_id  UUID NOT NULL,
    client_nom      VARCHAR(150),
    client_telephone VARCHAR(30),
    client_whatsapp VARCHAR(30),
    sous_total      NUMERIC(12,2) NOT NULL DEFAULT 0,
    remise          NUMERIC(12,2) NOT NULL DEFAULT 0,
    tva             NUMERIC(12,2) NOT NULL DEFAULT 0,
    total           NUMERIC(12,2) NOT NULL DEFAULT 0,
    mode_paiement   paiement_mode NOT NULL DEFAULT 'ESPECES',
    statut          vente_statut NOT NULL DEFAULT 'PAYEE',
    notes           TEXT,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'ventes_utilisateur_id_fkey') THEN
        ALTER TABLE ventes ADD CONSTRAINT ventes_utilisateur_id_fkey 
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE RESTRICT;
    END IF;
END $$;

DROP INDEX IF EXISTS idx_ventes_utilisateur;
DROP INDEX IF EXISTS idx_ventes_date;
DROP INDEX IF EXISTS idx_ventes_statut;
DROP INDEX IF EXISTS idx_ventes_whatsapp;
CREATE INDEX idx_ventes_utilisateur ON ventes(utilisateur_id);
CREATE INDEX idx_ventes_date ON ventes(created_at);
CREATE INDEX idx_ventes_statut ON ventes(statut);
CREATE INDEX idx_ventes_whatsapp ON ventes(client_whatsapp);

DROP TRIGGER IF EXISTS trg_ventes_updated ON ventes;
CREATE TRIGGER trg_ventes_updated BEFORE UPDATE ON ventes
    FOR EACH ROW EXECUTE FUNCTION set_updated_at();

-- =========================================================================
-- TABLE VENTE_LIGNES
-- =========================================================================
CREATE TABLE IF NOT EXISTS vente_lignes (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    vente_id        UUID NOT NULL,
    medicament_id   UUID NOT NULL,
    nom_snapshot    VARCHAR(200) NOT NULL,
    prix_unitaire   NUMERIC(12,2) NOT NULL CHECK (prix_unitaire >= 0),
    quantite        INTEGER NOT NULL CHECK (quantite > 0),
    remise_ligne    NUMERIC(12,2) NOT NULL DEFAULT 0,
    total_ligne     NUMERIC(12,2) NOT NULL,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'vente_lignes_vente_id_fkey') THEN
        ALTER TABLE vente_lignes ADD CONSTRAINT vente_lignes_vente_id_fkey 
        FOREIGN KEY (vente_id) REFERENCES ventes(id) ON DELETE CASCADE;
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'vente_lignes_medicament_id_fkey') THEN
        ALTER TABLE vente_lignes ADD CONSTRAINT vente_lignes_medicament_id_fkey 
        FOREIGN KEY (medicament_id) REFERENCES medicaments(id) ON DELETE RESTRICT;
    END IF;
END $$;

DROP INDEX IF EXISTS idx_vente_lignes_vente;
DROP INDEX IF EXISTS idx_vente_lignes_medicament;
CREATE INDEX idx_vente_lignes_vente ON vente_lignes(vente_id);
CREATE INDEX idx_vente_lignes_medicament ON vente_lignes(medicament_id);

-- =========================================================================
-- TABLE ALERTES
-- =========================================================================
CREATE TABLE IF NOT EXISTS alertes (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    medicament_id   UUID,
    type            alerte_type NOT NULL,
    statut          alerte_statut NOT NULL DEFAULT 'NOUVELLE',
    titre           VARCHAR(200) NOT NULL,
    message         TEXT,
    traite_par      UUID,
    traite_at       TIMESTAMPTZ,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'alertes_medicament_id_fkey') THEN
        ALTER TABLE alertes ADD CONSTRAINT alertes_medicament_id_fkey 
        FOREIGN KEY (medicament_id) REFERENCES medicaments(id) ON DELETE CASCADE;
    END IF;
END $$;

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'alertes_traite_par_fkey') THEN
        ALTER TABLE alertes ADD CONSTRAINT alertes_traite_par_fkey 
        FOREIGN KEY (traite_par) REFERENCES utilisateurs(id) ON DELETE SET NULL;
    END IF;
END $$;

DROP INDEX IF EXISTS idx_alertes_statut;
DROP INDEX IF EXISTS idx_alertes_type;
CREATE INDEX idx_alertes_statut ON alertes(statut);
CREATE INDEX idx_alertes_type ON alertes(type);

-- =========================================================================
-- TABLE ACTIVITES
-- =========================================================================
CREATE TABLE IF NOT EXISTS activites (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    utilisateur_id  UUID,
    action          VARCHAR(80) NOT NULL,
    entite          VARCHAR(80),
    entite_id       UUID,
    details         JSONB,
    ip_address      INET,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'activites_utilisateur_id_fkey') THEN
        ALTER TABLE activites ADD CONSTRAINT activites_utilisateur_id_fkey 
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL;
    END IF;
END $$;

DROP INDEX IF EXISTS idx_activites_user;
DROP INDEX IF EXISTS idx_activites_action;
DROP INDEX IF EXISTS idx_activites_date;
CREATE INDEX idx_activites_user ON activites(utilisateur_id);
CREATE INDEX idx_activites_action ON activites(action);
CREATE INDEX idx_activites_date ON activites(created_at);

-- =========================================================================
-- TABLE NOTIFICATIONS
-- =========================================================================
CREATE TABLE IF NOT EXISTS notifications (
    id              UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    utilisateur_id  UUID NOT NULL,
    titre           VARCHAR(200) NOT NULL,
    message         TEXT,
    lien            TEXT,
    lue             BOOLEAN NOT NULL DEFAULT FALSE,
    created_at      TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'notifications_utilisateur_id_fkey') THEN
        ALTER TABLE notifications ADD CONSTRAINT notifications_utilisateur_id_fkey 
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE;
    END IF;
END $$;

DROP INDEX IF EXISTS idx_notifications_user;
CREATE INDEX idx_notifications_user ON notifications(utilisateur_id, lue);

-- =========================================================================
-- TRIGGERS MÉTIER
-- =========================================================================

-- Trigger pour les lignes de vente
CREATE OR REPLACE FUNCTION trg_vente_ligne_after_insert()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE medicaments
       SET quantite = quantite - NEW.quantite
     WHERE id = NEW.medicament_id;

    INSERT INTO mouvements_stock (medicament_id, type, quantite, motif, reference)
    SELECT NEW.medicament_id, 'SORTIE', NEW.quantite, 'Vente', v.numero
      FROM ventes v WHERE v.id = NEW.vente_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS vente_ligne_after_insert ON vente_lignes;
CREATE TRIGGER vente_ligne_after_insert
AFTER INSERT ON vente_lignes
FOR EACH ROW EXECUTE FUNCTION trg_vente_ligne_after_insert();

-- Trigger pour les alertes de stock
CREATE OR REPLACE FUNCTION trg_medicament_check_stock()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.quantite <= 0 THEN
        INSERT INTO alertes(medicament_id, type, titre, message)
        VALUES (NEW.id, 'STOCK_CRITIQUE', 'Rupture de stock',
                'Le médicament ' || NEW.nom || ' est en rupture.');
    ELSIF NEW.quantite <= NEW.seuil_alerte THEN
        INSERT INTO alertes(medicament_id, type, titre, message)
        VALUES (NEW.id, 'STOCK_BAS', 'Stock bas',
                'Le médicament ' || NEW.nom || ' atteint le seuil (' || NEW.quantite || ' restants).');
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS medicament_check_stock ON medicaments;
CREATE TRIGGER medicament_check_stock
AFTER UPDATE OF quantite ON medicaments
FOR EACH ROW
WHEN (NEW.quantite <> OLD.quantite)
EXECUTE FUNCTION trg_medicament_check_stock();

-- =========================================================================
-- VUES
-- =========================================================================
DROP VIEW IF EXISTS v_stock_bas CASCADE;
DROP VIEW IF EXISTS v_expirations_proches CASCADE;
DROP VIEW IF EXISTS v_ventes_jour CASCADE;

CREATE VIEW v_stock_bas AS
SELECT id, nom, quantite, seuil_alerte, date_expiration
  FROM medicaments
 WHERE actif = TRUE AND quantite <= seuil_alerte;

CREATE VIEW v_expirations_proches AS
SELECT id, nom, quantite, date_expiration
  FROM medicaments
 WHERE actif = TRUE
   AND date_expiration IS NOT NULL
   AND date_expiration <= CURRENT_DATE + INTERVAL '60 days';

CREATE VIEW v_ventes_jour AS
SELECT DATE(created_at) AS jour,
       COUNT(*)         AS nb_ventes,
       SUM(total)       AS chiffre_affaires
  FROM ventes
 WHERE statut = 'PAYEE'
 GROUP BY DATE(created_at)
 ORDER BY jour DESC;

-- =========================================================================
-- FIN
-- =========================================================================