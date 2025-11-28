const pool = require('../config/database');

const generateReference = () => {
  return 'DSP-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
};

const createDispute = async (req, res) => {
  try {
    const { booking_id, reason, priority } = req.body;
    const client_id = req.userId;
    
    const booking = await pool.query(
      'SELECT provider_id, client_id FROM bookings WHERE id = $1',
      [booking_id]
    );
    
    if (booking.rows.length === 0) {
      return res.status(404).json({ error: 'Réservation non trouvée' });
    }
    
    if (booking.rows[0].client_id !== client_id) {
      return res.status(403).json({ error: 'Vous n\'êtes pas le client de cette réservation' });
    }
    
    const existing = await pool.query(
      'SELECT id FROM disputes WHERE booking_id = $1 AND status != $2',
      [booking_id, 'closed']
    );
    
    if (existing.rows.length > 0) {
      return res.status(400).json({ error: 'Un litige existe déjà pour cette réservation' });
    }
    
    const reference = generateReference();
    
    const result = await pool.query(
      `INSERT INTO disputes (reference, booking_id, client_id, provider_id, reason, priority)
       VALUES ($1, $2, $3, $4, $5, $6)
       RETURNING *`,
      [reference, booking_id, client_id, booking.rows[0].provider_id, reason, priority || 'medium']
    );
    
    await pool.query(
      'UPDATE bookings SET status = \'cancelled\' WHERE id = $1',
      [booking_id]
    );
    
    res.status(201).json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyDisputes = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT d.*, b.reference as booking_reference
       FROM disputes d
       JOIN bookings b ON d.booking_id = b.id
       WHERE d.client_id = $1
       ORDER BY d.opened_at DESC`,
      [req.userId]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getProviderDisputes = async (req, res) => {
  try {
    const provider = await pool.query(
      'SELECT id FROM providers WHERE user_id = $1',
      [req.userId]
    );
    
    if (provider.rows.length === 0) {
      return res.status(403).json({ error: 'Vous n\'êtes pas prestataire' });
    }
    
    const result = await pool.query(
      `SELECT d.*, b.reference as booking_reference, u.full_name as client_name
       FROM disputes d
       JOIN bookings b ON d.booking_id = b.id
       JOIN profiles u ON d.client_id = u.id
       WHERE d.provider_id = $1
       ORDER BY d.opened_at DESC`,
      [provider.rows[0].id]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const updateDisputeStatus = async (req, res) => {
  try {
    const { id } = req.params;
    const { status, resolution } = req.body;
    
    const validStatuses = ['open', 'arbitration', 'resolved', 'closed'];
    if (!validStatuses.includes(status)) {
      return res.status(400).json({ error: 'Statut invalide' });
    }
    
    const updateQuery = {
      status,
      resolved_at: status === 'resolved' || status === 'closed' ? new Date() : null
    };
    
    if (resolution) updateQuery.resolution = resolution;
    
    const result = await pool.query(
      `UPDATE disputes 
       SET status = $1, resolution = COALESCE($2, resolution), resolved_at = $3
       WHERE id = $4
       RETURNING *`,
      [status, resolution, updateQuery.resolved_at, id]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Litige non trouvé' });
    }
    
    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getAllDisputes = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT d.*, b.reference as booking_reference, u.full_name as client_name, p.full_name as provider_name
       FROM disputes d
       JOIN bookings b ON d.booking_id = b.id
       JOIN profiles u ON d.client_id = u.id
       JOIN providers pr ON d.provider_id = pr.id
       JOIN profiles p ON pr.user_id = p.id
       ORDER BY 
         CASE d.status 
           WHEN 'open' THEN 1 
           WHEN 'arbitration' THEN 2 
           ELSE 3 
         END,
         d.opened_at ASC`
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  createDispute,
  getMyDisputes,
  getProviderDisputes,
  updateDisputeStatus,
  getAllDisputes
};