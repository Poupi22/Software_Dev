const express = require('express');
const router = express.Router();
const { query } = require('../config/database');
const { authMiddleware, isAdmin } = require('../middleware/auth');

router.get('/', authMiddleware, isAdmin, async (req, res) => {
  try {
    const result = await query(`
      SELECT a.*, u.nom as utilisateur_nom
      FROM activites a
      LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
      ORDER BY a.created_at DESC
      LIMIT 200
    `);
    res.json({ success: true, data: result.rows });
  } catch (error) {
    console.error('Get activites error:', error);
    res.status(500).json({ success: false, message: 'Erreur lors de la récupération des activités' });
  }
});

router.get('/stats', authMiddleware, isAdmin, async (req, res) => {
  try {
    const result = await query(`
      SELECT 
        COUNT(*) as total,
        COUNT(DISTINCT utilisateur_id) as utilisateurs_actifs,
        COUNT(CASE WHEN action = 'CREATE_VENTE' THEN 1 END) as ventes,
        COUNT(CASE WHEN action LIKE '%MEDICAMENT%' THEN 1 END) as modifs_medicaments
      FROM activites
      WHERE created_at >= NOW() - INTERVAL '7 days'
    `);
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    console.error('Get stats error:', error);
    res.status(500).json({ success: false, message: 'Erreur lors de la récupération des statistiques' });
  }
});

module.exports = router;
