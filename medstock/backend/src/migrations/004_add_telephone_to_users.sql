-- =========================================================================
-- Migration 004: Ajout de la colonne telephone dans la table utilisateurs
-- =========================================================================

-- Ajouter la colonne telephone si elle n'existe pas
ALTER TABLE utilisateurs ADD COLUMN IF NOT EXISTS telephone VARCHAR(30);

-- =========================================================================
-- FIN DE LA MIGRATION
-- =========================================================================
