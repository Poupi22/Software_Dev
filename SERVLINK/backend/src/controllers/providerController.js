const pool = require('../config/database');

const registerProvider = async (req, res) => {
  try {
    const { category_id, city, address, tagline, description, price_from } = req.body;
    const user_id = req.userId;

    if (!category_id || !city) {
      return res.status(400).json({ error: 'Catégorie et ville sont requises' });
    }

    const existing = await pool.query('SELECT id FROM providers WHERE user_id = $1', [user_id]);

    if (existing.rows.length > 0) {
      return res.status(400).json({ error: 'Vous êtes déjà prestataire' });
    }

    let image_url = null;
    let cover_url = null;
    if (req.files) {
      if (req.files.image && req.files.image[0]) {
        image_url = `/uploads/${req.files.image[0].filename}`;
      }
      if (req.files.cover && req.files.cover[0]) {
        cover_url = `/uploads/${req.files.cover[0].filename}`;
      }
    }

    const result = await pool.query(
      `INSERT INTO providers (user_id, category_id, city, address, tagline, description, price_from, image_url, cover_url)
       VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)
       RETURNING *`,
      [user_id, category_id, city, address || null, tagline || null, description || null, price_from || 0, image_url, cover_url]
    );

    await pool.query(`INSERT INTO user_roles (user_id, role) VALUES ($1, 'provider') ON CONFLICT DO NOTHING`, [user_id]);

    res.status(201).json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyProvider = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT p.*, c.name as category_name, c.slug as category_slug
       FROM providers p 
       JOIN categories c ON p.category_id = c.id 
       WHERE p.user_id = $1`,
      [req.userId]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Fiche prestataire non trouvée' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const updateMyProvider = async (req, res) => {
  try {
    const { category_id, city, address, tagline, description, price_from, response_time, tags } = req.body;

    let image_url = undefined;
    let cover_url = undefined;
    if (req.files) {
      if (req.files.image && req.files.image[0]) {
        image_url = `/uploads/${req.files.image[0].filename}`;
      }
      if (req.files.cover && req.files.cover[0]) {
        cover_url = `/uploads/${req.files.cover[0].filename}`;
      }
    }

    const result = await pool.query(
      `UPDATE providers
       SET category_id = COALESCE($1, category_id),
           city = COALESCE($2, city),
           address = COALESCE($3, address),
           tagline = COALESCE($4, tagline),
           description = COALESCE($5, description),
           price_from = COALESCE($6, price_from),
           image_url = COALESCE($7, image_url),
           cover_url = COALESCE($8, cover_url),
           response_time = COALESCE($9, response_time),
           tags = COALESCE($10, tags),
           updated_at = NOW()
       WHERE user_id = $11
       RETURNING *`,
      [category_id, city, address, tagline, description, price_from, image_url || null, cover_url || null, response_time, tags, req.userId]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Fiche prestataire non trouvée' });
    }

    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getAllProviders = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT p.id, p.user_id, p.category_id, p.image_url, p.cover_url, p.tagline, p.description, p.city, p.price_from, p.rating, p.reviews_count, p.is_verified, p.is_featured,
              c.name as category_name, c.slug as category_slug, u.full_name, u.avatar_url
       FROM providers p
       JOIN categories c ON p.category_id = c.id
       JOIN profiles u ON p.user_id = u.id
       ORDER BY p.is_featured DESC, p.rating DESC`
    );
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getProviderById = async (req, res) => {
  try {
    const { id } = req.params;
    const result = await pool.query(
      `SELECT p.*, c.name as category_name, c.slug as category_slug, u.full_name, u.email, u.phone, u.avatar_url, u.city as user_city
       FROM providers p 
       JOIN categories c ON p.category_id = c.id
       JOIN profiles u ON p.user_id = u.id
       WHERE p.id = $1`,
      [id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Prestataire non trouvé' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getByCategory = async (req, res) => {
  try {
    const { categoryId } = req.params;
    const result = await pool.query(
      `SELECT p.id, p.user_id, p.image_url, p.tagline, p.city, p.price_from, p.rating, p.reviews_count,
              c.name as category_name, u.full_name, u.avatar_url
       FROM providers p 
       JOIN categories c ON p.category_id = c.id
       JOIN profiles u ON p.user_id = u.id
       WHERE p.category_id = $1 AND p.is_verified = true`,
      [categoryId]
    );
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getByCity = async (req, res) => {
  try {
    const { city } = req.params;
    const result = await pool.query(
      `SELECT p.id, p.user_id, p.image_url, p.tagline, p.city, p.price_from, p.rating,
              c.name as category_name, u.full_name, u.avatar_url
       FROM providers p 
       JOIN categories c ON p.category_id = c.id
       JOIN profiles u ON p.user_id = u.id
       WHERE p.city ILIKE $1 AND p.is_verified = true`,
      [`%${city}%`]
    );
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  registerProvider,
  getMyProvider,
  updateMyProvider,
  getAllProviders,
  getProviderById,
  getByCategory,
  getByCity
};