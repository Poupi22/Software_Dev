const bcrypt = require('bcrypt');
const { query } = require('../config/database');
const { generateRandomPassword } = require('../utils/passwordGenerator');
const { sendGerantCredentialsEmail } = require('../config/email');

const utilisateurController = {
  async getAllGerants(req, res) {
    try {
      const result = await query(`
        SELECT id, nom, email, telephone, role, statut, avatar_url, 
               derniere_connexion, created_at, updated_at
        FROM utilisateurs
        WHERE role = 'GERANT'
        ORDER BY created_at DESC
      `);
      res.json({ success: true, data: result.rows });
    } catch (error) {
      console.error('Get gerants error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération des gérants' });
    }
  },

  async getGerantById(req, res) {
    try {
      const { id } = req.params;
      const result = await query(`
        SELECT id, nom, email, telephone, role, statut, avatar_url, 
               derniere_connexion, created_at, updated_at
        FROM utilisateurs
        WHERE id = $1 AND role = 'GERANT'
      `, [id]);
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Gérant non trouvé' });
      }
      
      res.json({ success: true, data: result.rows[0] });
    } catch (error) {
      console.error('Get gerant error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la récupération du gérant' });
    }
  },

  async createGerant(req, res) {
    try {
      const { nom, email, telephone, role = 'GERANT' } = req.body;
      
      if (!nom || !email) {
        return res.status(400).json({ success: false, message: 'Le nom et l\'email sont requis' });
      }
      
      const existing = await query('SELECT id FROM utilisateurs WHERE email = $1', [email.toLowerCase()]);
      if (existing.rows.length > 0) {
        return res.status(409).json({ success: false, message: 'Un utilisateur avec cet email existe déjà' });
      }
      
      const plainPassword = generateRandomPassword(10);
      const saltRounds = 10;
      const hashedPassword = await bcrypt.hash(plainPassword, saltRounds);
      
      const result = await query(`
        INSERT INTO utilisateurs (nom, email, telephone, mot_de_passe, role, statut)
        VALUES ($1, $2, $3, $4, $5, 'ACTIF')
        RETURNING id, nom, email, telephone, role, statut, created_at
      `, [nom, email.toLowerCase(), telephone, hashedPassword, role]);
      
      const newGerant = result.rows[0];
      
      try {
        await sendGerantCredentialsEmail(email, plainPassword, nom);
        console.log('📧 Email envoyé à:', email);
      } catch (emailError) {
        console.error('Erreur envoi email:', emailError.message);
      }
      
      await query(`
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES ($1, 'CREATE_GERANT', 'utilisateur', $2, $3)
      `, [req.user.id, newGerant.id, JSON.stringify({ email, nom, role })]);
      
      res.status(201).json({
        success: true,
        message: 'Gérant créé avec succès. Les identifiants ont été envoyés par email.',
        data: newGerant
      });
    } catch (error) {
      console.error('Create gerant error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la création du gérant' });
    }
  },

  async updateGerant(req, res) {
    try {
      const { id } = req.params;
      const { nom, telephone, statut } = req.body;
      
      const result = await query(`
        UPDATE utilisateurs
        SET nom = COALESCE($1, nom),
            telephone = COALESCE($2, telephone),
            statut = COALESCE($3, statut),
            updated_at = NOW()
        WHERE id = $4 AND role = 'GERANT'
        RETURNING id, nom, email, telephone, role, statut, updated_at
      `, [nom, telephone, statut, id]);
      
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Gérant non trouvé' });
      }
      
      await query(`
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES ($1, 'UPDATE_GERANT', 'utilisateur', $2, $3)
      `, [req.user.id, id, JSON.stringify({ nom, telephone, statut })]);
      
      res.json({
        success: true,
        message: 'Gérant mis à jour avec succès',
        data: result.rows[0]
      });
    } catch (error) {
      console.error('Update gerant error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la mise à jour du gérant' });
    }
  },

  async deleteGerant(req, res) {
    try {
      const { id } = req.params;
      
      const check = await query('SELECT id, role FROM utilisateurs WHERE id = $1', [id]);
      if (check.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Gérant non trouvé' });
      }
      if (check.rows[0].role === 'ADMIN') {
        return res.status(403).json({ success: false, message: 'Impossible de supprimer un administrateur' });
      }
      
      const result = await query('DELETE FROM utilisateurs WHERE id = $1 AND role = $2 RETURNING id', [id, 'GERANT']);
      if (result.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Gérant non trouvé' });
      }
      
      await query(`
        INSERT INTO activites (utilisateur_id, action, entite, entite_id)
        VALUES ($1, 'DELETE_GERANT', 'utilisateur', $2)
      `, [req.user.id, id]);
      
      res.json({ success: true, message: 'Gérant supprimé avec succès' });
    } catch (error) {
      console.error('Delete gerant error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la suppression du gérant' });
    }
  },

  async resetPassword(req, res) {
    try {
      const { id } = req.params;
      
      const check = await query('SELECT id, email, nom, role FROM utilisateurs WHERE id = $1', [id]);
      if (check.rows.length === 0) {
        return res.status(404).json({ success: false, message: 'Utilisateur non trouvé' });
      }
      
      const newPassword = generateRandomPassword(10);
      const hashedPassword = await bcrypt.hash(newPassword, 10);
      
      await query('UPDATE utilisateurs SET mot_de_passe = $1, updated_at = NOW() WHERE id = $2', [hashedPassword, id]);
      
      try {
        await sendGerantCredentialsEmail(check.rows[0].email, newPassword, check.rows[0].nom);
        console.log('📧 Nouveau mot de passe envoyé à:', check.rows[0].email);
      } catch (emailError) {
        console.error('Erreur envoi email:', emailError.message);
      }
      
      await query(`
        INSERT INTO activites (utilisateur_id, action, entite, entite_id, details)
        VALUES ($1, 'RESET_PASSWORD', 'utilisateur', $2, $3)
      `, [req.user.id, id, JSON.stringify({ email: check.rows[0].email })]);
      
      res.json({
        success: true,
        message: 'Mot de passe réinitialisé avec succès. Les nouveaux identifiants ont été envoyés par email.'
      });
    } catch (error) {
      console.error('Reset password error:', error);
      res.status(500).json({ success: false, message: 'Erreur lors de la réinitialisation du mot de passe' });
    }
  },
  async updateProfile(req, res) {
  try {
    const userId = req.user.id;
    const { nom, telephone } = req.body;
    
    const result = await query(
      `UPDATE utilisateurs 
       SET nom = COALESCE($1, nom),
           telephone = COALESCE($2, telephone),
           updated_at = NOW()
       WHERE id = $3
       RETURNING id, nom, email, telephone, role, statut`,
      [nom, telephone, userId]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Utilisateur non trouvé' });
    }
    
    // Enregistrer l'activité
    await query(
      `INSERT INTO activites (utilisateur_id, action, entite, details)
       VALUES ($1, 'UPDATE_PROFILE', 'utilisateur', $2)`,
      [userId, JSON.stringify({ nom, telephone })]
    );
    
    res.json({ 
      success: true, 
      message: 'Profil mis à jour avec succès',
      data: result.rows[0]
    });
  } catch (error) {
    console.error('Update profile error:', error);
    res.status(500).json({ success: false, message: 'Erreur lors de la mise à jour du profil' });
  }
},

async getProfile(req, res) {
  try {
    const userId = req.user.id;
    const result = await query(
      `SELECT id, nom, email, telephone, role, statut, avatar_url, created_at
       FROM utilisateurs
       WHERE id = $1`,
      [userId]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({ success: false, message: 'Utilisateur non trouvé' });
    }
    
    res.json({ success: true, data: result.rows[0] });
  } catch (error) {
    console.error('Get profile error:', error);
    res.status(500).json({ success: false, message: 'Erreur lors de la récupération du profil' });
  }
},
async changePassword(req, res) {
  try {
    const { old_password, new_password } = req.body;
    const userId = req.user.id;
    
    if (!old_password || !new_password) {
      return res.status(400).json({
        success: false,
        message: 'Ancien et nouveau mot de passe requis'
      });
    }
    
    if (new_password.length < 6) {
      return res.status(400).json({
        success: false,
        message: 'Le nouveau mot de passe doit contenir au moins 6 caractères'
      });
    }
    
    const result = await query(
      'SELECT mot_de_passe FROM utilisateurs WHERE id = $1',
      [userId]
    );
    
    if (result.rows.length === 0) {
      return res.status(404).json({
        success: false,
        message: 'Utilisateur non trouvé'
      });
    }
    
    const isValid = await bcrypt.compare(old_password, result.rows[0].mot_de_passe);
    
    if (!isValid) {
      return res.status(401).json({
        success: false,
        message: 'Ancien mot de passe incorrect'
      });
    }
    
    const hashedPassword = await bcrypt.hash(new_password, 10);
    
    await query(
      'UPDATE utilisateurs SET mot_de_passe = $1, updated_at = NOW() WHERE id = $2',
      [hashedPassword, userId]
    );
    
    await query(
      `INSERT INTO activites (utilisateur_id, action, entite, details)
       VALUES ($1, 'CHANGE_PASSWORD', 'utilisateur', $2)`,
      [userId, JSON.stringify({ changed: true })]
    );
    
    res.json({
      success: true,
      message: 'Mot de passe changé avec succès'
    });
  } catch (error) {
    console.error('Change password error:', error);
    res.status(500).json({
      success: false,
      message: 'Erreur lors du changement de mot de passe'
    });
  }
},
};

module.exports = utilisateurController;