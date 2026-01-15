const express = require('express');
const router = express.Router();
const alerteController = require('../controllers/alerteController');
const { authMiddleware, isAdmin } = require('../middleware/auth');

router.get('/', authMiddleware, alerteController.getAll);
router.get('/non-traitees', authMiddleware, alerteController.getNonTraitees);
router.get('/statistiques', authMiddleware, alerteController.getStatistiques);
router.post('/generer', authMiddleware, isAdmin, alerteController.genererAlertesManuelles);
router.put('/:id/lue', authMiddleware, alerteController.marquerLue);
router.put('/:id/traiter', authMiddleware, alerteController.traiter);

module.exports = router;