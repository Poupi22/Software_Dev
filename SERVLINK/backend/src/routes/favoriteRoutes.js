const express = require('express');
const {
  addFavorite,
  removeFavorite,
  getMyFavorites,
  checkFavorite
} = require('../controllers/favoriteController');
const authenticate = require('../middleware/auth');

const router = express.Router();

router.get('/', authenticate, getMyFavorites);
router.post('/:providerId', authenticate, addFavorite);
router.delete('/:providerId', authenticate, removeFavorite);
router.get('/:providerId/check', authenticate, checkFavorite);

module.exports = router;