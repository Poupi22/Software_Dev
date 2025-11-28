const express = require('express');
const {
  createBooking,
  getMyBookings,
  getMyJobs,
  getBookingById,
  updateStatus,
  cancelBooking
} = require('../controllers/bookingController');
const authenticate = require('../middleware/auth');

const router = express.Router();

router.post('/', authenticate, createBooking);
router.get('/my-bookings', authenticate, getMyBookings);
router.get('/my-jobs', authenticate, getMyJobs);
router.get('/:id', authenticate, getBookingById);
router.put('/:id/status', authenticate, updateStatus);
router.put('/:id/cancel', authenticate, cancelBooking);

module.exports = router;