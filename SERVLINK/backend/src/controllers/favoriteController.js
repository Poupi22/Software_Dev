const pool = require('../config/database');

const addFavorite = async (req, res) => {
  try {
    const { providerId } = req.params;
    const client_id = req.userId;
    
    const provider = await pool.query(
      'SELECT id FROM providers WHERE id = $1',
      [providerId]
    );
    
    if (provider.rows.length === 0) {
      return res.status(404).json({ error: 'Prestataire non trouvé' });
    }
    
    await pool.query(
      `INSERT INTO favorites (client_id, provider_id) 
       VALUES ($1, $2)
       ON CONFLICT DO NOTHING`,
      [client_id, providerId]
    );
    
    res.status(201).json({ message: 'Ajouté aux favoris' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const removeFavorite = async (req, res) => {
  try {
    const { providerId } = req.params;
    const client_id = req.userId;
    
    await pool.query(
      'DELETE FROM favorites WHERE client_id = $1 AND provider_id = $2',
      [client_id, providerId]
    );
    
    res.json({ message: 'Retiré des favoris' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyFavorites = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT p.id, p.user_id, p.image_url, p.tagline, p.city, p.price_from, p.rating, p.reviews_count,
              c.name as category_name, u.full_name, u.avatar_url,
              f.created_at as favorited_at
       FROM favorites f
       JOIN providers p ON f.provider_id = p.id
       JOIN categories c ON p.category_id = c.id
       JOIN profiles u ON p.user_id = u.id
       WHERE f.client_id = $1
       ORDER BY f.created_at DESC`,
      [req.userId]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const checkFavorite = async (req, res) => {
  try {
    const { providerId } = req.params;
    const client_id = req.userId;
    
    const result = await pool.query(
      'SELECT 1 FROM favorites WHERE client_id = $1 AND provider_id = $2',
      [client_id, providerId]
    );
    
    res.json({ isFavorite: result.rows.length > 0 });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  addFavorite,
  removeFavorite,
  getMyFavorites,
  checkFavorite
};