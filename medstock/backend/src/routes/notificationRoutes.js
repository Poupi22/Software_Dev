const express = require('express');
const router = express.Router();
const notificationController = require('../controllers/notificationController');
const { authMiddleware } = require('../middleware/auth');

router.get('/', authMiddleware, notificationController.getAll);
router.get('/non-lues', authMiddleware, notificationController.getNonLues);
router.put('/:id/lue', authMiddleware, notificationController.marquerLue);
router.put('/tout-lue', authMiddleware, notificationController.toutMarquerLue);
router.delete('/:id', authMiddleware, notificationController.supprimer);

module.exports = router;
