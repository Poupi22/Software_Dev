const express = require('express');
const router = express.Router();
const medicamentController = require('../controllers/medicamentController');
const { authMiddleware, isAdmin } = require('../middleware/auth');
const { upload } = require('../config/cloudinary');

router.get('/', authMiddleware, medicamentController.getAll);
router.get('/search', authMiddleware, medicamentController.search);
router.get('/:id', authMiddleware, medicamentController.getById);
router.post('/', authMiddleware, isAdmin, upload.single('image'), medicamentController.create);
router.put('/:id', authMiddleware, isAdmin, upload.single('image'), medicamentController.update);
router.delete('/:id', authMiddleware, isAdmin, medicamentController.delete);

module.exports = router;
