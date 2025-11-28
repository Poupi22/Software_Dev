const pool = require('../config/database');

const getAllCategories = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const result = await pool.query(
      `SELECT c.*, COUNT(p.id) as providers_count
       FROM categories c
       LEFT JOIN providers p ON p.category_id = c.id
       GROUP BY c.id
       ORDER BY c.position ASC`
    );

    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const createCategory = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { name, slug, icon, position } = req.body;

    if (!name || !slug) {
      return res.status(400).json({ error: 'Nom et slug sont requis' });
    }

    let image_url = null;
    if (req.file) {
      image_url = `/uploads/${req.file.filename}`;
    }

    const result = await pool.query(
      `INSERT INTO categories (name, slug, icon, position, image_url)
       VALUES ($1, $2, $3, $4, $5)
       RETURNING *`,
      [name, slug, icon || null, position || 0, image_url]
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

const updateCategory = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { id } = req.params;
    const { name, slug, icon, position } = req.body;

    let image_url = undefined;
    if (req.file) {
      image_url = `/uploads/${req.file.filename}`;
    }

    const fields = [];
    const values = [];
    let idx = 1;

    if (name) { fields.push(`name = $${idx}`); values.push(name); idx++; }
    if (slug) { fields.push(`slug = $${idx}`); values.push(slug); idx++; }
    if (icon !== undefined) { fields.push(`icon = $${idx}`); values.push(icon || null); idx++; }
    if (position !== undefined) { fields.push(`position = $${idx}`); values.push(position); idx++; }
    if (image_url) { fields.push(`image_url = $${idx}`); values.push(image_url); idx++; }

    if (fields.length === 0) {
      return res.status(400).json({ error: 'Aucun champ à mettre à jour' });
    }

    values.push(id);
    const result = await pool.query(
      `UPDATE categories SET ${fields.join(', ')} WHERE id = $${idx} RETURNING *`,
      values
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Catégorie non trouvée' });
    }

    res.json(result.rows[0]);
  } catch (error) {
    if (error.code === '23505') {
      res.status(400).json({ error: 'Ce slug existe déjà' });
    } else {
      res.status(500).json({ error: error.message });
    }
  }
};

const deleteCategory = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { id } = req.params;

    const result = await pool.query(
      'DELETE FROM categories WHERE id = $1 RETURNING id',
      [id]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Catégorie non trouvée' });
    }

    res.json({ message: 'Catégorie supprimée', id: result.rows[0].id });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  getAllCategories,
  createCategory,
  updateCategory,
  deleteCategory
};
