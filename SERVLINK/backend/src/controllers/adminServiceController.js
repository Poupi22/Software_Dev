const pool = require('../config/database');

const getAllServices = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const result = await pool.query(
      `SELECT s.*,
              p.full_name as provider_name,
              c.name as category_name
       FROM services s
       JOIN providers pr ON s.provider_id = pr.id
       JOIN profiles p ON pr.user_id = p.id
       LEFT JOIN categories c ON s.category_id = c.id
       ORDER BY s.created_at DESC`
    );

    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const createService = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { name, description, price, duration_min, provider_id, category_id, is_active } = req.body;

    if (!name || !price || !provider_id) {
      return res.status(400).json({ error: 'Nom, prix et prestataire sont requis' });
    }

    let image_url = null;
    if (req.file) {
      image_url = `/uploads/${req.file.filename}`;
    }

    const result = await pool.query(
      `INSERT INTO services (provider_id, category_id, name, description, price, duration_min, is_active, image_url)
       VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
       RETURNING *`,
      [provider_id, category_id || null, name, description || null, price, duration_min || null, is_active !== false, image_url]
    );

    res.status(201).json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const updateService = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { id } = req.params;
    const { name, description, price, duration_min, category_id, is_active } = req.body;

    let image_url = undefined;
    if (req.file) {
      image_url = `/uploads/${req.file.filename}`;
    }

    const result = await pool.query(
      `UPDATE services
       SET name = COALESCE($1, name),
           description = COALESCE($2, description),
           price = COALESCE($3, price),
           duration_min = COALESCE($4, duration_min),
           category_id = COALESCE($5, category_id),
           is_active = COALESCE($6, is_active),
           image_url = COALESCE($7, image_url),
           updated_at = NOW()
       WHERE id = $8
       RETURNING *`,
      [name, description, price, duration_min, category_id || null, is_active, image_url || null, id]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Service non trouvé' });
    }

    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const deleteService = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { id } = req.params;

    const result = await pool.query(
      'DELETE FROM services WHERE id = $1 RETURNING id',
      [id]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Service non trouvé' });
    }

    res.json({ message: 'Service supprimé', id: result.rows[0].id });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const toggleService = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { id } = req.params;

    const result = await pool.query(
      `UPDATE services SET is_active = NOT is_active, updated_at = NOW() WHERE id = $1 RETURNING *`,
      [id]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Service non trouvé' });
    }

    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  getAllServices,
  createService,
  updateService,
  deleteService,
  toggleService
};
