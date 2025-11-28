const pool = require('../config/database');
const campay = require('../services/campayService');

const generateReference = () => {
  return 'TX-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
};

const createTransaction = async (req, res) => {
  try {
    const { booking_id, amount, phone } = req.body;
    const client_id = req.userId;

    if (!booking_id || !amount || !phone) {
      return res.status(400).json({ error: 'booking_id, amount et phone sont requis' });
    }

    const booking = await pool.query(
      'SELECT provider_id, reference as booking_reference FROM bookings WHERE id = $1 AND client_id = $2',
      [booking_id, client_id]
    );

    if (booking.rows.length === 0) {
      return res.status(404).json({ error: 'Réservation non trouvée' });
    }

    const reference = generateReference();
    const method = phone.startsWith('23767') || phone.startsWith('2376') ? 'mtn_momo' : 'orange_money';

    let campayResponse = null;
    try {
      campayResponse = await campay.initiatePayment({
        amount,
        phone,
        description: `Paiement ServLink - ${booking.rows[0].booking_reference || booking_id}`,
        external_reference: reference
      });
    } catch (campayError) {
      console.log('[CamPay] Error:', campayError.response?.status, JSON.stringify(campayError.response?.data || campayError.message));
    }

    const result = await pool.query(
      `INSERT INTO transactions (reference, booking_id, client_id, provider_id, amount, method, phone, campay_reference, campay_status, status)
       VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, 'pending')
       RETURNING *`,
      [
        reference,
        booking_id,
        client_id,
        booking.rows[0].provider_id,
        amount,
        method,
        phone,
        campayResponse?.reference || null,
        campayResponse?.status || 'PENDING'
      ]
    );

    res.status(201).json({
      transaction: result.rows[0],
      campay: {
        reference: campayResponse?.reference || reference,
        ussd_code: campayResponse?.ussd_code || null,
        operator: campayResponse?.operator || method
      }
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const checkTransactionStatus = async (req, res) => {
  try {
    const { id } = req.params;

    const tx = await pool.query(
      'SELECT * FROM transactions WHERE id = $1 AND client_id = $2',
      [id, req.userId]
    );

    if (tx.rows.length === 0) {
      return res.status(404).json({ error: 'Transaction non trouvée' });
    }

    const transaction = tx.rows[0];

    if (!transaction.campay_reference) {
      return res.json(transaction);
    }

    const campayStatus = await campay.checkPaymentStatus(transaction.campay_reference);

    let newStatus = transaction.status;
    let paidAt = transaction.paid_at;

    if (campayStatus.status === 'SUCCESSFUL') {
      newStatus = 'succeeded';
      paidAt = new Date();
    } else if (campayStatus.status === 'FAILED') {
      newStatus = 'failed';
    }

    if (newStatus !== transaction.status) {
      await pool.query(
        `UPDATE transactions SET status = $1, campay_status = $2, paid_at = $3 WHERE id = $4`,
        [newStatus, campayStatus.status, paidAt, id]
      );

      if (newStatus === 'succeeded') {
        await pool.query(
          'UPDATE bookings SET status = \'confirmed\' WHERE id = $1',
          [transaction.booking_id]
        );
      }
    }

    res.json({
      ...transaction,
      status: newStatus,
      campay_status: campayStatus.status,
      paid_at: paidAt
    });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const campayWebhook = async (req, res) => {
  try {
    const { status, reference, external_reference, amount } = req.body;

    if (!reference && !external_reference) {
      return res.status(400).json({ error: 'Référence manquante' });
    }

    const query = external_reference
      ? 'SELECT * FROM transactions WHERE reference = $1'
      : 'SELECT * FROM transactions WHERE campay_reference = $1';
    const param = external_reference || reference;

    const tx = await pool.query(query, [param]);

    if (tx.rows.length === 0) {
      return res.status(404).json({ error: 'Transaction non trouvée' });
    }

    const transaction = tx.rows[0];
    let newStatus = transaction.status;
    let paidAt = transaction.paid_at;

    if (status === 'SUCCESSFUL') {
      newStatus = 'succeeded';
      paidAt = new Date();
    } else if (status === 'FAILED') {
      newStatus = 'failed';
    }

    await pool.query(
      `UPDATE transactions SET status = $1, campay_status = $2, paid_at = $3 WHERE id = $4`,
      [newStatus, status, paidAt, transaction.id]
    );

    if (newStatus === 'succeeded') {
      await pool.query(
        'UPDATE bookings SET status = \'confirmed\' WHERE id = $1',
        [transaction.booking_id]
      );
    }

    res.json({ message: 'Webhook traité', status: newStatus });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyTransactions = async (req, res) => {
  try {
    const result = await pool.query(
      `SELECT t.*, b.reference as booking_reference
       FROM transactions t
       LEFT JOIN bookings b ON t.booking_id = b.id
       WHERE t.client_id = $1
       ORDER BY t.created_at DESC`,
      [req.userId]
    );

    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getProviderTransactions = async (req, res) => {
  try {
    const provider = await pool.query(
      'SELECT id FROM providers WHERE user_id = $1',
      [req.userId]
    );

    if (provider.rows.length === 0) {
      return res.status(403).json({ error: 'Vous n\'êtes pas prestataire' });
    }

    const result = await pool.query(
      `SELECT t.*, b.reference as booking_reference, u.full_name as client_name
       FROM transactions t
       LEFT JOIN bookings b ON t.booking_id = b.id
       JOIN profiles u ON t.client_id = u.id
       WHERE t.provider_id = $1
       ORDER BY t.created_at DESC`,
      [provider.rows[0].id]
    );

    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  createTransaction,
  checkTransactionStatus,
  campayWebhook,
  getMyTransactions,
  getProviderTransactions
};
