const { query } = require('../config/database');
const { cloudinary } = require('../config/cloudinary');

const categorieController = {
  async getAll(req, res) {
    try {
      const result = await query('SELECT * FROM categories ORDER BY nom ASC');
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get categories error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des catégories' });
    }
  },

  async getById(req, res) {
    try {
      const { id } = req.params;
      const result = await query('SELECT * FROM categories WHERE id = $1', [id]);
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Catégorie non trouvée' });
      }
      res.json({ success: true, data: result.rows[0] });
    } catch (error) {
      console.error('Get category error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération de la catégorie' });
    }
  },

  async create(req, res) {
    try {
      const nom = req.body.nom;
      const description = req.body.description;
      const couleur = req.body.couleur;
      let image_url = null;
      
      if (req.file) {
        image_url = req.file.path;
      }
      
      if (!nom) {
        return res.status(400).json({ success: false, message: 'Le nom de la catégorie est requis' });
      }
      
      const result = await query(
        'INSERT INTO categories (nom, description, image_url, couleur) VALUES ($1, $2, $3, $4) RETURNING *',
        [nom, description, image_url, couleur || null]
      );
      
      res.status(201).json({ success: true, message: 'Catégorie créée avec succès', data: result.rows[0] });
    } catch (error) {
      if (error.code === '23505') {
        return res.status(409).json({ success: false, message: 'Une catégorie avec ce nom existe déjà' });
      }
      console.error('Create category error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la création de la catégorie' });
    }
  },

  async update(req, res) {
    try {
      const { id } = req.params;
      const nom = req.body.nom;
      const description = req.body.description;
      const couleur = req.body.couleur;
      let image_url = null;
      
      if (req.file) {
        image_url = req.file.path;
      }
      
      const result = await query(
        'UPDATE categories SET nom = COALESCE($1, nom), description = COALESCE($2, description), image_url = COALESCE($3, image_url), couleur = COALESCE($4, couleur), updated_at = NOW() WHERE id = $5 RETURNING *',
        [nom, description, image_url, couleur, id]
      );
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Catégorie non trouvée' });
      }
      
      res.json({ success: true, message: 'Catégorie mise à jour avec succès', data: result.rows[0] });
    } catch (error) {
      console.error('Update category error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la mise à jour de la catégorie' });
    }
  },

  async delete(req, res) {
    try {
      const { id } = req.params;
      const category = await query('SELECT image_url FROM categories WHERE id = $1', [id]);
      
      if (category.rows.length > 0 && category.rows[0].image_url) {
        const publicId = category.rows[0].image_url.split('/').slice(-2).join('/').split('.')[0];
        await cloudinary.uploader.destroy(publicId);
      }
      
      const result = await query('DELETE FROM categories WHERE id = $1 RETURNING id', [id]);
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Catégorie non trouvée' });
      }
      
      res.json({ success: true, message: 'Catégorie supprimée avec succès' });
    } catch (error) {
      if (error.code === '23503') {
        return res.status(409).json({ success: false, message: 'Cette catégorie est utilisée par des médicaments et ne peut pas être supprimée' });
      }
      console.error('Delete category error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la suppression de la catégorie' });
    }
  }
};

module.exports = categorieController;