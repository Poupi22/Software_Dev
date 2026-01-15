-- =========================================================================
-- Migration 003: Ajout de la table des activités système
-- =========================================================================

-- Création de la table des activités
CREATE TABLE IF NOT EXISTS activites (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    utilisateur_id UUID REFERENCES utilisateurs(id) ON DELETE SET NULL,
    action VARCHAR(80) NOT NULL,
    entite VARCHAR(80),
    entite_id UUID,
    details JSONB,
    ip_address INET,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- Création des index
CREATE INDEX IF NOT EXISTS idx_activites_user ON activites(utilisateur_id);
CREATE INDEX IF NOT EXISTS idx_activites_action ON activites(action);
CREATE INDEX IF NOT EXISTS idx_activites_entite ON activites(entite);
CREATE INDEX IF NOT EXISTS idx_activites_date ON activites(created_at);

-- =========================================================================
-- Triggers pour enregistrer automatiquement les activités
-- =========================================================================

-- Fonction pour enregistrer les activités sur les médicaments
CREATE OR REPLACE FUNCTION log_medicament_activity()
RETURNS TRIGGER AS $$
DECLARE
    user_id UUID;
BEGIN
    BEGIN
        user_id := current_setting('app.current_user_id', true)::UUID;
    EXCEPTION WHEN OTHERS THEN
        user_id := NULL;
    END;
    
    IF TG_OP = 'INSERT' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'CREATE_MEDICAMENT', 'medicament', NEW.id, jsonb_build_object('nom', NEW.nom));
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'UPDATE_MEDICAMENT', 'medicament', NEW.id, jsonb_build_object('nom', NEW.nom, 'ancien_quantite', OLD.quantite, 'nouveau_quantite', NEW.quantite));
    ELSIF TG_OP = 'DELETE' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'DELETE_MEDICAMENT', 'medicament', OLD.id, jsonb_build_object('nom', OLD.nom));
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS medicament_activity_trigger ON medicaments;
CREATE TRIGGER medicament_activity_trigger
AFTER INSERT OR UPDATE OR DELETE ON medicaments
FOR EACH ROW EXECUTE FUNCTION log_medicament_activity();

-- Fonction pour enregistrer les activités sur les catégories
CREATE OR REPLACE FUNCTION log_categorie_activity()
RETURNS TRIGGER AS $$
DECLARE
    user_id UUID;
BEGIN
    BEGIN
        user_id := current_setting('app.current_user_id', true)::UUID;
    EXCEPTION WHEN OTHERS THEN
        user_id := NULL;
    END;
    
    IF TG_OP = 'INSERT' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'CREATE_CATEGORIE', 'categorie', NEW.id, jsonb_build_object('nom', NEW.nom));
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'UPDATE_CATEGORIE', 'categorie', NEW.id, jsonb_build_object('nom', NEW.nom));
    ELSIF TG_OP = 'DELETE' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'DELETE_CATEGORIE', 'categorie', OLD.id, jsonb_build_object('nom', OLD.nom));
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS categorie_activity_trigger ON categories;
CREATE TRIGGER categorie_activity_trigger
AFTER INSERT OR UPDATE OR DELETE ON categories
FOR EACH ROW EXECUTE FUNCTION log_categorie_activity();

-- Fonction pour enregistrer les activités sur les ventes
CREATE OR REPLACE FUNCTION log_vente_activity()
RETURNS TRIGGER AS $$
DECLARE
    ip_addr INET;
BEGIN
    BEGIN
        ip_addr := current_setting('app.current_ip', true)::INET;
    EXCEPTION WHEN OTHERS THEN
        ip_addr := NULL;
    END;
    
    INSERT INTO activites (utilisateur_id, action, entite, entite_id, details, ip_address)
    VALUES (NEW.utilisateur_id, 'CREATE_VENTE', 'vente', NEW.id, jsonb_build_object('numero', NEW.numero, 'total', NEW.total), ip_addr);
    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS vente_activity_trigger ON ventes;
CREATE TRIGGER vente_activity_trigger
AFTER INSERT ON ventes
FOR EACH ROW EXECUTE FUNCTION log_vente_activity();

-- Fonction pour enregistrer les activités sur les utilisateurs
CREATE OR REPLACE FUNCTION log_user_activity()
RETURNS TRIGGER AS $$
DECLARE
    user_id UUID;
BEGIN
    BEGIN
        user_id := current_setting('app.current_user_id', true)::UUID;
    EXCEPTION WHEN OTHERS THEN
        user_id := NULL;
    END;
    
    IF TG_OP = 'INSERT' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'CREATE_USER', 'utilisateur', NEW.id, jsonb_build_object('nom', NEW.nom, 'role', NEW.role));
    ELSIF TG_OP = 'UPDATE' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'UPDATE_USER', 'utilisateur', NEW.id, jsonb_build_object('nom', NEW.nom, 'statut', NEW.statut));
    ELSIF TG_OP = 'DELETE' THEN
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES (user_id, 'DELETE_USER', 'utilisateur', OLD.id, jsonb_build_object('nom', OLD.nom));
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS user_activity_trigger ON utilisateurs;
CREATE TRIGGER user_activity_trigger
AFTER INSERT OR UPDATE OR DELETE ON utilisateurs
FOR EACH ROW EXECUTE FUNCTION log_user_activity();

-- =========================================================================
-- FIN DE LA MIGRATION
-- =========================================================================
