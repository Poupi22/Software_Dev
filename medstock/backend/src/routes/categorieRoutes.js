const express = require('express');
const router = express.Router();
const categorieController = require('../controllers/categorieController');
const { authMiddleware, isAdmin } = require('../middleware/auth');
const { upload } = require('../config/cloudinary');

router.get('/', authMiddleware, categorieController.getAll);
router.get('/:id', authMiddleware, categorieController.getById);
router.post('/', authMiddleware, isAdmin, upload.single('image'), categorieController.create);
router.put('/:id', authMiddleware, isAdmin, upload.single('image'), categorieController.update);
router.delete('/:id', authMiddleware, isAdmin, categorieController.delete);

module.exports = router;