const pool = require('../config/database');

const createReview = async (req, res) => {
  try {
    const { booking_id, rating, comment } = req.body;
    const client_id = req.userId;
    
    const booking = await pool.query(
      'SELECT id, provider_id, status FROM bookings WHERE id = $1 AND client_id = $2',
      [booking_id, client_id]
    );
    
    if (booking.rows.length === 0) {
      return res.status(404).json({ error: 'Réservation non trouvée' });
    }
    
    if (booking.rows[0].status !== 'completed') {
      return res.status(400).json({ error: 'Vous ne pouvez évaluer qu\'une réservation terminée' });
    }
    
    const existing = await pool.query(
      'SELECT id FROM reviews WHERE booking_id = $1',
      [booking_id]
    );
    
    if (existing.rows.length > 0) {
      return res.status(400).json({ error: 'Vous avez déjà évalué cette réservation' });
    }
    
    const provider_id = booking.rows[0].provider_id;
    
    const result = await pool.query(
      `INSERT INTO reviews (booking_id, client_id, provider_id, rating, comment)
       VALUES ($1, $2, $3, $4, $5)
       RETURNING *`,
      [booking_id, client_id, provider_id, rating, comment]
    );
    
    await pool.query(
      `UPDATE providers 
       SET rating = (
         SELECT AVG(rating)::NUMERIC(2,1) FROM reviews WHERE provider_id = $1
       ),
       reviews_count = reviews_count + 1
       WHERE id = $1`,
      [provider_id]
    );
    
    res.status(201).json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getProviderReviews = async (req, res) => {
  try {
    const { providerId } = req.params;
    
    const result = await pool.query(
      `SELECT r.*, u.full_name as client_name, u.avatar_url
       FROM reviews r
       JOIN profiles u ON r.client_id = u.id
       WHERE r.provider_id = $1
       ORDER BY r.created_at DESC`,
      [providerId]
    );
    
    const stats = await pool.query(
      `SELECT 
        COUNT(*) as total,
        AVG(rating)::NUMERIC(2,1) as average,
        COUNT(CASE WHEN rating = 5 THEN 1 END) as five_star,
        COUNT(CASE WHEN rating = 4 THEN 1 END) as four_star,
        COUNT(CASE WHEN rating = 3 THEN 1 END) as three_star,
        COUNT(CASE WHEN rating = 2 THEN 1 END) as two_star,
        COUNT(CASE WHEN rating = 1 THEN 1 END) as one_star
       FROM reviews WHERE provider_id = $1`,
      [providerId]
    );
    
    res.json({
      stats: stats.rows[0],
      reviews: result.rows
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const updateReview = async (req, res) => {
  try {
    const { id } = req.params;
    const { rating, comment } = req.body;
    
    const result = await pool.query(
      `UPDATE reviews 
       SET rating = COALESCE($1, rating),
           comment = COALESCE($2, comment)
       WHERE id = $3 AND client_id = $4
       RETURNING *`,
      [rating, comment, id, req.userId]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Avis non trouvé' });
    }
    
    await pool.query(
      `UPDATE providers 
       SET rating = (SELECT AVG(rating)::NUMERIC(2,1) FROM reviews WHERE provider_id = $1)
       WHERE id = $1`,
      [result.rows[0].provider_id]
    );
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const deleteReview = async (req, res) => {
  try {
    const { id } = req.params;
    
    const review = await pool.query(
      'SELECT provider_id FROM reviews WHERE id = $1 AND client_id = $2',
      [id, req.userId]
    );
    
    if (review.rows.length === 0) {
      return res.status(404).json({ error: 'Avis non trouvé' });
    }
    
    await pool.query('DELETE FROM reviews WHERE id = $1', [id]);
    
    await pool.query(
      `UPDATE providers 
       SET rating = COALESCE((SELECT AVG(rating)::NUMERIC(2,1) FROM reviews WHERE provider_id = $1), 0),
           reviews_count = reviews_count - 1
       WHERE id = $1`,
      [review.rows[0].provider_id]
    );
    
    res.json({ message: 'Avis supprimé' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const flagReview = async (req, res) => {
  try {
    const { id } = req.params;
    const { reason } = req.body;
    
    const result = await pool.query(
      `UPDATE reviews 
       SET is_flagged = TRUE, flag_reason = $1
       WHERE id = $2
       RETURNING *`,
      [reason, id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Avis non trouvé' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  createReview,
  getProviderReviews,
  updateReview,
  deleteReview,
  flagReview
};