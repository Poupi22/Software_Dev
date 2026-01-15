const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const { query } = require('../config/database');

const authController = {
  // Connexion
  async login(req, res) {
    try {
      const { email, mot_de_passe } = req.body;
      
      if (!email || !mot_de_passe) {
        return res.status(400).json({
          success: false,
          message: 'Email et mot de passe requis'
        });
      }
      
      const result = await query(
        'SELECT id, nom, email, mot_de_passe, role, statut FROM utilisateurs WHERE email = $1',
        [email.toLowerCase()]
      );
      
      if (result.rows.length === 0) {
        return res.status(401).json({
          success: false,
          message: 'Email ou mot de passe incorrect'
        });
      }
      
      const user = result.rows[0];
      
      if (user.statut !== 'ACTIF') {
        return res.status(401).json({
          success: false,
          message: 'Compte désactivé. Contactez l\'administrateur.'
        });
      }
      
      const validPassword = await bcrypt.compare(mot_de_passe, user.mot_de_passe);
      
      if (!validPassword) {
        return res.status(401).json({
          success: false,
          message: 'Email ou mot de passe incorrect'
        });
      }
      
      await query(
        'UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = $1',
        [user.id]
      );
      
      const token = jwt.sign(
        { 
          id: user.id, 
          email: user.email, 
          role: user.role,
          nom: user.nom
        },
        process.env.JWT_SECRET,
        { expiresIn: '24h' }
      );
      
      const { mot_de_passe: _, ...userData } = user;
      
      res.json({
        success: true,
        message: 'Connexion réussie',
        data: {
          user: userData,
          token
        }
      });
      
    } catch (error) {
      console.error('Login error:', error);
      res.status(500).json({
        success: false,
        message: 'Erreur lors de la connexion'
      });
    }
  },

  // Récupérer le profil
  async getProfile(req, res) {
    try {
      const result = await query(
        `SELECT id, nom, email, telephone, role, statut, avatar_url, 
                derniere_connexion, created_at
         FROM utilisateurs 
         WHERE id = $1`,
        [req.user.id]
      );
      
      if (result.rows.length === 0) {
        return res.status(404).json({
          success: false,
          message: 'Utilisateur non trouvé'
        });
      }
      
      res.json({
        success: true,
        data: result.rows[0]
      });
    } catch (error) {
      console.error('Get profile error:', error);
      res.status(500).json({
        success: false,
        message: 'Erreur lors de la récupération du profil'
      });
    }
  },

  // Changer le mot de passe
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
  }
};

module.exports = authController;