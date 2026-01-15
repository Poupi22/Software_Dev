const express = require('express');
const router = express.Router();
const utilisateurController = require('../controllers/utilisateurController');
const { authMiddleware, isAdmin } = require('../middleware/auth');

// Routes pour le profil (accessible par tous)
router.get('/profile', authMiddleware, utilisateurController.getProfile);
router.put('/profile', authMiddleware, utilisateurController.updateProfile);

// Routes admin pour les gérants
router.get('/gerants', authMiddleware, isAdmin, utilisateurController.getAllGerants);
router.get('/gerants/:id', authMiddleware, isAdmin, utilisateurController.getGerantById);
router.post('/gerants', authMiddleware, isAdmin, utilisateurController.createGerant);
router.put('/gerants/:id', authMiddleware, isAdmin, utilisateurController.updateGerant);
router.delete('/gerants/:id', authMiddleware, isAdmin, utilisateurController.deleteGerant);
router.post('/gerants/:id/reset-password', authMiddleware, isAdmin, utilisateurController.resetPassword);

module.exports = router;