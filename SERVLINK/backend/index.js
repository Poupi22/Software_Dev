const dotenv = require('dotenv');
dotenv.config();

const express = require('express');
const cors = require('cors');
const path = require('path');
const authRoutes = require('./src/routes/authRoutes');
const categoryRoutes = require('./src/routes/categoryRoutes');
const serviceRoutes = require('./src/routes/serviceRoutes');
const providerRoutes = require('./src/routes/providerRoutes');
const bookingRoutes = require('./src/routes/bookingRoutes');
const reviewRoutes = require('./src/routes/reviewRoutes');
const favoriteRoutes = require('./src/routes/favoriteRoutes');
const messageRoutes = require('./src/routes/messageRoutes');
const transactionRoutes = require('./src/routes/transactionRoutes');
const disputeRoutes = require('./src/routes/disputeRoutes');
const adminRoutes = require('./src/routes/adminRoutes');
const adminServiceRoutes = require('./src/routes/adminServiceRoutes');
const adminCategoryRoutes = require('./src/routes/adminCategoryRoutes');
const searchRoutes = require('./src/routes/searchRoutes');
const newsletterRoutes = require('./src/routes/newsletterRoutes');

const app = express();
const PORT = process.env.PORT || 3000;


app.use(cors());
app.use(express.json());
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Route de test
app.get('/', (req, res) => {
  res.json({ message: 'SERVLINK API - En cours de développement' });
});
app.use('/api/auth', authRoutes);
app.use('/api/categories', categoryRoutes);
app.use('/api/services', serviceRoutes);
app.use('/api/providers', providerRoutes);
app.use('/api/bookings', bookingRoutes);
app.use('/api/reviews', reviewRoutes);
app.use('/api/favorites', favoriteRoutes);
app.use('/api/messages', messageRoutes);
app.use('/api/transactions', transactionRoutes);
app.use('/api/disputes', disputeRoutes);
app.use('/api/admin', adminRoutes);
app.use('/api/admin/services', adminServiceRoutes);
app.use('/api/admin/categories', adminCategoryRoutes);
app.use('/api/search', searchRoutes);
app.use('/api/newsletter', newsletterRoutes);




app.listen(PORT, () => {
  console.log(`✅ Serveur démarré sur http://localhost:${PORT}`);
});

module.exports = app;