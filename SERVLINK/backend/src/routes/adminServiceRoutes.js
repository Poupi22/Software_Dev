const express = require('express');
const {
  getAllServices,
  createService,
  updateService,
  deleteService,
  toggleService
} = require('../controllers/adminServiceController');
const authenticate = require('../middleware/auth');
const upload = require('../middleware/upload');

const router = express.Router();

router.get('/', authenticate, getAllServices);
router.post('/', authenticate, upload.single('image'), createService);
router.put('/:id', authenticate, upload.single('image'), updateService);
router.delete('/:id', authenticate, deleteService);
router.patch('/:id/toggle', authenticate, toggleService);

module.exports = router;
