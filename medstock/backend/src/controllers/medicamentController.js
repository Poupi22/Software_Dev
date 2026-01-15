const { query } = require('../config/database');
const { cloudinary } = require('../config/cloudinary');

const medicamentController = {
  async getAll(req, res) {
    try {
      const result = await query(`
        SELECT m.*, c.nom as categorie_nom, f.nom as fournisseur_nom
        FROM medicaments m
        LEFT JOIN categories c ON m.categorie_id = c.id
        LEFT JOIN fournisseurs f ON m.fournisseur_id = f.id
        ORDER BY m.nom ASC
      `);
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get medicaments error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des médicaments' });
    }
  },

  async getById(req, res) {
    try {
      const { id } = req.params;
      const result = await query(`
        SELECT m.*, c.nom as categorie_nom, f.nom as fournisseur_nom
        FROM medicaments m
        LEFT JOIN categories c ON m.categorie_id = c.id
        LEFT JOIN fournisseurs f ON m.fournisseur_id = f.id
        WHERE m.id = $1
      `, [id]);
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Médicament non trouvé' });
      }
      res.json({ success: true, data: result.rows[0] });
    } catch (error) {
      console.error('Get medicament error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération du médicament' });
    }
  },

  async create(req, res) {
    try {
      const {
        code_barre, nom, description, categorie_id, fournisseur_id,
        dosage, forme, prix_achat, prix_vente, quantite,
        seuil_alerte, date_expiration, ordonnance
      } = req.body;
      
      let image_url = null;
      if (req.file) {
        image_url = req.file.path;
      }
      
      if (!nom || !categorie_id) {
        return res.status(400).json({ success: false, message: 'Le nom et la catégorie sont requis' });
      }
      
      const result = await query(`
        INSERT INTO medicaments (
          code_barre, nom, description, image_url, categorie_id, fournisseur_id,
          dosage, forme, prix_achat, prix_vente, quantite, seuil_alerte,
          date_expiration, ordonnance
        ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14)
        RETURNING *
      `, [code_barre, nom, description, image_url, categorie_id, fournisseur_id,
          dosage, forme, prix_achat, prix_vente, quantite || 0, seuil_alerte || 10,
          date_expiration, ordonnance || false]);
      
      res.status(201).json({ success: true, message: 'Médicament créé avec succès', data: result.rows[0] });
    } catch (error) {
      console.error('Create medicament error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la création du médicament' });
    }
  },

  async update(req, res) {
  try {
    const { id } = req.params;
    const {
      code_barre, nom, description, categorie_id, fournisseur_id,
      dosage, forme, prix_achat, prix_vente, quantite,
      seuil_alerte, date_expiration, ordonnance, actif
    } = req.body;
    
    let image_url = null;
    if (req.file) {
      image_url = req.file.path;
    }
    
    // Convertir la chaîne vide en null pour la date
    const expirationDate = date_expiration === "" ? null : date_expiration;
    
    const result = await query(`
      UPDATE medicaments SET
        code_barre = COALESCE($1, code_barre),
        nom = COALESCE($2, nom),
        description = COALESCE($3, description),
        image_url = COALESCE($4, image_url),
        categorie_id = COALESCE($5, categorie_id),
        fournisseur_id = COALESCE($6, fournisseur_id),
        dosage = COALESCE($7, dosage),
        forme = COALESCE($8, forme),
        prix_achat = COALESCE($9, prix_achat),
        prix_vente = COALESCE($10, prix_vente),
        quantite = COALESCE($11, quantite),
        seuil_alerte = COALESCE($12, seuil_alerte),
        date_expiration = COALESCE($13, date_expiration),
        ordonnance = COALESCE($14, ordonnance),
        actif = COALESCE($15, actif),
        updated_at = NOW()
      WHERE id = $16
      RETURNING *
    `, [code_barre, nom, description, image_url, categorie_id, fournisseur_id,
        dosage, forme, prix_achat, prix_vente, quantite, seuil_alerte,
        expirationDate, ordonnance, actif, id]);
    
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Médicament non trouvé' });
    }
    
    res.json({ success: true, message: 'Médicament mis à jour avec succès', data: result.rows[0] });
  } catch (error) {
    console.error('Update medicament error:', error);
    res.status(500).json({ success: false, message: 'Erreur lors de la mise à jour du médicament' });
  }
},

  async delete(req, res) {
    try {
      const { id } = req.params;
      
      const medicament = await query('SELECT image_url FROM medicaments WHERE id = $1', [id]);
      if (medicament.rows.length > 0 && medicament.rows[0].image_url) {
        const publicId = medicament.rows[0].image_url.split('/').slice(-2).join('/').split('.')[0];
        await cloudinary.uploader.destroy(publicId);
      }
      
      const result = await query('DELETE FROM medicaments WHERE id = $1 RETURNING id', [id]);
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Médicament non trouvé' });
      }
      
      res.json({ success: true, message: 'Médicament supprimé avec succès' });
    } catch (error) {
      console.error('Delete medicament error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la suppression du médicament' });
    }
  },

  async search(req, res) {
    try {
      const { q } = req.query;
      if (!q) {
        return res.status(400).json({ success: false, message: 'Terme de recherche requis' });
      }
      
      const result = await query(`
        SELECT * FROM medicaments 
        WHERE nom ILIKE $1 OR code_barre ILIKE $1
        ORDER BY nom ASC
        LIMIT 20
      `, [`%${q}%`]);
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Search medicaments error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la recherche' });
    }
  }
};

module.exports = medicamentController;