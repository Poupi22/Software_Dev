const express = require('express');
const {
  getOrCreateThread,
  getMyThreads,
  getMessages,
  sendMessage,
  markAsRead,
  editMessage,
  deleteMessage
} = require('../controllers/messageController');
const authenticate = require('../middleware/auth');

const router = express.Router();

router.get('/threads', authenticate, getMyThreads);
router.post('/threads/:providerId', authenticate, getOrCreateThread);
router.get('/threads/:threadId/messages', authenticate, getMessages);
router.post('/threads/:threadId/messages', authenticate, sendMessage);
router.put('/threads/:threadId/read', authenticate, markAsRead);
router.put('/messages/:messageId', authenticate, editMessage);
router.delete('/messages/:messageId', authenticate, deleteMessage);

module.exports = router;