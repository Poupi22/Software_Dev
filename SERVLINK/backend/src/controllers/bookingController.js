const pool = require('../config/database');

const generateReference = () => {
  return 'BK-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
};

const createBooking = async (req, res) => {
  try {
    const { provider_id, service_id, scheduled_at, address, notes, amount } = req.body;
    const client_id = req.userId;
    
    const reference = generateReference();
    
    const result = await pool.query(
      `INSERT INTO bookings (reference, client_id, provider_id, service_id, scheduled_at, address, notes, amount)
       VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
       RETURNING *`,
      [reference, client_id, provider_id, service_id, scheduled_at, address, notes, amount]
    );
    
    res.status(201).json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyBookings = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT b.*, p.full_name as provider_name, p.phone as provider_phone, 
              pr.tagline, pr.city
       FROM bookings b
       JOIN providers pr ON b.provider_id = pr.id
       JOIN profiles p ON pr.user_id = p.id
       WHERE b.client_id = $1
       ORDER BY b.created_at DESC`,
      [req.userId]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyJobs = async (req, res) => {
  try {
    const provider = await pool.query(
      'SELECT id FROM providers WHERE user_id = $1',
      [req.userId]
    );
    
    if (provider.rows.length === 0) {
      return res.status(403).json({ error: 'Vous n\'êtes pas prestataire' });
    }
    
    const result = await pool.query(
      `SELECT b.*, u.full_name as client_name, u.email as client_email, u.phone as client_phone,
              s.name as service_name
       FROM bookings b
       JOIN profiles u ON b.client_id = u.id
       LEFT JOIN services s ON b.service_id = s.id
       WHERE b.provider_id = $1
       ORDER BY b.scheduled_at ASC`,
      [provider.rows[0].id]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getBookingById = async (req, res) => {
  try {
    const { id } = req.params;
    
    const result = await pool.query(
      `SELECT b.*, 
              u.full_name as client_name, u.email as client_email, u.phone as client_phone,
              p.full_name as provider_name, p.email as provider_email,
              pr.tagline, pr.city
       FROM bookings b
       JOIN profiles u ON b.client_id = u.id
       JOIN providers pr ON b.provider_id = pr.id
       JOIN profiles p ON pr.user_id = p.id
       WHERE b.id = $1`,
      [id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Réservation non trouvée' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const updateStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const { status } = req.body;
    
    const validStatuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'refunded'];
    if (!validStatuses.includes(status)) {
      return res.status(400).json({ error: 'Statut invalide' });
    }
    
    const result = await pool.query(
      `UPDATE bookings 
       SET status = $1, updated_at = NOW()
       WHERE id = $2
       RETURNING *`,
      [status, id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Réservation non trouvée' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const cancelBooking = async (req, res) => {
  try {
    const { id } = req.params;
    
    const result = await pool.query(
      `UPDATE bookings 
       SET status = 'cancelled', updated_at = NOW()
       WHERE id = $1 AND client_id = $2
       RETURNING *`,
      [id, req.userId]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Réservation non trouvée' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  createBooking,
  getMyBookings,
  getMyJobs,
  getBookingById,
  updateStatus,
  cancelBooking
};