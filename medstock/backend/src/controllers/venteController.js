const { query } = require('../config/database');

const venteController = {
  async getAll(req, res) {
    try {
      const result = await query(`
        SELECT v.*, u.nom as vendeur_nom
        FROM ventes v
        LEFT JOIN utilisateurs u ON v.utilisateur_id = u.id
        ORDER BY v.created_at DESC
      `);
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get ventes error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des ventes' });
    }
  },

  async getById(req, res) {
    try {
      const { id } = req.params;
      const vente = await query(`
        SELECT v.*, u.nom as vendeur_nom
        FROM ventes v
        LEFT JOIN utilisateurs u ON v.utilisateur_id = u.id
        WHERE v.id = $1
      `, [id]);
      
      if (vente.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Vente non trouvée' });
      }
      
      const lignes = await query(`
        SELECT vl.*, m.nom as medicament_nom
        FROM vente_lignes vl
        LEFT JOIN medicaments m ON vl.medicament_id = m.id
        WHERE vl.vente_id = $1
      `, [id]);
      
      res.json({ 
        success: true, 
        data: {
          vente: vente.rows[0],
          lignes: lignes.rows
        }
      });
    } catch (error) {
      console.error('Get vente error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération de la vente' });
    }
  },

  async create(req, res) {
    try {
      await query('BEGIN');
      
      const {
        client_nom, client_telephone, client_whatsapp,
        items, remise = 0, tva = 0, mode_paiement = 'ESPECES'
      } = req.body;
      
      if (!items || !Array.isArray(items) || items.length === 0) {
        await query('ROLLBACK');
        return res.status(400).json({ success: false, message: 'Au moins un article est requis' });
      }
      
      let sous_total = 0;
      for (const item of items) {
        const medicament = await query(
          'SELECT prix_vente, quantite FROM medicaments WHERE id = $1',
          [item.medicament_id]
        );
        if (medicament.rows.length === 0) {
          throw new Error(`Médicament ${item.medicament_id} non trouvé`);
        }
        if (medicament.rows[0].quantite < item.quantite) {
          throw new Error(`Stock insuffisant pour le médicament`);
        }
        const prix = item.prix_unitaire || medicament.rows[0].prix_vente;
        sous_total += prix * item.quantite;
      }
      
      const total = sous_total + tva - remise;
      const numero = `F-${new Date().getFullYear()}-${Math.floor(Math.random() * 10000).toString().padStart(4, '0')}`;
      
      const venteResult = await query(`
        INSERT INTO ventes (
          numero, utilisateur_id, client_nom, client_telephone, client_whatsapp,
          sous_total, remise, tva, total, mode_paiement, statut
        ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, 'PAYEE')
        RETURNING *
      `, [numero, req.user.id, client_nom, client_telephone, client_whatsapp,
          sous_total, remise, tva, total, mode_paiement]);
      
      const vente = venteResult.rows[0];
      
      for (const item of items) {
        const medicament = await query(
          'SELECT nom, prix_vente FROM medicaments WHERE id = $1',
          [item.medicament_id]
        );
        const prix_unitaire = item.prix_unitaire || medicament.rows[0].prix_vente;
        const total_ligne = prix_unitaire * item.quantite;
        
        await query(`
          INSERT INTO vente_lignes (
            vente_id, medicament_id, nom_snapshot, prix_unitaire, quantite, total_ligne
          ) VALUES ($1, $2, $3, $4, $5, $6)
        `, [vente.id, item.medicament_id, medicament.rows[0].nom, prix_unitaire, item.quantite, total_ligne]);
      }
      
      await query('COMMIT');
      
      res.status(201).json({ success: true, message: 'Vente enregistrée avec succès', data: vente });
    } catch (error) {
      await query('ROLLBACK');
      console.error('Create vente error:', error);
      res.status(500).json({ success: false, message: error.message || 'Erreur lors de la création de la vente' });
    }
  },

  async getStats(req, res) {
    try {
      const today = await query(`
        SELECT COUNT(*) as nb_ventes, COALESCE(SUM(total), 0) as chiffre
        FROM ventes WHERE DATE(created_at) = CURRENT_DATE AND statut = 'PAYEE'
      `);
      
      const month = await query(`
        SELECT COUNT(*) as nb_ventes, COALESCE(SUM(total), 0) as chiffre
        FROM ventes WHERE EXTRACT(MONTH FROM created_at) = EXTRACT(MONTH FROM CURRENT_DATE)
        AND EXTRACT(YEAR FROM created_at) = EXTRACT(YEAR FROM CURRENT_DATE)
        AND statut = 'PAYEE'
      `);
      
      const topProducts = await query(`
        SELECT m.nom, COUNT(vl.id) as nb_ventes, SUM(vl.quantite) as quantite
        FROM vente_lignes vl
        JOIN medicaments m ON vl.medicament_id = m.id
        GROUP BY m.id, m.nom
        ORDER BY quantite DESC
        LIMIT 5
      `);
      
      res.json({
        success: true,
        data: {
          today: today.rows[0],
          month: month.rows[0],
          topProducts: topProducts.rows
        }
      });
    } catch (error) {
      console.error('Get stats error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des statistiques' });
    }
  },

  async exportPDF(req, res) {
    try {
      const { id } = req.params;
      const { genererFacturePDF } = require('../services/pdfService');
      await genererFacturePDF(id, res);
    } catch (error) {
      console.error('Export PDF error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de l\'export PDF' });
    }
  }
};

module.exports = venteController;