const express = require('express');
const router = express.Router();
const venteController = require('../controllers/venteController');
const { authMiddleware } = require('../middleware/auth');

router.get('/', authMiddleware, venteController.getAll);
router.get('/stats', authMiddleware, venteController.getStats);
router.get('/:id', authMiddleware, venteController.getById);
router.get('/:id/pdf', authMiddleware, venteController.exportPDF);
router.post('/', authMiddleware, venteController.create);

module.exports = router;