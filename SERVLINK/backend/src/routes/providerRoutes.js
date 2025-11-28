const express = require('express');
const {
  registerProvider,
  getMyProvider,
  updateMyProvider,
  getAllProviders,
  getProviderById,
  getByCategory,
  getByCity
} = require('../controllers/providerController');
const authenticate = require('../middleware/auth');
const upload = require('../middleware/upload');

const router = express.Router();

router.post('/register', authenticate, upload.fields([{ name: 'image', maxCount: 1 }, { name: 'cover', maxCount: 1 }]), registerProvider);
router.get('/me', authenticate, getMyProvider);
router.put('/me', authenticate, upload.fields([{ name: 'image', maxCount: 1 }, { name: 'cover', maxCount: 1 }]), updateMyProvider);
router.get('/', getAllProviders);
router.get('/category/:categoryId', getByCategory);
router.get('/city/:city', getByCity);
router.get('/:id', getProviderById);

module.exports = router;