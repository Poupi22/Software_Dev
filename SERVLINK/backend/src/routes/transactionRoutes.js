const express = require('express');
const {
  createTransaction,
  checkTransactionStatus,
  campayWebhook,
  getMyTransactions,
  getProviderTransactions
} = require('../controllers/transactionController');
const authenticate = require('../middleware/auth');

const router = express.Router();

router.post('/', authenticate, createTransaction);
router.get('/my-transactions', authenticate, getMyTransactions);
router.get('/provider-transactions', authenticate, getProviderTransactions);
router.get('/:id/status', authenticate, checkTransactionStatus);
router.post('/webhook/campay', campayWebhook);

module.exports = router;
