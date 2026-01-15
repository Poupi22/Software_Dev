const { query } = require('../config/database');

const notificationController = {
  async getAll(req, res) {
    try {
      const result = await query(
        `SELECT * FROM notifications 
         WHERE utilisateur_id = $1 
         ORDER BY created_at DESC`,
        [req.user.id]
      );
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get notifications error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des notifications' });
    }
  },

  async getNonLues(req, res) {
    try {
      const result = await query(
        `SELECT * FROM notifications 
         WHERE utilisateur_id = $1 AND lue = false 
         ORDER BY created_at DESC`,
        [req.user.id]
      );
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get unread notifications error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des notifications' });
    }
  },

  async marquerLue(req, res) {
    try {
      const { id } = req.params;
      const result = await query(
        `UPDATE notifications 
         SET lue = true 
         WHERE id = $1 AND utilisateur_id = $2 
         RETURNING *`,
        [id, req.user.id]
      );
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Notification non trouvée' });
      }
      
      res.json({ success: true, message: 'Notification marquée comme lue', data: result.rows[0] });
    } catch (error) {
      console.error('Mark as read error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors du marquage' });
    }
  },

  async toutMarquerLue(req, res) {
    try {
      await query(
        `UPDATE notifications 
         SET lue = true 
         WHERE utilisateur_id = $1 AND lue = false`,
        [req.user.id]
      );
      res.json({ success: true, message: 'Toutes les notifications marquées comme lues' });
    } catch (error) {
      console.error('Mark all as read error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors du marquage' });
    }
  },

  async supprimer(req, res) {
    try {
      const { id } = req.params;
      const result = await query(
        `DELETE FROM notifications 
         WHERE id = $1 AND utilisateur_id = $2 
         RETURNING id`,
        [id, req.user.id]
      );
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Notification non trouvée' });
      }
      
      res.json({ success: true, message: 'Notification supprimée' });
    } catch (error) {
      console.error('Delete notification error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la suppression' });
    }
  },

  async creerNotification(utilisateur_id, titre, message, lien = null) {
    try {
      const result = await query(
        `INSERT INTO notifications (utilisateur_id, titre, message, lien, lue)
         VALUES ($1, $2, $3, $4, false)
         RETURNING *`,
        [utilisateur_id, titre, message, lien]
      );
      return result.rows[0];
    } catch (error) {
      console.error('Create notification error:', error);
      return null;
    }
  }
};

module.exports = notificationController;
