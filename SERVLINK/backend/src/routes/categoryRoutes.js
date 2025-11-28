const express = require('express');
const {
  createCategory,
  getAllCategories,
  getCategoryById,
  updateCategory,
  deleteCategory
} = require('../controllers/categoryController');
const authenticate = require('../middleware/auth');

const router = express.Router();

// Routes publiques
router.get('/', getAllCategories);
router.get('/:id', getCategoryById);

// Routes admin seulement
router.post('/', authenticate, createCategory);      // Admin seulement
router.put('/:id', authenticate, updateCategory);    // Admin seulement
router.delete('/:id', authenticate, deleteCategory); // Admin seulement

module.exports = router;