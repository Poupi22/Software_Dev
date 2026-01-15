const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const { execSync } = require('child_process');
require('dotenv').config();

const authRoutes = require('./src/routes/authRoutes');
const categorieRoutes = require('./src/routes/categorieRoutes');
const medicamentRoutes = require('./src/routes/medicamentRoutes');
const venteRoutes = require('./src/routes/venteRoutes');
const alerteRoutes = require('./src/routes/alerteRoutes');
const utilisateurRoutes = require('./src/routes/utilisateurRoutes');
const notificationRoutes = require('./src/routes/notificationRoutes');
const activiteRoutes = require('./src/routes/activiteRoutes');
const { authMiddleware } = require('./src/middleware/auth');

const app = express();
const PORT = process.env.PORT || 5000;

// Fonction pour exécuter les migrations et le seeder
const runMigrationsAndSeed = () => {
  console.log('\n📦 Vérification des migrations...');
  try {
    execSync('node src/migrations/migration_runner.js', { stdio: 'inherit' });
    console.log('✅ Migrations OK\n');
  } catch (error) {
    console.log('⚠️ Les migrations ont déjà été exécutées ou une erreur est survenue\n');
  }

  console.log('🌱 Vérification du seeder...');
  try {
    execSync('node src/seeders/seeder.js', { stdio: 'inherit' });
    console.log('✅ Seeder OK\n');
  } catch (error) {
    console.log('⚠️ Le seeder a déjà été exécuté ou une erreur est survenue\n');
  }
};

// Exécuter les migrations et le seeder avant de démarrer le serveur
runMigrationsAndSeed();

app.use(helmet());
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(morgan('dev'));

app.use('/api/auth', authRoutes);
app.use('/api/categories', categorieRoutes);
app.use('/api/medicaments', medicamentRoutes);
app.use('/api/ventes', venteRoutes);
app.use('/api/alertes', alerteRoutes);
app.use('/api/utilisateurs', utilisateurRoutes);
app.use('/api/notifications', notificationRoutes);
app.use('/api/activites', activiteRoutes);

app.get('/', (req, res) => {
  res.json({ message: 'Bienvenue sur l\'API PharmaCare' });
});

app.get('/health', (req, res) => {
  res.json({ status: 'OK', timestamp: new Date().toISOString() });
});

app.get('/api/profile', authMiddleware, (req, res) => {
  res.json({
    success: true,
    user: req.user
  });
});

// ✅ Fixed: binding to 0.0.0.0 so Render can detect the open port
app.listen(PORT, '0.0.0.0', () => {
  console.log(`🚀 Server running on port ${PORT}`);
  console.log(`📝 Environment: ${process.env.NODE_ENV || 'development'}`);
  console.log(`🔗 API URL: http://localhost:${PORT}`);
  console.log(`🔐 Auth endpoint: http://localhost:${PORT}/api/auth/login`);
  console.log(`📁 Categories endpoint: http://localhost:${PORT}/api/categories`);
  console.log(`💊 Medicaments endpoint: http://localhost:${PORT}/api/medicaments`);
  console.log(`💰 Ventes endpoint: http://localhost:${PORT}/api/ventes`);
  console.log(`⚠️ Alertes endpoint: http://localhost:${PORT}/api/alertes`);
  console.log(`👥 Utilisateurs endpoint: http://localhost:${PORT}/api/utilisateurs`);
  console.log(`🔔 Notifications endpoint: http://localhost:${PORT}/api/notifications`);
  console.log(`📋 Activites endpoint: http://localhost:${PORT}/api/activites`);

  // ✅ Keep-alive ping every 10 minutes to prevent Render free tier sleep
  if (process.env.NODE_ENV === 'production') {
    setInterval(() => {
      fetch('https://medstock-backend-npn9.onrender.com/health')
        .catch(() => {});
    }, 10 * 60 * 1000);
    console.log('🔄 Keep-alive ping activated');
  }
});

module.exports = app;