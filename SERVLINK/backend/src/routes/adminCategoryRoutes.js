const express = require('express');
const {
  getAllCategories,
  createCategory,
  updateCategory,
  deleteCategory
} = require('../controllers/adminCategoryController');
const authenticate = require('../middleware/auth');
const upload = require('../middleware/upload');

const router = express.Router();

router.get('/', authenticate, getAllCategories);
router.post('/', authenticate, upload.single('image'), createCategory);
router.put('/:id', authenticate, upload.single('image'), updateCategory);
router.delete('/:id', authenticate, deleteCategory);

module.exports = router;
