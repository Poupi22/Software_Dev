const { query } = require('../config/database');

const alerteController = {
  async getAll(req, res) {
    try {
      const result = await query(`
        SELECT a.*, m.nom as medicament_nom, m.quantite as stock_actuel, 
               m.seuil_alerte, u.nom as traite_par_nom
        FROM alertes a
        LEFT JOIN medicaments m ON a.medicament_id = m.id
        LEFT JOIN utilisateurs u ON a.traite_par = u.id
        ORDER BY a.created_at DESC
      `);
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get alertes error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des alertes' });
    }
  },

  async getNonTraitees(req, res) {
    try {
      const result = await query(`
        SELECT a.*, m.nom as medicament_nom, m.quantite as stock_actuel, m.seuil_alerte
        FROM alertes a
        LEFT JOIN medicaments m ON a.medicament_id = m.id
        WHERE a.statut IN ('NOUVELLE', 'LUE')
        ORDER BY a.created_at DESC
      `);
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get alertes non traitées error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des alertes' });
    }
  },

  async marquerLue(req, res) {
    try {
      const { id } = req.params;
      const result = await query(`
        UPDATE alertes 
        SET statut = 'LUE', updated_at = NOW()
        WHERE id = $1 AND statut = 'NOUVELLE'
        RETURNING *
      `, [id]);
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Alerte non trouvée ou déjà lue' });
      }
      
      res.json({ success: true, message: 'Alerte marquée comme lue', data: result.rows[0] });
    } catch (error) {
      console.error('Marquer alerte lue error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la mise à jour' });
    }
  },

  async traiter(req, res) {
    try {
      const { id } = req.params;
      const { commentaire } = req.body;
      
      const result = await query(`
        UPDATE alertes 
        SET statut = 'TRAITEE', traite_par = $2, traite_at = NOW(), message = COALESCE($3, message)
        WHERE id = $1
        RETURNING *
      `, [id, req.user.id, commentaire]);
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Alerte non trouvée' });
      }
      
      res.json({ success: true, message: 'Alerte traitée avec succès', data: result.rows[0] });
    } catch (error) {
      console.error('Traiter alerte error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors du traitement' });
    }
  },

  async getStatistiques(req, res) {
    try {
      const stats = await query(`
        SELECT 
          COUNT(*) as total,
          COUNT(CASE WHEN statut = 'NOUVELLE' THEN 1 END) as nouvelles,
          COUNT(CASE WHEN statut = 'LUE' THEN 1 END) as lues,
          COUNT(CASE WHEN statut = 'TRAITEE' THEN 1 END) as traitees,
          COUNT(CASE WHEN type = 'STOCK_CRITIQUE' THEN 1 END) as stock_critique,
          COUNT(CASE WHEN type = 'STOCK_BAS' THEN 1 END) as stock_bas,
          COUNT(CASE WHEN type = 'EXPIRATION_PROCHE' THEN 1 END) as expiration_proche,
          COUNT(CASE WHEN type = 'EXPIRE' THEN 1 END) as expire
        FROM alertes
      `);
      
      res.json({ success: true, data: stats.rows[0] });
    } catch (error) {
      console.error('Get stats alertes error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des statistiques' });
    }
  },

  async genererAlertesManuelles(req, res) {
    try {
      // Vérifier les stocks bas
      const stockBas = await query(`
        SELECT id, nom, quantite, seuil_alerte, date_expiration
        FROM medicaments
        WHERE actif = true AND quantite <= seuil_alerte
      `);
      
      for (const medoc of stockBas.rows) {
        const type = medoc.quantite <= 0 ? 'STOCK_CRITIQUE' : 'STOCK_BAS';
        const titre = medoc.quantite <= 0 ? 'Rupture de stock' : 'Stock bas';
        const message = `Le médicament ${medoc.nom} a un stock de ${medoc.quantite} unités (seuil: ${medoc.seuil_alerte})`;
        
        await query(`
          INSERT INTO alertes (medicament_id, type, statut, titre, message)
          VALUES ($1, $2, 'NOUVELLE', $3, $4)
          ON CONFLICT DO NOTHING
        `, [medoc.id, type, titre, message]);
      }
      
      // Vérifier les expirations
      const expirations = await query(`
        SELECT id, nom, quantite, date_expiration
        FROM medicaments
        WHERE actif = true 
          AND date_expiration IS NOT NULL
          AND date_expiration <= CURRENT_DATE + INTERVAL '60 days'
      `);
      
      for (const medoc of expirations.rows) {
        const joursRestants = Math.ceil((new Date(medoc.date_expiration) - new Date()) / (1000 * 60 * 60 * 24));
        const type = joursRestants <= 0 ? 'EXPIRE' : 'EXPIRATION_PROCHE';
        const titre = joursRestants <= 0 ? 'Médicament expiré' : 'Expiration proche';
        const message = `Le médicament ${medoc.nom} expire le ${new Date(medoc.date_expiration).toLocaleDateString('fr-FR')} (${Math.abs(joursRestants)} jours ${joursRestants <= 0 ? 'de retard' : 'restants'})`;
        
        await query(`
          INSERT INTO alertes (medicament_id, type, statut, titre, message)
          VALUES ($1, $2, 'NOUVELLE', $3, $4)
          ON CONFLICT DO NOTHING
        `, [medoc.id, type, titre, message]);
      }
      
      res.json({ 
        success: true, 
        message: 'Alertes générées avec succès',
        data: {
          stock_bas: stockBas.rows.length,
          expirations: expirations.rows.length
        }
      });
    } catch (error) {
      console.error('Generer alertes error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la génération des alertes' });
    }
  }
};

module.exports = alerteController;