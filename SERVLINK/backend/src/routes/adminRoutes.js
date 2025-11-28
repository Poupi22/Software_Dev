const express = require('express');
const router = express.Router();
const authenticate = require('../middleware/auth');
const {
  getAllUsers,
  getStats,
  getAllTransactions,
  getFlaggedReviews,
  toggleBlockUser,
  deleteUser
} = require('../controllers/adminController');

router.use(authenticate);

router.get('/users', getAllUsers);
router.get('/stats', getStats);
router.get('/transactions', getAllTransactions);
router.get('/reviews', getFlaggedReviews);
router.patch('/users/:id/block', toggleBlockUser);
router.delete('/users/:id', deleteUser);

module.exports = router;
