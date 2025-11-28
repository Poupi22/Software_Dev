const express = require('express');
const {
  createDispute,
  getMyDisputes,
  getProviderDisputes,
  updateDisputeStatus,
  getAllDisputes
} = require('../controllers/disputeController');
const authenticate = require('../middleware/auth');

const router = express.Router();

router.post('/', authenticate, createDispute);
router.get('/my-disputes', authenticate, getMyDisputes);
router.get('/provider-disputes', authenticate, getProviderDisputes);
router.get('/all', authenticate, getAllDisputes);
router.put('/:id/status', authenticate, updateDisputeStatus);

module.exports = router;