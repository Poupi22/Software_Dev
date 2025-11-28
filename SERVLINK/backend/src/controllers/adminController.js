const pool = require('../config/database');

const getAllUsers = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const result = await pool.query(
      `SELECT p.id, p.full_name, p.email, p.phone, p.avatar_url, p.is_blocked, p.created_at,
              ur.role
       FROM profiles p
       LEFT JOIN user_roles ur ON p.id = ur.user_id
       ORDER BY p.created_at DESC`
    );

    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getStats = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const usersCount = await pool.query('SELECT COUNT(*) FROM profiles');
    const providersCount = await pool.query('SELECT COUNT(*) FROM providers');
    const openDisputes = await pool.query(
      "SELECT COUNT(*) FROM disputes WHERE status = 'open'"
    );
    const revenue = await pool.query(
      "SELECT COALESCE(SUM(amount), 0) as total FROM transactions WHERE status = 'succeeded'"
    );

    res.json({
      users: parseInt(usersCount.rows[0].count),
      providers: parseInt(providersCount.rows[0].count),
      openDisputes: parseInt(openDisputes.rows[0].count),
      revenue: parseInt(revenue.rows[0].total)
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getAllTransactions = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const result = await pool.query(
      `SELECT t.*,
              c.full_name as client_name,
              p.full_name as provider_name
       FROM transactions t
       JOIN profiles c ON t.client_id = c.id
       JOIN providers pr ON t.provider_id = pr.id
       JOIN profiles p ON pr.user_id = p.id
       ORDER BY t.created_at DESC`
    );

    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getFlaggedReviews = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const result = await pool.query(
      `SELECT r.*,
              a.full_name as author_name,
              p.full_name as provider_name
       FROM reviews r
       JOIN profiles a ON r.client_id = a.id
       JOIN providers pr ON r.provider_id = pr.id
       JOIN profiles p ON pr.user_id = p.id
       WHERE r.is_flagged = true
       ORDER BY r.created_at DESC`
    );

    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const toggleBlockUser = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { id } = req.params;

    const result = await pool.query(
      `UPDATE profiles SET is_blocked = NOT is_blocked, updated_at = NOW() WHERE id = $1 RETURNING id, full_name, email, is_blocked`,
      [id]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Utilisateur non trouvé' });
    }

    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const deleteUser = async (req, res) => {
  try {
    if (req.userRole !== 'admin') {
      return res.status(403).json({ error: 'Accès réservé aux administrateurs' });
    }

    const { id } = req.params;

    const result = await pool.query(
      'DELETE FROM profiles WHERE id = $1 RETURNING id',
      [id]
    );

    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Utilisateur non trouvé' });
    }

    res.json({ message: 'Utilisateur supprimé', id: result.rows[0].id });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  getAllUsers,
  getStats,
  getAllTransactions,
  getFlaggedReviews,
  toggleBlockUser,
  deleteUser
};
