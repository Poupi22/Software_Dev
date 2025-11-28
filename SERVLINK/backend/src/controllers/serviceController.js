const pool = require('../config/database');

const createService = async (req, res) => {
  try {
    const { name, description, price, duration_min } = req.body;
    
    const provider = await pool.query(
      'SELECT id FROM providers WHERE user_id = $1',
      [req.userId]
    );
    
    if (provider.rows.length === 0) {
      return res.status(403).json({ error: 'Vous devez être prestataire' });
    }
    
    const result = await pool.query(
      `INSERT INTO services (provider_id, name, description, price, duration_min)
       VALUES ($1, $2, $3, $4, $5)
       RETURNING *`,
      [provider.rows[0].id, name, description, price, duration_min]
    );
    
    res.status(201).json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyServices = async (req, res) => {
  try {
    const provider = await pool.query(
      'SELECT id FROM providers WHERE user_id = $1',
      [req.userId]
    );
    
    if (provider.rows.length === 0) {
      return res.status(403).json({ error: 'Vous n\'êtes pas prestataire' });
    }
    
    const result = await pool.query(
      'SELECT * FROM services WHERE provider_id = $1 ORDER BY created_at DESC',
      [provider.rows[0].id]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const updateService = async (req, res) => {
  try {
    const { id } = req.params;
    const { name, description, price, duration_min, is_active } = req.body;
    
    const provider = await pool.query(
      'SELECT id FROM providers WHERE user_id = $1',
      [req.userId]
    );
    
    const result = await pool.query(
      `UPDATE services 
       SET name = COALESCE($1, name),
           description = COALESCE($2, description),
           price = COALESCE($3, price),
           duration_min = COALESCE($4, duration_min),
           is_active = COALESCE($5, is_active),
           updated_at = NOW()
       WHERE id = $6 AND provider_id = $7
       RETURNING *`,
      [name, description, price, duration_min, is_active, id, provider.rows[0].id]
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
    const { id } = req.params;
    
    const provider = await pool.query(
      'SELECT id FROM providers WHERE user_id = $1',
      [req.userId]
    );
    
    const result = await pool.query(
      'DELETE FROM services WHERE id = $1 AND provider_id = $2 RETURNING id',
      [id, provider.rows[0].id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Service non trouvé' });
    }
    
    res.json({ message: 'Service supprimé', id: result.rows[0].id });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getProviderServices = async (req, res) => {
  try {
    const { providerId } = req.params;
    
    const result = await pool.query(
      'SELECT * FROM services WHERE provider_id = $1 AND is_active = true ORDER BY price ASC',
      [providerId]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  createService,
  getMyServices,
  updateService,
  deleteService,
  getProviderServices
};