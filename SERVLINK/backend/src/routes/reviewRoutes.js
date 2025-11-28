const express = require('express');
const {
  createReview,
  getProviderReviews,
  updateReview,
  deleteReview,
  flagReview
} = require('../controllers/reviewController');
const authenticate = require('../middleware/auth');

const router = express.Router();

router.post('/', authenticate, createReview);
router.get('/provider/:providerId', getProviderReviews);
router.put('/:id', authenticate, updateReview);
router.delete('/:id', authenticate, deleteReview);
router.put('/:id/flag', authenticate, flagReview);

module.exports = router;