const pool = require('../config/database');

const getOrCreateThread = async (req, res) => {
  try {
    const { providerId } = req.params;
    const client_id = req.userId;
    
    let thread = await pool.query(
      'SELECT * FROM threads WHERE client_id = $1 AND provider_id = $2',
      [client_id, providerId]
    );
    
    if (thread.rows.length === 0) {
      const result = await pool.query(
        `INSERT INTO threads (client_id, provider_id) 
         VALUES ($1, $2) 
         RETURNING *`,
        [client_id, providerId]
      );
      thread = result;
    }
    
    res.json(thread.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMyThreads = async (req, res) => {
  try {
    const user_id = req.userId;
    
    const result = await pool.query(
      `SELECT t.*, 
              CASE 
                WHEN t.client_id = $1 THEN u.full_name 
                ELSE p.full_name 
              END as other_party_name,
              CASE 
                WHEN t.client_id = $1 THEN pr.image_url 
                ELSE u.avatar_url 
              END as other_party_avatar
       FROM threads t
       JOIN profiles u ON t.client_id = u.id
       JOIN providers pr ON t.provider_id = pr.id
       JOIN profiles p ON pr.user_id = p.id
       WHERE t.client_id = $1 OR t.provider_id IN (SELECT id FROM providers WHERE user_id = $1)
       ORDER BY t.last_message_at DESC NULLS LAST`,
      [user_id]
    );
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const getMessages = async (req, res) => {
  try {
    const { threadId } = req.params;
    const user_id = req.userId;
    
    const thread = await pool.query(
      `SELECT * FROM threads WHERE id = $1 AND (client_id = $2 OR provider_id IN (SELECT id FROM providers WHERE user_id = $2))`,
      [threadId, user_id]
    );
    
    if (thread.rows.length === 0) {
      return res.status(404).json({ error: 'Fil non trouvé' });
    }
    
    const result = await pool.query(
      `SELECT m.*, u.full_name as sender_name
       FROM messages m
       JOIN profiles u ON m.sender_id = u.id
       WHERE m.thread_id = $1
       ORDER BY m.created_at ASC`,
      [threadId]
    );
    
    if (thread.rows[0].client_id === user_id) {
      await pool.query(
        'UPDATE threads SET unread_count_client = 0 WHERE id = $1',
        [threadId]
      );
    } else {
      await pool.query(
        'UPDATE threads SET unread_count_provider = 0 WHERE id = $1',
        [threadId]
      );
    }
    
    res.json(result.rows);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const sendMessage = async (req, res) => {
  try {
    const { threadId } = req.params;
    const { body } = req.body;
    const sender_id = req.userId;
    
    const thread = await pool.query(
      `SELECT t.*, pr.user_id as provider_user_id
       FROM threads t
       JOIN providers pr ON t.provider_id = pr.id
       WHERE t.id = $1 AND (t.client_id = $2 OR pr.user_id = $2)`,
      [threadId, sender_id]
    );
    
    if (thread.rows.length === 0) {
      return res.status(404).json({ error: 'Fil non trouvé' });
    }
    
    const result = await pool.query(
      `INSERT INTO messages (thread_id, sender_id, body) 
       VALUES ($1, $2, $3) 
       RETURNING *`,
      [threadId, sender_id, body]
    );
    
    const updateFields = {
      last_message: body,
      last_message_at: new Date()
    };
    
    if (sender_id === thread.rows[0].client_id) {
      updateFields.unread_count_provider = pool.query(
        'SELECT unread_count_provider + 1 FROM threads WHERE id = $1',
        [threadId]
      );
    } else {
      updateFields.unread_count_client = pool.query(
        'SELECT unread_count_client + 1 FROM threads WHERE id = $1',
        [threadId]
      );
    }
    
    await pool.query(
      `UPDATE threads 
       SET last_message = $1, last_message_at = $2
       WHERE id = $3`,
      [body, new Date(), threadId]
    );
    
    if (sender_id === thread.rows[0].client_id) {
      await pool.query(
        'UPDATE threads SET unread_count_provider = unread_count_provider + 1 WHERE id = $1',
        [threadId]
      );
    } else {
      await pool.query(
        'UPDATE threads SET unread_count_client = unread_count_client + 1 WHERE id = $1',
        [threadId]
      );
    }
    
    res.status(201).json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const markAsRead = async (req, res) => {
  try {
    const { threadId } = req.params;
    const user_id = req.userId;
    
    const thread = await pool.query(
      `SELECT * FROM threads WHERE id = $1 AND (client_id = $2 OR provider_id IN (SELECT id FROM providers WHERE user_id = $2))`,
      [threadId, user_id]
    );
    
    if (thread.rows.length === 0) {
      return res.status(404).json({ error: 'Fil non trouvé' });
    }
    
    if (thread.rows[0].client_id === user_id) {
      await pool.query('UPDATE threads SET unread_count_client = 0 WHERE id = $1', [threadId]);
    } else {
      await pool.query('UPDATE threads SET unread_count_provider = 0 WHERE id = $1', [threadId]);
    }
    
    res.json({ message: 'Marqué comme lu' });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const editMessage = async (req, res) => {
  try {
    const { messageId } = req.params;
    const { body } = req.body;
    const user_id = req.userId;

    if (!body || !body.trim()) {
      return res.status(400).json({ error: 'Le message ne peut pas être vide' });
    }

    const msg = await pool.query('SELECT * FROM messages WHERE id = $1', [messageId]);
    if (msg.rows.length === 0) {
      return res.status(404).json({ error: 'Message non trouvé' });
    }

    if (msg.rows[0].sender_id !== user_id) {
      return res.status(403).json({ error: 'Vous ne pouvez modifier que vos propres messages' });
    }

    const result = await pool.query(
      `UPDATE messages SET body = $1, edited_at = NOW() WHERE id = $2 RETURNING *`,
      [body.trim(), messageId]
    );

    if (result.rows[0].thread_id) {
      const lastMsg = await pool.query(
        'SELECT id FROM messages WHERE thread_id = $1 ORDER BY created_at DESC LIMIT 1',
        [result.rows[0].thread_id]
      );
      if (lastMsg.rows.length > 0 && lastMsg.rows[0].id === messageId) {
        await pool.query(
          'UPDATE threads SET last_message = $1 WHERE id = $2',
          [body.trim(), result.rows[0].thread_id]
        );
      }
    }

    res.json(result.rows[0]);
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

const deleteMessage = async (req, res) => {
  try {
    const { messageId } = req.params;
    const user_id = req.userId;

    const msg = await pool.query('SELECT * FROM messages WHERE id = $1', [messageId]);
    if (msg.rows.length === 0) {
      return res.status(404).json({ error: 'Message non trouvé' });
    }

    if (msg.rows[0].sender_id !== user_id) {
      return res.status(403).json({ error: 'Vous ne pouvez supprimer que vos propres messages' });
    }

    const threadId = msg.rows[0].thread_id;
    await pool.query('DELETE FROM messages WHERE id = $1', [messageId]);

    const lastMsg = await pool.query(
      'SELECT body FROM messages WHERE thread_id = $1 ORDER BY created_at DESC LIMIT 1',
      [threadId]
    );
    await pool.query(
      'UPDATE threads SET last_message = $1 WHERE id = $2',
      [lastMsg.rows.length > 0 ? lastMsg.rows[0].body : null, threadId]
    );

    res.json({ success: true });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
};

module.exports = {
  getOrCreateThread,
  getMyThreads,
  getMessages,
  sendMessage,
  markAsRead,
  editMessage,
  deleteMessage
};