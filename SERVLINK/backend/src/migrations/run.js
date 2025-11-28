const pool = require('../config/database');
const fs = require('fs');
const path = require('path');
const seedAdmin = require('../seeders/adminSeeder');

async function runMigrations() {
  console.log('🚀 Lancement des migrations...\n');
  
  try {
    // Activer UUID
    await pool.query('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
    console.log('✅ Extension UUID activée');
    
    // Lire tous les fichiers SQL
    const migrationsDir = __dirname;
    const files = fs.readdirSync(migrationsDir)
      .filter(f => f.endsWith('.sql'))
      .sort();
    
    for (const file of files) {
      console.log(`📦 Exécution: ${file}`);
      const sql = fs.readFileSync(path.join(migrationsDir, file), 'utf8');
      await pool.query(sql);
      console.log(`✅ ${file} terminé`);
    }
    
    // Exécuter le seeder admin
    console.log('\n👑 Seed admin...');
    await seedAdmin();
    
    console.log('\n✨ Initialisation terminée !');
    process.exit(0);
  } catch (error) {
    console.error('❌ Erreur:', error.message);
    process.exit(1);
  }
}

runMigrations();