const pool = require('../config/database');

const globalSearch = async (req, res) => {
  try {
    const { q, city } = req.query;

    if ((!q || q.trim().length < 2) && !city) {
      return res.json({ providers: [], categories: [], services: [] });
    }

    const query = q ? `%${q.trim()}%` : '%';
    const cityFilter = city ? `%${city.trim()}%` : null;

    let providerQuery;
    let providerParams;

    if (cityFilter) {
      providerQuery = `SELECT p.id, p.image_url, p.cover_url, p.city, p.price_from, p.rating, p.reviews_count, p.is_verified, p.is_featured, p.description,
              c.name as category_name, c.slug as category_slug, u.full_name, u.avatar_url
       FROM providers p
       JOIN categories c ON p.category_id = c.id
       JOIN profiles u ON p.user_id = u.id
       WHERE (u.full_name ILIKE $1 OR p.description ILIKE $1 OR p.city ILIKE $1 OR c.name ILIKE $1 OR p.tagline ILIKE $1)
         AND p.city ILIKE $2
       ORDER BY p.rating DESC
       LIMIT 10`;
      providerParams = [query, cityFilter];
    } else {
      providerQuery = `SELECT p.id, p.image_url, p.cover_url, p.city, p.price_from, p.rating, p.reviews_count, p.is_verified, p.is_featured, p.description,
              c.name as category_name, c.slug as category_slug, u.full_name, u.avatar_url
       FROM providers p
       JOIN categories c ON p.category_id = c.id
       JOIN profiles u ON p.user_id = u.id
       WHERE u.full_name ILIKE $1 OR p.description ILIKE $1 OR p.city ILIKE $1 OR c.name ILIKE $1 OR p.tagline ILIKE $1
       ORDER BY p.rating DESC
       LIMIT 10`;
      providerParams = [query];
    }

    const [providersResult, categoriesResult, servicesResult] = await Promise.all([
      pool.query(providerQuery, providerParams),
      pool.query(
        `SELECT id, name, slug, icon, image_url
         FROM categories
         WHERE name ILIKE $1 OR slug ILIKE $1
         LIMIT 6`,
        [query]
      ),
      pool.query(
        `SELECT s.id, s.name, s.description, s.price, s.provider_id
         FROM services s
         WHERE s.name ILIKE $1 OR s.description ILIKE $1
         LIMIT 10`,
        [query]
      ),
    ]);

    res.json({
      providers: providersResult.rows,
      categories: categoriesResult.rows,
      services: servicesResult.rows,
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = { globalSearch };
