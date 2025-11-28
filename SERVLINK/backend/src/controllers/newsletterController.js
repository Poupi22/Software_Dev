const pool = require('../config/database');

const subscribe = async (req, res) => {
  try {
    const { email, locale } = req.body;

    if (!email || !email.includes('@')) {
      return res.status(400).json({ error: 'Email invalide' });
    }

    const existing = await pool.query(
      'SELECT id, is_active FROM newsletter_subscribers WHERE email = $1',
      [email.toLowerCase().trim()]
    );

    if (existing.rows.length > 0) {
      if (existing.rows[0].is_active) {
        return res.status(409).json({ error: 'already_subscribed' });
      }
      await pool.query(
        'UPDATE newsletter_subscribers SET is_active = true, unsubscribed_at = NULL, locale = $2 WHERE email = $1',
        [email.toLowerCase().trim(), locale || 'fr']
      );
      return res.json({ success: true, resubscribed: true });
    }

    await pool.query(
      'INSERT INTO newsletter_subscribers (email, locale) VALUES ($1, $2)',
      [email.toLowerCase().trim(), locale || 'fr']
    );

    res.status(201).json({ success: true });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const unsubscribe = async (req, res) => {
  try {
    const { email } = req.body;

    if (!email) {
      return res.status(400).json({ error: 'Email requis' });
    }

    await pool.query(
      'UPDATE newsletter_subscribers SET is_active = false, unsubscribed_at = NOW() WHERE email = $1',
      [email.toLowerCase().trim()]
    );

    res.json({ success: true });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = { subscribe, unsubscribe };
