-- =========================================================================
-- Migration 002: Ajout des triggers pour notifications automatiques
-- =========================================================================

-- Trigger pour créer une notification quand une vente est faite
CREATE OR REPLACE FUNCTION notify_new_sale()
RETURNS TRIGGER AS $$
BEGIN
  INSERT INTO notifications (utilisateur_id, titre, message, lien)
  VALUES (
    NEW.utilisateur_id,
    'Nouvelle vente enregistrée',
    'Vente n° ' || NEW.numero || ' d''un montant de ' || NEW.total || ' FCFA',
    '/admin/ventes/' || NEW.id
  );
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trigger_new_sale ON ventes;
CREATE TRIGGER trigger_new_sale
AFTER INSERT ON ventes
FOR EACH ROW
EXECUTE FUNCTION notify_new_sale();

-- Trigger pour créer une notification quand un médicament est en stock bas ou critique
CREATE OR REPLACE FUNCTION notify_critical_stock()
RETURNS TRIGGER AS $$
BEGIN
  -- Stock faible (entre 1 et seuil_alerte)
  IF NEW.quantite <= NEW.seuil_alerte AND NEW.quantite > 0 THEN
    INSERT INTO notifications (utilisateur_id, titre, message, lien)
    SELECT 
      u.id,
      'Stock faible',
      'Le médicament ' || NEW.nom || ' est en stock faible: ' || NEW.quantite || ' unités restantes',
      '/admin/medicaments/' || NEW.id
    FROM utilisateurs u
    WHERE u.role IN ('ADMIN', 'GERANT') AND u.statut = 'ACTIF';
  -- Rupture de stock
  ELSIF NEW.quantite = 0 THEN
    INSERT INTO notifications (utilisateur_id, titre, message, lien)
    SELECT 
      u.id,
      'Stock critique',
      'Le médicament ' || NEW.nom || ' est en rupture de stock!',
      '/admin/medicaments/' || NEW.id
    FROM utilisateurs u
    WHERE u.role IN ('ADMIN', 'GERANT') AND u.statut = 'ACTIF';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trigger_critical_stock ON medicaments;
CREATE TRIGGER trigger_critical_stock
AFTER UPDATE OF quantite ON medicaments
FOR EACH ROW
WHEN (OLD.quantite IS DISTINCT FROM NEW.quantite)
EXECUTE FUNCTION notify_critical_stock();

-- Trigger pour créer une notification quand un médicament expire bientôt (UNIQUEMENT pour UPDATE)
CREATE OR REPLACE FUNCTION notify_expiring_medicament()
RETURNS TRIGGER AS $$
BEGIN
  IF NEW.date_expiration IS NOT NULL AND NEW.date_expiration <= CURRENT_DATE + INTERVAL '30 days' THEN
    INSERT INTO notifications (utilisateur_id, titre, message, lien)
    SELECT 
      u.id,
      'Expiration proche',
      'Le médicament ' || NEW.nom || ' expire le ' || TO_CHAR(NEW.date_expiration, 'DD/MM/YYYY'),
      '/admin/medicaments/' || NEW.id
    FROM utilisateurs u
    WHERE u.role IN ('ADMIN', 'GERANT') AND u.statut = 'ACTIF';
  END IF;
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trigger_expiring_medicament ON medicaments;
CREATE TRIGGER trigger_expiring_medicament
AFTER UPDATE OF date_expiration ON medicaments
FOR EACH ROW
WHEN (OLD.date_expiration IS DISTINCT FROM NEW.date_expiration)
EXECUTE FUNCTION notify_expiring_medicament();

-- Trigger pour créer une notification quand un nouvel utilisateur est créé
CREATE OR REPLACE FUNCTION notify_new_user()
RETURNS TRIGGER AS $$
BEGIN
  INSERT INTO notifications (utilisateur_id, titre, message, lien)
  SELECT 
    u.id,
    'Nouvel utilisateur',
    'Un nouveau ' || NEW.role || ' a été créé: ' || NEW.nom,
    '/admin/utilisateurs'
  FROM utilisateurs u
  WHERE u.role = 'ADMIN' AND u.statut = 'ACTIF';
  
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trigger_new_user ON utilisateurs;
CREATE TRIGGER trigger_new_user
AFTER INSERT ON utilisateurs
FOR EACH ROW
EXECUTE FUNCTION notify_new_user();

-- =========================================================================
-- FIN DE LA MIGRATION
-- =========================================================================
