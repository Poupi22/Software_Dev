const pool = require('../config/database');

// CREATE - Créer une catégorie (Admin seulement)
const createCategory = async (req, res) => {
  try {
    const { name, slug, icon, position } = req.body;
    
    if (!name || !slug) {
      return res.status(400).json({ error: 'Nom et slug sont requis' });
    }
    
    const result = await pool.query(
      `INSERT INTO categories (name, slug, icon, position) 
       VALUES ($1, $2, $3, $4) 
       RETURNING id, name, slug, icon, position`,
      [name, slug, icon, position || 0]
    );
    
    res.status(201).json(result.rows[0]);
  } catch (error) {
    if (error.code === '23505') {
      res.status(400).json({ error: 'Ce slug existe déjà' });
    } else {
      res.status(500).json({ error: error.message });
    }
  }
};

// READ ALL - Toutes les catégories
const getAllCategories = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT id, name, slug, icon, position, image_url
       FROM categories
       ORDER BY position ASC`
    );
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

// READ ONE - Une catégorie par ID
const getCategoryById = async (req, res) => {
  try {
    const { id } = req.params;
    const result = await pool.query(
      `SELECT id, name, slug, icon, position 
       FROM categories WHERE id = $1`,
      [id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Catégorie non trouvée' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

// UPDATE - Modifier une catégorie (Admin seulement)
const updateCategory = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, slug, icon, position } = req.body;
    
    const result = await pool.query(
      `UPDATE categories 
       SET name = COALESCE($1, name),
           slug = COALESCE($2, slug),
           icon = COALESCE($3, icon),
           position = COALESCE($4, position)
       WHERE id = $5
       RETURNING id, name, slug, icon, position`,
      [name, slug, icon, position, id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Catégorie non trouvée' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

// DELETE - Supprimer une catégorie (Admin seulement)
const deleteCategory = async (req, res) => {
  try {
    const { id } = req.params;
    const result = await pool.query(
      `DELETE FROM categories WHERE id = $1 RETURNING id`,
      [id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Catégorie non trouvée' });
    }
    
    res.json({ message: 'Catégorie supprimée avec succès', id: result.rows[0].id });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  createCategory,
  getAllCategories,
  getCategoryById,
  updateCategory,
  deleteCategory
};