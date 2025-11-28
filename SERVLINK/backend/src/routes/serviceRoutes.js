const express = require('express');
const {
  createService,
  getMyServices,
  updateService,
  deleteService,
  getProviderServices
} = require('../controllers/serviceController');
const authenticate = require('../middleware/auth');

const router = express.Router();

router.post('/', authenticate, createService);
router.get('/my-services', authenticate, getMyServices);
router.put('/:id', authenticate, updateService);
router.delete('/:id', authenticate, deleteService);
router.get('/provider/:providerId', getProviderServices);

module.exports = router;